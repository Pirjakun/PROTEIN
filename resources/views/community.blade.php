@extends('layouts.app')

@section('title', 'Community - Buitenworks')

@section('content')
    <main class="community-page">
        <header class="community-header">
            <h1>Community</h1>
            <p>See how our pieces live in the wild – styled by our community.</p>
        </header>

        <section class="masonry-grid">
            <article class="masonry-item">
                <img src="{{ asset('assets/community1.jpg') }}" alt="Street fit look 1">
                <div class="masonry-meta">
                    <h3>Street Fit 001</h3>
                    <p>Layered leather and jersey for late-night city rides.</p>
                </div>
            </article>

            <article class="masonry-item">
                <img src="{{ asset('assets/community2.jpg') }}" alt="Night ride look">
                <div class="masonry-meta">
                    <h3>Night Ride</h3>
                    <p>Statement jacket with clean monochrome base.</p>
                </div>
            </article>

            <article class="masonry-item">
                <img src="{{ asset('assets/community3.jpg') }}" alt="Studio session outfit">
                <div class="masonry-meta">
                    <h3>Studio Session</h3>
                    <p>Relaxed track set for long days in the booth.</p>
                </div>
            </article>

            <article class="masonry-item">
                <img src="{{ asset('assets/community4.jpg') }}" alt="Match day outfit">
                <div class="masonry-meta">
                    <h3>Match Day</h3>
                    <p>Sport-inspired layers built around our racing jacket.</p>
                </div>
            </article>

            <article class="masonry-item">
                <img src="{{ asset('assets/community5.jpg') }}" alt="City walk outfit">
                <div class="masonry-meta">
                    <h3>City Walk</h3>
                    <p>Everyday uniform with subtle contrast details.</p>
                </div>
            </article>

            <article class="masonry-item">
                <img src="{{ asset('assets/community6.jpg') }}" alt="Weekend mood outfit">
                <div class="masonry-meta">
                    <h3>Weekend Mood</h3>
                    <p>Easy throw-on set for slow days and quick coffee runs.</p>
                </div>
            </article>
        </section>
    </main>
@endsection