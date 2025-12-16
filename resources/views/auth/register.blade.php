@extends('layouts.app')

@section('title', 'Register - Buitenworks')

@section('content')
    <div class="container py-5 d-flex justify-content-center align-items-center" style="min-height: 60vh;">
        <div class="card border-0 shadow-lg p-4" style="max-width: 400px; width: 100%; border-radius: 16px;">
            <h2 class="text-center fw-bold text-uppercase mb-4" style="font-family: 'Oswald', sans-serif;">Sign Up</h2>

            <form action="{{ route('register') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label fw-bold small">Full Name</label>
                    <input type="text" class="form-control rounded-3" id="name" name="name" value="{{ old('name') }}"
                        required autofocus>
                    @error('name')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label fw-bold small">Email Address</label>
                    <input type="email" class="form-control rounded-3" id="email" name="email" value="{{ old('email') }}"
                        required>
                    @error('email')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="password" class="form-label fw-bold small">Password</label>
                    <input type="password" class="form-control rounded-3" id="password" name="password" required>
                    <div class="form-text small">Must be at least 6 characters.</div>
                    @error('password')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>
                <div class="d-grid mb-3">
                    <button type="submit" class="btn btn-dark fw-bold py-2 rounded-3">Create Account</button>
                </div>
            </form>

            <div class="text-center small">
                <span class="text-muted">Already have an account?</span>
                <a href="{{ route('login') }}" class="text-dark fw-bold text-decoration-none">Login</a>
            </div>
        </div>
    </div>
@endsection