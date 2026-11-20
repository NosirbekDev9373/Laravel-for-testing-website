<x-layouts.main>

    <x-slot:title>
        {{ __('Xizmatlar') }}
    </x-slot:title>

<main>

    <x-page-header>
        {{ __('Xizmatlar') }}
    </x-page-header>

    <section class="services-section section-padding">
        <div class="container">
            <div class="row">

                <div class="col-lg-12 col-12 d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
                    <h2 class="mb-0">{{ __('Barcha xizmatlar') }}</h2>
                    <a href="{{ route('posts.index') }}" class="btn btn-outline-dark btn-sm">{{ __('Barcha postlar') }}</a>
                </div>

                <div class="col-lg-12 col-12">
                    <form method="GET" action="{{ route('services') }}" class="row g-2 mb-4">
                        @if($categoryId)
                            <input type="hidden" name="category" value="{{ $categoryId }}">
                        @endif
                        <div class="col-md-8">
                            <label for="services-search" class="form-label">{{ __('Qidiruv') }}</label>
                            <input id="services-search" type="search" name="search" value="{{ $search }}" class="form-control" placeholder="{{ __('Sarlavha, mazmun yoki kategoriya bo‘yicha qidirish') }}">
                        </div>
                        <div class="col-md-4 d-flex align-items-end gap-2">
                            <button type="submit" class="btn btn-dark"><i class="bi bi-search me-1"></i>{{ __('Qidirish') }}</button>
                            @if($search !== '')
                                <a href="{{ route('services', array_filter(['category' => $categoryId])) }}" class="btn btn-outline-dark">{{ __('Tozalash') }}</a>
                            @endif
                        </div>
                    </form>

                    <div class="d-flex flex-wrap gap-2 mb-4 align-items-center">
                        <a href="{{ route('services', array_filter(['search' => $search])) }}"
                           class="btn btn-sm {{ !$categoryId ? 'btn-dark' : 'btn-outline-dark' }}">
                            {{ __('Barchasi') }}
                        </a>

                        @foreach ($categories as $category)
                            <a href="{{ route('services', array_filter(['category' => $category->id, 'search' => $search])) }}"
                               class="btn btn-sm {{ $categoryId === $category->id ? 'btn-dark' : 'btn-outline-dark' }}">
                                {{ $category->name }}
                            </a>
                        @endforeach
                        <span class="text-secondary small ms-auto">{{ $posts->total() }} {{ __('ta xizmat') }}</span>
                    </div>
                </div>

                @forelse ($posts as $post)
                    <x-post-card :post="$post" />
                @empty
                    <div class="col-12">
                        <div class="alert alert-info mb-0">{{ $search !== '' ? __('Qidiruv bo‘yicha xizmat topilmadi.') : __('Hozircha xizmatlar yo‘q.') }}</div>
                    </div>
                @endforelse

                <div class="col-12">
                    {{ $posts->links() }}
                </div>

            </div>
        </div>
    </section>
</main>

</x-layouts.main>
