<div class="d-flex flex-column flex-shrink-0 p-3 text-bg-dark" style="width: 240px;">
    <a href="/" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
        <svg class="bi pe-none me-2" width="40" height="32"><use xlink:href="#bootstrap"/></svg>
        <span class="fs-4">Dashboard</span>
    </a>
    <hr>
    <ul class="nav nav-pills flex-column mb-auto">
        @if (Auth::check())
        <li class="nav-item">
            <a href="/dashboard" class="{{ (request()->is('dashboard')) ? 'nav-link active' : 'nav-link text-white' }}" aria-current="page">
                <svg class="bi pe-none me-2" style="vertical-align: -.125em;" width="16" height="16"><use xlink:href="#dashboard"/></svg>
                @if (Auth::user()->part == "Admin")
                Home (Admin)
                @elseif (Auth::user()->part == "KBU")
                Home (KBU)
                @elseif (Auth::user()->part == "KTT")
                Home (KTT)
                @elseif (Auth::user()->part == "KBPS")
                Home (KBPS)
                @endif
            </a>
        </li>
        @if (Auth::user()->part == "KBU" || Auth::user()->part == "KTT" || Auth::user()->part == "KBPS")
        <li class="nav-item">
            <a href="/masters/officers" class="{{ (request()->is('masters/officers')) ? 'nav-link active' : 'nav-link text-white' }}" aria-current="page">
                <svg class="bi pe-none me-2" style="vertical-align: -.125em;" width="16" height="16"><use xlink:href="#officer"/></svg>
                Pegawai
            </a>
        </li>
        @endif
        @if (Auth::user()->part == "Admin")
        <li class="nav-item">
            <button class="nav-link text-white collapsed dropdown-toggle" data-bs-toggle="collapse" data-bs-target="#masters-collapse" aria-expanded="
            {{ (request()->is('masters*')) ? 'true' : 'false' }}
            ">
                <svg class="bi pe-none me-2" style="vertical-align: -.125em;" width="16" height="16"><use xlink:href="#master"/></svg>
                Master
            </button>
            <div class="{{ (request()->is('masters*')) ? 'collapse show' : 'collapse' }} multi-collapse" id="masters-collapse">
                <ul class="list-unstyled fw-normal pb-1 small">
                    <li>
                        <a href="/masters/officers" class="{{ (request()->is('masters/officers')) ? 'nav-link active' : 'nav-link text-white' }}" aria-current="page">
                            <svg class="bi pe-none me-2" style="vertical-align: -.125em;" width="16" height="16"><use xlink:href="#officer"/></svg>
                            Pegawai
                        </a>
                    </li>
                    <li>
                        <a href="/masters/users" class="{{ (request()->is('masters/users')) ? 'nav-link active' : 'nav-link text-white' }}" aria-current="page">
                            <svg class="bi pe-none me-2" style="vertical-align: -.125em;" width="16" height="16"><use xlink:href="#user"/></svg>
                            Pengguna
                        </a>
                    </li>
                    <li>
                        <a href="/masters/criterias" class="{{ (request()->is('masters/criterias')) ? 'nav-link active' : 'nav-link text-white' }}" aria-current="page">
                            <svg class="bi pe-none me-2" style="vertical-align: -.125em;" width="16" height="16"><use xlink:href="#criteria"/></svg>
                            Kriteria (Beta)
                        </a>
                    </li>
                    <li>
                        <a href="/masters/periods" class="{{ (request()->is('masters/periods')) ? 'nav-link active' : 'nav-link text-white' }}" aria-current="page">
                            <svg class="bi pe-none me-2" style="vertical-align: -.125em;" width="16" height="16"><use xlink:href="#period"/></svg>
                            Periode
                        </a>
                    </li>
                </ul>
            </div>
        </li>
        @endif
        <li class="nav-item">
            <button class="nav-link text-white collapsed dropdown-toggle" data-bs-toggle="collapse" data-bs-target="#inputs-collapse" aria-expanded="
            {{ (request()->is('inputs*')) ? 'true' : 'false' }}
            ">
                <svg class="bi pe-none me-2" style="vertical-align: -.125em;" width="16" height="16"><use xlink:href="#input"/></svg>
                Input
            </button>
            <div class="{{ (request()->is('inputs*')) ? 'collapse show' : 'collapse' }} multi-collapse" id="inputs-collapse">
                <ul class="list-unstyled fw-normal pb-1 small">
                    @if (Auth::user()->part == "Admin")
                    <li>
                        <a href="/inputs/presences" class="{{ (request()->is('inputs/presences')) ? 'nav-link active' : 'nav-link text-white' }}" aria-current="page">
                            <svg class="bi pe-none me-2" style="vertical-align: -.125em;" width="16" height="16"><use xlink:href="#presence"/></svg>
                            Data Kehadiran
                        </a>
                    </li>
                    @elseif (Auth::user()->part == "KBU" || Auth::user()->part == "KTT")
                    <li>
                        <a href="/inputs/performances" class="{{ (request()->is('inputs/performances')) ? 'nav-link active' : 'nav-link text-white' }}" aria-current="page">
                            <svg class="bi pe-none me-2" style="vertical-align: -.125em;" width="16" height="16"><use xlink:href="#performance"/></svg>
                            Data Prestasi Kerja
                        </a>
                    </li>
                    @elseif (Auth::user()->part == "KBPS")
                    <li>
                        <a href="/inputs/results" class="{{ (request()->is('inputs/results')) ? 'nav-link active' : 'nav-link text-white' }}" aria-current="page">
                            <svg class="bi pe-none me-2" style="vertical-align: -.125em;" width="16" height="16"><use xlink:href="#dashboard"/></svg>
                            Hasil
                        </a>
                    </li>
                    @endif
                </ul>
            </div>
        </li>
        <li class="nav-item">
            <button class="nav-link text-white collapsed dropdown-toggle" data-bs-toggle="collapse" data-bs-target="#analysis-collapse" aria-expanded="
            {{ (request()->is('analysis*')) ? 'true' : 'false' }}
            ">
                <svg class="bi pe-none me-2" style="vertical-align: -.125em;" width="16" height="16"><use xlink:href="#input"/></svg>
                Analysis
            </button>
            <div class="{{ (request()->is('analysis*')) ? 'collapse show' : 'collapse' }} multi-collapse" id="analysis-collapse">
                <ul class="list-unstyled fw-normal pb-1 small">
                    <li>
                        <a href="/analysis/saw" class="{{ (request()->is('analysis/saw*')) ? 'nav-link active' : 'nav-link text-white' }}" aria-current="page">
                            <svg class="bi pe-none me-2" style="vertical-align: -.125em;" width="16" height="16"><use xlink:href="#user"/></svg>
                            SAW
                        </a>
                    </li>
                    <li>
                        <a href="/analysis/wp" class="{{ (request()->is('analysis/wp*')) ? 'nav-link active' : 'nav-link text-white' }}" aria-current="page">
                            <svg class="bi pe-none me-2" style="vertical-align: -.125em;" width="16" height="16"><use xlink:href="#user"/></svg>
                            WP
                        </a>
                    </li>
                </ul>
            </div>
        </li>
        <li class="nav-item">
            <a href="/reports" class="{{ (request()->is('reports')) ? 'nav-link active' : 'nav-link text-white' }}" aria-current="page">
                <svg class="bi pe-none me-2" style="vertical-align: -.125em;" width="16" height="16"><use xlink:href="#report"/></svg>
                Laporan
            </a>
        </li>
        @endif
    </ul>
    <hr>
    <div class="dropup">
        <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
            <img src="https://github.com/mdo.png" alt="" width="32" height="32" class="rounded-circle me-2">
            <strong>{{ Str::limit(Auth::user()->officer->name, 19); }}</strong>
        </a>
        <ul class="dropdown-menu dropdown-menu-dark text-small shadow mx-0 w-account-menu">
            <li>
                <a class="dropdown-item d-flex gap-2 align-items-center" href="/">
                    <svg class="bi" width="16" height="16" style="vertical-align: -.125em;"><use xlink:href="#home"/></svg>
                    Halaman Utama
                </a>
            </li>
            <li><hr class="dropdown-divider"></li>
            <li>
                <button class="dropdown-item d-flex gap-2 align-items-center" data-bs-toggle="modal" data-bs-target="#modallogout">
                    <svg class="bi" width="16" height="16" style="vertical-align: -.125em;"><use xlink:href="#logout"/></svg>
                    Keluar
                </button>
            </li>
        </ul>
    </div>
</div>
