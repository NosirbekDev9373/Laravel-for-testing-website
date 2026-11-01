<nav class="navbar navbar-expand-lg">
    <div class="container">
        <a class="navbar-brand" href="{{ route('main') }}">
            <img src="{{ asset('images/bubbles.png') }}" class="logo img-fluid" alt="">
            <span class="ms-2">Clean Work</span>
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="{{ route('main') }}">{{ __('Bosh sahifa') }}</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('about') }}">{{ __('Biz haqimizda') }}</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('services') }}">{{ __('Xizmatlar') }}</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('posts.index') }}">{{ __('Blog') }}</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('contact') }}">{{ __('Kontakt') }}</a></li>

                @auth
                    <li class="nav-item ms-3 position-relative d-flex align-items-center">
                        <a href="{{ route('notifications.index') }}" class="nav-link text-white position-relative">
                            <i class="bi-bell-fill fs-5"></i>
                            @if(auth()->user()->unreadNotifications->count() > 0)
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size:10px">
                                    {{ auth()->user()->unreadNotifications->count() }}
                                </span>
                            @endif
                        </a>
                    </li>

                    @if(auth()->user()->hasRole('admin'))
                        <li class="nav-item ms-3">
                            <a class="nav-link custom-btn custom-border-btn custom-btn-bg-white btn" href="{{ route('admin.dashboard') }}">Admin panel</a>
                        </li>
                    @endif

                    <li class="nav-item ms-3 d-flex align-items-center">
                        <span class="nav-link text-white">{{ auth()->user()->name }}</span>
                    </li>
                    <li class="nav-item">
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="nav-link custom-btn custom-border-btn custom-btn-bg-white btn">{{ __('Chiqish') }}</button>
                        </form>
                    </li>
                @else
                    <li class="nav-item ms-3"><a class="nav-link custom-btn custom-border-btn custom-btn-bg-white btn" href="{{ route('login') }}">{{ __('Kirish') }}</a></li>
                    <li class="nav-item ms-2"><a class="nav-link custom-btn custom-border-btn custom-btn-bg-white btn" href="{{ route('register') }}">{{ __('Ro‘yxatdan o‘tish') }}</a></li>
                @endauth

                <li class="nav-item dropdown ms-lg-3 d-flex align-items-center">
                    <a class="nav-link dropdown-toggle text-white" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">🌐 {{ strtoupper(session('locale', config('app.locale'))) }}</a>
                    <ul class="dropdown-menu dropdown-menu-dark" data-bs-popper="static">
                        <li><a class="dropdown-item" href="{{ route('locale.change', 'uz') }}">O‘zbekcha</a></li>
                        <li><a class="dropdown-item" href="{{ route('locale.change', 'ru') }}">Русский</a></li>
                        <li><a class="dropdown-item" href="{{ route('locale.change', 'en') }}">English</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>
