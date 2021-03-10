<?php

namespace Database\Factories\Company;

use App\Models\Company\Employee;
use App\Models\Company\Notification;
use Illuminate\Database\Eloquent\Factories\Factory;

class NotificationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Notification::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'employee_id' => Employee::factory(),
            'action' => 'notification',
            'objects' => '{"user": 1}',
            'read' => false,
        ];
    }
}
