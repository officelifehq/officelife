<?php

namespace Database\Factories\Company;

use App\Models\Company\Comment;
use App\Models\Company\Company;
use App\Models\Company\Employee;
use App\Models\Company\ProjectMessage;
use Illuminate\Database\Eloquent\Factories\Factory;

class CommentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Comment::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'company_id' => Company::factory(),
            'author_id' => function (array $attributes) {
                return Employee::factory()->create([
                    'company_id' => $attributes['company_id'],
                ]);
            },
            'author_name' => $this->faker->name,
            'content' => $this->faker->sentence,
            'commentable_id' => ProjectMessage::factory(),
            'commentable_type' => '\App\Models\ProjectMessage',
        ];
    }
}
