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
                    <form method="GET" action="{{ route('services') }}" class="row g-2 mb-4 align-items-end">
                        <div class="col-md-6">
                            <label for="services-search" class="form-label">{{ __('Qidiruv') }}</label>
                            <input id="services-search" type="search" name="search" value="{{ $search }}" class="form-control" placeholder="{{ __('Sarlavha, mazmun yoki kategoriya bo‘yicha qidirish') }}">
                        </div>
                        <div class="col-md-3">
                            <label for="services-category" class="form-label">{{ __('Kategoriya') }}</label>
                            <select id="services-category" name="category" class="form-select" onchange="this.form.submit()">
                                <option value="">{{ __('Barchasi') }}</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" @selected($categoryId === $category->id)>{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 d-flex align-items-end gap-2">
                            <button type="submit" class="btn btn-dark"><i class="bi bi-search me-1"></i>{{ __('Qidirish') }}</button>
                            @if($search !== '' || $categoryId)
                                <a href="{{ route('services') }}" class="btn btn-outline-dark">{{ __('Tozalash') }}</a>
                            @endif
                        </div>
                    </form>

                    <div class="d-flex justify-content-end mb-3">
                        <span class="text-secondary small">{{ $posts->total() }} {{ __('ta xizmat') }}</span>
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
