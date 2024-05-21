<div class="d-flex flex-column flex-shrink-0 bg-body-tertiary" style="width: 4.5rem;">
    <!--HOME-->
    <a href="/" class="d-block p-3 link-body-emphasis text-decoration-none" title="Home" data-bs-toggle="tooltip" data-bs-placement="right">
        <svg class="bi pe-none" width="24" height="24" role="img" aria-label="Bootstrap"><use xlink:href="#bootstrap"/></svg>
        <span class="visually-hidden">Home</span>
    </a>
    <!--PAGE MENU-->
    <ul class="nav nav-pills nav-flush flex-column mb-auto text-center">
        <li class="nav-item">
            <a href="/officer" class="{{ (request()->is('officer')) ? 'nav-link active' : 'nav-link' }} py-3 border-bottom rounded-0" aria-current="page" title="Dashboard" data-bs-toggle="tooltip" data-bs-placement="right">
                <svg class="bi pe-none" width="24" height="24" role="img" aria-label="Dashboard"><use xlink:href="#dashboard"/></svg>
            </a>
        </li>
        <li>
            <a href="/officer/votes" class="{{ (request()->is('officer/votes*')) ? 'nav-link active' : 'nav-link' }} py-3 border-bottom rounded-0" title="Voting" data-bs-toggle="tooltip" data-bs-placement="right">
                <svg class="bi pe-none" width="24" height="24" role="img" aria-label="Voting"><use xlink:href="#vote"/></svg>
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
