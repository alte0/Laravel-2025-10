<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

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
        return [
            'title' => $this->faker->title(),
            'description' => $this->faker->text(),
            'author_id' => DB::table('users')->select('id')->inRandomOrder()->first()->id,
            'executor_id' => (rand(0, 1) ? DB::table('users')->select('id')->inRandomOrder()->first()->id : null),
            'created_at' => now(),
            'updated_at' => now(),
            'start_date' => Carbon::now()->addDays(rand(0, 1)),
            'end_date' => Carbon::now()->addDays(rand(2, 10)),
        ];
    }
}
