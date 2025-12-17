@extends('layouts.app')

@section('title', 'Edit Archive - Buitenworks')

@section('content')
    <main class="container py-5" style="margin-top: 80px;">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center py-3">
                        <h5 class="mb-0 fw-bold">Edit Archive Content</h5>
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-sm btn-secondary">Back</a>
                    </div>
                    <div class="card-body">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('archives.update', $archive->id) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label class="form-label">Link</label>
                                <input type="url" name="link" class="form-control" value="{{ old('link', $archive->link) }}"
                                    required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Title / Caption</label>
                                <input type="text" name="title" class="form-control"
                                    value="{{ old('title', $archive->title) }}">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Description</label>
                                <textarea name="description" class="form-control"
                                    rows="4">{{ old('description', $archive->description) }}</textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Image</label>
                                <input type="file" name="image" class="form-control">
                                <div class="form-text">Leave blank to keep current image.</div>
                                <img src="{{ asset('assets/archives/' . $archive->image) }}" class="mt-2 rounded"
                                    width="150">
                            </div>

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-dark">Update Content</button>
                                <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection