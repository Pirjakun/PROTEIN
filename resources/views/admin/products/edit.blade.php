@extends('layouts.app')

@section('title', 'Edit Product - Buitenworks')

@section('content')
    <main class="container py-5" style="margin-top: 80px;">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Edit Product</h5>
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

                        <form action="{{ route('products.update', $product->id) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label class="form-label">Name</label>
                                <input type="text" name="name" class="form-control"
                                    value="{{ old('name', $product->name) }}" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Category</label>
                                <select name="category" class="form-select" required>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->name }}" {{ old('category', $product->category) == $category->name ? 'selected' : '' }}>
                                            {{ ucfirst($category->name) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Price</label>
                                <input type="number" name="price" class="form-control"
                                    value="{{ old('price', $product->price) }}" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Description</label>
                                <textarea name="description" class="form-control"
                                    rows="3">{{ old('description', $product->description) }}</textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Stock</label>
                                <input type="number" name="stock" class="form-control"
                                    value="{{ old('stock', $product->stock) }}">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Shopee Link</label>
                                <input type="url" name="shopee_link" class="form-control"
                                    value="{{ old('shopee_link', $product->shopee_link) }}">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Image (Thumbnail)</label>
                                <input type="file" name="image" class="form-control">
                                <div class="form-text">Leave blank to keep current image.</div>
                                <img src="{{ asset('assets/products/' . $product->image) }}" class="mt-2" width="100">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Gallery Images</label>
                                <input type="file" name="gallery[]" class="form-control mb-2" multiple>
                                <div class="form-text">Upload new images to append to the existing gallery.</div>
                                <div class="d-flex gap-2 flex-wrap mt-2">
                                    @if($product->gallery)
                                        @foreach($product->gallery as $galImage)
                                            <div class="position-relative text-center">
                                                <img src="{{ asset('assets/products/' . $galImage) }}" class="img-thumbnail"
                                                    width="100" style="width: 100px; height: 100px; object-fit: cover;">
                                                <div class="mt-1">
                                                    <input type="checkbox" name="delete_gallery[]" value="{{ $galImage }}"
                                                        id="del_{{ $loop->index }}" class="form-check-input">
                                                    <label for="del_{{ $loop->index }}"
                                                        class="form-check-label small text-danger">Delete</label>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-dark">Update</button>
                                <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.querySelector('form');
            form.addEventListener('submit', function (e) {
                const deleteCheckboxes = document.querySelectorAll('input[name="delete_gallery[]"]:checked');
                if (deleteCheckboxes.length > 0) {
                    if (!confirm('Are you sure you want to delete ' + deleteCheckboxes.length + ' gallery image(s)? This action cannot be undone.')) {
                        e.preventDefault();
                    }
                }
            });
        });
    </script>
@endpush