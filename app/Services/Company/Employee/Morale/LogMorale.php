<?php

namespace App\Services\Company\Employee\Morale;

use Carbon\Carbon;
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
    public function rules(): array
    {
        return [
            'author_id' => 'required|integer|exists:employees,id',
            'company_id' => 'required|integer|exists:companies,id',
            'employee_id' => 'required|integer|exists:employees,id',
            'emotion' => 'required',
                Rule::in([
                    1,
                    2,
                    3,
                ]),
            'comment' => 'nullable|string|max:65535',
            'date' => 'nullable|date_format:Y-m-d',
        ];
    }

    /**
     * Log how an employee feels on a specific day.
     * This can only happen once per day.
     * Logging can only be done by the employee.
     *
     * @param array $data
     *
     * @return Morale
     */
    public function execute(array $data): Morale
    {
        $this->validateRules($data);

        $employee = $this->validateEmployeeBelongsToCompany($data);

        $this->author($data['author_id'])
            ->inCompany($data['company_id'])
            ->asAtLeastHR()
            ->canBypassPermissionLevelIfEmployee($data['employee_id'])
            ->canExecuteService();

        if ($employee->hasAlreadyLoggedMoraleToday()) {
            throw new MoraleAlreadyLoggedTodayException();
        }

        $morale = Morale::create([
            'employee_id' => $data['employee_id'],
            'emotion' => $data['emotion'],
            'comment' => $this->valueOrNull($data, 'comment'),
            'created_at' => $this->valueOrNow($data, 'date'),
        ]);

        LogAccountAudit::dispatch([
            'company_id' => $employee->company_id,
            'action' => 'employee_morale_logged',
            'author_id' => $this->author->id,
            'author_name' => $this->author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'employee_id' => $employee->id,
                'employee_name' => $employee->name,
                'morale_id' => $morale->id,
                'emotion' => $morale->emotion,
            ]),
        ])->onQueue('low');

        LogEmployeeAudit::dispatch([
            'employee_id' => $employee->id,
            'action' => 'morale_logged',
            'author_id' => $this->author->id,
            'author_name' => $this->author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'employee_id' => $employee->id,
                'employee_name' => $employee->name,
                'morale_id' => $morale->id,
                'emotion' => $morale->emotion,
            ]),
        ])->onQueue('low');

        return $morale;
    }
}
