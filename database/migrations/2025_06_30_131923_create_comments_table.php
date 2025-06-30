<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->text('content'); // Conținutul comentariului
            $table->boolean('is_approved')->default(false); // Starea de aprobare a comentariului
            $table->foreignId('post_id')->constrained()->onDelete('cascade'); // Cheie externă către tabelul 'posts'
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Cheie externă către tabelul 'users'
            $table->foreignId('parent_id')->nullable()->constrained('comments')->onDelete('cascade'); // Cheie externă pentru comentarii părinte (pentru răspunsuri)
            $table->timestamps(); // Coloanele created_at și updated_at

            // Adăugarea indexurilor pentru performanță
            $table->index(['post_id', 'is_approved']);
            $table->index('parent_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};