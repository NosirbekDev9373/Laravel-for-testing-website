<x-layouts.main>
    <x-slot:title>{{ __("Ro'yxatdan o'tish") }}</x-slot:title>
    <x-page-header>{{ __("Ro'yxatdan o'tish") }}</x-page-header>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-5 col-12">
                <form class="custom-form consulting-form bg-white shadow-lg p-4"
                      action="{{ route('register_store') }}" method="POST">
                    @csrf
                    <h3 class="mb-4">{{ __("Ro'yxatdan o'tish") }}</h3>

                    <div class="mb-3">
                        <label for="name" class="visually-hidden">Ismingiz</label>
                        <input type="text" name="name" id="name" class="form-control"
                               placeholder="Ismingiz" value="{{ old('name') }}"
                               required autofocus autocomplete="name"
                               aria-describedby="name-error"/>
                        @error('name')
                        <div class="alert alert-danger mt-1" id="name-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="email" class="visually-hidden">Email</label>
                        <input type="email" name="email" id="email" class="form-control"
                               placeholder="Email" value="{{ old('email') }}"
                               required autocomplete="email"
                               aria-describedby="email-error"/>
                        @error('email')
                        <div class="alert alert-danger mt-1" id="email-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password" class="visually-hidden">Parol</label>
                        <input type="password" name="password" id="password" class="form-control"
                               placeholder="Parol" required minlength="8" autocomplete="new-password"
                               aria-describedby="password-error"/>
                        @error('password')
                        <div class="alert alert-danger mt-1" id="password-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="password_confirmation" class="visually-hidden">Parolni tasdiqlang</label>
                        <input type="password" name="password_confirmation" id="password_confirmation"
                               class="form-control" placeholder="Parolni tasdiqlang"
                               required minlength="8" autocomplete="new-password"/>
                    </div>

                    <button type="submit" class="form-control custom-btn">{{ __("Ro'yxatdan o'tish") }}</button>

                    <p class="text-center mt-3">
                        Akkauntingiz bormi?
                        <a href="{{ route('login') }}">{{ __('Kirish') }}</a>
                    </p>
                </form>
            </div>
        </div>
    </div>
</x-layouts.main>
