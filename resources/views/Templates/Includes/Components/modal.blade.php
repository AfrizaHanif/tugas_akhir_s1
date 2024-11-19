<!--LOGOUT CONFIRMATION-->
<div class="modal fade modal-sheet p-4 py-md-5" tabindex="-1" role="dialog" id="modallogout">
    <div class="modal-dialog" role="document">
        <div class="modal-content rounded-3 shadow">
            <form action="{{ route('logout') }}" method="post">
                <div class="modal-body p-4 text-center">
                    <h5 class="mb-0">Keluar?</h5>
                    <p class="mb-0">Semua hasil proses akan terhapus setelah keluar dari sesi ini.</p>
                </div>
                <div class="modal-footer flex-nowrap p-0">
                    @csrf
                    <input type="submit" class="btn btn-lg btn-link fs-6 text-decoration-none col-6 py-3 m-0 rounded-0 border-end" value="Ya">
                    <button type="button" class="btn btn-lg btn-link fs-6 text-decoration-none col-6 py-3 m-0 rounded-0" data-bs-dismiss="modal">Tidak</button>
                </div>
            </form>
        </div>
    </div>
</div>

@if (Request::is('admin') || Request::is('officer'))
<!--WELCOME (FIRST TIME)-->
<div class="modal modal-sheet fade p-4 py-md-1" tabindex="-1" role="dialog" id="modal-dsh-first">
    <div class="modal-dialog" role="document">
        <div class="modal-content rounded-4 shadow">
            <div class="modal-body p-5">
                <h2 class="fw-bold mb-0">Selamat Datang!</h2>
                <ul class="d-grid gap-4 my-5 list-unstyled small">
                    @if (Auth::user()->part == 'Admin')
                    <li class="d-flex gap-4">
                        <svg class="bi text-body-secondary flex-shrink-0" width="48" height="48"><use xlink:href="#first_easy"/></svg>
                        <div>
                            <h5 class="mb-0">Mudah Digunakan</h5>
                            Mengelola data jadi lebih mudah hanya dalam satu halaman saja.
                        </div>
                    </li>
                    @elseif (Auth::user()->part == 'KBPS' || Auth::user()->part == 'Pegawai')
                    <li class="d-flex gap-4">
                        <svg class="bi text-body-secondary flex-shrink-0" width="48" height="48"><use xlink:href="#first_easy"/></svg>
                        <div>
                            <h5 class="mb-0">Mudah Digunakan</h5>
                            Melihat data jadi lebih mudah hanya dengan navigasi dalam satu halaman saja.
                        </div>
                    </li>
                    @endif
                    @if (Auth::user()->part == 'Admin')
                    <li class="d-flex gap-4">
                        <svg class="bi text-success flex-shrink-0" width="48" height="48"><use xlink:href="#first_fast"/></svg>
                        <div>
                            <h5 class="mb-0">Cepat Diproses</h5>
                            Pemasukkan data menjadi lebih cepat dengan metode Import (Pegawai dan Data Input).
                        </div>
                    </li>
                    @elseif (Auth::user()->part == 'KBPS')
                    <li class="d-flex gap-4">
                        <svg class="bi text-success flex-shrink-0" width="48" height="48"><use xlink:href="#first_progress"/></svg>
                        <div>
                            <h5 class="mb-0">Diproses Oleh Sistem</h5>
                            Pengambilan Nilai Akhir langsung dapat hanya dengan satu klik saat validasi.
                        </div>
                    </li>
                    @elseif (Auth::user()->part == 'Pegawai')
                    <li class="d-flex gap-4">
                        <svg class="bi text-success flex-shrink-0" width="48" height="48"><use xlink:href="#first_chart"/></svg>
                        <div>
                            <h5 class="mb-0"><span class="badge text-bg-primary">Baru</span> Diagram Garis</h5>
                            Kini dilengkapi dengan diagram garis untuk membandingkan nilai akhir di seluruh periode.
                        </div>
                    </li>
                    @endif
                    <li class="d-flex gap-4">
                        <svg class="bi text-primary flex-shrink-0" width="48" height="48"><use xlink:href="#first_help"/></svg>
                        <div>
                            <h5 class="mb-0">Butuh Bantuan?</h5>
                            Klik Bantuan atau Panduan untuk mengetahui cara kerja sistem ini.
                        </div>
                    </li>
                </ul>
                <button type="button" class="btn btn-lg btn-primary mt-0 w-100" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endif

