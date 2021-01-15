<?php

namespace Database\Factories\User;

use App\Models\User\Avatar;
use App\Models\Company\Company;
use App\Models\Company\Employee;
use Illuminate\Database\Eloquent\Factories\Factory;

class AvatarFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Avatar::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $company = Company::factory()->create();

        return [
            'company_id' => $company->id,
            'employee_id' => Employee::factory()->create([
                'company_id' => $company->id,
            ]),
            'original_filename' => 'test',
            'new_filename' => 'test',
            'extension' => 'png',
            'size' => 123,
            'height' => 123,
            'width' => 123,
        ];
    }
}
