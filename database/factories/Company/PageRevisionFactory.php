<?php

namespace Database\Factories\Company;

use App\Models\Company\Page;
use App\Models\Company\Employee;
use App\Models\Company\PageRevision;
use Illuminate\Database\Eloquent\Factories\Factory;

class PageRevisionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PageRevision::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'page_id' => Page::factory(),
            'employee_id' => Employee::factory(),
            'employee_name' => $this->faker->name(),
            'title' => $this->faker->text(100),
            'content' => $this->faker->text(),
        ];
    }
}