<!--ABOUT-->
<div class="modal fade" id="modal-about" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Tentang Aplikasi</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-4 align-items-md-stretch">
                    <div class="col-md-6">
                        <div class="h-100 p-5 bg-primary text-white rounded-3">
                            <h2><i class="bi bi-app"></i> Aplikasi untuk Anda</h2>
                            <p>Apakah Anda ingin menentukan siapa yang terpilih menjadi karyawan terbaik sesuai dengan kinerja karyawan Anda? Kami disini hadir untuk membangun suatu aplikasi agar proses penentuan tersebut berjalan dengan cepat dan lancar.</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="h-100 p-5 bg-primary-subtle text-primary-emphasis rounded-3">
                            <h2><i class="bi bi-flag"></i> Tujuan Kami</h2>
                            <p>Aplikasi yang Anda gunakan saat ini dapat melakukan penentuan karyawan terbaik oleh sistem sesuai dengan kinerja pegawai dan memenuhi tujuh kriteria BerAkhlak. Dengan adanya penerapan metode yang digunakan oleh sistem ini (SAW) diharapkan mampu untuk membantu pengguna untuk menentukan pegawai yang terpilih sebagai karyawan terbaik.</p>
                        </div>
                    </div>
                </div>
                <div class="p-5 bg-body-tertiary rounded-3">
                    <div class="container-fluid py-2">
                        <h1 class="display-5 fw-bold"><i class="bi bi-file-person"></i> Dibentuk Oleh</h1>
                        <div class="row justify-content-center g-3">
                            <div class="col-md-3">
                                <div class="position-sticky" style="top: 0rem;">
                                    <img src="{{ asset('Images/About/Firza.JPG') }}" onerror="this.onerror=null; this.src='{{ asset('Images/Default/Portrait.png') }}'" class="img-thumbnail rounded">
                                </div>
                            </div>
                            <div class="col-md-9">
                                <h2>Muhammad Afriza Hanif</h2>
                                <h5>firzavista728@gmail.com</h5>
                                </br>
                                <p class="fs-4">
                                    Aplikasi ini dibentuk sebagai tugas akhir dalam menyelesaikan studi sarjana program studi Sistem Informasi
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!--GUIDE (BETA)-->
<div class="modal fade p-4 py-md-1" tabindex="-1" role="dialog" id="modal-guide">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div>
                <button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 m-2 z-3" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div id="imagecarousel" class="carousel slide carousel-fade">
                <div class="carousel-indicators">
                    <button type="button" data-bs-target="#imagecarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                    <button type="button" data-bs-target="#imagecarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
                    <button type="button" data-bs-target="#imagecarousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
                </div>
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <img src="{{ asset('Images/About/1. First.jpg') }}" class="d-block w-100 rounded-2" alt="...">
                        <div class="carousel-caption d-none d-md-block">
                            <h4>Tentang Aplikasi Ini</h4>
                            <p>Aplikasi ini dirancang untuk memudahkan pengguna untuk melakukan penentuan karyawan terbaik secara cepat dan tepat tanpa menggunakan cara tradisional.</p>
                        </div>
                    </div>
                    <div class="carousel-item">
                        <img src="{{ asset('Images/About/2. Middle.jpg') }}" class="d-block w-100 rounded-2" alt="...">
                        <div class="carousel-caption d-none d-md-block">
                            <h5>Tujuan Kami</h5>
                            <p>Aplikasi yang anda gunakaan saat ini dapat melakukan penentuan karyawan terbaik oleh sistem sesuai dengan kinerja pegawai dan memenuhi kriteria BerAkhlak. Dengan adanya penerapan metode yang digunakan oleh sistem ini (SAW) diharapkan mampu untuk membantu pengguna untuk menentukan pegawai yang terpilih sebagai karyawan terbaik.</p>
                        </div>
                    </div>
                    <div class="carousel-item">
                        <img src="{{ asset('Images/About/3. Last.jpg') }}" class="d-block w-100 rounded-2" alt="...">
                        <div class="carousel-caption d-none d-md-block">
                            <h5>Third slide label</h5>
                            <p>Some representative placeholder content for the third slide.</p>
                        </div>
                    </div>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#imagecarousel" data-bs-slide="prev" style="width: 5%">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#imagecarousel" data-bs-slide="next" style="width: 5%">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>
        </div>
    </div>
</div>
