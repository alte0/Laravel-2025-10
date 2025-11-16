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

        $authorId = !empty($users) ? $this->faker->randomElement($users->all()) : User::factory()->create()->id;

        if (!empty($users)) {
            $executorId = $this->faker->randomElement($users->add(null)->all());
        } else {
            $executorId = $this->faker->randomElement([false, true]) ? User::factory()->create()->id : null;
        }

        return [
            'title' => $this->faker->title(),
            'description' => $this->faker->text(),
            'author_id' => $authorId,
            'executor_id' => $executorId,
            'created_at' => now(),
            'updated_at' => now(),
            'start_date' => Carbon::now()->addDays($this->faker->randomElement([0, 1])),
            'end_date' => Carbon::now()->addDays($this->faker->randomElement([3, 10])),
        ];
    }
}
