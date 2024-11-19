<header>
    <!--UPPER MENU-->
    <div class="px-3 py-2 text-bg-dark border-bottom">
        <div class="container">
            <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
                <!--LOGO-->
                <a href="/" class="d-flex align-items-center col-md-4 my-2 my-lg-0 me-lg-auto text-white text-decoration-none">
                    <img src="{{ asset('Images/Logo/BPS White.png') }}" style="max-width: 70%;">
                </a>
                <!--NAVIGATIONS-->
                <ul class="nav col-12 col-lg-auto my-2 justify-content-center my-md-0 text-small">
                    <li>
                        <a href="/" class="nav-link {{ (request()->is('/')) ? 'text-secondary' : 'text-white' }}">
                            <svg class="bi d-block mx-auto mb-1" style="vertical-align: -.125em;" width="24" height="24"><use xlink:href="#home"/></svg>
                            Home
                        </a>
                    </li>
                    <li>
                        <a href="/eotm" class="nav-link {{ (request()->is('eotm')) ? 'text-secondary' : 'text-white' }}">
                            <svg class="bi d-block mx-auto mb-1" style="vertical-align: -.125em;" width="24" height="24"><use xlink:href="#eotm"/></svg>
                            Hasil
                        </a>
                    </li>
                    <li>
                        <button class="nav-link text-white" data-bs-toggle="modal" data-bs-target="#modal-about">
                            <svg class="bi d-block mx-auto mb-1" style="vertical-align: -.125em;" width="24" height="24"><use xlink:href="#about"/></svg>
                            About
                        </button>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <!--DOWNER MENU-->
    <div class="px-3 py-2 border-bottom mb-3">
        <div class="container d-flex flex-wrap justify-content-center">
            <div class="col-12 col-lg-auto mb-2 mb-lg-0 me-lg-auto">
                @if (Auth::check())
                <button type="button" class="btn">{{Auth::user()->name}}</button>
                @endif
            </div>
            <!--LOGIN / REGISTER-->
            <div class="text-end">
                @if (Auth::check())
                    @if (Auth::user()->part == "Pegawai")
                    <a href="/officer" type="button" class="btn btn-light text-dark me-2">
                        <i class="bi bi-speedometer2"></i>
                        Dashboard
                    </a>
                    @elseif (Auth::user()->part == "Dev")
                    <a href="/developer" type="button" class="btn btn-light text-dark me-2">
                        <i class="bi bi-speedometer2"></i>
                        Dashboard
                    </a>
                    @else
                    <a href="/admin" type="button" class="btn btn-light text-dark me-2">
                        <i class="bi bi-speedometer2"></i>
                        Dashboard
                    </a>
                    @endif
                <button type="button" class="btn me-2 btn-outline-danger" data-bs-toggle="modal" data-bs-target="#modallogout">
                    <i class="bi bi-door-closed"></i>
                    Logout
                </button>
                @else
                <a href="/login" type="button" class="btn btn-light text-dark me-2">
                    <i class="bi bi-door-open"></i>
                    Login
                </a>
                <!--<button type="button" class="btn btn-light text-dark me-2" data-bs-toggle="modal" data-bs-target="#modal-idx-login">Login</button>-->
                @endif
            </div>
        </div>
    </div>
</header>
