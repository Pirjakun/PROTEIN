@extends('layouts.app')

@section('content')
    <div id="homeCarousel" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#homeCarousel" data-bs-slide-to="0" aria-label="Slide 1"></button>
            <button type="button" data-bs-target="#homeCarousel" data-bs-slide-to="1" class="active" aria-current="true"
                aria-label="Slide 2"></button>
            <button type="button" data-bs-target="#homeCarousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
        </div>
        <div class="carousel-inner">
            <div class="carousel-item" data-bs-interval="10000">
                <img src="{{ asset('assets/Landing Page Buitenworks Final/5.png') }}" class="d-block w-100" alt="Banner 1">
            </div>
            <div class="carousel-item active" data-bs-interval="10000">
                <img src="{{ asset('assets/Landing Page Buitenworks Final/6.png') }}" class="d-block w-100" alt="Banner 2">
            </div>
            <div class="carousel-item" data-bs-interval="10000">
                <img src="{{ asset('assets/Landing Page Buitenworks Final/7.png') }}" class="d-block w-100" alt="Banner 3">
            </div>
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
            <div class="col-md-4 col-sm-6">
                <div class="card border-0 h-100">
                    <img src="{{ asset('assets/prod1.png') }}" class="card-img-top object-fit-contain" alt="">
                    <div class="card-body px-0">
                        <div class="card-title h6 fw-bold text-uppercase">Leather Crop Racing Jacket - Black</div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-sm-6">
                <div class="card border-0 h-100">
                    <img src="{{ asset('assets/prod2.png') }}" class="card-img-top object-fit-contain" alt="">
                    <div class="card-body px-0">
                        <div class="card-title h6 fw-bold text-uppercase">Leather Crop Racing Jacket - Red</div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-sm-6">
                <div class="card border-0 h-100">
                    <img src="{{ asset('assets/prod3.png') }}" class="card-img-top object-fit-contain" alt="">
                    <div class="card-body px-0">
                        <div class="card-title h6 fw-bold text-uppercase">Jersey Track Set - Black</div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection