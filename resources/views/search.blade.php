@extends('layouts.site')

@section('title', ($query !== '' ? 'Cari: '.$query.' — ' : 'Cari — ').'Incer News')
@section('description', 'Cari berita terbit di Incer News.')
@section('canonical', route('search'))

@section('content')
    <div class="mx-auto max-w-[1400px] px-4 py-6 md:px-6 lg:px-8">
        <nav aria-label="Breadcrumb" class="mb-4 flex items-center gap-2 text-xs text-on-surface-variant">
            <a href="{{ route('home') }}" class="hover:text-primary">Beranda</a>
            <span>/</span>
            <span class="font-bold text-on-surface">Cari berita</span>
        </nav>

        <header class="mb-6 rounded-xl bg-surface-lowest p-6 shadow-sm md:p-8">
            <h1 class="text-3xl font-extrabold tracking-tight md:text-4xl">Cari berita</h1>
            <p class="mt-2 text-sm text-on-surface-variant">Cari judul atau ringkasan berita yang sudah terbit.</p>
            <form action="{{ route('search') }}" method="get" class="mt-6 flex max-w-2xl flex-col gap-2 sm:flex-row">
                <label class="sr-only" for="search-q">Kata kunci</label>
                <input id="search-q" name="q" value="{{ $query }}" type="search" placeholder="Judul atau isi berita" class="h-11 flex-1 rounded border border-outline-variant bg-surface-low px-4 text-sm outline-none focus:border-primary focus:ring-2 focus:ring-primary">
                <button class="h-11 shrink-0 rounded bg-primary px-5 text-sm font-semibold whitespace-nowrap text-white hover:bg-primary-container">Cari</button>
            </form>
        </header>

        @if ($query === '')
            <p class="text-on-surface-variant">Ketik kata kunci untuk mencari berita yang sudah terbit.</p>
        @elseif ($articles->isEmpty())
            <p class="text-on-surface-variant">Tidak ada berita untuk “{{ $query }}”.</p>
        @else
            <p class="mb-4 text-sm text-on-surface-variant">{{ $articles->total() }} berita untuk “{{ $query }}”</p>
            <div class="flex flex-col gap-4">
                @foreach ($articles as $article)
                    <x-story-card :article="$article" variant="horizontal" />
                @endforeach
            </div>
            <div class="mt-8">{{ $articles->links() }}</div>
        @endif
    </div>
@endsection
