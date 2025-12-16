@extends('layouts.app')

@section('title', 'Catalog - Buitenworks')

@section('content')
  <main class="container py-5">
    <div class="row">
      <!-- Sidebar -->
      <aside class="col-md-3 mb-4">
        <div class="p-3 bg-light rounded border">
          <input type="text" placeholder="Search" class="form-control mb-3 rounded-pill" />
          <h4 class="h6 fw-bold text-uppercase mb-2">Product Type</h4>
          <div class="form-check">
            <input class="form-check-input" type="checkbox" checked id="allProd">
            <label class="form-check-label" for="allProd">All Products</label>
          </div>
          <div class="form-check">
            <input class="form-check-input" type="checkbox" id="featProd">
            <label class="form-check-label" for="featProd">Featured Products</label>
          </div>
        </div>
      </aside>

      <!-- Product Grid -->
      <section class="col-md-9">
        <div class="d-flex justify-content-between align-items-center mb-4">
          <h1 class="h3 fw-bold m-0">All Products</h1>
          <div class="dropdown">
            <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
              Sort: Featured
            </button>
            <ul class="dropdown-menu">
              <li><a class="dropdown-item" href="#">Price: Low to High</a></li>
              <li><a class="dropdown-item" href="#">Price: High to Low</a></li>
            </ul>
          </div>
        </div>

        <div id="server-products" class="row g-4">
          @if(isset($products) && $products->count() > 0)
            @foreach($products as $product)
              <div class="col-6 col-md-4 col-lg-4">
                <a class="text-decoration-none text-dark h-100 d-flex flex-column product-card-hover rounded p-2"
                  href="{{ route('products.show', $product->id) }}">
                  <div class="position-relative bg-white rounded overflow-hidden ratio ratio-1x1 mb-3">
                    <img src="{{ asset('assets/' . $product->image) }}" class="img-fluid object-fit-cover p-0 w-100 h-100"
                      alt="{{ $product->name }}">
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
  </main>
@endsection