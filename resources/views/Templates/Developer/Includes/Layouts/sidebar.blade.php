<div class="d-flex flex-column flex-shrink-0 bg-body-tertiary" style="width: 5.5rem;">
    <!--HOME-->
    <a href="/" class="d-block p-3 link-body-emphasis text-decoration-none" title="Home" data-bs-toggle="tooltip" data-bs-placement="right">
        <svg class="bi pe-none" width="40" height="32" role="img" aria-label="Bootstrap"><use xlink:href="#bootstrap"/></svg>
        <span class="visually-hidden">Home</span>
    </a>
    <!--PAGE MENU-->
    <ul class="nav nav-pills nav-flush flex-column mb-auto text-center">
        <li class="nav-item">
            <a href="/developer/" class="{{ (request()->is('developer')) ? 'nav-link active' : 'nav-link' }} py-3 border-bottom rounded-0" aria-current="page" title="Dashboard" data-bs-toggle="tooltip" data-bs-placement="right">
                <svg class="bi pe-none" width="24" height="24" role="img" aria-label="Dashboard"><use xlink:href="#dashboard"/></svg>
            </a>
        </li>
        <li>
            <div class="dropend">
                <button id="masters" class="{{ (request()->is('developer/masters*')) ? 'nav-link active' : 'nav-link' }} py-3 border-bottom rounded-0 dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <svg class="bi pe-none" width="24" height="24" role="img" aria-label="Kelola Masters"><use xlink:href="#master"/></svg>
                </button>
                <ul class="dropdown-menu">
                    <li>
                        <a class="dropdown-item disabled">Kelola Masters</a>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <a class="dropdown-item" href="/developer/masters/employees">
                            <i class="bi bi-people-fill"></i>
                            Karyawan
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="/developer/masters/users">
                            <i class="bi bi-people-fill"></i>
                            Pengguna
                        </a>
                    </li>
                </ul>
            </div>
        </li>
        <li class="nav-item">
            <a href="/developer/logs/" class="{{ (request()->is('developer/logs*')) ? 'nav-link active' : 'nav-link' }} py-3 border-bottom rounded-0" aria-current="page" title="Logs" data-bs-toggle="tooltip" data-bs-placement="right">
                <svg class="bi pe-none" width="24" height="24" role="img" aria-label="Logs"><use xlink:href="#logs"/></svg>
            </a>
        </li>
        <li class="nav-item">
            <a href="/developer/messages/" class="{{ (request()->is('developer/messages*')) ? 'nav-link active' : 'nav-link' }} py-3 border-bottom rounded-0" aria-current="page" title="Pesan" data-bs-toggle="tooltip" data-bs-placement="right">
                <svg class="bi pe-none" width="24" height="24" role="img" aria-label="Pesan"><use xlink:href="#message"/></svg>
            </a>
        </li>
        <li class="nav-item">
            <a href="/developer/settings/" class="{{ (request()->is('developer/settings*')) ? 'nav-link active' : 'nav-link' }} py-3 border-bottom rounded-0" aria-current="page" title="Pengaturan" data-bs-toggle="tooltip" data-bs-placement="right">
                <svg class="bi pe-none" width="24" height="24" role="img" aria-label="Pengaturan"><use xlink:href="#settings"/></svg>
            </a>
        </li>
    </ul>
    <!--USER MENU-->
    <div class="dropup border-top">
        <a href="#" class="d-flex align-items-center justify-content-center p-3 link-body-emphasis text-decoration-none dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
            <img src="https://github.com/mdo.png" alt="mdo" width="24" height="24" class="rounded-circle">
        </a>
        <ul class="dropdown-menu text-small shadow w-account-menu">
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
