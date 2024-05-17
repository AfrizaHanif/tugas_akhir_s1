<div id="sidebar" class="d-flex flex-column flex-shrink-0 p-3 text-bg-dark" style="width: 240px;">
    <a href="/admin" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
        <svg class="bi pe-none me-2" width="40" height="32"><use xlink:href="#bootstrap"/></svg>
        <span class="fs-4">Dashboard</span>
    </a>
    <hr/>
    <ul class="nav nav-pills flex-column mb-auto">
        @if (Auth::check())
            <li class="nav-item">
                <a href="/admin" class="{{ (request()->is('admin')) ? 'nav-link active' : 'nav-link text-white' }}" aria-current="page">
                    <svg class="bi pe-none me-2" style="vertical-align: -.125em;" width="16" height="16"><use xlink:href="#dashboard"/></svg>
                    Dashboard
                </a>
            </li>
            @if (Auth::user()->part == "KBU" || Auth::user()->part == "KTT" || Auth::user()->part == "KBPS")
            <li class="nav-item">
                <a href="/admin/masters/officers" class="{{ (request()->is('admin/masters/officers*')) ? 'nav-link active' : 'nav-link text-white' }}" aria-current="page">
                    <svg class="bi pe-none me-2" style="vertical-align: -.125em;" width="16" height="16"><use xlink:href="#officer"/></svg>
                    Pegawai
                </a>
            </li>
            @endif
            @if (Auth::user()->part == "Admin")
            <div id="selector">
                <li class="nav-item">
                    <button class="nav-link text-white collapsed dropdown-toggle" data-bs-toggle="collapse" data-bs-target="#masters-collapse" aria-expanded=" {{ (request()->is('admin/masters*')) ? 'true' : 'false' }}">
                        <svg class="bi pe-none me-2" style="vertical-align: -.125em;" width="16" height="16"><use xlink:href="#master"/></svg>
                        Master
                    </button>
                    <div class="{{ (request()->is('admin/masters*')) ? 'collapse show' : 'collapse' }} multi-collapse" id="masters-collapse" data-bs-parent="#selector">
                        <ul class="list-unstyled fw-normal pb-1 small">
                            <li>
                                <a href="/admin/masters/officers" class="{{ (request()->is('admin/masters/officers*')) ? 'nav-link active' : 'nav-link text-white' }}" aria-current="page">
                                    <svg class="bi pe-none me-2" style="vertical-align: -.125em;" width="16" height="16"><use xlink:href="#officer"/></svg>
                                    Pegawai
                                </a>
                            </li>
                            <li>
                                <a href="/admin/masters/users" class="{{ (request()->is('admin/masters/users')) ? 'nav-link active' : 'nav-link text-white' }}" aria-current="page">
                                    <svg class="bi pe-none me-2" style="vertical-align: -.125em;" width="16" height="16"><use xlink:href="#user"/></svg>
                                    Pengguna
                                </a>
                            </li>
                            <li class="nav-item">
                                <div class="dropend">
                                    <a class="{{ (request()->is('admin/masters/criterias') || request()->is('admin/masters/vote-criterias')) ? 'nav-link active' : 'nav-link text-white' }} dropdown-toggle" aria-current="page" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <svg class="bi pe-none me-2" style="vertical-align: -.125em;" width="16" height="16"><use xlink:href="#criteria"/></svg>
                                        Kriteria
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-dark shadow mx-0 w-sidebar-menu">
                                        <li>
                                            <a class="dropdown-item d-flex gap-2 align-items-center" href="/admin/masters/criterias/">
                                                <svg class="bi pe-none me-2" style="vertical-align: -.125em;" width="16" height="16"><use xlink:href="#crit-score"/></svg>
                                                Untuk Penilaian
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item d-flex gap-2 align-items-center" href="/admin/masters/vote-criterias/">
                                                <svg class="bi pe-none me-2" style="vertical-align: -.125em;" width="16" height="16"><use xlink:href="#crit-vote"/></svg>
                                                Untuk Pemilihan
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                            <li>
                                <a href="/admin/masters/periods" class="{{ (request()->is('admin/masters/periods')) ? 'nav-link active' : 'nav-link text-white' }}" aria-current="page">
                                    <svg class="bi pe-none me-2" style="vertical-align: -.125em;" width="16" height="16"><use xlink:href="#period"/></svg>
                                    Periode
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                @endif
                <li class="nav-item">
                    <button class="nav-link text-white collapsed dropdown-toggle" data-bs-toggle="collapse" data-bs-target="#inputs-collapse" aria-expanded="{{ (request()->is('admin/inputs*')) ? 'true' : 'false' }}">
                        <svg class="bi pe-none me-2" style="vertical-align: -.125em;" width="16" height="16"><use xlink:href="#input"/></svg>
                        Input
                    </button>
                    <div class="{{ (request()->is('admin/inputs*')) ? 'collapse show' : 'collapse' }} multi-collapse" id="inputs-collapse" data-bs-parent="#selector">
                        <ul class="list-unstyled fw-normal pb-1 small">
                            @if (Auth::user()->part == "Admin")
                            <li>
                                <a href="/admin/inputs/presences" class="{{ (request()->is('admin/inputs/presences')) ? 'nav-link active' : 'nav-link text-white' }}" aria-current="page">
                                    <svg class="bi pe-none me-2" style="vertical-align: -.125em;" width="16" height="16"><use xlink:href="#presence"/></svg>
                                    Data Kehadiran
                                </a>
                            </li>
                            @elseif (Auth::user()->part == "KBU")
                            <li>
                                <a href="/admin/inputs/kbu/performances" class="{{ (request()->is('admin/inputs/kbu/performances')) ? 'nav-link active' : 'nav-link text-white' }}" aria-current="page">
                                    <svg class="bi pe-none me-2" style="vertical-align: -.125em;" width="16" height="16"><use xlink:href="#performance"/></svg>
                                    Data Prestasi Kerja
                                </a>
                            </li>
                            @elseif (Auth::user()->part == "KTT")
                            <li>
                                <a href="/admin/inputs/ktt/performances" class="{{ (request()->is('admin/inputs/ktt/performances')) ? 'nav-link active' : 'nav-link text-white' }}" aria-current="page">
                                    <svg class="bi pe-none me-2" style="vertical-align: -.125em;" width="16" height="16"><use xlink:href="#performance"/></svg>
                                    Data Prestasi Kerja
                                </a>
                            </li>
                            @elseif (Auth::user()->part == "KBPS")
                            <li>
                                <a href="/admin/inputs/kbps/performances" class="{{ (request()->is('admin/inputs/kbps/performances')) ? 'nav-link active' : 'nav-link text-white' }}" aria-current="page">
                                    <svg class="bi pe-none me-2" style="vertical-align: -.125em;" width="16" height="16"><use xlink:href="#performance"/></svg>
                                    Data Prestasi Kerja
                                </a>
                            </li>
                            <li>
                                <a href="/admin/inputs/scores" class="{{ (request()->is('admin/inputs/scores')) ? 'nav-link active' : 'nav-link text-white' }}" aria-current="page">
                                    <svg class="bi pe-none me-2" style="vertical-align: -.125em;" width="16" height="16"><use xlink:href="#score"/></svg>
                                    Validasi
                                </a>
                            </li>
                            @endif
                            <li>
                                <a href="/admin/inputs/votes" class="{{ (request()->is('admin/inputs/votes*')) ? 'nav-link active' : 'nav-link text-white' }}" aria-current="page">
                                    <svg class="bi pe-none me-2" style="vertical-align: -.125em;" width="16" height="16"><use xlink:href="#vote"/></svg>
                                    Voting
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item">
                    <button class="nav-link text-white collapsed dropdown-toggle" data-bs-toggle="collapse" data-bs-target="#analysis-collapse" aria-expanded="{{ (request()->is('admin/analysis*')) ? 'true' : 'false' }}">
                        <svg class="bi pe-none me-2" style="vertical-align: -.125em;" width="16" height="16"><use xlink:href="#analysis"/></svg>
                        Analysis
                    </button>
                    <div class="{{ (request()->is('admin/analysis*')) ? 'collapse show' : 'collapse' }} multi-collapse" id="analysis-collapse" data-bs-parent="#selector">
                        <ul class="list-unstyled fw-normal pb-1 small">
                            <li>
                                <a href="/admin/analysis/saw" class="{{ (request()->is('admin/analysis/saw*')) ? 'nav-link active' : 'nav-link text-white' }}" aria-current="page">
                                    <svg class="bi pe-none me-2" style="vertical-align: -.125em;" width="16" height="16"><use xlink:href="#saw"/></svg>
                                    SAW
                                </a>
                            </li>
                            <li>
                                <a href="/admin/analysis/wp" class="{{ (request()->is('admin/analysis/wp*')) ? 'nav-link active' : 'nav-link text-white' }}" aria-current="page">
                                    <svg class="bi pe-none me-2" style="vertical-align: -.125em;" width="16" height="16"><use xlink:href="#wp"/></svg>
                                    WP
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
            </div>
            <li class="nav-item">
                <a href="/admin/reports" class="{{ (request()->is('admin/reports')) ? 'nav-link active' : 'nav-link text-white' }}" aria-current="page">
                    <svg class="bi pe-none me-2" style="vertical-align: -.125em;" width="16" height="16"><use xlink:href="#report"/></svg>
                    Laporan
                </a>
            </li>
        @endif
    </ul>
    <hr/>
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
