<?php

namespace App\Http\Controllers;

use App\Models\Post; // Asigură-te că Post este importat
use Illuminate\Http\Request; // Asigură-te că Request este importat

class PostController extends Controller
{
    /**
     * Display a listing of the posts.
     */
    public function index()
    {
        // Obține postările publicate, cu utilizatorul asociat, ordonate după data publicării, paginare 6
        $posts = Post::with('user')
            ->published() // Utilizează scope-ul 'published' definit în modelul Post
            ->latest('published_at') // Ordonează după cea mai recentă dată de publicare
            ->paginate(6); // Paginare cu 6 postări pe pagină

        // Returnează vederea 'posts.index' cu postările
        return view('posts.index', compact('posts'));
    }

    /**
     * Display the specified post.
     */
    public function show(Post $post)
    {
        // Dacă postarea nu este publicată ȘI utilizatorul nu este autentificat SAU nu este admin, aruncă 404
        if (!$post->is_published && (!auth()->check() || !auth()->user()->is_admin)) {
            abort(404); // Not Found
        }

        // Încarcă relațiile necesare pentru postare: utilizatorul și comentariile aprobate (cu răspunsurile lor)
        $post->load([
            'user', // Încarcă utilizatorul care a creat postarea
            'approvedComments' => function ($query) { // Încarcă doar comentariile aprobate
                $query->with('user', 'approvedReplies.user') // Încarcă utilizatorii comentariilor și ai răspunsurilor aprobate
                      ->whereNull('parent_id') // Obține doar comentariile părinte (de nivel superior)
                      ->latest(); // Ordonează comentariile după data creării (cele mai noi primele)
            }
        ]);

        // Returnează vederea 'posts.show' cu postarea
        return view('posts.show', compact('post'));
    }
}