@extends('layouts.app')

@section('content')
    <div id="homeCarousel" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-indicators">
            @if($slides->count() > 0)
                @foreach($slides as $key => $slide)
                    <button type="button" data-bs-target="#homeCarousel" data-bs-slide-to="{{ $key }}"
                        class="{{ $key == 0 ? 'active' : '' }}" aria-label="Slide {{ $key + 1 }}"></button>
                @endforeach
            @else
                <!-- Fallback Indicators -->
                <button type="button" data-bs-target="#homeCarousel" data-bs-slide-to="0" aria-label="Slide 1"></button>
                <button type="button" data-bs-target="#homeCarousel" data-bs-slide-to="1" class="active" aria-current="true"
                    aria-label="Slide 2"></button>
                <button type="button" data-bs-target="#homeCarousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
            @endif
        </div>
        <div class="carousel-inner">
            @if($slides->count() > 0)
                @foreach($slides as $key => $slide)
                    <div class="carousel-item {{ $key == 0 ? 'active' : '' }}" data-bs-interval="5000">
                        <img src="{{ asset('assets/slides/' . $slide->image) }}" class="d-block w-100 object-fit-cover"
                            alt="Slide {{ $key + 1 }}">
                    </div>
                @endforeach
            @else
                <!-- Fallback Images -->
                <div class="carousel-item" data-bs-interval="10000">
                    <img src="{{ asset('assets/slides/5.png') }}" class="d-block w-100" alt="Banner 1">
                </div>
                <div class="carousel-item active" data-bs-interval="10000">
                    <img src="{{ asset('assets/slides/6.png') }}" class="d-block w-100" alt="Banner 2">
                </div>
                <div class="carousel-item" data-bs-interval="10000">
                    <img src="{{ asset('assets/slides/7.png') }}" class="d-block w-100" alt="Banner 3">
                </div>
            @endif
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#homeCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#homeCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>

    <main class="container py-5">
        <h2 class="mb-4">Featured</h2>
        <div class="row g-4">
            @foreach($featuredProducts as $product)
                <div class="col-md-4 col-sm-6">
                    <a href="{{ route('products.show', $product->id) }}" class="text-decoration-none text-dark">
                        <div class="card border-0 h-100">
                            <div class="ratio ratio-1x1 bg-white rounded">
                                <img src="{{ asset('assets/products/' . $product->image) }}"
                                    class="card-img-top object-fit-contain p-3" alt="{{ $product->name }}">
                            </div>
                            <div class="card-body px-0">
                                <div class="card-title h6 fw-bold text-uppercase">{{ $product->name }}</div>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </main>
@endsection