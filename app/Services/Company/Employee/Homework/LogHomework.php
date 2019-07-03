<?php

namespace App\Services\Company\Employee\Homework;

use Carbon\Carbon;
use App\Services\BaseService;
use App\Models\Company\Employee;
use App\Models\Company\Homework;
use App\Services\Company\Employee\LogEmployeeAction;
use App\Exceptions\HomeworkAlreadyLoggedTodayException;
use App\Services\Company\Adminland\Company\LogAuditAction;

class LogHomework extends BaseService
{
    /**
     * Get the validation rules that apply to the service.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'employee_id' => 'required|integer|exists:employees,id',
            'content' => 'required|string|max:65535',
            'is_dummy' => 'nullable|boolean',
        ];
    }

    /**
     * Log the work that the employee has done.
     * Logging can only happen once per day.
     *
     * @param array $data
     * @return Homework
     */
    public function execute(array $data) : Homework
    {
        $this->validate($data);

        $employee = Employee::findOrFail($data['employee_id']);

        $author = $this->validatePermissions(
            $data['author_id'],
            $employee->company_id,
            config('homas.authorizations.user'),
            $data['employee_id']
        );

        $this->hasAlreadyLoggedHomeworkToday($data);

        $homework = Homework::create([
            'employee_id' => $data['employee_id'],
            'content' => $data['content'],
            'is_dummy' => $this->valueOrFalse($data, 'is_dummy'),
        ]);

        (new LogAuditAction)->execute([
            'company_id' => $employee->company_id,
            'action' => 'employee_homework_logged',
            'objects' => json_encode([
                'author_id' => $author->id,
                'author_name' => $author->name,
                'employee_id' => $employee->id,
                'employee_name' => $employee->name,
                'homework_id' => $homework->id,
            ]),
            'is_dummy' => $this->valueOrFalse($data, 'is_dummy'),
        ]);

        (new LogEmployeeAction)->execute([
            'company_id' => $employee->company_id,
            'employee_id' => $data['employee_id'],
            'action' => 'homework_logged',
            'objects' => json_encode([
                'author_id' => $author->id,
                'author_name' => $author->name,
                'employee_id' => $employee->id,
                'employee_name' => $employee->name,
                'homework_id' => $homework->id,
            ]),
            'is_dummy' => $this->valueOrFalse($data, 'is_dummy'),
        ]);

        return $homework;
    }

    /**
     * Check if the employee has already logged something today.
     *
     * @param array $data
     * @return bool
     */
    private function hasAlreadyLoggedHomeworkToday(array $data) : bool
    {
        $homework = Homework::where('employee_id', $data['employee_id'])
            ->whereDate('created_at', Carbon::today())
            ->get();

        if ($homework->count() != 0) {
            throw new HomeworkAlreadyLoggedTodayException();
        }

        return false;
    }
}
