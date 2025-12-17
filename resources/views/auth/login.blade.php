@extends('layouts.app')

@section('title', 'Login - Buitenworks')

@section('content')
    <div class="container h-100 d-flex flex-column justify-content-center align-items-center py-5">
        <div class="card border-0 shadow-lg p-4" style="max-width: 400px; width: 100%; border-radius: 16px;">
            <h2 class="text-center fw-bold text-uppercase mb-4" style="font-family: 'Oswald', sans-serif;">Login</h2>

            <form action="{{ route('login') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="email" class="form-label fw-bold small">Email Address</label>
                    <input type="email" class="form-control rounded-3" id="email" name="email" value="{{ old('email') }}"
                        required autofocus>
                    @error('email')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="password" class="form-label fw-bold small">Password</label>
                    <input type="password" class="form-control rounded-3" id="password" name="password" required>
                </div>
                <div class="d-grid mb-3">
                    <button type="submit" class="btn btn-dark fw-bold py-2 rounded-3">Login</button>
                </div>
            </form>

            <div class="text-center small">
                <span class="text-muted">Don't have an account?</span>
                <a href="{{ route('register') }}" class="text-dark fw-bold text-decoration-none">Sign Up</a>
            </div>
        </div>
    </div>
@endsection