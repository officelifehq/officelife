<?php

namespace Database\Factories\Company;

use App\Models\User\User;
use App\Models\User\Pronoun;
use App\Models\Company\Company;
use App\Models\Company\Employee;
use App\Models\Company\Position;
use App\Models\Company\EmployeeStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

class EmployeeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Employee::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $company = Company::factory()->create();

        return [
            'user_id' => User::factory(),
            'company_id' => $company->id,
            'position_id' => Position::factory()->create([
                'company_id' => $company->id,
            ]),
            'pronoun_id' => Pronoun::factory(),
            'uuid' => $this->faker->uuid,
            'permission_level' => config('officelife.permission_level.user'),
            'email' => 'dwigth@dundermifflin.com',
            'first_name' => 'Dwight',
            'last_name' => 'Schrute',
            'birthdate' => $this->faker->dateTimeThisCentury()->format('Y-m-d H:i:s'),
            'consecutive_worklog_missed' => 0,
            'employee_status_id' => EmployeeStatus::factory()->create([
                'company_id' => $company->id,
            ]),
            'amount_of_allowed_holidays' => 30,
            'can_manage_expenses' => false,
        ];
    }

    /**
     * Indicate that the employee is administrator.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function asAdministrator()
    {
        return $this->state(function (array $attributes) {
            return [
                'permission_level' => config('officelife.permission_level.administrator'),
            ];
        });
    }

    /**
     * Indicate that the employee is HR.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function asHR()
    {
        return $this->state(function (array $attributes) {
            return [
                'permission_level' => config('officelife.permission_level.hr'),
            ];
        });
    }

    /**
     * Indicate that the employee is a normal mployee.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function asNormalEmployee()
    {
        return $this->state(function (array $attributes) {
            return [
                'permission_level' => config('officelife.permission_level.user'),
            ];
        });
    }
}
