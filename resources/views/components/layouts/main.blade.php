<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="description" content="{{ __('Tozalash va xizmatlar platformasi') }}">
    <meta name="author" content="Tozalash Web Sayt">

    <title>{{ $title ?? 'Tozalash Web Sayt' }}</title>

    <!-- CSS FILES -->
    <link rel="preconnect" href="https://fonts.googleapis.com">

    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,400;0,500;0,700;1,400&display=swap" rel="stylesheet">

    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">

    <link href="{{ asset('css/bootstrap-icons.css') }}" rel="stylesheet">

    <link href="{{ asset('css/tooplate-clean-work.css') }}" rel="stylesheet">

    <link href="{{ asset('css/mobile.css') }}" rel="stylesheet">

    {{--@vite(['resources/css/app.css', 'resources/js/app.js'])--}}
</head>

<body>

<header class="site-header">
    <div class="container">
        <div class="row">

            <div class="col-lg-12 col-12 d-flex flex-wrap">
                <p class="d-flex me-4 mb-0">
                    <i class="bi-house-fill me-2"></i>
                    {{ __('Tozalash va xizmatlar platformasi') }}
                </p>

                <p class="d-flex d-lg-block d-md-block d-none me-4 mb-0">
                    <i class="bi-globe2 me-2"></i>
                    {{ __('Onlayn platforma') }}
                </p>

                <p class="site-header-icon-wrap text-white d-flex mb-0 ms-auto">
                    <i class="site-header-icon bi-envelope-fill me-2"></i>

                    <a href="{{ route('contact') }}" class="text-white">
                        {{ __('Bog‘lanish') }}
                    </a>
                </p>
            </div>

        </div>
    </div>
</header>

<x-navbar></x-navbar>

{{ $slot }}


<footer class="site-footer">
    <div class="container">
        <div class="row">

            <div class="col-lg-12 col-12 d-flex align-items-center mb-4 pb-2">
                <div>
                    <img src="{{ asset('images/bubbles.png') }}" class="logo img-fluid" alt="Tozalash Web Sayt">
                </div>

                <ul class="footer-menu d-flex flex-wrap ms-5">
                    <li class="footer-menu-item"><a href="{{ route('about') }}" class="footer-menu-link">{{ __('Biz haqimizda') }}</a></li>

                    <li class="footer-menu-item"><a href="{{ route('posts.index') }}" class="footer-menu-link">{{ __('Postlar') }}</a></li>

                    <li class="footer-menu-item"><a href="{{ route('services') }}" class="footer-menu-link">{{ __('Xizmatlar') }}</a></li>

                    <li class="footer-menu-item"><a href="{{ route('contact') }}" class="footer-menu-link">{{ __('Kontakt') }}</a></li>
                </ul>
            </div>

            <div class="col-lg-5 col-12 mb-4 mb-lg-0">
                <h5 class="site-footer-title mb-3">{{ __('Tezkor havolalar') }}</h5>

                <ul class="footer-menu">
                    <li class="footer-menu-item">
                        <a href="{{ route('posts.index') }}" class="footer-menu-link">
                            <i class="bi-chevron-double-right footer-menu-link-icon me-2"></i>
                            {{ __('Barcha postlar') }}
                        </a>
                    </li>

                    <li class="footer-menu-item">
                        <a href="{{ route('about') }}" class="footer-menu-link">
                            <i class="bi-chevron-double-right footer-menu-link-icon me-2"></i>
                            {{ __('Platforma haqida') }}
                        </a>
                    </li>

                    <li class="footer-menu-item">
                        <a href="{{ route('services') }}" class="footer-menu-link">
                            <i class="bi-chevron-double-right footer-menu-link-icon me-2"></i>
                            {{ __('Xizmatlar') }}
                        </a>
                    </li>

                    <li class="footer-menu-item">
                        <a href="{{ route('contact') }}" class="footer-menu-link">
                            <i class="bi-chevron-double-right footer-menu-link-icon me-2"></i>
                            {{ __('Bog‘lanish') }}
                        </a>
                    </li>
                </ul>
            </div>

            <div class="col-lg-4 col-md-6 col-12 mb-4 mb-lg-0 mb-md-0">
                <h5 class="site-footer-title mb-3">{{ __('Aloqa') }}</h5>

                <p class="text-white mt-3 mb-2">
                    {{ __('Savollaringiz bormi? Kontakt sahifasi orqali biz bilan bog‘laning.') }}
                </p>

                <a href="{{ route('contact') }}" class="btn btn-outline-light mt-3">
                    {{ __('Kontakt sahifasi') }}
                </a>
            </div>

            <div class="col-lg-3 col-md-6 col-6 mt-3 mt-lg-0 mt-md-0">
                <div class="featured-block">
                    <h5 class="text-white mb-3">{{ __('Platforma') }}</h5>

                    <p class="text-white mb-3">{{ __('Turli xizmatlar va foydali postlar bir joyda.') }}</p>

                    <a href="{{ route('posts.index') }}" class="text-white">{{ __('Postlarni ko‘rish') }}</a>
                </div>
            </div>
        </div>
    </div>

    <div class="site-footer-bottom">
        <div class="container">
            <div class="row">

                <div class="col-lg-6 col-12">
                    <p class="copyright-text mb-0">Copyright © {{ date('Y') }} Tozalash Web Sayt.</p>
                </div>

                <div class="col-lg-6 col-12 text-end">
                    <p class="copyright-text mb-0">{{ __('Barcha huquqlar himoyalangan.') }}</p>
                </div>

            </div>
        </div>
    </div>
</footer>

<!-- JAVASCRIPT FILES -->
<script src="{{ asset('js/jquery.min.js') }}"></script>
<script src="{{ asset('js/bootstrap.min.js') }}"></script>
<script src="{{ asset('js/jquery.backstretch.min.js') }}"></script>
<script src="{{ asset('js/counter.js') }}"></script>
<script src="{{ asset('js/countdown.js') }}"></script>
<script src="{{ asset('js/init.js') }}"></script>
<script src="{{ asset('js/modernizr.js') }}"></script>
<script src="{{ asset('js/animated-headline.js') }}"></script>
<script src="{{ asset('js/custom.js') }}"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('input[data-max-files]').forEach(function (input) {
        var maxFiles = parseInt(input.dataset.maxFiles, 10);

        if (!maxFiles) {
            return;
        }

        input.addEventListener('change', function () {
            if (input.files.length <= maxFiles) {
                return;
            }

            var dataTransfer = new DataTransfer();

            Array.prototype.slice.call(input.files, 0, maxFiles).forEach(function (file) {
                dataTransfer.items.add(file);
            });

            input.files = dataTransfer.files;
            alert('Ko‘pi bilan ' + maxFiles + ' ta rasm yuklash mumkin.');
        });
    });
});
</script>

</body>
</html>
