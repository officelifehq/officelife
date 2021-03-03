<?php

namespace Database\Factories\Company;

use App\Models\Company\File;
use App\Models\Company\Company;
use Illuminate\Database\Eloquent\Factories\Factory;

class FileFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = File::class;

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
            'filename' => 'filename',
            'hashed_filename' => '123',
            'extension' => 'png',
            'size_in_kb' => 123,
        ];
    }
}
