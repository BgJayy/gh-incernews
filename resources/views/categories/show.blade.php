@extends('layouts.site')

@section('title', $category->name.' — Incer News')
@section('description', $category->description ?: 'Berita '.$category->name.' di Incer News')
@section('canonical', route('categories.show', $category->slug))

@section('content')
    <div class="mx-auto max-w-[1400px] px-4 py-6 md:px-6 lg:px-8">
        <section class="relative mb-6 overflow-hidden rounded-xl {{ $category->accentClass() }} p-6 text-white md:p-10">
            <nav aria-label="Breadcrumb" class="mb-4 flex items-center gap-2 text-xs text-white/80">
                <a href="{{ route('home') }}" class="hover:text-white">Beranda</a>
                <span>/</span>
                <span class="font-bold uppercase tracking-wider">{{ $category->name }}</span>
            </nav>
            <h1 class="text-3xl font-extrabold tracking-tight md:text-4xl">Kategori: {{ $category->name }}</h1>
            @if ($category->description)
                <p class="mt-3 max-w-2xl text-lg text-white/90">{{ $category->description }}</p>
            @endif
        </section>

        @if ($articles->isEmpty())
            <p class="py-16 text-outline">Belum ada berita di kategori ini.</p>
        @else
            <p class="mb-4 text-sm text-on-surface-variant">{{ $articles->total() }} berita terbit</p>
            <div class="flex flex-col gap-4">
                @foreach ($articles as $article)
                    <x-story-card :article="$article" variant="horizontal" />
                @endforeach
            </div>
            <div class="mt-8">{{ $articles->links() }}</div>
        @endif
    </div>
@endsection
