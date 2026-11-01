<x-layouts.main>
    <x-slot:title>
        Post - {{ $post->id }}
    </x-slot:title>

    <main>

        <x-page-header>
            Post - {{ $post->id }}
        </x-page-header>


        <section class="services-detail-section section-padding">
            <div class="container">
                <div class="row">

                    <div class="col-lg-6 col-12">
                        <div class="services-image-wrap">
                            <img src="{{ asset('storage/'.$post->image) }}"
                                 class="services-image img-fluid" alt="">

                            <img src="{{ asset('storage/'.($post->image_hover ?? $post->image)) }}"
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
                        </div>
                    </div>

                    <div class="col-lg-6 col-12">
                        <div class="services-info px-4 pt-4">
                            {{-- Kategoriya --}}
                            @if($post->category)
                                <span class="badge bg-dark mb-3">
                                <i class="bi-tag me-1">Kategoryasi: </i> {{ $post->category->name }}
                            </span>
                            @endif
                            @if($post->user)
                                <span class="badge bg-dark mb-3">
                                <i class="bi-tag me-1"> Post yaratuvchi: </i> {{ $post->user->name }}
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

                            <p> {{ $post->content }} <a href="#"></a></p>

                        </div>
                    </div>

                </div>
            </div>
        </section>


        <section class="section-padding pt-0">
            <div class="container">
                <div class="row">

                    <div class="col-lg-12 col-12">
                        <h2 class="mb-4">Photo gallery</h2>
                    </div>

                    @if($post->gallery_images && count($post->gallery_images) > 0 )
                        @foreach($post->gallery_images as $img)
                            <div class="col-lg-4 col-md-4 col-12">
                                <a href="{{ asset('storage/'.$img) }}" class="image-popup">
                                    <img src="{{ asset('storage/'.$img) }}"
                                         class="gallery-image img-fluid">
                                </a>
                            </div>
                        @endforeach
                    @endif

                </div>
            </div>
        </section>

        <section class="section-padding">
            <div class="container">
                <div class="row">

                    <div class="col-lg-8 col-12">
                        <h3 class="mb-4">{{ $post->comments()->count() }} Izohlar</h3>

                        {{-- Izohlar ro'yxati --}}
                        @forelse($post->comments as $comment)
                            <div class="bg-white shadow-sm p-4 mb-3 rounded">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="bi-person-circle me-2 fs-4"></i>
                                    <strong>{{ $comment->user->name }}</strong>
                                    <span class="text-muted ms-auto small">
                                {{ $comment->created_at->diffForHumans() }}
                            </span>
                                </div>
                                <p class="mb-0">{{ $comment->body }}</p>
                            </div>
                        @empty
                            <p class="text-muted">Hali izoh yo'q. Birinchi bo'ling!</p>
                        @endforelse

                        {{-- Izoh yozish formasi --}}
                        <div class="mt-5">
                            @auth
                                <h5 class="mb-3">Izoh qoldiring</h5>
                                <form action="{{ route('comments.store', ['post' => $post->id]) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="post_id" value="{{ $post->id }}">
                                    <div class="mb-3">
                                <textarea name="body" rows="4" class="form-control"
                                          placeholder="Izohingizni yozing...">{{ old('body') }}</textarea>
                                        @error('body')
                                        <div class="alert alert-danger mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <button type="submit" class="custom-btn btn">Yuborish</button>
                                </form>
                            @else
                                <div class="alert alert-info">
                                    Izoh qoldirish uchun
                                    <a href="{{ route('login') }}">kiring</a>
                                    yoki
                                    <a href="{{ route('register') }}">ro'yxatdan o'ting</a>.
                                </div>
                            @endauth
                        </div>
                    </div>

                </div>
            </div>
        </section>

        <section class="section-padding section-bg">
            <div class="container">
                <div class="row">

                    <div class="col-lg-12 col-12">
                        <h2 class="mb-4">Related Services</h2>
                    </div>
                    @foreach($related_services as $ralated)
                        <div class="col-lg-6 col-12">
                            <div class="services-thumb mb-lg-0">
                                <div class="row">
                                    <div class="col-lg-5 col-md-5 col-12">
                                        <div class="services-image-wrap">
                                            <a href="{{ route('posts.show', ['post' => $ralated->id]) }}">
                                                <img src="{{ asset('storage/'.$ralated->image) }}"
                                                     class="services-image img-fluid" alt="">

                                                <img
                                                    src="{{ asset('storage/'.($ralated->image_hover ?? $ralated->image)) }}"
                                                    class="services-image services-image-hover img-fluid" alt="">

                                                <div class="services-icon-wrap">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <p class="text-white mb-0">
                                                            <i class="bi-cash me-2"></i>
                                                            $ {{ $ralated->price }}
                                                        </p>

                                                        <p class="text-white mb-0">
                                                            <i class="bi-clock-fill me-2"></i>
                                                            {{ $ralated->duration }} hrs
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
                                                   href="{{ route('posts.show', ['post' => $ralated->id]) }}">{{ $post->title }}</a>
                                            </h4>

                                            <p>{{ $ralated->short_content }}</p>

                                            <div class="d-flex flex-wrap align-items-center">
                                                <div class="reviews-icons">
                                                    <i class="bi-star-fill"></i>
                                                    <i class="bi-star-fill"></i>
                                                    <i class="bi-star-fill"></i>
                                                    <i class="bi-star-fill"></i>
                                                    <i class="bi-star-fill"></i>
                                                </div>

                                                <a href="{{ route('posts.show', ['post' => $ralated->id]) }}"
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

                </div>
            </div>
        </section>


        <section class="partners-section">
            <div class="container">
                <div class="row justify-content-center align-items-center">

                    <div class="col-lg-12 col-12">
                        <h4 class="partners-section-title bg-white shadow-lg">Trusted by companies</h4>
                    </div>

                    <div class="col-lg-2 col-md-4 col-6">
                        <img src="/images/partners/glorix.svg" class="partners-image img-fluid">
                    </div>

                    <div class="col-lg-2 col-md-4 col-6">
                        <img src="/images/partners/rolf-leasing.svg" class="partners-image img-fluid">
                    </div>

                    <div class="col-lg-2 col-md-4 col-6">
                        <img src="/images/partners/woocommerce.svg" class="partners-image img-fluid">
                    </div>

                    <div class="col-lg-2 col-md-4 col-6">
                        <img src="/images/partners/toprak-leasing.svg" class="partners-image img-fluid">
                    </div>

                    <div class="col-lg-2 col-md-4 col-6">
                        <img src="/images/partners/unilabs.svg" class="partners-image img-fluid">
                    </div>

                </div>
            </div>
        </section>
    </main>

</x-layouts.main>
