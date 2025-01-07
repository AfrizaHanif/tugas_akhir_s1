<div class="offcanvas offcanvas-start" style="width: 240px;" tabindex="-1" id="offcanvas-menu" aria-labelledby="offcanvasExampleLabel">
    <!--LOGO AND CLOSE-->
    <div class="offcanvas-header text-bg-dark">
        <a href="/admin" class="d-flex align-items-center mb-md-0 me-md-auto text-white text-decoration-none">
            <svg class="bi pe-none me-2" width="40" height="32"><use xlink:href="#bootstrap"/></svg>
            <span class="fs-4">Dashboard</span>
        </a>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <!--PAGE MENU (CHANGE IF PAGE MENU FROM SIDEBAR HAS CHANGED)-->
    <div class="offcanvas-body text-bg-dark">
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
                    <a href="/admin/masters/employees" class="{{ (request()->is('admin/masters/employees*')) ? 'nav-link active' : 'nav-link text-white' }}" aria-current="page">
                        <svg class="bi pe-none me-2" style="vertical-align: -.125em;" width="16" height="16"><use xlink:href="#employee"/></svg>
                        Karyawan
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
                                <a href="/admin/masters/employees" class="{{ (request()->is('admin/masters/employees*')) ? 'nav-link active' : 'nav-link text-white' }}" aria-current="page">
                                    <svg class="bi pe-none me-2" style="vertical-align: -.125em;" width="16" height="16"><use xlink:href="#employee"/></svg>
                                    Karyawan
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
    </div>
</div>
