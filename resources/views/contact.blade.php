<x-layouts.main>

    <x-slot:title>
        {{ __('Kontakt') }}
    </x-slot:title>

<main>
    <x-page-header>
        {{ __('Kontakt') }}
    </x-page-header>

    <section class="contact-section section-padding">
        <div class="container">
            <x-alert />

            <div class="row">

                <div class="col-lg-5 col-12 me-auto mb-lg-0 mb-5">
                    <h2 class="my-3">{{ __('Savollaringiz bormi?') }}</h2>

                    <p>{{ __('Bizga xabar yuboring yoki quyidagi kontakt ma’lumotlari orqali bog‘laning. Tez orada javob beramiz.') }}</p>

                    <div class="contact-info bg-white shadow-lg">
                        <h3 class="mb-4">{{ __('Kontakt ma’lumotlar') }}</h3>

                        <h5 class="d-flex mt-3 mb-3">
                            <i class="bi-geo-alt-fill custom-icon me-3"></i>
                            {{ __('O‘zbekiston') }}
                        </h5>

                        <h5 class="d-flex mb-3">
                            <i class="bi-telephone-fill custom-icon me-3"></i>

                            <a href="tel:+998933559373">
                                +998 93 355 93 73
                            </a>
                        </h5>

                        <h5 class="d-flex">
                            <i class="bi-envelope-fill custom-icon me-3"></i>

                            <a href="mailto:nosir@gmail.com">
                                nosir@gmail.com
                            </a>
                        </h5>
                    </div>
                </div>

                <div class="col-lg-6 col-12">
                    <form class="custom-form consulting-form bg-white shadow-lg mb-5 mb-lg-0" action="{{ route('contact.store') }}" method="post" role="form">
                        @csrf

                        <div class="consulting-form-header d-flex align-items-center">
                            <h3 class="mb-4">{{ __('Xabar yuborish') }}</h3>
                        </div>

                        <div class="consulting-form-body">
                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-12 mb-3">
                                    <label for="name" class="form-label">{{ __('Ismingiz') }}</label>

                                    <input type="text" name="name" id="name" value="{{ old('name') }}"
                                           class="form-control @error('name') is-invalid @enderror"
                                           placeholder="{{ __('Ism Familiya') }}" required>

                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-lg-6 col-md-6 col-12 mb-3">
                                    <label for="email" class="form-label">{{ __('Email') }}</label>

                                    <input type="email" name="email" id="email" value="{{ old('email') }}"
                                           class="form-control @error('email') is-invalid @enderror"
                                           placeholder="email@example.com" required>

                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="service_type" class="form-label">{{ __('Xizmat turi') }}</label>

                                <select name="service_type" id="service_type"
                                        class="form-select form-control @error('service_type') is-invalid @enderror" required>
                                    <option value="">{{ __('Tanlang') }}</option>

                                    @foreach ($categories as $category)
                                        <option value="{{ $category->name }}" @selected(old('service_type') === $category->name)>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach

                                    <option value="Boshqa" @selected(old('service_type') === 'Boshqa')>{{ __('Boshqa') }}</option>
                                </select>

                                @error('service_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="message" class="form-label">{{ __('Xabar') }}</label>

                                <textarea name="message" rows="5" id="message"
                                          class="form-control @error('message') is-invalid @enderror"
                                          placeholder="{{ __('Xabaringizni yozing') }}" required>{{ old('message') }}</textarea>

                                @error('message')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-lg-6 col-md-10 col-8 mx-auto">
                                <button type="submit" class="form-control">{{ __('Xabar yuborish') }}</button>
                            </div>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </section>
</main>

</x-layouts.main>
