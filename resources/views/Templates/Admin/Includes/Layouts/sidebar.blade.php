<div id="sidebar" class="d-flex flex-column flex-shrink-0 p-3 text-bg-dark" style="width: 240px;">
    <!--LOGO-->
    <img class="align-items-center" src="{{ asset('Images/Logo/BPS White.png') }}">
    <hr/>
    <!--PAGE MENU-->
    <ul class="nav nav-pills flex-column mb-auto">
        <div id="selector-sidebar">
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
            <li class="nav-item">
                <button class="nav-link text-white collapsed dropdown-toggle" data-bs-toggle="collapse" data-bs-target="#masters-collapse" aria-expanded=" {{ (request()->is('admin/masters*')) ? 'true' : 'false' }}">
                    <svg class="bi pe-none me-2" style="vertical-align: -.125em;" width="16" height="16"><use xlink:href="#master"/></svg>
                    Master
                </button>
                <div class="{{ (request()->is('admin/masters*')) ? 'collapse show' : 'collapse' }} multi-collapse" id="masters-collapse" data-bs-parent="#selector-sidebar">
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
                        <li>
                            <a href="/admin/masters/criterias" class="{{ (request()->is('admin/masters/criterias')) ? 'nav-link active' : 'nav-link text-white' }}" aria-current="page">
                                <svg class="bi pe-none me-2" style="vertical-align: -.125em;" width="16" height="16"><use xlink:href="#crit-score"/></svg>
                                Kriteria
                            </a>
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
            @if (Auth::user()->part == "Admin")
            <li class="nav-item">
                <a href="/admin/inputs/data" class="{{ (request()->is('admin/inputs/data*')) ? 'nav-link active' : 'nav-link text-white' }}" aria-current="page">
                    <svg class="bi pe-none me-2" style="vertical-align: -.125em;" width="16" height="16"><use xlink:href="#presence"/></svg>
                    Data Input
                </a>
            </li>
            @elseif (Auth::user()->part == "KBPS")
            <li class="nav-item">
                <a href="/admin/inputs/validate" class="{{ (request()->is('admin/inputs/validate*')) ? 'nav-link active' : 'nav-link text-white' }}" aria-current="page">
                    <svg class="bi pe-none me-2" style="vertical-align: -.125em;" width="16" height="16"><use xlink:href="#score"/></svg>
                    Verifikasi Input
                </a>
            </li>
            @endif
            <li class="nav-item">
                <a href="/admin/analysis" class="{{ (request()->is('admin/analysis*')) ? 'nav-link active' : 'nav-link text-white' }}" aria-current="page">
                    <svg class="bi pe-none me-2" style="vertical-align: -.125em;" width="16" height="16"><use xlink:href="#analysis"/></svg>
                    Analisis Data
                </a>
            </li>
            <li class="nav-item">
                <a href="/admin/reports" class="{{ (request()->is('admin/reports')) ? 'nav-link active' : 'nav-link text-white' }}" aria-current="page">
                    <svg class="bi pe-none me-2" style="vertical-align: -.125em;" width="16" height="16"><use xlink:href="#report"/></svg>
                    Laporan
                </a>
            </li>
            @endif
        </div>
    </ul>
    <hr/>
    <!--USER MENU-->
    <div class="dropup">
        <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
            <snap class="d-inline-block" tabindex="0" data-bs-toggle="tooltip" data-bs-title="{{ Auth::user()->username }}">
                @if (Auth::user()->part == 'Admin')
                <img src="{{ url('Images/User/'.Auth::user()->part.'.png') }}" onerror="this.onerror=null; this.src='{{ asset('Images/Default/Portrait.png') }}'" alt="" width="32" height="32" style="object-fit:cover;" class="rounded me-2">
                @elseif (Auth::user()->part == 'KBPS')
                <img src="{{ url('Images/User/'.Auth::user()->part.'.png') }}" onerror="this.onerror=null; this.src='{{ asset('Images/Default/Portrait.png') }}'" alt="" width="32" height="32" style="object-fit:cover;" class="rounded me-2">
                @endif
                </snap>
            <strong>{{ Auth::user()->part }}</strong>
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
                <a class="dropdown-item d-flex gap-2 align-items-center" href="/admin/messages">
                    <svg class="bi" width="16" height="16" style="vertical-align: -.125em;"><use xlink:href="#message"/></svg>
                    Feedback
                </a>
            </li>
            <li>
                <button class="dropdown-item d-flex gap-2 align-items-center" data-bs-toggle="modal" data-bs-target="#modallogout">
                    <svg class="bi" width="16" height="16" style="vertical-align: -.125em;"><use xlink:href="#logout"/></svg>
                    Keluar
                </button>
            </li>
        </ul>
    </div>
</div>
