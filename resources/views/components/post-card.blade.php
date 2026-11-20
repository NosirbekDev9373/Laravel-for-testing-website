@props(['post'])

<div class="col-lg-6 col-12 mb-4">
    <div class="services-thumb section-bg h-100 mb-0">
        <div class="row h-100">
            <div class="col-lg-5 col-md-5 col-12">
                <div class="services-image-wrap">
                    <a href="{{ route('posts.show', $post) }}">
                        <img src="{{ $post->image_url }}" class="services-image img-fluid" alt="{{ $post->title }}" loading="lazy">

                        <img src="{{ $post->hover_image_url }}" class="services-image services-image-hover img-fluid" alt="{{ $post->title }}" loading="lazy">

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
                    @if ($post->category)
                        <span class="badge bg-secondary mb-2">{{ $post->category->name }}</span>
                    @endif

                    <h4 class="services-title mb-1 mb-lg-2">
                        <a class="services-title-link" href="{{ route('posts.show', $post) }}">{{ $post->title }}</a>
                    </h4>

                    <p>{{ $post->short_content }}</p>

                    <div class="d-flex flex-wrap align-items-center">
                        <a href="{{ route('posts.show', $post) }}" class="custom-btn btn button button--atlas mt-2 ms-auto">
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
