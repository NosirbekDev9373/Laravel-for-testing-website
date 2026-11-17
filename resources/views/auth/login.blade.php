<x-layouts.main>
    <x-slot:title>{{ __('Kirish') }}</x-slot:title>
    <x-page-header>{{ __('Kirish') }}</x-page-header>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-5 col-12">
                <form class="custom-form consulting-form bg-white shadow-lg p-4"
                      action="{{ route('authenticate') }}" method="POST">
                    @csrf
                    <h3 class="mb-4">{{ __('Kirish') }}</h3>

                    <div class="mb-3">
                        <label for="email" class="visually-hidden">Email</label>
                        <input type="email" name="email" id="email" class="form-control"
                               placeholder="Email" value="{{ old('email') }}"
                               required autofocus autocomplete="username"
                               aria-describedby="email-error"/>
                        @error('email')
                        <div class="alert alert-danger mt-1" id="email-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password" class="visually-hidden">Parol</label>
                        <input type="password" name="password" id="password" class="form-control"
                               placeholder="Parol" required autocomplete="current-password"
                               aria-describedby="password-error"/>
                        @error('password')
                        <div class="alert alert-danger mt-1" id="password-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4 form-check">
                        <input type="checkbox" name="remember" value="1" class="form-check-input" id="remember"
                               @checked(old('remember'))/>
                        <label class="form-check-label" for="remember">Meni eslab qol</label>
                    </div>

                    <button type="submit" class="form-control custom-btn">{{ __('Kirish') }}</button>

                    <p class="text-center mt-3">
                        Akkauntingiz yo'qmi?
                        <a href="{{ route('register') }}">{{ __("Ro'yxatdan o'tish") }}</a>
                    </p>
                </form>
            </div>
        </div>
    </div>
</x-layouts.main>
