<?php

namespace Database\Factories;

use App\Models\User; // Asigură-te că User este importat
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str; // Asigură-te că Str este importat

class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->sentence(6, true); // Generează un titlu de 6 cuvinte

        return [
            'title' => $title,
            'slug' => Str::slug($title), // Generează un slug din titlu
            'excerpt' => fake()->paragraph(2), // Un scurt rezumat
            'content' => fake()->paragraphs(8, true), // Conținut mai lung
            'featured_image' => 'https://picsum.photos/800/400?random=' . fake()->numberBetween(1, 1000), // Imagine aleatorie
            'is_published' => fake()->boolean(80), // 80% șanse să fie publicată
            'published_at' => fake()->optional(0.8)->dateTimeBetween('-1 year', 'now'), // Data publicării (opțională)
            'user_id' => User::factory(), // Asociază cu un utilizator existent sau nou
            'created_at' => fake()->dateTimeBetween('-1 year', 'now'), // Data creării
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function published(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_published' => true,
            'published_at' => fake()->dateTimeBetween('-6 months', 'now'),
        ]);
    }

    public function draft(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_published' => false,
            'published_at' => null,
        ]);
    }
}