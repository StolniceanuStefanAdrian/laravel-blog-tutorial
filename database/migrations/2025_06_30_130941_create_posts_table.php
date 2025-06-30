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
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique(); // Slug-ul pentru URL-uri prietenoase, trebuie să fie unic
            $table->text('excerpt')->nullable(); // Un scurt rezumat al postării
            $table->longText('content'); // Conținutul complet al postării
            $table->string('featured_image')->nullable(); // Calea către imaginea principală a postării
            $table->boolean('is_published')->default(false); // Starea de publicare a postării
            $table->timestamp('published_at')->nullable(); // Data și ora publicării
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Cheie externă către tabelul 'users'
            $table->timestamps(); // Coloanele created_at și updated_at
            
            // Adăugarea indexurilor pentru performanță la interogări
            $table->index(['is_published', 'published_at']);
            $table->index('slug');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};