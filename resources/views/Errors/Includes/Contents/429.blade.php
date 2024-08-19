<main class="form-signin w-100 m-auto">
    <div class="container my-5">
        <div class="row g-1 px-2 py-2 align-items-center rounded-3 border shadow-lg">
            <div class="col-md-6 d-flex justify-content-center">
                <img src="{{ asset('Images/Vector/Error 429.png') }}" class="img-fluid" alt="Phone image">
            </div>
            <div class="col-md-6">
                <h1>Error 429</h1>
                <h2>Too Many Request</h2>
                <br/>
                <a class="btn btn-primary" href={{ Request::url() }}>
                    <i class="bi bi-arrow-clockwise"></i>
                    Refresh
                </a>
                <a class="btn btn-secondary disabled" data-bs-toggle="offcanvas" href="#offcanvas-help" role="button" aria-controls="offcanvas-help">
                    <i class="bi bi-question-lg"></i>
                    Bantuan
                </a>
                <a class="btn btn-secondary" href="/">
                    <i class="bi bi-arrow-return-left"></i>
                    Kembali
                </a>
            </div>
        </div>
    </div>
</main>
