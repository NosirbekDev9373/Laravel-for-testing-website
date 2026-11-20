<x-layouts.main>
    <x-slot:title>
        {{ $post->title }}
    </x-slot:title>

    <main>

        <x-page-header>
            {{ $post->title }}
        </x-page-header>


        <section class="services-detail-section section-padding">
            <div class="container">
                <div class="row">

                    <div class="col-lg-6 col-12">
                        <div class="services-image-wrap">
                            <img src="{{ $post->image_url }}"
                                 class="services-image img-fluid" alt="{{ $post->title }}">

                            <img src="{{ $post->hover_image_url }}"
                                 class="services-image services-image-hover img-fluid"
                                 alt="{{ $post->title }}">

                            <div class="services-icon-wrap">
                                <div class="d-flex justify-content-between align-items-center">
                                    <p class="text-white mb-0">
                                        <i class="bi-cash me-2"></i>
                                        {{ number_format((float) $post->price, 0, ',', ' ') }} so‘m
                                    </p>

                                    <p class="text-white mb-0">
                                        <i class="bi-clock-fill me-2"></i>
                                        {{ $post->duration }} {{ __('soat') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6 col-12">
                        <div class="services-info px-4 pt-4">
                            {{-- Kategoriya --}}
                            @if($post->category)
                                <span class="badge bg-dark mb-3">
                                    <i class="bi-tag me-1"></i>{{ __('Kategoriya') }}: {{ $post->category->name }}
                                </span>
                            @endif
                            @if($post->user)
                                <span class="badge bg-dark mb-3">
                                    <i class="bi-tag me-1"></i>{{ __('Post yaratuvchi') }}: {{ $post->user->name }}
                                </span>
                            @endif
                            {{-- Teglar --}}
                            @if($post->tags->count() > 0)
                                <div class="mb-3 d-flex flex-wrap gap-2">
                                    @foreach($post->tags as $tag)
                                        <span class="badge bg-secondary">
                                        <i class="bi-hash me-1"></i>{{ $tag->name }}
                                    </span>
                                    @endforeach
                                </div>
                            @endif

                            <h2 class="mb-4">{{ $post->title }}</h2>

                            <div class="post-content">{!! nl2br(e($post->content)) !!}</div>

                        </div>
                    </div>

                </div>
            </div>
        </section>


        @if($post->gallery_images && count($post->gallery_images) > 0)
            <section class="section-padding pt-0">
                <div class="container">
                    <div class="row">

                        <div class="col-lg-12 col-12">
                            <h2 class="mb-4">{{ __('Galereya') }}</h2>
                        </div>

                        @foreach($post->gallery_images as $img)
                            <div class="col-lg-3 col-md-6 col-12 mb-4">
                                <a href="{{ asset('storage/'.$img) }}" class="image-popup">
                                    <img src="{{ asset('storage/'.$img) }}"
                                         class="gallery-image img-fluid" alt="{{ $post->title }} — {{ __('galereya') }} {{ $loop->iteration }}"
                                         loading="lazy">
                                </a>
                            </div>
                        @endforeach

                    </div>
                </div>
            </section>
        @endif

        <section class="section-padding">
            <div class="container">
                <div class="row">

                    <div class="col-lg-8 col-12">
                        <h3 class="mb-4">{{ $post->comments_count }} {{ __('Izohlar') }}</h3>

                        {{-- Izohlar ro'yxati --}}
                        @forelse($comments as $comment)
                            <div class="bg-white shadow-sm p-4 mb-3 rounded">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="bi-person-circle me-2 fs-4"></i>
                                    <strong>{{ $comment->user?->name ?? __('Foydalanuvchi') }}</strong>
                                    <span class="text-muted ms-auto small">
                                {{ $comment->created_at->diffForHumans() }}
                            </span>
                                </div>
                                <p class="mb-0">{!! nl2br(e($comment->body)) !!}</p>
                            </div>
                        @empty
                            <p class="text-muted">{{ __("Hali izoh yo'q. Birinchi bo'ling!") }}</p>
                        @endforelse

                        @if($comments->hasPages())
                            <div class="mt-3">{{ $comments->links() }}</div>
                        @endif

                        {{-- Izoh yozish formasi --}}
                        <div class="mt-5">
                            @auth
                                <h5 class="mb-3">{{ __('Izoh qoldiring') }}</h5>
                                <form action="{{ route('comments.store') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="post_id" value="{{ $post->id }}">
                                    <div class="mb-3">
                                <textarea name="body" rows="4" class="form-control"
                                          placeholder="{{ __('Izohingizni yozing...') }}" maxlength="2000"
                                          required>{{ old('body') }}</textarea>
                                        @error('body')
                                        <div class="alert alert-danger mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <button type="submit" class="custom-btn btn">{{ __('Yuborish') }}</button>
                                </form>
                            @else
                                <div class="alert alert-info">
                                    {{ __('Izoh qoldirish uchun') }}
                                    <a href="{{ route('login') }}">{{ __('kiring') }}</a>
                                    {{ __('yoki') }}
                                    <a href="{{ route('register') }}">{{ __("ro'yxatdan o'ting") }}</a>.
                                </div>
                            @endauth
                        </div>
                    </div>

                </div>
            </div>
        </section>

        @if($related_services->isNotEmpty())
        <section class="section-padding section-bg">
            <div class="container">
                <div class="row">

                    <div class="col-lg-12 col-12">
                        <h2 class="mb-4">{{ __('Related Services') }}</h2>
                    </div>
                    @foreach($related_services as $related)
                        <div class="col-lg-6 col-12">
                            <div class="services-thumb mb-lg-0">
                                <div class="row">
                                    <div class="col-lg-5 col-md-5 col-12">
                                        <div class="services-image-wrap">
                                            <a href="{{ route('posts.show', ['post' => $related->id]) }}">
                                                <img src="{{ $related->image_url }}"
                                                     class="services-image img-fluid" alt="{{ $related->title }}"
                                                     loading="lazy">

                                                <img src="{{ $related->hover_image_url }}"
                                                     class="services-image services-image-hover img-fluid"
                                                     alt="{{ $related->title }}" loading="lazy">

                                                <div class="services-icon-wrap">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <p class="text-white mb-0">
                                                            <i class="bi-cash me-2"></i>
                                                            {{ number_format((float) $related->price, 0, ',', ' ') }} so‘m
                                                        </p>

                                                        <p class="text-white mb-0">
                                                            <i class="bi-clock-fill me-2"></i>
                                                            {{ $related->duration }} {{ __('soat') }}
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
                                                   href="{{ route('posts.show', ['post' => $related->id]) }}">{{ $related->title }}</a>
                                            </h4>

                                            <p>{{ $related->short_content }}</p>

                                            <div class="d-flex flex-wrap align-items-center">
                                                <div class="reviews-icons">
                                                    <i class="bi-star-fill"></i>
                                                    <i class="bi-star-fill"></i>
                                                    <i class="bi-star-fill"></i>
                                                    <i class="bi-star-fill"></i>
                                                    <i class="bi-star-fill"></i>
                                                </div>

                                                <a href="{{ route('posts.show', ['post' => $related->id]) }}"
                                                   class="custom-btn btn button button--atlas mt-2 ms-auto">
                                                    <span>{{ __("O'qish") }}</span>

                                                    <div class="marquee" aria-hidden="true">
                                                        <div class="marquee__inner">
                                                            <span>{{ __("Batafsil ma'lumot") }}</span>
                                                            <span>{{ __("Batafsil ma'lumot") }}</span>
                                                            <span>{{ __("Batafsil ma'lumot") }}</span>
                                                            <span>{{ __("Batafsil ma'lumot") }}</span>
                                                        </div>
                                                    </div>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach

                </div>
            </div>
        </section>
        @endif


        <section class="partners-section">
            <div class="container">
                <div class="row justify-content-center align-items-center">

                    <div class="col-lg-12 col-12">
                        <h4 class="partners-section-title bg-white shadow-lg">{{ __('Trusted by companies') }}</h4>
                    </div>

                    <div class="col-lg-2 col-md-4 col-6">
                        <img src="{{ asset('images/partners/glorix.svg') }}" class="partners-image img-fluid" alt="Glorix">
                    </div>

                    <div class="col-lg-2 col-md-4 col-6">
                        <img src="{{ asset('images/partners/rolf-leasing.svg') }}" class="partners-image img-fluid" alt="Rolf Leasing">
                    </div>

                    <div class="col-lg-2 col-md-4 col-6">
                        <img src="{{ asset('images/partners/woocommerce.svg') }}" class="partners-image img-fluid" alt="WooCommerce">
                    </div>

                    <div class="col-lg-2 col-md-4 col-6">
                        <img src="{{ asset('images/partners/toprak-leasing.svg') }}" class="partners-image img-fluid" alt="Toprak Leasing">
                    </div>

                    <div class="col-lg-2 col-md-4 col-6">
                        <img src="{{ asset('images/partners/unilabs.svg') }}" class="partners-image img-fluid" alt="Unilabs">
                    </div>

                </div>
            </div>
        </section>
    </main>

</x-layouts.main>
