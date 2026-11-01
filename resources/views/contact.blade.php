<x-layouts.main>

    <x-slot:title>
        Konatakt
    </x-slot:title>

<main>
    <x-page-header>
        Kontakt
    </x-page-header>
    <section class="contact-section section-padding">
        <div class="container">
            <div class="row">

                <div class="col-lg-5 col-12 me-auto mb-lg-0 mb-5">
                    <h2 class="my-3">We're happy to help</h2>

                    <p>Best Cleaning Service is ready to serve you. Clean Work is a professional website layout for your business.</p>

                    <div class="contact-info bg-white shadow-lg">
                        <h3 class="mb-4">Kontakt ma'lumotlar</h3>

                        <h5 class="d-flex mt-3 mb-3">
                            <i class="bi-geo-alt-fill custom-icon me-3"></i>
                            Uzbekistan
                        </h5>

                        <h5 class="d-flex mb-3">
                            <i class="bi-telephone-fill custom-icon me-3"></i>

                            <a href="#">
                                +998 93 355 93 73
                            </a>
                        </h5>

                        <h5 class="d-flex">
                            <i class="bi-envelope-fill custom-icon me-3"></i>

                            <a href="#">
                                nosir@gmail.com
                            </a>
                        </h5>
                    </div>
                </div>

                <div class="col-lg-6 col-12">
                    <form class="custom-form consulting-form bg-white shadow-lg mb-5 mb-lg-0" action="#" method="post" role="form">
                        <div class="consulting-form-header d-flex align-items-center">
                            <h3 class="mb-4">Get a Free Quotation</h3>
                        </div>

                        <div class="consulting-form-body">
                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-12">
                                    <input type="text" name="volunteer-name" id="volunteer-name" class="form-control" placeholder="John Walker" required>
                                </div>

                                <div class="col-lg-6 col-md-6 col-12">
                                    <input type="email" name="volunteer-email" id="volunteer-email" pattern="[^ @]*@[^ @]*" class="form-control" placeholder="yours@gmail.com" required>
                                </div>
                            </div>

                            <select class="form-select form-control" name="job-salary" id="job-salary" aria-label="Default select example">
                                <option selected>Service Type</option>
                                <option value="1">House cleaning</option>
                                <option value="2">Car washing</option>
                                <option value="2">Office cleaning</option>
                                <option value="2">Factory cleaning</option>
                            </select>

                            <textarea name="volunteer-message" rows="3" class="form-control" id="volunteer-message" placeholder="Comment (Optional)"></textarea>

                            <div class="col-lg-6 col-md-10 col-8 mx-auto">
                                <button type="submit" class="form-control">Submit Request</button>
                            </div>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </section>
</main>

</x-layouts.main>
