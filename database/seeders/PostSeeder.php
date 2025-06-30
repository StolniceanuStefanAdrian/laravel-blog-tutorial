<?php

namespace Database\Seeders;

use App\Models\Post; // Asigură-te că Post este importat
use App\Models\User; // Asigură-te că User este importat
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all(); // Obține toți utilizatorii existenți

        // Creează 15 postări publicate, asociindu-le aleatoriu cu utilizatori
        Post::factory(15)
            ->published() // Utilizează starea 'published' din PostFactory
            ->recycle($users) // Reciclează utilizatorii existenți
            ->create();

        // Creează 5 postări ciorne (draft), asociindu-le aleatoriu cu utilizatori
        Post::factory(5)
            ->draft() // Utilizează starea 'draft' din PostFactory
            ->recycle($users)
            ->create();

        // Creează o postare specifică de bun venit
        Post::create([
            'title' => 'Welcome to Our Laravel Blog Tutorial',
            'slug' => 'welcome-to-our-laravel-blog-tutorial',
            'excerpt' => 'This is the first post in our Laravel blog tutorial series. Learn how to build a complete blog application with Laravel and Livewire.',
            'content' => '                <h2>Welcome to the Laravel Blog Tutorial</h2>                <p>This tutorial series will guide you through building a complete blog application using Laravel 11 and Livewire 3.</p>                <h3>What You\'ll Learn</h3>                <ul>                    <li>Setting up Laravel with Docker</li>                    <li>Implementing authentication with Sanctum</li>                    <li>Creating database relationships</li>                    <li>Building interactive components with Livewire</li>                    <li>Styling with Tailwind CSS</li>                </ul>                <h3>Prerequisites</h3>                <p>Basic knowledge of PHP and Laravel is recommended but not required. We\'ll guide you through each step.</p>                <h3>Getting Started</h3>                <p>Follow along with the daily tutorials and build your own blog application. Each day builds upon the previous lessons.</p>                <p>Happy coding! &#128640;</p>            ', // Am corectat emoji-ul, era un caracter invalid
            'featured_image' => 'https://picsum.photos/800/400?random=1',
            'is_published' => true,
            'published_at' => now(),
            'user_id' => User::where('email', 'admin@example.com')->first()->id, // Asociază cu utilizatorul admin
        ]);
    }
}