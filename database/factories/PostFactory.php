<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::get()->random()->id,
            'description' => $this->faker->realTextBetween($minNbChars = 100, $maxNbChars = 500),
            'image_url' => $this->faker->imageUrl(640, 480, 'cats', true),
            'localisation' => $this->faker->city,
            'date' => $this->faker->dateTimeBetween('-1 month', '+ 1 month'),
            
        ];
    }
}
