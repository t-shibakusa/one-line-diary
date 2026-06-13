<?php

namespace Database\Factories;

use App\Models\Diary;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Diary>
 */
class DiaryFactory extends Factory
{
    protected $model = Diary::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'body' => fake()->realText(80),
            'image_path' => null,
            'diary_date' => fake()->unique()->date(),
        ];
    }
}
