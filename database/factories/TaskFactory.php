<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $users = User::all()->pluck('id');

        return [
            'title' => $this->faker->title(),
            'description' => $this->faker->text(),
            'author_id' => $this->faker->randomElement($users->all()),
            'executor_id' => $this->faker->randomElement($users->add(null)->all()),
            'created_at' => now(),
            'updated_at' => now(),
            'start_date' => Carbon::now()->addDays($this->faker->randomElement([0, 1])),
            'end_date' => Carbon::now()->addDays($this->faker->randomElement([3, 10])),
        ];
    }
}
