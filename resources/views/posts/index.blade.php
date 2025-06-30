@extends('layouts.blog') {{-- Extinde layout-ul blogului --}}

@section('title', 'Laravel Blog Tutorial') {{-- Setează titlul paginii --}}
@section('description', 'Welcome to our Laravel blog tutorial. Learn Laravel and Livewire step by step.') {{-- Setează descrierea paginii --}}

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Hero Section -->
    <div class="text-center mb-12">
        <h1 class="text-4xl font-bold text-gray-900 mb-4">
            Welcome to Laravel Blog Tutorial
        </h1>
        <p class="text-xl text-gray-600 max-w-2xl mx-auto">
            Learn how to build a complete blog application with Laravel 11 and Livewire 3.
            Follow our step-by-step tutorials and master modern web development.
        </p>
    </div>

    <!-- Posts Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @forelse($posts as $post) {{-- Iterează prin fiecare postare --}}
            <article class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
                @if($post->featured_image) {{-- Afișează imaginea principală dacă există --}}
                    <img src="{{ $post->featured_image }}" alt="{{ $post->title }}"
                         class="w-full h-48 object-cover">
                @endif

                <div class="p-6">
                    <div class="flex items-center text-sm text-gray-500 mb-2">
                        <span>{{ $post->user->name }}</span> {{-- Numele autorului --}}
                        <span class="mx-2">&#8226;</span> {{-- Punct separator --}}
                        <time datetime="{{ $post->published_at->toISOString() }}">
                            {{ $post->published_at->format('M j, Y') }} {{-- Data publicării --}}
                        </time>
                    </div>

                    <h2 class="text-xl font-semibold text-gray-900 mb-3">
                        <a href="{{ route('posts.show', $post) }}" class="hover:text-blue-600">
                            {{ $post->title }}
                        </a>
                    </h2>

                    @if($post->excerpt) {{-- Afișează excerpt-ul dacă există --}}
                        <p class="text-gray-600 mb-4">
                            {{ Str::limit($post->excerpt, 120) }} {{-- Limitează excerpt-ul la 120 de caractere --}}
                        </p>
                    @endif

                    <div class="flex items-center justify-between">
                        <a href="{{ route('posts.show', $post) }}"
                           class="text-blue-600 hover:text-blue-800 font-medium">
                            Read More &rarr; {{-- Săgeată HTML corectă --}}
                        </a>

                        <span class="text-sm text-gray-500">
                            {{ $post->comments_count ?? $post->approvedComments->count() }} comments {{-- Numărul de comentarii --}}
                        </span>
                    </div>
                </div>
            </article>
        @empty {{-- Dacă nu există postări --}}
            <div class="col-span-3 text-center py-12">
                <h3 class="text-lg font-medium text-gray-900 mb-2">No posts yet</h3>
                <p class="text-gray-600">Check back later for new content!</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($posts->hasPages()) {{-- Afișează paginarea dacă există mai multe pagini --}}
        <div class="mt-12">
            {{ $posts->links() }} {{-- Link-urile de paginare Laravel --}}
        </div>
    @endif
</div>
@endsection