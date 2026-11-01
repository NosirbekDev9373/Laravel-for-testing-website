<x-layouts.main>
    <x-slot:title>Kirish</x-slot:title>
    <x-page-header>Kirish</x-page-header>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-5 col-12">
                <form class="custom-form consulting-form bg-white shadow-lg p-4"
                      action="{{ route('login') }}" method="POST">
                    @csrf
                    <h3 class="mb-4">Kirish</h3>

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

                    <div class="mb-4 form-check">
                        <input type="checkbox" name="remember" class="form-check-input" id="remember"/>
                        <label class="form-check-label" for="remember">Meni eslab qol</label>
                    </div>

                    <button type="submit" class="form-control custom-btn">Kirish</button>

                    <p class="text-center mt-3">
                        Akkauntingiz yo'qmi?
                        <a href="{{ route('register') }}">Ro'yxatdan o'tish</a>
                    </p>
                </form>
            </div>
        </div>
    </div>
</x-layouts.main>
