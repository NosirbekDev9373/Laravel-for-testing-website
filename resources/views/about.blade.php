<x-layouts.main>

    <x-slot:title>
        {{ __('Biz haqimizda') }}
    </x-slot:title>

<main>

    <x-page-header>
        {{ __('Biz haqimizda') }}
    </x-page-header>


    <section class="section-padding">
        <div class="container">
            <div class="row align-items-center">

                <div class="col-lg-6 col-12">
                    <img src="{{ asset('images/cleaning-concept-with-man-showing-business-card.jpg') }}" class="featured-image img-fluid" alt="{{ __('Tozalash va xizmatlar platformasi') }}">
                </div>

                <div class="col-lg-6 col-12">
                    <div class="featured-block">
                        <h2 class="mb-4">{{ __('Tozalash va xizmatlar platformasi') }}</h2>

                        <p>{{ __('Platformamiz foydalanuvchilarga turli xizmatlar haqida foydali postlar topish, ular bilan tanishish va tajriba almashish imkonini beradi.') }}</p>

                        <p>{{ __('Har bir postda narx, davomiylik, kategoriya va batafsil ma’lumot mavjud. Foydalanuvchilar postlar ostida o‘z fikrlarini qoldirishlari mumkin.') }}</p>

                        <a class="custom-btn btn button button--atlas mt-3" href="{{ route('contact') }}">
                            <span>{{ __('Bog‘lanish') }}</span>

                            <div class="marquee" aria-hidden="true">
                                <div class="marquee__inner">
                                    <span>{{ __('Bog‘lanish') }}</span>
                                    <span>{{ __('Bog‘lanish') }}</span>
                                    <span>{{ __('Bog‘lanish') }}</span>
                                    <span>{{ __('Bog‘lanish') }}</span>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </section>


    @if ($authors->isNotEmpty())
        <section class="team-section section-padding section-bg">
            <div class="container">
                <div class="row">

                    <div class="col-lg-12 col-12">
                        <h2 class="mb-4">{{ __('Bizning mualliflar') }}</h2>
                    </div>

                    @foreach ($authors as $author)
                        <div class="col-lg-3 col-md-6 col-12 mb-4">
                            <div class="featured-block h-100 text-center">
                                <i class="bi-person-circle fs-1"></i>

                                <h4 class="mt-3 mb-2">{{ $author->name }}</h4>

                                <div class="d-flex flex-wrap gap-1 justify-content-center mb-2">
                                    @forelse($author->roles as $role)
                                        <span class="badge bg-secondary">{{ __(ucfirst($role->name)) }}</span>
                                    @empty
                                        <span class="badge bg-light text-dark">{{ __('Muallif') }}</span>
                                    @endforelse
                                </div>

                                <p class="mb-0">{{ $author->posts_count }} {{ __('ta post') }}</p>
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
                    <h4 class="partners-section-title bg-white shadow-lg">{{ __('Platforma statistikasi') }}</h4>
                </div>

                @foreach ($stats as $stat)
                    <div class="col-lg-3 col-md-6 col-12 mb-4">
                        <div class="featured-block h-100 text-center">
                            <h3 class="mb-1">{{ number_format($stat['value'], 0, '.', ' ') }}</h3>

                            <p class="mb-0">{{ __($stat['label']) }}</p>
                        </div>
                    </div>
                @endforeach

            </div>
        </div>
    </section>
</main>

</x-layouts.main>
