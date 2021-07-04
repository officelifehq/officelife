<?php

namespace Database\Factories\Company;

use App\Models\Company\Page;
use App\Models\Company\Wiki;
use Illuminate\Database\Eloquent\Factories\Factory;

class PageFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Page::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'wiki_id' => Wiki::factory(),
            'title' => $this->faker->text(100),
            'content' => $this->faker->text(),
            'pageviews_counter' => 0,
        ];
    }
}
