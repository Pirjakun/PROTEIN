@extends('layouts.app')

@section('title', 'Manage Categories')

@section('content')
    <div class="container py-5" style="margin-top: 60px;">
        <h2 class="mb-4 fw-bold">Manage Categories</h2>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row">
            <!-- Create Category Form -->
            <div class="col-md-4 mb-4">
                <div class="card border-0 shadow-sm p-4">
                    <h5 class="fw-bold mb-3">Add New Category</h5>
                    <form action="{{ route('admin.categories.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label">Category Name</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                                name="name" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-dark w-100">Add Category</button>
                    </form>
                </div>
            </div>

            <!-- Categories List -->
            <div class="col-md-8">
                <div class="card border-0 shadow-sm p-4">
                    <h5 class="fw-bold mb-3">All Categories</h5>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 100%;">Category Name & Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($categories as $category)
                                    <tr>
                                        <td>
                                            <form action="{{ route('admin.categories.update', $category->id) }}" method="POST"
                                                class="d-flex gap-2">
                                                @csrf
                                                @method('PUT')
                                                <input type="text" class="form-control form-control-sm" name="name"
                                                    value="{{ $category->name }}">
                                                <button type="submit" class="btn btn-sm btn-outline-dark">Update</button>
                                                <button type="button" class="btn btn-sm btn-danger ms-1"
                                                    onclick="confirmDelete('{{ route('admin.categories.destroy', $category->id) }}')">
                                                    Delete
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                                @if($categories->isEmpty())
                                    <tr>
                                        <td colspan="2" class="text-center text-muted">No categories found.</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Custom Delete Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4 p-3">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold text-danger d-flex align-items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                            class="bi bi-exclamation-octagon-fill" viewBox="0 0 16 16">
                            <path
                                d="M11.46.146A.5.5 0 0 0 11.107 0H4.893a.5.5 0 0 0-.353.146L.146 4.54A.5.5 0 0 0 0 4.893v6.214a.5.5 0 0 0 .146.353l4.394 4.394a.5.5 0 0 0 .353.146h6.214a.5.5 0 0 0 .353-.146l4.394-4.394a.5.5 0 0 0 .146-.353V4.893a.5.5 0 0 0-.146-.353L11.46.146zM8 4c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z" />
                        </svg>
                        Delete Confirmation
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body py-4">
                    <p class="mb-0 fs-5 text-dark">Are you really sure you want to delete this category?</p>
                    <p class="text-muted small mb-0 mt-2">This process cannot be undone.</p>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light px-4 rounded-pill fw-bold"
                        data-bs-dismiss="modal">Cancel</button>
                    <form id="deleteForm" method="POST" action="">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger px-4 rounded-pill fw-bold">Yes, Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function confirmDelete(actionUrl) {
            const deleteForm = document.getElementById('deleteForm');
            deleteForm.action = actionUrl;
            const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
            modal.show();
        }
    </script>
@endsection