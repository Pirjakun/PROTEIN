@extends('layouts.app')

@section('title', 'My Profile - Buitenworks')

@section('content')
    <main class="container py-5" style="margin-top: 20px;">
        <h2 class="mb-4 fw-bold text-center">My Profile</h2>

        <div class="row g-5">
            <!-- User Information & Password -->
            <div class="col-lg-6 mx-auto">
                <div class="card border-0 shadow-sm p-4">
                    <h5 class="fw-bold mb-4">Edit Information</h5>

                    <form action="{{ route('profile.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="name" class="form-label small text-muted">Name</label>
                            <input type="text" class="form-control" id="name" name="name"
                                value="{{ old('name', $user->name) }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label small text-muted">Email Address</label>
                            <input type="email" class="form-control" id="email" name="email"
                                value="{{ old('email', $user->email) }}" required>
                        </div>

                        <hr class="my-4">

                        <h5 class="fw-bold mb-3">Change Password</h5>
                        <p class="small text-muted mb-3">Leave blank if you don't want to change it.</p>

                        <div class="mb-3">
                            <label for="password" class="form-label small text-muted">New Password</label>
                            <input type="password" class="form-control" id="password" name="password">
                        </div>

                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label small text-muted">Confirm New
                                Password</label>
                            <input type="password" class="form-control" id="password_confirmation"
                                name="password_confirmation">
                        </div>

                        <button type="submit" class="btn btn-dark w-100 mt-2">Update Profile</button>
                    </form>
                </div>
            </div>
        </div>
    </main>
@endsection