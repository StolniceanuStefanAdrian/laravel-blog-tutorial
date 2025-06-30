<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    // Atribute care pot fi asignate în masă
    protected $fillable = [
        'content',
        'is_approved',
        'post_id',
        'user_id',
        'parent_id',
    ];

    // Atribute care ar trebui să fie convertite automat la tipuri specifice
    protected $casts = [
        'is_approved' => 'boolean',
    ];

    /**
     * Obține postarea căreia îi aparține comentariul.
     */
    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    /**
     * Obține utilizatorul care a creat comentariul.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Obține comentariul părinte (dacă acesta este un răspuns).
     */
    public function parent()
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }

    /**
     * Obține răspunsurile la acest comentariu.
     */
    public function replies()
    {
        return $this->hasMany(Comment::class, 'parent_id');
    }

    /**
     * Obține doar răspunsurile aprobate la acest comentariu.
     */
    public function approvedReplies()
    {
        return $this->hasMany(Comment::class, 'parent_id')->where('is_approved', true);
    }

    /**
     * Scope de interogare pentru a filtra doar comentariile aprobate.
     */
    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }

    /**
     * Scope de interogare pentru a filtra comentariile de nivel superior (fără părinte).
     */
    public function scopeParent($query)
    {
        return $query->whereNull('parent_id');
    }
}