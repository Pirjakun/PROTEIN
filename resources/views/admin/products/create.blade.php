@extends('layouts.app')

@section('title', 'Add Product - Buitenworks')

@section('content')
    <main class="container py-5" style="margin-top: 80px;">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Add New Product</h5>
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

                        <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="mb-3">
                                <label class="form-label">Name</label>
                                <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Category</label>
                                <select name="category" class="form-select" required>
                                    <option value="">Select Category</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->name }}" {{ old('category') == $category->name ? 'selected' : '' }}>
                                            {{ ucfirst($category->name) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Price</label>
                                <input type="number" name="price" class="form-control" value="{{ old('price') }}" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Description</label>
                                <textarea name="description" class="form-control"
                                    rows="3">{{ old('description') }}</textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Stock</label>
                                <input type="number" name="stock" class="form-control" value="{{ old('stock', 10) }}">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Shopee Link</label>
                                <input type="url" name="shopee_link" class="form-control" value="{{ old('shopee_link') }}"
                                    placeholder="https://shopee.co.id/...">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Image (Thumbnail)</label>
                                <input type="file" name="image" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Gallery Images (Optional)</label>
                                <input type="file" name="gallery[]" class="form-control" multiple>
                                <div class="form-text">You can select multiple files.</div>
                            </div>

                            <button type="submit" class="btn btn-dark">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection