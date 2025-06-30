<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment; // Asigură-te că Comment este importat
use Illuminate\Http\Request; // Asigură-te că Request este importat

class CommentController extends Controller
{
    /**
     * Display a listing of the resource (all comments for admin).
     */
    public function index()
    {
        // Obține toate comentariile cu utilizatorul și postarea asociate, ordonate după cele mai recente, paginare 10
        $comments = Comment::with(['user', 'post'])
            ->latest()
            ->paginate(10);

        // Returnează vederea de index pentru comentarii în admin
        return view('admin.comments.index', compact('comments'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Comment $comment)
    {
        // Încarcă utilizatorul, postarea și comentariul părinte (cu utilizatorul său) pentru comentariu
        $comment->load(['user', 'post', 'parent.user']);
        // Returnează vederea de vizualizare a unui comentariu în admin
        return view('admin.comments.show', compact('comment'));
    }

    /**
     * Toggle the approval status of the specified comment.
     */
    public function approve(Comment $comment)
    {
        // Inversează starea de aprobare a comentariului
        $comment->update(['is_approved' => !$comment->is_approved]);

        // Determină mesajul de succes bazat pe noua stare
        $status = $comment->is_approved ? 'approved' : 'unapproved';
        // Redirecționează înapoi la pagina anterioară cu un mesaj de succes
        return back()->with('success', "Comment {$status} successfully.");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Comment $comment)
    {
        // Șterge comentariul
        $comment->delete();
        // Redirecționează către lista de comentarii cu un mesaj de succes
        return redirect()->route('admin.comments.index')->with('success', 'Comment deleted successfully.');
    }
}