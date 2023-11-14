<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Post;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Commentaire>
 */
class CommentaireFactory extends Factory
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
            'post_id' => Post::get()->random()->id,
            'content' => $this->faker->realTextBetween($minNbChars = 50, $maxNbChars = 144),
            'date' => $this->faker->dateTimeBetween('-1 month', '+ 1 month'),
        ];
    }
}
