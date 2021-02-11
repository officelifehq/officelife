<?php

namespace Database\Factories\Company;

use App\Models\Company\ImportJob;
use App\Models\Company\ImportJobReport;
use Illuminate\Database\Eloquent\Factories\Factory;

class ImportJobReportFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ImportJobReport::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'import_job_id' => ImportJob::factory(),
        ];
    }
}
