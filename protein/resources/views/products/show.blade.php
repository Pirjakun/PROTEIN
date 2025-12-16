@extends('layouts.app')

@section('title', $product->name . ' - Buitenworks')

@section('content')
    <div class="container py-5">
        <div class="row gx-5 mb-5">
            <!-- Left Column: Image -->
            <div class="col-md-6 mb-4 mb-md-0">
                <style>
                    @keyframes fadeIn {
                        from {
                            opacity: 0;
                            transform: scale(0.98);
                        }

                        to {
                            opacity: 1;
                            transform: scale(1);
                        }
                    }

                    .animate-fade {
                        animation: fadeIn 0.3s ease-out forwards;
                    }

                    .gallery-thumb {
                        transition: all 0.2s ease;
                        border: 1px solid transparent;
                        opacity: 0.7;
                    }

                    .gallery-thumb:hover {
                        transform: translateY(-2px);
                        opacity: 1;
                        border-color: #ddd;
                    }

                    .gallery-thumb.active {
                        border: 2px solid #000 !important;
                        opacity: 1;
                        transform: scale(0.95);
                    }
                </style>

                <div
                    class="bg-white rounded mb-3 d-flex justify-content-center align-items-center position-relative ratio ratio-1x1 overflow-hidden">
                    <img src="{{ asset('assets/' . $product->image) }}" alt="{{ $product->name }}" id="mainImage"
                        class="img-fluid object-fit-contain w-100 h-100 p-3">
                </div>

                <!-- Thumbnails -->
                <div class="d-flex flex-nowrap overflow-auto gap-2 pb-2" style="scrollbar-width: thin; padding-left: 1px;">
                    @php
                        $gallery = $product->gallery ?? [];
                        if (!is_array($gallery))
                            $gallery = [];
                        // Prepend main image
                        array_unshift($gallery, $product->image);
                        $gallery = array_unique($gallery);
                    @endphp

                    @foreach($gallery as $key => $img)
                        <div style="min-width: 80px; width: 25%;">
                            <div class="bg-white rounded cursor-pointer position-relative ratio ratio-1x1 gallery-thumb {{ $key === 0 ? 'active' : '' }}"
                                onclick="changeImage('{{ asset('assets/' . $img) }}', this)">
                                <img src="{{ asset('assets/' . $img) }}" class="img-fluid rounded w-100 h-100 object-fit-cover"
                                    alt="{{ $product->name }}">
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>



            <!-- Right Column: Details -->
            <div class="col-md-6">
                <span class="badge bg-black text-white mb-2 rounded-0">In Stock</span>
                <h1 class="display-5 fw-bold text-uppercase mb-2" style="font-family: 'Oswald', sans-serif;">
                    {{ $product->name }}
                </h1>
                <div class="h4 fw-bold mb-4">Rp {{ number_format($product->price, 0, ',', '.') }}</div>

                <div class="mb-4 p-3 bg-white border rounded d-flex justify-content-between">
                    <span>Quantity Information</span>
                    <span class="fw-bold">Maximum Quantity: {{ $product->stock }}</span>
                </div>

                <div class="mb-4">
                    <h6 class="fw-bold mb-2">Size</h6>
                    <div class="d-flex gap-2" id="sizeSelector">
                        <button class="btn btn-outline-secondary px-3 size-btn" type="button" data-size="S"
                            onclick="selectSize(this)">S</button>
                        <button class="btn btn-outline-secondary px-3 size-btn" type="button" data-size="M"
                            onclick="selectSize(this)">M</button>
                        <button class="btn btn-outline-secondary px-3 size-btn" type="button" data-size="L"
                            onclick="selectSize(this)">L</button>
                        <button class="btn btn-outline-secondary px-3 size-btn" type="button" data-size="XL"
                            onclick="selectSize(this)">XL</button>
                    </div>
                    <div id="sizeError" class="text-danger small mt-2" style="display:none;">Please select a size.</div>
                </div>

                <div class="d-flex align-items-center gap-3 mb-4">
                    <div class="input-group" style="width: 140px;">
                        <button class="btn btn-outline-secondary" type="button" onclick="decreaseQty()">-</button>
                        <input type="text" class="form-control text-center bg-white" value="1" id="qtyInput" min="1"
                            max="{{ $product->stock }}" readonly>
                        <button class="btn btn-outline-secondary" type="button" onclick="increaseQty()">+</button>
                    </div>

                </div>

                <div class="mb-5">
                    <div class="d-flex gap-2 mb-2">
                        <!-- Add to Cart (JS specific) -->
                        <button type="button" class="btn btn-outline-dark btn-lg flex-grow-1"
                            onclick="handleAddToCart({{ $product->id }})">Add to Cart</button>

                        <!-- Wishlist Button -->
                        <button type="button" class="btn btn-outline-dark btn-lg" id="wishlistProductBtn"
                            onclick="handleWishlist({{ $product->id }})" aria-label="Add to Wishlist">
                            <span id="wishlistIcon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor"
                                    class="bi bi-bookmark" viewBox="0 0 16 16">
                                    <path
                                        d="M2 2a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v13.5a.5.5 0 0 1-.777.416L8 13.101l-5.223 2.815A.5.5 0 0 1 2 15.5V2zm2-1a1 1 0 0 0-1 1v12.566l4.723-2.482a.5.5 0 0 1 .554 0L13 14.566V2a1 1 0 0 0-1-1H4z" />
                                </svg>
                            </span>
                        </button>
                    </div>

                    <!-- Buy Now could just add to cart and redirect to checkout -->
                    <div class="d-grid">
                        <button type="button" class="btn btn-dark btn-lg" onclick="handleBuyNow({{ $product->id }})">Buy It
                            Now</button>
                    </div>
                </div>

                <div class="prose">
                    <p>{!! nl2br(e($product->description)) !!}</p>
                    <p class="text-muted fst-italic">Luxury, and attitude – a true statement of fearless design.</p>

                    <div class="bg-light p-3 rounded small text-muted mt-3">
                        <strong>Delivery</strong><br>
                        Shipped within 2 weeks.<br>
                        (Upon confirmation of payment)
                    </div>
                </div>
            </div>
        </div>

        <!-- Related Products -->
        <div class="border-top pt-5">
            <h3 class="h4 fw-bold mb-4">You Might Also Like</h3>
            <div class="row g-4">
                @foreach($relatedProducts as $related)
                    <div class="col-6 col-md-4 col-lg-3">
                        <a href="{{ route('products.show', $related->id) }}"
                            class="text-decoration-none text-dark h-100 d-flex flex-column">
                            <div
                                class="bg-white rounded mb-2 position-relative ratio ratio-1x1 d-flex align-items-center justify-content-center">
                                <img src="{{ asset('assets/' . $related->image) }}" class="img-fluid object-fit-contain"
                                    alt="{{ $related->name }}">
                            </div>
                            <div>
                                <div class="small fw-bold text-uppercase mb-1" style="font-family: 'Oswald', sans-serif;">
                                    {{ $related->name }}
                                </div>
                                <div class="small text-muted">Rp {{ number_format($related->price, 0, ',', '.') }}</div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <script>
        let selectedSize = null;

        function selectSize(btn) {
            // Reset styles
            document.querySelectorAll('.size-btn').forEach(b => {
                b.classList.remove('btn-dark', 'text-white');
                b.classList.add('btn-outline-secondary');
            });
            // Set active
            btn.classList.remove('btn-outline-secondary');
            btn.classList.add('btn-dark', 'text-white');
            selectedSize = btn.getAttribute('data-size');
            document.getElementById('sizeError').style.display = 'none';
        }

        function increaseQty() {
            let input = document.getElementById('qtyInput');
            let val = parseInt(input.value);
            let max = parseInt(input.getAttribute('max'));
            if (val < max) {
                input.value = val + 1;
            }
        }
        function decreaseQty() {
            let input = document.getElementById('qtyInput');
            let val = parseInt(input.value);
            if (val > 1) {
                input.value = val - 1;
            }
        }
        function changeImage(src, element) {
            const mainImg = document.getElementById('mainImage');

            if (mainImg) {
                // Reset animation
                mainImg.classList.remove('animate-fade');
                void mainImg.offsetWidth; // Trigger reflow
                mainImg.src = src;
                mainImg.classList.add('animate-fade');
            }

            // Toggle active class
            const thumbs = document.querySelectorAll('.gallery-thumb');
            thumbs.forEach(el => el.classList.remove('active'));

            if (element) {
                element.classList.add('active');
            }
        }

        function handleAddToCart(productId) {
            try {
                if (!selectedSize) {
                    document.getElementById('sizeError').style.display = 'block';
                    return;
                }
                let qty = parseInt(document.getElementById('qtyInput').value);

                // Debug check
                if (typeof window.addToCart !== 'function') {
                    alert('CRITICAL ERROR: scripts.js not loaded properly. Please refresh page.');
                    return;
                }

                let db = loadDB();
                let res = addToCart(db, productId, qty, selectedSize);

                if (res.error === 'LOGIN_REQUIRED') {
                    window.location.href = '/login';
                } else {
                    renderCartUI(db);
                    // Open Drawer
                    document.getElementById('cartDrawer').classList.add('open');
                    document.getElementById('cartDrawer').setAttribute('aria-hidden', 'false');

                    // Show Toast
                    const toastEl = document.getElementById('liveToast');
                    const toastMsg = document.getElementById('toastMessage');
                    if (toastEl && toastMsg) {
                        toastMsg.innerText = 'Product added to cart!';
                        const toast = new bootstrap.Toast(toastEl, { delay: 2000 });
                        toast.show();
                    }
                }
            } catch (e) {
                alert('Error adding to cart: ' + e.message);
                console.error(e);
            }
        }

        function handleBuyNow(productId) {
            if (!selectedSize) {
                document.getElementById('sizeError').style.display = 'block';
                return;
            }
            let qty = parseInt(document.getElementById('qtyInput').value);
            let db = loadDB();
            let res = addToCart(db, productId, qty, selectedSize);

            if (res.error === 'LOGIN_REQUIRED') {
                window.location.href = '/login';
            } else {
                renderCartUI(db);
                // Go to checkout immediately
                window.location.href = '/checkout';
            }
        }

        function handleWishlist(productId) {
            let db = loadDB();
            let res = toggleWishlist(db, productId);
            if (res.error === 'LOGIN_REQUIRED') {
                window.location.href = '/login';
            } else {
                // Update button style
                updateWishlistBtnState(productId);

                // Show Toast
                const toastEl = document.getElementById('liveToast');
                const toastMsg = document.getElementById('toastMessage');
                if (toastEl && toastMsg) {
                    const isAdded = res.wishlist.includes(productId);
                    toastMsg.innerText = isAdded ? 'Product added to Wishlist!' : 'Product removed from Wishlist!';
                    const toast = new bootstrap.Toast(toastEl, { delay: 2000 });
                    toast.show();
                }
            }
        }

        function updateWishlistBtnState(productId) {
            const btn = document.getElementById('wishlistProductBtn');
            const iconSpan = document.getElementById('wishlistIcon');
            if (!btn || !iconSpan) return;

            const markOutline = `<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-bookmark" viewBox="0 0 16 16">
                                        <path d="M2 2a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v13.5a.5.5 0 0 1-.777.416L8 13.101l-5.223 2.815A.5.5 0 0 1 2 15.5V2zm2-1a1 1 0 0 0-1 1v12.566l4.723-2.482a.5.5 0 0 1 .554 0L13 14.566V2a1 1 0 0 0-1-1H4z"/>
                                     </svg>`;

            const markFilled = `<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-bookmark-fill" viewBox="0 0 16 16">
                                        <path d="M2 2v13.5a.5.5 0 0 0 .74.439L8 13.069l5.26 2.87A.5.5 0 0 0 14 15.5V2a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2z"/>
                                    </svg>`;

            let db = loadDB();
            const user = getCurrentUser(db);
            // If not logged in, default to outline (inactive)
            if (!user) {
                btn.classList.remove('btn-dark');
                btn.classList.add('btn-outline-dark');
                iconSpan.innerHTML = markOutline;
                return;
            }

            const wishlist = getUserWishlist(user);
            if (wishlist.includes(productId)) {
                btn.classList.remove('btn-outline-dark');
                btn.classList.add('btn-dark');
                iconSpan.innerHTML = markFilled;
            } else {
                btn.classList.remove('btn-dark');
                btn.classList.add('btn-outline-dark');
                iconSpan.innerHTML = markOutline;
            }
        }

        // Init State on Load
        document.addEventListener('DOMContentLoaded', () => {
            updateWishlistBtnState({{ $product->id }});
        });
    </script>
@endsection