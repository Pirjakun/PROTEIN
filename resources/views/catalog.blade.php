@extends('layouts.app')

@section('title', 'Catalog - Buitenworks')

@section('content')
  <main class="container py-5">
    <div class="row">
      <!-- Sidebar -->
      <aside class="col-md-3 mb-4">
        <div class="p-3 bg-light rounded border">
          <input type="text" id="searchInput" placeholder="Search" class="form-control mb-3 rounded-pill" />
          <h4 class="h6 fw-bold text-uppercase mb-2">Filters</h4>
          <div class="form-check">
            <input class="form-check-input filter-check" type="radio" name="filterType" id="filterAll" value="all"
              checked>
            <label class="form-check-label" for="filterAll">All Products</label>
          </div>
          <div class="form-check">
            <input class="form-check-input filter-check" type="radio" name="filterType" id="filterFeatured"
              value="featured">
            <label class="form-check-label" for="filterFeatured">Featured Products</label>
          </div>
          @foreach($categories as $category)
            <div class="form-check">
              <input class="form-check-input filter-check" type="radio" name="filterType"
                id="filter{{ str_replace(' ', '', $category->name) }}" value="{{ $category->name }}">
              <label class="form-check-label text-capitalize" for="filter{{ str_replace(' ', '', $category->name) }}">
                {{ $category->name }}
              </label>
            </div>
          @endforeach
        </div>
      </aside>

      <!-- Product Grid -->
      <section class="col-md-9">
        <div class="d-flex justify-content-between align-items-center mb-4">
          <h1 class="h3 fw-bold m-0">All Products</h1>
          <div>
            <select id="sortSelect" class="form-select form-select-sm" style="min-width: 150px;">
              <option value="featured">Sort: Featured</option>
              <option value="price-asc">Price: Low to High</option>
              <option value="price-desc">Price: High to Low</option>
            </select>
          </div>
        </div>

        <div id="products" class="row g-4">
          @if(isset($products) && $products->count() > 0)
            @foreach($products as $product)
              <div class="col-6 col-md-4 col-lg-4">
                <a class="text-decoration-none text-dark h-100 d-flex flex-column product-card-hover rounded p-2"
                  href="{{ route('products.show', $product->id) }}">
                  <div class="position-relative bg-white rounded overflow-hidden ratio ratio-1x1 mb-3">
                    <img src="{{ asset('assets/products/' . $product->image) }}"
                      class="img-fluid object-fit-cover p-0 w-100 h-100" alt="{{ $product->name }}">
                    @if($product->stock <= 0)
                      <div
                        class="position-absolute bottom-0 start-0 bg-black text-white px-2 py-1 small fw-bold text-uppercase m-2">
                        SOLD OUT</div>
                    @endif
                  </div>
                  <div class="text-start px-2">
                    <div class="fw-bold text-uppercase small mb-1" style="font-family: 'Oswald', sans-serif;">
                      {{ $product->name }}
                    </div>
                    <div class="fw-semibold small">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                  </div>
                </a>
              </div>
            @endforeach
          @else
            <div class="col-12 text-center text-muted py-5">
              <p>No products found in database.</p>
            </div>
          @endif
        </div>
      </section>
    </div>
    </div>
  </main>
  <script>
    window.SERVER_PRODUCTS = {!! json_encode($products) !!};
  </script>
@endsection