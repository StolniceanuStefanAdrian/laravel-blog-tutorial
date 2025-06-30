<?php

namespace Database\Factories;

use App\Models\Post; // Asigură-te că Post este importat
use App\Models\User; // Asigură-te că User este importat
use App\Models\Comment; // Asigură-te că Comment este importat
use Illuminate\Database\Eloquent\Factories\Factory;

class CommentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'content' => fake()->paragraph(2), // Conținutul comentariului
            'is_approved' => fake()->boolean(90), // 90% șanse să fie aprobat
            'post_id' => Post::factory(), // Asociază cu o postare existentă sau nouă
            'user_id' => User::factory(), // Asociază cu un utilizator existent sau nou
            'parent_id' => null, // Implicit, nu este un răspuns
            'created_at' => fake()->dateTimeBetween('-6 months', 'now'), // Data creării
        ];
    }

    public function approved(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_approved' => true,
        ]);
    }

    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_approved' => false,
        ]);
    }

    public function reply(): static
    {
        return $this->state(fn (array $attributes) => [
            'parent_id' => Comment::factory(), // Asociază cu un comentariu părinte existent sau nou
        ]);
    }
}