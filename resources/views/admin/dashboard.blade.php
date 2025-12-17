@extends('layouts.app')

@section('title', 'Admin Dashboard - Buitenworks')

@section('content')
    <main class="container py-4" style="margin-top: 10px;">
        <!-- Slideshow Management Section -->
        <div class="card border-0 shadow-sm mb-5">
            <div class="card-header bg-white border-0 py-3">
                <h4 class="mb-0 fw-bold">Homepage Slideshow</h4>
            </div>
            <div class="card-body">
                @if ($errors->any())
                    <div id="global-alert-error" class="position-fixed top-0 start-50 translate-middle-x p-3 mt-5"
                        style="z-index: 2000;">
                        <div class="alert alert-danger alert-dismissible fade show shadow-lg border-0 rounded-0" role="alert">
                            <div class="d-flex align-items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor"
                                    class="bi bi-exclamation-octagon-fill" viewBox="0 0 16 16">
                                    <path
                                        d="M11.46.146A.5.5 0 0 0 11.107 0H4.893a.5.5 0 0 0-.353.146L.146 4.54A.5.5 0 0 0 0 4.893v6.214a.5.5 0 0 0 .146.353l4.394 4.394a.5.5 0 0 0 .353.146h6.214a.5.5 0 0 0 .353-.146l4.394-4.394a.5.5 0 0 0 .146-.353V4.893a.5.5 0 0 0-.146-.353L11.46.146zM8 4c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z" />
                                </svg>
                                <div class="fw-semibold">
                                    <ul class="mb-0 list-unstyled">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    </div>
                    <script>
                        setTimeout(function () {
                            var alertNode = document.getElementById('global-alert-error');
                            if (alertNode) {
                                var bsAlert = new bootstrap.Alert(alertNode.querySelector('.alert'));
                                bsAlert.close();
                            }
                        }, 5000); // 5 seconds for errors
                    </script>
                @endif
                <!-- Upload Form -->
                <form action="{{ route('slides.store') }}" method="POST" enctype="multipart/form-data"
                    class="d-flex gap-2 mb-4 align-items-end">
                    @csrf
                    <div class="flex-grow-1">
                        <label for="slideImage" class="form-label small text-muted">Upload New Slide (1920x1080
                            recommended)</label>
                        <input type="file" class="form-control" id="slideImage" name="image" required>
                    </div>
                    <button type="submit" class="btn btn-dark">Upload Slide</button>
                </form>

                <!-- Slides List -->
                <div class="row g-3">
                    @forelse($slides as $slide)
                        <div class="col-md-3 col-6">
                            <div class="position-relative group">
                                <img src="{{ asset('assets/slides/' . $slide->image) }}" class="img-fluid rounded border"
                                    alt="Slide">

                                <a href="{{ route('slides.edit', $slide->id) }}"
                                    class="position-absolute top-0 start-0 m-2 btn btn-light btn-sm rounded-circle p-2 lh-1 shadow-sm"
                                    style="width: 32px; height: 32px;" title="Edit">
                                    ✎
                                </a>

                                <button type="button"
                                    class="position-absolute top-0 end-0 m-2 btn btn-danger btn-sm rounded-circle p-2 lh-1 shadow-sm"
                                    style="width: 32px; height: 32px;" title="Delete"
                                    onclick="confirmDelete('{{ route('slides.destroy', $slide->id) }}', 'slide')">
                                    ✕
                                </button>
                            </div>
                        </div>
                    @empty
                        <div class="col-12 text-muted small">No slides uploaded. Default slides will be shown on homepage.</div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Archives Management Section -->
        <div class="card border-0 shadow-sm mb-5">
            <div class="card-header bg-white border-0 py-3">
                <h4 class="mb-0 fw-bold">Shop Blog / Archives</h4>
            </div>
            <div class="card-body">
                @if ($errors->any())
                    <div id="global-alert-error" class="position-fixed top-0 start-50 translate-middle-x p-3 mt-5"
                        style="z-index: 2000;">
                        <div class="alert alert-danger alert-dismissible fade show shadow-lg border-0 rounded-0" role="alert">
                            <div class="d-flex align-items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor"
                                    class="bi bi-exclamation-octagon-fill" viewBox="0 0 16 16">
                                    <path
                                        d="M11.46.146A.5.5 0 0 0 11.107 0H4.893a.5.5 0 0 0-.353.146L.146 4.54A.5.5 0 0 0 0 4.893v6.214a.5.5 0 0 0 .146.353l4.394 4.394a.5.5 0 0 0 .353.146h6.214a.5.5 0 0 0 .353-.146l4.394-4.394a.5.5 0 0 0 .146-.353V4.893a.5.5 0 0 0-.146-.353L11.46.146zM8 4c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z" />
                                </svg>
                                <div class="fw-semibold">
                                    <ul class="mb-0 list-unstyled">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    </div>
                    <script>
                        setTimeout(function () {
                            var alertNode = document.getElementById('global-alert-error');
                            if (alertNode) {
                                var bsAlert = new bootstrap.Alert(alertNode.querySelector('.alert'));
                                bsAlert.close();
                            }
                        }, 5000); // 5 seconds for errors
                    </script>
                @endif
                <!-- Upload Form -->
                <form action="{{ route('archives.store') }}" method="POST" enctype="multipart/form-data"
                    class="d-flex flex-wrap gap-2 mb-4 align-items-end">
                    @csrf
                    <div class="flex-grow-1" style="min-width: 200px;">
                        <label for="archiveImage" class="form-label small text-muted">Image</label>
                        <input type="file" class="form-control" id="archiveImage" name="image" required>
                    </div>
                    <div class="flex-grow-1" style="min-width: 200px;">
                        <label for="archiveLink" class="form-label small text-muted">Instagram Link</label>
                        <input type="url" class="form-control" id="archiveLink" name="link" required
                            placeholder="https://instagram.com/...">
                    </div>
                    <div class="flex-grow-1" style="min-width: 200px;">
                        <label for="archiveTitle" class="form-label small text-muted">Caption/Title (Optional)</label>
                        <input type="text" class="form-control" id="archiveTitle" name="title"
                            placeholder="Short description...">
                    </div>
                    <div class="flex-grow-1" style="min-width: 200px;">
                        <label for="archiveDesc" class="form-label small text-muted">Description (Optional)</label>
                        <textarea class="form-control" id="archiveDesc" name="description" rows="1"
                            placeholder="Details..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-dark">Add Content</button>
                </form>

                <!-- Archives List -->
                <div class="row g-3">
                    @forelse($archives as $archive)
                        <div class="col-md-3 col-6">
                            <div class="position-relative group border rounded overflow-hidden">
                                <div class="ratio ratio-1x1">
                                    <img src="{{ asset('assets/archives/' . $archive->image) }}" class="object-fit-cover"
                                        alt="Archive">
                                </div>

                                <a href="{{ route('archives.edit', $archive->id) }}"
                                    class="position-absolute top-0 start-0 m-2 btn btn-light btn-sm rounded-circle p-2 lh-1 shadow-sm"
                                    style="width: 32px; height: 32px;" title="Edit">
                                    ✎
                                </a>
                                <div class="p-2 bg-light small text-truncate">
                                    <a href="{{ $archive->link }}" target="_blank"
                                        class="text-decoration-none fw-bold text-dark">{{ $archive->title ?: 'No Title' }}</a>
                                </div>

                                <button type="button"
                                    class="position-absolute top-0 end-0 m-2 btn btn-danger btn-sm rounded-circle p-2 lh-1 shadow-sm"
                                    style="width: 32px; height: 32px;" title="Delete"
                                    onclick="confirmDelete('{{ route('archives.destroy', $archive->id) }}', 'archive')">
                                    ✕
                                </button>
                            </div>
                        </div>
                    @empty
                        <div class="col-12 text-muted small">No archive content uploaded.</div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Community Management Section -->
        <div class="card border-0 shadow-sm mb-5">
            <div class="card-header bg-white border-0 py-3">
                <h4 class="mb-0 fw-bold">Community Management</h4>
            </div>
            <div class="card-body">
                @if ($errors->any())
                    <div id="global-alert-error" class="position-fixed top-0 start-50 translate-middle-x p-3 mt-5"
                        style="z-index: 2000;">
                        <div class="alert alert-danger alert-dismissible fade show shadow-lg border-0 rounded-0" role="alert">
                            <div class="d-flex align-items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor"
                                    class="bi bi-exclamation-octagon-fill" viewBox="0 0 16 16">
                                    <path
                                        d="M11.46.146A.5.5 0 0 0 11.107 0H4.893a.5.5 0 0 0-.353.146L.146 4.54A.5.5 0 0 0 0 4.893v6.214a.5.5 0 0 0 .146.353l4.394 4.394a.5.5 0 0 0 .353.146h6.214a.5.5 0 0 0 .353-.146l4.394-4.394a.5.5 0 0 0 .146-.353V4.893a.5.5 0 0 0-.146-.353L11.46.146zM8 4c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z" />
                                </svg>
                                <div class="fw-semibold">
                                    <ul class="mb-0 list-unstyled">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    </div>
                    <script>
                        setTimeout(function () {
                            var alertNode = document.getElementById('global-alert-error');
                            if (alertNode) {
                                var bsAlert = new bootstrap.Alert(alertNode.querySelector('.alert'));
                                bsAlert.close();
                            }
                        }, 5000); // 5 seconds for errors
                    </script>
                @endif
                <!-- Upload Form -->
                <form action="{{ route('communities.store') }}" method="POST" enctype="multipart/form-data"
                    class="d-flex flex-wrap gap-2 mb-4 align-items-end">
                    @csrf
                    <div class="flex-grow-1" style="min-width: 200px;">
                        <label for="communityImage" class="form-label small text-muted">Upload Photo</label>
                        <input type="file" class="form-control" id="communityImage" name="image" required>
                    </div>
                    <button type="submit" class="btn btn-dark">Add Photo</button>
                </form>

                <!-- Community List -->
                <div class="row g-3">
                    @forelse($communities as $community)
                        <div class="col-md-3 col-6">
                            <div class="position-relative group border rounded overflow-hidden">
                                <div class="ratio ratio-1x1">
                                    <img src="{{ asset('assets/community/' . $community->image) }}" class="object-fit-cover"
                                        alt="Community">
                                </div>

                                <button type="button"
                                    class="position-absolute top-0 end-0 m-2 btn btn-danger btn-sm rounded-circle p-2 lh-1 shadow-sm"
                                    style="width: 32px; height: 32px;" title="Delete"
                                    onclick="confirmDelete('{{ route('communities.destroy', $community->id) }}', 'community')">
                                    ✕
                                </button>
                            </div>
                        </div>
                    @empty
                        <div class="col-12 text-muted small">No community photos uploaded.</div>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Product Catalog</h2>
            <div>
                <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-dark me-2">Manage Categories</a>
                <a href="{{ route('products.create') }}" class="btn btn-dark">Add New Product</a>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th style="width: 50px;">No</th>
                        <th style="width: 100px;">Image</th>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th style="width: 50px;">Featured</th>
                        <th style="width: 150px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($products as $i => $product)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>
                                <img src="{{ asset('assets/products/' . $product->image) }}" alt="{{ $product->name }}"
                                    style="width: 50px; height: 50px; object-fit: cover;">
                            </td>
                            <td>{{ $product->name }}</td>
                            <td>{{ $product->category }}</td>
                            <td>
                                <form action="{{ route('products.toggleFeatured', $product->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-link text-decoration-none p-0 border-0" title="Toggle Featured">
                                        @if($product->is_featured)
                                            <span class="fs-4 text-warning">★</span>
                                        @else
                                            <span class="fs-4 text-secondary">☆</span>
                                        @endif
                                    </button>
                                </form>
                            </td>
                            <td>Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a class="btn btn-sm btn-primary" href="{{ route('products.edit', $product->id) }}">Edit</a>
                                    <button type="button" class="btn btn-sm btn-danger"
                                        onclick="confirmDelete('{{ route('products.destroy', $product->id) }}')">Delete</button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
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
                        <p class="mb-0 fs-5 text-dark">Are you really sure you want to delete this item?</p>
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
    </main>
@endsection