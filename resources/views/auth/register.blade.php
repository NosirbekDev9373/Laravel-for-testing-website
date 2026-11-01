<x-layouts.main>
    <x-slot:title>Ro'yxatdan o'tish</x-slot:title>
    <x-page-header>Ro'yxatdan o'tish</x-page-header>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-5 col-12">
                <form class="custom-form consulting-form bg-white shadow-lg p-4"
                      action="{{ route('register') }}" method="POST">
                    @csrf
                    <h3 class="mb-4">Ro'yxatdan o'tish</h3>

                    <div class="mb-3">
                        <input type="text" name="name" class="form-control"
                               placeholder="Ismingiz" value="{{ old('name') }}"/>
                        @error('name')
                        <div class="alert alert-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <input type="email" name="email" class="form-control"
                               placeholder="Email" value="{{ old('email') }}"/>
                        @error('email')
                        <div class="alert alert-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <input type="password" name="password" class="form-control"
                               placeholder="Parol"/>
                        @error('password')
                        <div class="alert alert-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <input type="password" name="password_confirmation" class="form-control"
                               placeholder="Parolni tasdiqlang"/>
                    </div>

                    <button type="submit" class="form-control custom-btn">Ro'yxatdan o'tish</button>

                    <p class="text-center mt-3">
                        Akkauntingiz bormi?
                        <a href="{{ route('login') }}">Kirish</a>
                    </p>
                </form>
            </div>
        </div>
    </div>
</x-layouts.main>
