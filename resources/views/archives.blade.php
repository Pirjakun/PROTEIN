@extends('layouts.app')

@section('title', 'Archives - Buitenworks')

@section('content')
    <main class="w-100 py-5">
        <div class="container px-5 px-md-0">
            <header class="blog-header text-center mb-5">
                <h1 class="font-weight-bold" style="font-family: 'Oswald', sans-serif;">SHOP BLOG</h1>
                <p class="text-muted">Discover our latest events, stories, and promotions here!</p>
            </header>

            @if($archives->count() > 0)
                <div class="row g-4">
                    @foreach($archives as $archive)
                        <div class="col-lg-4 col-md-6">
                            <a href="{{ $archive->link }}" target="_blank" class="text-decoration-none text-dark">
                                <div class="card border-0 shadow-sm h-100 overflow-hidden hover-zoom">
                                    <div class="row g-0 h-100 align-items-center">
                                        <!-- Image Side -->
                                        <div class="col-8 h-100">
                                            <div class="h-100 w-100 position-relative" style="min-height: 300px;">
                                                <img src="{{ asset('assets/archives/' . $archive->image) }}"
                                                    class="object-fit-cover position-absolute top-0 start-0 w-100 h-100"
                                                    alt="{{ $archive->title ?? 'Archive' }}">
                                            </div>
                                        </div>
                                        <!-- Content Side -->
                                        <div class="col-4">
                                            <div class="card-body d-flex flex-column h-100 justify-content-center p-4">
                                                <h5 class="card-title fw-bold mb-3"
                                                    style="font-family: 'Oswald', sans-serif; font-size: 1.25rem;">
                                                    {{ $archive->title }}
                                                </h5>
                                                <p class="card-text text-muted small"
                                                    style="display: -webkit-box; -webkit-line-clamp: 4; -webkit-box-orient: vertical; overflow: hidden;">
                                                    {{ $archive->description }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            @else
                <section class="text-center py-5">
                    <div class="mb-3" style="font-size: 3rem;">📦</div>
                    <h2>No Blog Posts Yet</h2>
                    <p class="text-muted">Stay tuned for our latest updates and stories.</p>
                </section>
            @endif
        </div>
    </main>
@endsection