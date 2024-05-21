<nav id="navbar" class="navbar navbar-expand-lg bg-body-tertiary">
    <div class="container-fluid">
        <!--OFFCANVAS MENU-->
        <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" href="#offcanvas-menu" role="button" aria-controls="offcanvas-menu">
            <span class="navbar-toggler-icon"></span>
        </button>
        <a class="navbar-brand" href="#">Dashboard</a>
        <!--USER MENU-->
        <div class="dropstart">
            <button class="navbar-toggler" type="button" style="border: none" data-bs-toggle="dropdown" aria-expanded="false">
                <span class="navbar-toggler-icon" style="background-image: url('https://github.com/mdo.png'); border-radius: 50%;"></span>
            </button>
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
</nav>
