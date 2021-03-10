<?php

namespace Database\Factories\Company;

use App\Models\Company\Company;
use App\Models\Company\AuditLog;
use App\Models\Company\Employee;
use Illuminate\Database\Eloquent\Factories\Factory;

class AuditLogFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = AuditLog::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'company_id' => Company::factory(),
            'action' => 'account_created',
            'author_id' => Employee::factory(),
            'author_name' => $this->faker->name,
            'audited_at' => $this->faker->dateTimeThisCentury(),
            'objects' => '{"user": 1}',
        ];
    }
}
