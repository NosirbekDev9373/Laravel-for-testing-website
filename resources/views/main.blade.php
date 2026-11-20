<x-layouts.main>

    <x-slot:title>
        {{ __('Bosh sahifa') }}
    </x-slot:title>

<main>

    <section class="hero-section hero-section-full-height d-flex justify-content-center align-items-center">
        <div class="section-overlay"></div>

        <div class="container">
            <div class="row">

                <div class="col-lg-7 col-12 text-center mx-auto">
                    <h1 class="cd-headline rotate-1 text-white mb-4 pb-2">
                        <span>{{ __('Platformamizda toping:') }}</span>
                        <span class="cd-words-wrapper">
                            @forelse($categories->take(5) as $category)
                                <b @class(['is-visible' => $loop->first])>{{ $category->name }}</b>
                            @empty
                                <b class="is-visible">{{ __('xizmatlar') }}</b>
                            @endforelse
                        </span>
                    </h1>

                    <a class="custom-btn btn button button--atlas me-3" href="{{ route('posts.index') }}">
                        <span>{{ __('Postlarni ko‘rish') }}</span>

                        <div class="marquee" aria-hidden="true">
                            <div class="marquee__inner">
                                <span>{{ __('Postlarni ko‘rish') }}</span>
                                <span>{{ __('Postlarni ko‘rish') }}</span>
                                <span>{{ __('Postlarni ko‘rish') }}</span>
                                <span>{{ __('Postlarni ko‘rish') }}</span>
                            </div>
                        </div>
                    </a>

                    <a class="custom-btn custom-border-btn custom-btn-bg-white btn button button--pan" href="{{ route('about') }}">
                        <span>{{ __('Biz haqimizda') }}</span>
                    </a>
                </div>

            </div>
        </div>

        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="#ffffff" fill-opacity="1" d="M0,224L40,229.3C80,235,160,245,240,250.7C320,256,400,256,480,240C560,224,640,192,720,176C800,160,880,160,960,138.7C1040,117,1120,75,1200,80C1280,85,1360,139,1400,165.3L1440,192L1440,320L1400,320C1360,320,1280,320,1200,320C1120,320,1040,320,960,320C880,320,800,320,720,320C640,320,560,320,480,320C400,320,320,320,240,320C160,320,80,320,40,320L0,320Z"></path></svg>
    </section>


    <section class="intro-section" id="intro-section">
        <div class="container">
            <div class="row justify-content-lg-center align-items-center">

                <div class="col-lg-6 col-12">
                    <h2 class="mb-4">{{ __('Ishonchli va qulay') }} <br> {{ __('xizmatlar platformasi') }}</h2>

                    <p>{{ __('Platformamiz orqali turli xizmatlar, mutaxassislar va foydali postlar bilan tanishing. Har bir postda narx, davomiylik va batafsil ma’lumot mavjud.') }}</p>
                    <p>{{ __('O‘zingizga mos taklifni tanlang yoki savollaringiz bo‘lsa, biz bilan bog‘laning.') }}</p>

                    <a href="{{ route('posts.index') }}" class="custom-btn btn mt-3">{{ __('Postlarni ko‘rish') }}</a>
                </div>

                <div class="col-lg-6 col-12 custom-block-wrap">
                    <img src="{{ asset('images/male-wearing-apron-female-white-t-shirt-smiling-broadly-being-glad-clean.png') }}" class="img-fluid" alt="{{ __('Xizmatlar platformasi') }}">

                    <div class="custom-block d-flex flex-column">
                        <h6 class="text-white mb-3">{{ __('Savollaringiz bormi?') }} <br> {{ __('Biz bilan bog‘laning:') }}</h6>

                        <p class="d-flex mb-0">
                            <i class="bi-envelope-fill custom-icon me-2"></i>

                            <a href="{{ route('contact') }}">
                                {{ __('Kontakt sahifasi') }}
                            </a>
                        </p>
                    </div>
                </div>

            </div>
        </div>
    </section>


    <section class="services-section section-padding section-bg" id="services-section">
        <div class="container">
            <div class="row">

                <div class="col-lg-12 col-12 d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
                    <h2 class="mb-0">{{ __('Eng yaxshi takliflar') }}</h2>
                    <a href="{{ route('posts.index') }}" class="btn btn-outline-dark btn-sm">{{ __('Barcha postlar') }}</a>
                </div>

                @forelse($posts as $post)
                    <div class="col-lg-6 col-12">
                        <div class="services-thumb {{ $loop->iteration > 2 ? 'mb-lg-0' : '' }}">
                            <div class="row">
                                <div class="col-lg-5 col-md-5 col-12">
                                    <div class="services-image-wrap">
                                        <a href="{{ route('posts.show', $post) }}">
                                            <img src="{{ $post->image_url }}"
                                                 class="services-image img-fluid"
                                                 alt="{{ $post->title }}" loading="lazy">

                                            <img src="{{ $post->hover_image_url }}"
                                                 class="services-image services-image-hover img-fluid"
                                                 alt="{{ $post->title }}" loading="lazy">

                                            <div class="services-icon-wrap">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <p class="text-white mb-0">
                                                        <i class="bi-cash me-2"></i>
                                                        {{ number_format((float) $post->price, 0, ',', ' ') }} so‘m
                                                    </p>

                                                    <p class="text-white mb-0">
                                                        <i class="bi-clock-fill me-2"></i>
                                                        {{ $post->duration ? $post->duration.' '.__('soat') : '—' }}
                                                    </p>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                </div>

                                <div class="col-lg-7 col-md-7 col-12 d-flex align-items-center">
                                    <div class="services-info mt-4 mt-lg-0 mt-md-0">
                                        <h4 class="services-title mb-1 mb-lg-2">
                                            <a class="services-title-link"
                                               href="{{ route('posts.show', $post) }}">{{ $post->title }}</a>
                                        </h4>

                                        <p>{{ \Illuminate\Support\Str::limit($post->short_content, 140) }}</p>

                                        <div class="d-flex flex-wrap align-items-center gap-2">
                                            @if($post->category)
                                                <span class="badge bg-dark">{{ $post->category->name }}</span>
                                            @endif

                                            <a href="{{ route('posts.show', $post) }}"
                                               class="custom-btn btn button button--atlas mt-2 ms-auto">
                                                <span>{{ __('Batafsil') }}</span>

                                                <div class="marquee" aria-hidden="true">
                                                    <div class="marquee__inner">
                                                        <span>{{ __('Batafsil') }}</span>
                                                        <span>{{ __('Batafsil') }}</span>
                                                        <span>{{ __('Batafsil') }}</span>
                                                        <span>{{ __('Batafsil') }}</span>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="alert alert-info mb-0">
                            {{ __('Hozircha postlar yo‘q.') }}
                            @can('create', \App\Models\Post::class)
                                <a href="{{ route('posts.create') }}">{{ __('Birinchi postni yarating') }}</a>.
                            @endcan
                        </div>
                    </div>
                @endforelse

            </div>
        </div>
    </section>


    @if ($testimonials->isNotEmpty())
        <section class="testimonial-section section-padding section-bg">
            <div class="section-overlay"></div>

            <div class="container">
                <div class="row">

                    <div class="col-lg-12 col-12 text-center">
                        <h2 class="text-white mb-4">{{ __('Foydalanuvchilar fikrlari') }}</h2>
                    </div>

                    @foreach ($testimonials as $comment)
                        <div class="col-lg-4 col-12 mb-4">
                            <div class="featured-block h-100 mb-0">
                                <div class="d-flex align-items-center mb-3">
                                    <i class="bi-person-circle text-white fs-1"></i>

                                    <div class="ms-3">
                                        <h4 class="mb-0">{{ $comment->user?->name ?? __('Foydalanuvchi') }}</h4>

                                        @if ($comment->post)
                                            <a href="{{ route('posts.show', $comment->post) }}" class="small text-white-50">
                                                {{ \Illuminate\Support\Str::limit($comment->post->title, 40) }}
                                            </a>
                                        @endif
                                    </div>
                                </div>

                                <p class="mb-0">{{ \Illuminate\Support\Str::limit($comment->body, 180) }}</p>
                            </div>
                        </div>
                    @endforeach

                </div>
            </div>
        </section>
    @endif


    @if ($categories->isNotEmpty())
        <section class="partners-section">
            <div class="container">
                <div class="row justify-content-center align-items-center">

                    <div class="col-lg-12 col-12">
                        <h4 class="partners-section-title bg-white shadow-lg">{{ __('Kategoriyalar') }}</h4>
                    </div>

                    @foreach ($categories as $category)
                        <div class="col-lg-3 col-md-4 col-6 mb-3">
                            <a href="{{ route('posts.index', ['category' => $category->id]) }}" class="btn btn-outline-dark w-100">
                                {{ $category->name }}
                            </a>
                        </div>
                    @endforeach

                </div>
            </div>
        </section>
    @endif
</main>


</x-layouts.main>
