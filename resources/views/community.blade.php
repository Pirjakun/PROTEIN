@extends('layouts.app')

@section('title', 'Community - Buitenworks')

@section('content')
    <main class="w-100">
        <section class="container px-5 px-md-0">
            <div class="row g-0">
                @foreach($communities as $community)
                    <div class="col-12 col-md-4">
                        <div class="ratio ratio-1x1">
                            <img src="{{ asset('assets/community/' . $community->image) }}" alt="Community Photo"
                                class="object-fit-cover w-100 h-100">
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
    </main>
@endsection