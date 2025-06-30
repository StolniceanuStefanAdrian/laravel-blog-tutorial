<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str; // Asigură-te că aceasta este inclusă pentru funcția Str::slug

class Post extends Model
{
    use HasFactory;

    // Atribute care pot fi asignate în masă (mass assignable)
    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'content',
        'featured_image',
        'is_published',
        'published_at',
        'user_id',
    ];

    // Atribute care ar trebui să fie convertite automat la tipuri specifice
    protected $casts = [
        'is_published' => 'boolean',
        'published_at' => 'datetime',
    ];

    /**
     * Metoda "boot" a modelului.
     * Folosită pentru a înregistra evenimente de model.
     */
    protected static function boot()
    {
        parent::boot();

        // Generare slug la crearea postării dacă nu există deja
        static::creating(function ($post) {
            if (empty($post->slug)) {
                $post->slug = Str::slug($post->title);
            }
        });

        // Regenerare slug la actualizare dacă titlul s-a schimbat și slug-ul este gol
        static::updating(function ($post) {
            if ($post->isDirty('title') && empty($post->slug)) {
                $post->slug = Str::slug($post->title);
            }
        });
    }

    /**
     * Obține utilizatorul care a creat postarea.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Obține comentariile asociate acestei postări.
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Obține doar comentariile aprobate asociate acestei postări.
     */
    public function approvedComments()
    {
        return $this->hasMany(Comment::class)->where('is_approved', true);
    }

    /**
     * Scope de interogare pentru a filtra postările publicate.
     */
    public function scopePublished($query)
    {
        return $query->where('is_published', true)
                    ->whereNotNull('published_at')
                    ->where('published_at', '<=', now());
    }

    /**
     * Obține numele cheii de rută pentru model (untuk rute implicite).
     * Va folosi 'slug' în loc de 'id' în URL-uri.
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }
}