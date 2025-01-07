<div id="sidebar" class="d-flex flex-column flex-shrink-0 p-3 bg-body-tertiary" style="width: 240px;">
    <!--LOGO-->
    <img class="align-items-center" src="{{ asset('Images/Logo/BPS Black.png') }}">
    <hr/>
    <ul class="nav nav-pills flex-column mb-auto">
        <div id="selector-sidebar">
            <li class="nav-item">
                <a href="/employee" class="{{ (request()->is('employee')) ? 'nav-link active' : 'nav-link link-body-emphasis' }}" aria-current="page">
                    <svg class="bi pe-none me-2" style="vertical-align: -.125em;" width="16" height="16"><use xlink:href="#dashboard"/></svg>
                    Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a href="/employee/employees" class="{{ (request()->is('employee/employees*')) ? 'nav-link active' : 'nav-link link-body-emphasis' }}" aria-current="page">
                    <svg class="bi pe-none me-2" style="vertical-align: -.125em;" width="16" height="16"><use xlink:href="#employee"/></svg>
                    Karyawan
                </a>
            </li>
            <li class="nav-item">
                <a href="/employee/eotm" class="{{ (request()->is('employee/eotm*')) ? 'nav-link active' : 'nav-link link-body-emphasis' }}" aria-current="page">
                    <svg class="bi pe-none me-2" style="vertical-align: -.125em;" width="16" height="16"><use xlink:href="#result"/></svg>
                    Karyawan Terbaik
                </a>
            </li>
            <li class="nav-item">
                <a href="/employee/reports" class="{{ (request()->is('employee/reports')) ? 'nav-link active' : 'nav-link link-body-emphasis' }}" aria-current="page">
                    <svg class="bi pe-none me-2" style="vertical-align: -.125em;" width="16" height="16"><use xlink:href="#report"/></svg>
                    Laporan
                </a>
            </li>
        </div>
    </ul>
    <hr>
    <div class="dropup">
        <a href="#" class="d-flex align-items-center link-body-emphasis text-decoration-none dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
            <snap class="d-inline-block" tabindex="0" data-bs-toggle="tooltip" data-bs-title="{{ Auth::user()->employee->name }}">
                <img src="{{ url('Images/User/'.Auth::user()->part.'.png') }}" onerror="this.onerror=null; this.src='{{ asset('Images/Default/Portrait.png') }}'" alt="" width="32" height="32" style="object-fit:cover;" class="rounded me-2">
                </snap>
            <strong>{{ Auth::user()->part }}</strong>
        </a>
        <ul class="dropdown-menu text-small shadow mx-0 w-account-menu">
            <li>
                <a class="dropdown-item d-flex gap-2 align-items-center" href="/">
                    <svg class="bi" width="16" height="16" style="vertical-align: -.125em;"><use xlink:href="#home"/></svg>
                    Halaman Utama
                </a>
            </li>
            <li><hr class="dropdown-divider"></li>
            <li>
                <a class="dropdown-item d-flex gap-2 align-items-center" href="/employee/settings">
                    <svg class="bi" width="16" height="16" style="vertical-align: -.125em;"><use xlink:href="#settings"/></svg>
                    Pengaturan
                </a>
            </li>
            <li>
                <a class="dropdown-item d-flex gap-2 align-items-center" href="/employee/logs">
                    <svg class="bi" width="16" height="16" style="vertical-align: -.125em;"><use xlink:href="#logs"/></svg>
                    Logs
                </a>
            </li>
            <li>
                <a class="dropdown-item d-flex gap-2 align-items-center" href="/employee/messages">
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
