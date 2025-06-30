<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post; // Asigură-te că Post este importat
use Illuminate\Http\Request; // Asigură-te că Request este importat
use Illuminate\Support\Str; // Asigură-te că Str este importat pentru slug

class PostController extends Controller
{
    /**
     * Display a listing of the resource (all posts for admin).
     */
    public function index()
    {
        // Obține toate postările cu utilizatorul asociat, ordonate după cele mai recente, paginare 10
        $posts = Post::with('user')
            ->latest()
            ->paginate(10);

        // Returnează vederea de index pentru postări în admin
        return view('admin.posts.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Returnează vederea pentru crearea unei noi postări
        return view('admin.posts.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validează datele primite
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:posts,slug', // Slug-ul trebuie să fie unic
            'excerpt' => 'nullable|string',
            'content' => 'required|string',
            'featured_image' => 'nullable|url', // Imaginea principală este o URL
            'is_published' => 'boolean', // is_published este un boolean
        ]);

        // Generează slug dacă nu este furnizat
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['title']);
        }

        // Setează data publicării dacă postarea este marcată ca publicată
        if ($validated['is_published'] ?? false) { // Folosim ?? false pentru a gestiona cazul în care checkbox-ul nu este trimis
            $validated['published_at'] = now();
        }

        // Asociază postarea cu utilizatorul autentificat
        $validated['user_id'] = auth()->id();

        // Creează postarea în baza de date
        Post::create($validated);

        // Redirecționează către lista de postări cu un mesaj de succes
        return redirect()->route('admin.posts.index')->with('success', 'Post created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        // Încarcă utilizatorul și comentariile (cu utilizatorii lor) pentru postare
        $post->load('user', 'comments.user');
        // Returnează vederea de vizualizare a unei postări în admin
        return view('admin.posts.show', compact('post'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        // Returnează vederea pentru editarea unei postări
        return view('admin.posts.edit', compact('post'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        // Validează datele primite pentru actualizare
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:posts,slug,' . $post->id, // Slug-ul trebuie să fie unic, ignorând postarea curentă
            'excerpt' => 'nullable|string',
            'content' => 'required|string',
            'featured_image' => 'nullable|url',
            'is_published' => 'boolean',
        ]);

        // Generează slug dacă nu este furnizat
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['title']);
        }

        // Logică pentru actualizarea 'published_at'
        if (($validated['is_published'] ?? false) && !$post->published_at) {
            // Dacă este marcată ca publicată și nu avea data publicării setată, setează acum
            $validated['published_at'] = now();
        } elseif (!($validated['is_published'] ?? false)) {
            // Dacă nu este marcată ca publicată, șterge data publicării
            $validated['published_at'] = null;
        }

        // Actualizează postarea în baza de date
        $post->update($validated);

        // Redirecționează către lista de postări cu un mesaj de succes
        return redirect()->route('admin.posts.index')->with('success', 'Post updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        // Șterge postarea
        $post->delete();
        // Redirecționează către lista de postări cu un mesaj de succes
        return redirect()->route('admin.posts.index')->with('success', 'Post deleted successfully.');
    }
}
