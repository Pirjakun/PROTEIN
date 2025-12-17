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

<body class="d-flex flex-column min-vh-100">
    <nav class="navbar navbar-expand-lg navbar-light bg-white site-nav shadow-sm">
        <div class="container-fluid px-4 px-md-5">
            <a class="navbar-brand nav-left" href="{{ route('home') }}">
                <img src="{{ asset('assets/site/buitenworks-logo.png') }}" class="logo" alt="Buitenworks"
                    style="height: 40px; width: auto;">
            </a>

            <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarContent" aria-controls="navbarContent" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse justify-content-center bg-white" id="navbarContent">
                <ul class="navbar-nav gap-3 text-center my-4 my-lg-0 align-items-center">
                    <li class="nav-item"><a class="nav-link text-uppercase fw-bold text-dark"
                            href="{{ route('catalog') }}">Catalog</a></li>
                    <li class="nav-item"><a class="nav-link text-uppercase fw-bold text-dark"
                            href="/community">Community</a></li>
                    <li class="nav-item"><a class="nav-link text-uppercase fw-bold text-dark"
                            href="/archives">Archives</a></li>
                    <li class="nav-item"><a class="nav-link text-uppercase fw-bold text-dark" href="/about">About</a>
                    </li>

                    <!-- Mobile Auth Links (Visible only on LG down) -->
                    <li class="nav-item d-lg-none mt-3 border-top pt-3 w-100">
                        <a href="#" id="wishlistBtnMobile" class="nav-link text-uppercase fw-bold text-dark"
                            onclick="document.getElementById('wishlistDrawer').classList.add('open'); document.getElementById('drawerBackdrop').classList.add('visible');">Wishlist</a>
                    </li>
                    @guest
                        <li class="nav-item d-lg-none">
                            <a href="{{ route('login') }}" class="btn btn-outline-dark w-100 rounded-3">Login</a>
                        </li>
                    @else
                        <li class="nav-item d-lg-none">
                            <div class="fw-bold mb-2">{{ Auth::user()->name }}</div>
                            @if(Auth::user()->is_admin)
                                <a href="{{ route('admin.dashboard') }}"
                                    class="btn btn-outline-primary w-100 rounded-3 btn-sm mb-2 fw-bold">Admin Dashboard</a>
                            @endif
                            <a href="{{ route('profile.show') }}"
                                class="btn btn-outline-dark w-100 rounded-3 btn-sm mb-2 fw-bold">Edit Profile</a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="btn btn-danger w-100 rounded-3 btn-sm">Logout</button>
                            </form>
                        </li>
                    @endguest
                </ul>
            </div>

            <!-- Desktop Right Menu -->
            <div class="d-none d-lg-flex align-items-center gap-3">
                <a href="#" id="wishlistBtn" class="text-decoration-none text-dark fw-bold text-uppercase"
                    style="font-size: 0.9rem;">Wishlist</a>

                @auth
                    <div class="dropdown">
                        <button class="btn btn-dark dropdown-toggle px-3 py-2 rounded-3 fw-bold" type="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            {{ Auth::user()->name }}
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2 rounded-3 overflow-hidden">
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
                            <li><a class="dropdown-item small" href="{{ route('profile.show') }}">Edit Profile</a></li>
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
                    <a href="{{ route('login') }}" class="btn btn-outline-dark px-4 py-2 rounded-3 fw-bold text-uppercase"
                        style="font-size: 0.8rem;">Login</a>
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

    <main class="flex-grow-1">
        @yield('content')
    </main>

    <footer class="site-footer bg-black text-white py-4 mt-auto">
        <div class="container text-center">
            <div class="copyright fw-bold" style="font-family: 'Oswald', sans-serif;">© Buitenworks</div>
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