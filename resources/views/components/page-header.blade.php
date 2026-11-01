<section class="banner-section d-flex justify-content-center align-items-end">
    <div class="section-overlay"></div>

    <div class="container">
        <div class="row">

            <div class="col-lg-7 col-12">
                <h1 class="text-white mb-lg-0">
                    {{ $slot }}
                </h1>
            </div>

            <div class="col-lg-4 col-12 d-flex justify-content-lg-end align-items-center ms-auto">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-center">
                        <li class="breadcrumb-item"><a href="/">Home</a></li>

                        <li class="breadcrumb-item active" aria-current="page">{{ $slot }}</li>
                    </ol>
                </nav>
            </div>

        </div>
    </div>
</section>
