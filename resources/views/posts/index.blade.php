<x-layouts.main>

    <x-slot:title>
        Blog
    </x-slot:title>

    <main>

        <x-page-header>
            Blog
        </x-page-header>

        {{-- Search and category filter --}}
        <div class="container pt-4">
            <x-alert>
            </x-alert>

            <form method="GET" action="{{ route('posts.index') }}" class="row g-2 mb-4 align-items-end">
                <div class="col-md-6">
                    <label for="blog-search" class="form-label">{{ __('Qidiruv') }}</label>
                    <input id="blog-search" type="search" name="search" value="{{ $search }}" class="form-control" placeholder="{{ __('Sarlavha, mazmun yoki kategoriya bo‘yicha qidirish') }}">
                </div>
                <div class="col-md-3">
                    <label for="blog-category" class="form-label">{{ __('Kategoriya') }}</label>
                    <select id="blog-category" name="category" class="form-select" onchange="this.form.submit()">
                        <option value="">{{ __('Barchasi') }}</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" @selected($categoryId === $category->id)>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-dark"><i class="bi bi-search me-1"></i>{{ __('Qidirish') }}</button>
                    @if($search !== '' || $categoryId)
                        <a href="{{ route('posts.index') }}" class="btn btn-outline-dark">{{ __('Tozalash') }}</a>
                    @endif
                </div>
            </form>

            <div class="d-flex justify-content-end mb-3">
                <span class="text-secondary small">{{ $posts->total() }} {{ __('ta post') }}</span>
            </div>
        </div>
        {{-- End Search and category filter --}}

        {{--Servis--}}
        <section class="services-detail-section section-padding">
            <div class="container">
                <div class="row">
                    @forelse($posts as $post)
                        <div class="col-lg-6 col-12">
                            <div class="services-thumb section-bg mb-lg-0">
                                <div class="row">
                                    <div class="col-lg-5 col-md-5 col-12">
                                        <div class="services-image-wrap">
                                            <a href="{{ route('posts.show', ['post' => $post->id]) }}">
                                                <img src="{{ $post->image_url }}"
                                                     class="services-image img-fluid" alt="{{ $post->title }}"
                                                     loading="lazy">
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
                                                            {{ $post->duration }} {{ __('soat') }}
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
                                                   href="{{ route('posts.show', ['post' => $post->id]) }}">{{ $post->title }}</a>
                                            </h4>

                                            <p>{{ $post->short_content }}</p>

                                            <div class="d-flex flex-wrap align-items-center">
                                                <div class="reviews-icons">
                                                    <i class="bi-star-fill"></i>
                                                    <i class="bi-star-fill"></i>
                                                    <i class="bi-star-fill"></i>
                                                    <i class="bi-star"></i>
                                                    <i class="bi-star"></i>
                                                </div>
                                                    <a href="{{ route('posts.show', ['post' => $post->id]) }}"
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
                    @empty
                        <div class="col-12">
                            <div class="alert alert-info">{{ $search !== '' ? __('Qidiruv bo‘yicha post topilmadi.') : __('Hozircha postlar yo‘q.') }}</div>
                        </div>
                    @endforelse

                    {{ $posts->links() }}

                </div>
            </div>
        </section>
        {{--End Servis--}}

        {{--Trusted by companies--}}
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
        {{--End Trusted by companies--}}
    </main>

</x-layouts.main>
