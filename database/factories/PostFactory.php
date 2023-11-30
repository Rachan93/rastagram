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

        $placeholderImages = [
            'placeholders/placeholder1.jpg', //faut remettre des images dans "storage/app/public/placholders" à chaque fois
            'placeholders/placeholder2.jpg',
            'placeholders/placeholder3.png',
            'placeholders/placeholder4.png',
            'placeholders/placeholder5.gif',
            'placeholders/placeholder6.gif',
        ];

        return [
            'user_id' => User::get()->random()->id,
            'description' => $this->faker->realTextBetween($minNbChars = 1, $maxNbChars = 50),
            //'image_url' => $this->faker->imageUrl(640, 480, 'cats', true),    pour des url vers placeholders externes
            'image_url' => $this->faker->randomElement($placeholderImages), // pour placeholders internes
            'localisation' => $this->faker->city,
            'date' => $this->faker->dateTimeBetween('-1 month', '+ 1 month'),
            
        ];
    }
}
