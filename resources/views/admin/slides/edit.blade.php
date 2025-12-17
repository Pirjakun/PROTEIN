@extends('layouts.app')

@section('content')
    <div class="container py-5" style="margin-top: 60px;">
        <h2>Edit Slide</h2>
        <div class="card border-0 shadow-sm mt-4">
            <div class="card-body">
                <form action="{{ route('slides.update', $slide->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">Current Image</label>
                        <div class="mb-2">
                            <img src="{{ asset('assets/slides/' . $slide->image) }}" class="img-fluid rounded"
                                style="max-height: 200px;" alt="Current Slide">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="image" class="form-label">Replace Image (Optional)</label>
                        <input type="file" class="form-control" id="image" name="image">
                        <div class="form-text">Leave blank to keep current image.</div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-dark">Update Slide</button>
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection