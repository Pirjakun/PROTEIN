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
                    <img src="{{ asset('assets/products/' . $product->image) }}" alt="{{ $product->name }}" id="mainImage"
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
                                onclick="changeImage('{{ asset('assets/products/' . $img) }}', this)">
                                <img src="{{ asset('assets/products/' . $img) }}"
                                    class="img-fluid rounded w-100 h-100 object-fit-cover" alt="{{ $product->name }}">
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>



            <!-- Right Column: Details -->
            <div class="col-md-6">
                @if($product->stock > 0)
                    <span class="badge bg-black text-white mb-2 rounded-0">In Stock: {{ $product->stock }}</span>
                @else
                    <span class="badge bg-danger text-white mb-2 rounded-0">Out of Stock</span>
                @endif
                <h1 class="display-5 fw-bold text-uppercase mb-2" style="font-family: 'Oswald', sans-serif;">
                    {{ $product->name }}
                </h1>
                <div class="h4 fw-bold mb-4">Rp {{ number_format($product->price, 0, ',', '.') }}</div>





                <div class="mb-5">
                    <div class="d-flex gap-2 mb-2">
                        <!-- Buy on Shopee -->
                        @if($product->shopee_link)
                            <a href="{{ $product->shopee_link }}" target="_blank" class="btn btn-dark btn-lg flex-grow-1">
                                Buy on Shopee
                            </a>
                        @else
                            <button type="button" class="btn btn-secondary btn-lg flex-grow-1" disabled>
                                Shopee Link Not Available
                            </button>
                        @endif

                        <!-- Wishlist Button -->
                        <button type="button" class="btn btn-outline-dark btn-lg" id="wishlistProductBtn"
                            onclick="handleWishlist({{ $product->id }})" aria-label="Add to Wishlist">
                            <span id="wishlistIcon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor"
                                    class="bi bi-heart" viewBox="0 0 16 16">
                                    <path
                                        d="m8 2.748-.717-.737C5.6.281 2.514.878 1.4 3.053c-.523 1.023-.641 2.5.314 4.385.92 1.815 2.834 3.989 6.286 6.357 3.452-2.368 5.365-4.542 6.286-6.357.955-1.886.838-3.362.314-4.385C13.486.878 10.4.28 8.717 2.01L8 2.748zM8 15C-7.333 4.868 3.279-3.04 7.824 1.143c.06.055.119.112.176.171a3.12 3.12 0 0 1 .176-.17C12.72-3.042 23.333 4.867 8 15z" />
                                </svg>
                            </span>
                        </button>
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
                                <img src="{{ asset('assets/products/' . $related->image) }}"
                                    class="img-fluid object-fit-contain" alt="{{ $related->name }}">
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




        function changeImage(src, thumb) {
            const mainImg = document.getElementById('mainImage');
            if (mainImg) {
                mainImg.style.opacity = '0.5';
                setTimeout(() => {
                    mainImg.src = src;
                    mainImg.style.opacity = '1';
                }, 150);
            }

            // Update active state
            document.querySelectorAll('.gallery-thumb').forEach(el => el.classList.remove('active'));
            if (thumb) thumb.classList.add('active');
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

            const markOutline = `<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-heart" viewBox="0 0 16 16">
                                                    <path d="m8 2.748-.717-.737C5.6.281 2.514.878 1.4 3.053c-.523 1.023-.641 2.5.314 4.385.92 1.815 2.834 3.989 6.286 6.357 3.452-2.368 5.365-4.542 6.286-6.357.955-1.886.838-3.362.314-4.385C13.486.878 10.4.28 8.717 2.01L8 2.748zM8 15C-7.333 4.868 3.279-3.04 7.824 1.143c.06.055.119.112.176.171a3.12 3.12 0 0 1 .176-.17C12.72-3.042 23.333 4.867 8 15z"/>
                                                 </svg>`;

            const markFilled = `<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-heart-fill text-danger" viewBox="0 0 16 16">
                                                    <path fill-rule="evenodd" d="M8 1.314C12.438-3.248 23.534 4.735 8 15-7.534 4.736 3.562-3.248 8 1.314z"/>
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
                // Keep background white (outline), only change icon
                btn.classList.remove('btn-dark');
                btn.classList.add('btn-outline-dark');
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