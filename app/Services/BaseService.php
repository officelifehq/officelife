<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\Company\Team;
use App\Models\Company\Employee;
use Illuminate\Support\Facades\Validator;
use App\Exceptions\NotEnoughPermissionException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

abstract class BaseService
{
    /**
     * The author who calls the service.
     */
    protected Employee $author;

    /**
     * The author ID of the employee who calls the service.
     * Used to populate the author object above.
     */
    protected int $authorId;

    /**
     * The id of the company the service is supposed to be executed into.
     */
    protected int $companyId;

    /**
     * The id of the team that the service is about.
     */
    protected int $teamId;

    /**
     * The minimum permission level required to process the service for the
     * employee who triggers it.
     */
    protected int $requiredPermissionLevel;

    /**
     * Indicates whether the employee can bypass the minimum required permission
     * level to execute the service.
     */
    protected bool $bypassRequiredPermissionLevel = false;

    /**
     * Sets the author id for the service.
     *
     * @param int $givenAuthor
     * @return self
     */
    public function author(int $givenAuthor): self
    {
        $this->authorId = $givenAuthor;

        return $this;
    }

    /**
     * Sets the company id for the service.
     *
     * @param int $company
     * @return self
     */
    public function inCompany(int $company): self
    {
        $this->companyId = $company;

        return $this;
    }

    /**
     * Sets the permission level required for this service.
     *
     * @return self
     */
    public function asAtLeastAdministrator(): self
    {
        $this->requiredPermissionLevel = config('officelife.permission_level.administrator');

        return $this;
    }

    /**
     * Sets the permission level required for this service.
     *
     * @return self
     */
    public function asAtLeastHR(): self
    {
        $this->requiredPermissionLevel = config('officelife.permission_level.hr');

        return $this;
    }

    /**
     * Sets the permission level required for this service.
     *
     * @return self
     */
    public function asNormalUser(): self
    {
        $this->requiredPermissionLevel = config('officelife.permission_level.user');

        return $this;
    }

    /**
     * Sets the permission to bypass the minimum level requirement necessary to
     * execute the service if the author is the manager of the employee.
     *
     * @param int $managerId
     * @param int $employeeId
     * @return self
     */
    public function canBypassPermissionLevelIfManager(int $managerId, int $employeeId): self
    {
        $manager = Employee::where('company_id', $this->companyId)
            ->findOrFail($managerId);

        $isManager = $manager->isManagerOf($employeeId);
        $this->bypassRequiredPermissionLevel = $isManager;

        return $this;
    }

    /**
     * Sets the permission to bypass the minimum level requirement necessary to
     * execute the service if the author who calls it is actually the employee.
     *
     * @param int $employeeId
     * @return self
     */
    public function canBypassPermissionLevelIfEmployee(int $employeeId): self
    {
        $this->bypassRequiredPermissionLevel = ($this->authorId == $employeeId);

        return $this;
    }

    /**
     * Sets the permission to bypass the minimum level requirement necessary to
     * execute the service if the author who calls it is actually the employee
     * or the manager.
     *
     * @param int $employeeId
     * @return self
     */
    public function canBypassPermissionLevelIfEmployeeOrManager(int $managerId, int $employeeId): self
    {
        $manager = Employee::where('company_id', $this->companyId)
            ->findOrFail($managerId);

        $isManager = $manager->isManagerOf($employeeId);
        $this->bypassRequiredPermissionLevel = $isManager;

        if ($this->bypassRequiredPermissionLevel === false) {
            $this->bypassRequiredPermissionLevel = ($this->authorId == $employeeId);
        }

        return $this;
    }

    /**
     * Get the validation rules that apply to the service.
     *
     * @return array
     */
    public function rules(): array
    {
        return [];
    }

    /**
     * Validate an array against a set of rules.
     *
     * @param array $data
     * @return bool
     */
    public function validateRules(array $data): bool
    {
        Validator::make($data, $this->rules())
                ->validate();

        return true;

        // $validator = Validator::make($data, $this->rules());

        // if ($validator->fails()) {
        //     dd($validator->errors());
        // }
    }

    /**
     * Check that the employee effectively belongs to the given company.
     */
    public function validateEmployeeBelongsToCompany(array $data): Employee
    {
        try {
            $employee = Employee::where('company_id', $data['company_id'])
                ->findOrFail($data['employee_id']);
        } catch (ModelNotFoundException $e) {
            throw new ModelNotFoundException(trans('app.error_wrong_employee_id'));
        }

        return $employee;
    }

    /**
     * Check that the team effectively belongs to the given company.
     */
    public function validateTeamBelongsToCompany(array $data): Team
    {
        try {
            $team = Team::where('company_id', $data['company_id'])
                ->findOrFail($data['team_id']);
        } catch (ModelNotFoundException $e) {
            throw new ModelNotFoundException(trans('app.error_wrong_team_id'));
        }

        return $team;
    }

    /**
     * Checks if the employee executing the service has the permission
     * to do the action.
     *
     * @return bool
     */
    public function canExecuteService(): bool
    {
        try {
            $this->author = Employee::where('company_id', $this->companyId)
                ->where('id', $this->authorId)
                ->firstOrFail();
        } catch (ModelNotFoundException $e) {
            throw new ModelNotFoundException(trans('app.error_wrong_employee_id'));
        }

        if ($this->bypassRequiredPermissionLevel) {
            return true;
        }

        if ($this->requiredPermissionLevel < $this->author->permission_level) {
            throw new NotEnoughPermissionException(trans('app.error_not_enough_permission'));
        }

        return true;
    }

    /**
     * Checks if the value is empty or null.
     *
     * @param mixed $data
     * @param mixed $index
     * @return mixed
     */
    public function valueOrNull($data, $index)
    {
        if (empty($data[$index])) {
            return;
        }

        return $data[$index] == '' ? null : $data[$index];
    }

    /**
     * Gets the value if it’s set, or the current Now() date otherwise.
     *
     * @param mixed $data
     * @param mixed $index
     * @return Carbon
     */
    public function valueOrNow($data, $index): Carbon
    {
        if (empty($data[$index])) {
            return Carbon::now();
        }

        return Carbon::parse($data[$index]);
    }

    /**
     * Checks if the value is empty or null and returns a date from a string.
     *
     * @param mixed $data
     * @param mixed $index
     * @return mixed
     */
    public function nullOrDate($data, $index)
    {
        if (empty($data[$index])) {
            return;
        }

        return $data[$index] == '' ? null : Carbon::parse($data[$index]);
    }

    /**
     * Returns the value if it's defined, or false otherwise.
     *
     * @param mixed $data
     * @param mixed $index
     * @return mixed
     */
    public function valueOrFalse($data, $index)
    {
        if (empty($data[$index])) {
            return false;
        }

        return $data[$index];
    }
}
