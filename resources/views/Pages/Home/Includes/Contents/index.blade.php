<!--PRELOADER-->
<div class="preloader">
    <div class="loading">
        <div class="spinner-border text-primary" role="status"></div>
    </div>
</div>
<!--ALERT-->
<div class="container pt-1">
    @if (Session::get('code_alert') == 1)
    @include('Templates.Includes.Components.alert')
    @endif
</div>
<!--RESULT JUMBOTRON-->
@if (!empty($latest_best)) <!--IF CURRENT PERIOD HAS BEEN FINISHED-->
<div class="container my-1 collapse show pt-1" id="collapseExample">
    <div class="jumbotron jumbotron-fluid my-1 rounded-3">
        <video autoplay muted loop id="myVideo">
            <source src="{{ asset('Videos/Fireworks.mp4') }}" type="video/mp4">
            Your browser does not support HTML5 video.
        </video>
        <div class="container">
            <div class="p-4 text-center rounded-3">
                <h1 class="text-light">Selamat Kepada {{ $latest_best->employee_name }}</h1>
                <p class="text-light lead">
                    Atas terpilihnya menjadi <strong>KARYAWAN TERBAIK</strong> pada Periode {{ $latest_best->period->name }}
                </p>
                <div data-bs-theme="dark">
                    <button type="button" class="btn-close position-absolute top-0 end-0 m-2" data-bs-toggle="collapse" data-bs-target="#collapseExample" aria-expanded="true" aria-controls="collapseExample" aria-label="Close"></button>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
<!--MAIN CONTENT-->
<div class="container pt-1">
    <!--MAIN JUMBOTRON-->
    <div id="home-img" class="p-5 mb-4 text-bg-dark rounded-3">
        <div class="container-fluid py-5">
            <div class="row flex-lg-row-reverse align-items-center g-5">
                <div class="col-10 col-sm-8 col-lg-6">

                </div>
                <div class="col-lg-6">
                    <div class="typing" style="width: 21ch;">
                        <h1 class="display-5 fw-bold">Selamat Datang</h1>
                    </div>
                    <p class="col-md-8 fs-4">
                        Selamat Datang di Aplikasi Karyawan Terbaik BPS Provinsi Jawa Timur.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
<!--FEATURES-->
<div class="container px-4 pt-1" id="hanging-icons">
    <h2 class="pb-2 border-bottom">Fitur-Fitur</h2>
    <div class="row g-4 py-5 row-cols-1 row-cols-lg-3">
        <div class="col d-flex align-items-start">
            <div class="icon-square text-body-emphasis bg-body-secondary d-inline-flex align-items-center justify-content-center fs-4 flex-shrink-0 me-3">
                <svg class="bi" width="1em" height="1em"><use xlink:href="#import-home"/></svg>
            </div>
            <div>
                <h3 class="fs-2 text-body-emphasis">Import Data Nilai</h3>
                <p>Dengan adanya fitur Import, anda tidak perlu melakukan penambahan data nilai secara manual.</p>
            </div>
        </div>
        <div class="col d-flex align-items-start">
            <div class="icon-square text-body-emphasis bg-body-secondary d-inline-flex align-items-center justify-content-center fs-4 flex-shrink-0 me-3">
                <svg class="bi" width="1em" height="1em"><use xlink:href="#analysis-home"/></svg>
            </div>
            <div>
                <h3 class="fs-2 text-body-emphasis">Metode SAW</h3>
                <p>Proses penentuan karyawan terbaik menggunakan metode Simple Additive Weighting (SAW) untuk mempercepat proses penentuan.</p>
            </div>
        </div>
        <div class="col d-flex align-items-start">
            <div class="icon-square text-body-emphasis bg-body-secondary d-inline-flex align-items-center justify-content-center fs-4 flex-shrink-0 me-3">
                <svg class="bi" width="1em" height="1em"><use xlink:href="#easy-home"/></svg>
            </div>
            <div>
                <h3 class="fs-2 text-body-emphasis">Easy To Use</h3>
                <p>Penggunaan aplikasi akan terasa menjadi lebih cepat dan mudah dengan dukungan kombinasi Navs, Tabs, dan Modal dalam satu halaman saja.</p>
            </div>
        </div>
    </div>
</div>
