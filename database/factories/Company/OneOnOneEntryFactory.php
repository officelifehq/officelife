<?php

namespace Database\Factories\Company;

use App\Models\Company\Employee;
use App\Models\Company\OneOnOneEntry;
use Illuminate\Database\Eloquent\Factories\Factory;

class OneOnOneEntryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = OneOnOneEntry::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'manager_id' => Employee::factory(),
            'employee_id' => Employee::factory(),
            'happened_at' => '2020-03-02 00:00:00',
        ];
    }
}
