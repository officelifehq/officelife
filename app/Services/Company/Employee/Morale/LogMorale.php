<?php

namespace App\Services\Company\Employee\Morale;

use App\Jobs\LogAccountAudit;
use App\Services\BaseService;
use App\Jobs\LogEmployeeAudit;
use App\Models\Company\Morale;
use Illuminate\Validation\Rule;
use App\Models\Company\Employee;
use App\Exceptions\MoraleAlreadyLoggedTodayException;

class LogMorale extends BaseService
{
    /**
     * Get the validation rules that apply to the service.
     *
     * @return array
     */
    public function rules() : array
    {
        return [
            'author_id' => 'required|integer|exists:users,id',
            'employee_id' => 'required|integer|exists:employees,id',
            'emotion' => 'required',
                Rule::in([
                    1,
                    2,
                    3,
                ]),
            'comment' => 'required|string|max:65535',
            'is_dummy' => 'nullable|boolean',
        ];
    }

    /**
     * Log how an employee feels at a specific day.
     * This can only happen once per day.
     * Logging can only be done by the employee.
     *
     * @param array $data
     * @return Morale
     */
    public function execute(array $data) : Morale
    {
        $this->validate($data);

        $employee = Employee::findOrFail($data['employee_id']);

        $author = $this->validatePermissions(
            $data['author_id'],
            $employee->company_id,
            config('homas.authorizations.user'),
            $data['employee_id']
        );

        if ($employee->hasAlreadyLoggedMoraleToday()) {
            throw new MoraleAlreadyLoggedTodayException();
        }

        $morale = Morale::create([
            'employee_id' => $data['employee_id'],
            'emotion' => $data['emotion'],
            'comment' => $this->nullOrValue($data, 'comment'),
            'is_dummy' => $this->valueOrFalse($data, 'is_dummy'),
        ]);

        LogAccountAudit::dispatch([
            'company_id' => $employee->company_id,
            'action' => 'employee_morale_logged',
            'objects' => json_encode([
                'author_id' => $author->id,
                'author_name' => $author->name,
                'employee_id' => $employee->id,
                'employee_name' => $employee->name,
                'morale_id' => $morale->id,
                'emotion' => $morale->emotion,
            ]),
            'is_dummy' => $this->valueOrFalse($data, 'is_dummy'),
        ])->onQueue('low');

        LogEmployeeAudit::dispatch([
            'employee_id' => $employee->id,
            'action' => 'morale_logged',
            'objects' => json_encode([
                'author_id' => $author->id,
                'author_name' => $author->name,
                'employee_id' => $employee->id,
                'employee_name' => $employee->name,
                'morale_id' => $morale->id,
                'emotion' => $morale->emotion,
            ]),
            'is_dummy' => $this->valueOrFalse($data, 'is_dummy'),
        ])->onQueue('low');

        return $morale;
    }
}
