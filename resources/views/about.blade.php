@extends('layouts.app')

@section('title', 'About - Buitenworks')

@section('content')
    <main class="container-main" style="margin-top: 100px;">
        <h2>About Us</h2>
        <div class="content-block" style="max-width: 800px; text-align: justify;">
            <p class="mb-3">
                BUITENWORKS is a local clothing brand, driven by exploration and constant progress. Guided
                by the principle “Always In Progress,” we create designs that emphasize character, aesthetics, and freedom
                of expression.
            </p>
            <p>
                Inspired by urban culture and independent creativity, BUITENWORKS believes in aesthetic over logic. each
                piece represents a process, not just a final product.
            </p>
        </div>

        <div class="mt-5">
            <h4 class="fw-bold mb-3">Contact Us & Socials</h4>
            <ul class="list-unstyled">
                <li class="mb-2">
                    <strong>Instagram:</strong> <a href="https://instagram.com/buitenworks"
                        class="text-decoration-none text-dark">@buitenworks</a>
                </li>
                <li class="mb-2">
                    <strong>TikTok:</strong> <a href="#" class="text-decoration-none text-dark">@buitenworks.com</a>
                </li>
                <li class="mb-2">
                    <strong>WhatsApp:</strong> <a href="https://wa.me/6285183192144" class="text-decoration-none text-dark"
                        target="_blank">085183192144</a>
                </li>
            </ul>
        </div>
    </main>
@endsection