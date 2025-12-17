<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>@yield('title', 'Buitenworks')</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link
        href="https://fonts.googleapis.com/css2?family=Oswald:wght@400;700;800&family=Poppins:wght@300;400;600&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    @stack('styles')
</head>

<body>
    <nav class="site-nav">
        <div class="nav-inner">
            <a class="nav-left" href="{{ route('home') }}"><img src="{{ asset('assets/site/buitenworks-logo.png') }}"
                    class="logo" alt="Buitenworks"></a>
            <div class="nav-center">
                <a href="{{ route('catalog') }}">Catalog</a>
                <a href="/community">Community</a>
                <a href="/archives">Archives</a>
                <a href="/about">About</a>
            </div>
            <div class="nav-right">
                <a href="#" id="wishlistBtn" class="ghost text-decoration-none text-black">Wishlist</a>

                @auth
                    <div class="dropdown d-inline-block">
                        <button class="btn btn-dark dropdown-toggle px-3 py-2 rounded-3 fw-bold" type="button"
                            data-bs-toggle="dropdown" aria-expanded="false"
                            style="min-width: 140px; display: flex; justify-content: space-between; align-items: center;">
                            {{ Auth::user()->name }}
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2 rounded-3 overflow-hidden"
                            style="min-width: 140px;">
                            @if(Auth::user()->is_admin)
                                <li><a class="dropdown-item small fw-bold text-primary"
                                        href="{{ route('admin.dashboard') }}">Admin Dashboard</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                            @endif
                            <li>
                                <h6 class="dropdown-header small text-muted text-uppercase">Account</h6>
                            </li>
                            <li><a class="dropdown-item small" href="{{ route('profile.show') }}">Profile</a></li>

                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger small fw-bold">Logout</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @else
                    <a href="{{ route('login') }}" id="loginBtn" class="primary-outline">Login</a>
                @endauth
            </div>
        </div>
    </nav>

    @if(session('success') || session('error'))
        <div id="global-alert" class="position-fixed top-0 start-50 translate-middle-x p-3 mt-5" style="z-index: 2000;">
            <div class="alert {{ session('success') ? 'alert-success' : 'alert-danger' }} alert-dismissible fade show shadow-lg border-0 rounded-0"
                role="alert">
                <div class="d-flex align-items-center gap-2">
                    @if(session('success'))
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor"
                            class="bi bi-check-circle-fill" viewBox="0 0 16 16">
                            <path
                                d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z" />
                        </svg>
                    @else
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor"
                            class="bi bi-exclamation-triangle-fill" viewBox="0 0 16 16">
                            <path
                                d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z" />
                        </svg>
                    @endif
                    <div class="fw-semibold">{{ session('success') ?? session('error') }}</div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
        <script>
            setTimeout(function () {
                var alertNode = document.getElementById('global-alert');
                if (alertNode) {
                    var bsAlert = new bootstrap.Alert(alertNode.querySelector('.alert'));
                    bsAlert.close();
                }
            }, 3000);
        </script>
    @endif

    @yield('content')

    <footer class="site-footer">
        <div class="footer-inner d-flex justify-content-center w-100 py-3">
            <div class="copyright">© Buitenworks</div>
        </div>
    </footer>

    <!-- drawer backdrop -->
    <div id="drawerBackdrop" class="drawer-backdrop" aria-hidden="true"></div>



    <!-- wishlist drawer -->
    <aside id="wishlistDrawer" class="cart-drawer" aria-hidden="true">
        <div class="cart-head">
            <div class="cart-head-title">Wishlist</div>
            <button id="closeWishlist" class="cart-close-btn">✕</button>
        </div>
        <div class="cart-body">
            <div id="wishlistEmpty" class="cart-empty">Your wishlist is empty.</div>
            <div id="wishlistItems" class="cart-items"></div>
        </div>
    </aside>

    <!-- global loader -->
    <div id="pageLoader" class="page-loader" aria-hidden="true">
        <div class="loader-inner">
            <img src="{{ asset('assets/site/buitenworks-logo.png') }}" alt="Buitenworks" class="loader-logo">
            <div class="loader-bar">
                <div class="loader-bar-fill"></div>
            </div>
            <div class="loader-text">Loading experience...</div>
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        window.AUTH_USER = @json(Auth::user());
        window.SERVER_PRODUCTS = @json(\App\Models\Product::all());
    </script>
    <script src="{{ asset('js/scripts.js') }}?v={{ time() }}"></script>
    @stack('scripts')

    <!-- Toast Container for JS/Ajax Alerts -->
    <div class="toast-container position-fixed top-0 start-50 translate-middle-x p-3"
        style="z-index: 1060; margin-top: 60px;">
        <div id="liveToast" class="toast align-items-center text-white bg-black border-0" role="alert"
            aria-live="assertive" aria-atomic="true" data-bs-delay="2000">
            <div class="d-flex">
                <div class="toast-body fw-bold" id="toastMessage">
                    <!-- Message goes here -->
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                    aria-label="Close"></button>
            </div>
        </div>
    </div>
</body>

</html>