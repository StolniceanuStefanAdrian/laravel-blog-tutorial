<?php

namespace App\Http\Controllers;

use App\Models\Comment; // Asigură-te că Comment este importat
use App\Models\Post; // Asigură-te că Post este importat
use Illuminate\Http\Request; // Asigură-te că Request este importat

class CommentController extends Controller
{
    /**
     * Store a newly created comment in storage.
     */
    public function store(Request $request, Post $post)
    {
        // Validează cererea pentru conținutul comentariului și ID-ul părintelui
        $request->validate([
            'content' => 'required|string|max:1000',
            'parent_id' => 'nullable|exists:comments,id', // parent_id este opțional și trebuie să existe în tabelul comments
        ]);

        // Creează un nou comentariu asociat postării
        $comment = $post->comments()->create([
            'content' => $request->content,
            'user_id' => auth()->id(), // Asociază comentariul cu utilizatorul autentificat
            'parent_id' => $request->parent_id, // Setează parent_id dacă este un răspuns
            'is_approved' => false, // Comentariile noi necesită aprobare manuală
        ]);

        // Redirecționează înapoi la pagina anterioară cu un mesaj de succes
        return back()->with('success', 'Comment submitted successfully! It will be visible after approval.');
    }

    /**
     * Remove the specified comment from storage.
     */
    public function destroy(Comment $comment)
    {
        // Verifică dacă utilizatorul curent este proprietarul comentariului SAU un administrator
        if ($comment->user_id !== auth()->id() && !auth()->user()->is_admin) {
            abort(403); // Forbidden - acces neautorizat
        }

        // Șterge comentariul
        $comment->delete();

        // Redirecționează înapoi la pagina anterioară cu un mesaj de succes
        return back()->with('success', 'Comment deleted successfully.');
    }
}