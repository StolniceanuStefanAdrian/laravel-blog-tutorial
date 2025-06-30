<?php

namespace Database\Seeders;

use App\Models\Comment; // Asigură-te că Comment este importat
use App\Models\Post; // Asigură-te că Post este importat
use App\Models\User; // Asigură-te că User este importat
use Illuminate\Database\Seeder;

class CommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $posts = Post::where('is_published', true)->get(); // Obține doar postările publicate
        $users = User::all(); // Obține toți utilizatorii

        foreach ($posts as $post) {
            // Creează 2-5 comentarii părinte pentru fiecare postare
            $parentComments = Comment::factory(rand(2, 5))
                ->approved() // Asigură-te că sunt aprobate implicit
                ->create([
                    'post_id' => $post->id,
                    'user_id' => $users->random()->id, // Asociază cu un utilizator aleatoriu
                ]);

            // Creează răspunsuri la unele comentarii părinte (40% șanse)
            foreach ($parentComments as $parentComment) {
                if (rand(0, 100) < 40) { // 40% șanse de a avea răspunsuri
                    Comment::factory(rand(1, 3)) // 1-3 răspunsuri
                        ->approved()
                        ->create([
                            'post_id' => $post->id,
                            'user_id' => $users->random()->id,
                            'parent_id' => $parentComment->id, // Setează comentariul părinte
                        ]);
                }
            }

            // Creează 0-2 comentarii în așteptare pentru fiecare postare
            Comment::factory(rand(0, 2))
                ->pending() // Utilizează starea 'pending' din CommentFactory
                ->create([
                    'post_id' => $post->id,
                    'user_id' => $users->random()->id,
                ]);
        }
    }
}