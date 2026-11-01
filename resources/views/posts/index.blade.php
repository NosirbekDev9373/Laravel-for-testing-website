<x-layouts.main>

    <x-slot:title>
        Blog
    </x-slot:title>

    <main>

        <x-page-header>
            Blog
        </x-page-header>

        {{-- Category filter --}}
        <div class="container pt-4">
            <x-alert>
            </x-alert>
            <div class="d-flex flex-wrap gap-2 mb-4">
                <a href="{{ route('posts.index') }}"
                   class="btn btn-sm {{ !request('category') ? 'btn-dark' : 'btn-outline-dark' }}">
                    Barchasi
                </a>
                @foreach($categories as $category)
                    <a href="{{ route('posts.index', ['category' => $category->id]) }}"
                       class="btn btn-sm {{ request('category') == $category->id ? 'btn-dark' : 'btn-outline-dark' }}">
                        {{ $category->name }}
                    </a>
                @endforeach
            </div>
        </div>
        {{-- End Category filter --}}

        {{--Servis--}}
        <section class="services-detail-section section-padding">
            <div class="container">
                <div class="row">
                    @foreach($posts as $post)
                        <div class="col-lg-6 col-12">
                            <div class="services-thumb section-bg mb-lg-0">
                                <div class="row">
                                    <div class="col-lg-5 col-md-5 col-12">
                                        <div class="services-image-wrap">
                                            <a href="{{ route('posts.show', ['post' => $post->id]) }}">
                                                <img src="{{asset('storage/'.$post->image)}}"
                                                     class="services-image img-fluid" alt="">
                                                <img src="{{asset('storage/'.$post->image)}}"
                                                     class="services-image services-image-hover img-fluid" alt="">

                                                <div class="services-icon-wrap">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <p class="text-white mb-0">
                                                            <i class="bi-cash me-2"></i>
                                                            ${{ $post->price }}
                                                        </p>

                                                        <p class="text-white mb-0">
                                                            <i class="bi-clock-fill me-2"></i>
                                                            {{ $post->duration }} hrs
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
                                                        <span>O'qish</span>

                                                        <div class="marquee" aria-hidden="true">
                                                            <div class="marquee__inner">
                                                                <span>Batafsil ma'lumot</span>
                                                                <span>Batafsil ma'lumot</span>
                                                                <span>Batafsil ma'lumot</span>
                                                                <span>Batafsil ma'lumot</span>
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
                        <h4 class="partners-section-title bg-white shadow-lg">Trusted by companies</h4>
                    </div>

                    <div class="col-lg-2 col-md-4 col-6">
                        <img src="images/partners/glorix.svg" class="partners-image img-fluid">
                    </div>

                    <div class="col-lg-2 col-md-4 col-6">
                        <img src="images/partners/rolf-leasing.svg" class="partners-image img-fluid">
                    </div>

                    <div class="col-lg-2 col-md-4 col-6">
                        <img src="images/partners/woocommerce.svg" class="partners-image img-fluid">
                    </div>

                    <div class="col-lg-2 col-md-4 col-6">
                        <img src="images/partners/toprak-leasing.svg" class="partners-image img-fluid">
                    </div>

                    <div class="col-lg-2 col-md-4 col-6">
                        <img src="images/partners/unilabs.svg" class="partners-image img-fluid">
                    </div>

                </div>
            </div>
        </section>
        {{--End Trusted by companies--}}
    </main>

</x-layouts.main>
