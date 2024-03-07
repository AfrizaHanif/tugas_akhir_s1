<header>
    <!--UPPER MENU-->
    <div class="px-3 py-2 text-bg-dark border-bottom">
        <div class="container">
            <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
                <!--LOGO-->
                <a href="/" class="d-flex align-items-center my-2 my-lg-0 me-lg-auto text-white text-decoration-none">
                    <svg class="bi me-2" style="vertical-align: -.125em;" width="40" height="32" role="img" aria-label="Bootstrap"><use xlink:href="#bootstrap"/></svg>
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
                        <a href="/officers" class="nav-link {{ (request()->is('officers*')) ? 'text-secondary' : 'text-white' }}">
                            <svg class="bi d-block mx-auto mb-1" style="vertical-align: -.125em;" width="24" height="24"><use xlink:href="#officer"/></svg>
                            Pegawai
                        </a>
                    </li>
                    <li>
                        <a href="/scores" class="nav-link {{ (request()->is('scores')) ? 'text-secondary' : 'text-white' }}">
                            <svg class="bi d-block mx-auto mb-1" style="vertical-align: -.125em;" width="24" height="24"><use xlink:href="#score"/></svg>
                            Nilai Akhir
                        </a>
                    </li>
                    @if (Auth::check())
                        @if (Auth::user()->part == "Pegawai")
                        <li>
                            <a href="/votes" class="nav-link {{ (request()->is('votes')) ? 'text-secondary' : 'text-white' }}">
                                <svg class="bi d-block mx-auto mb-1" style="vertical-align: -.125em;" width="24" height="24"><use xlink:href="#vote"/></svg>
                                Voting
                            </a>
                        </li>
                        @else
                        <li>
                            <span data-bs-toggle="tooltip" data-bs-title="Mohon melakukan voting melalui Dashboard.">
                                <a href="/votes" class="nav-link text-secondary disabled">
                                    <svg class="bi d-block mx-auto mb-1" style="vertical-align: -.125em;" width="24" height="24"><use xlink:href="#vote"/></svg>
                                    Voting
                                </a>
                            </span>
                        </li>
                        @endif
                    @else
                    <span data-bs-toggle="tooltip" data-bs-title="Mohon login terlebih dahulu sebagai pegawai BPS Jawa Timur.">
                        <a href="/votes" class="nav-link text-secondary disabled">
                            <svg class="bi d-block mx-auto mb-1" style="vertical-align: -.125em;" width="24" height="24"><use xlink:href="#vote"/></svg>
                            Voting
                        </a>
                    </span>
                    @endif
                    <li>
                        <a href="/results" class="nav-link {{ (request()->is('results')) ? 'text-secondary' : 'text-white' }}">
                            <svg class="bi d-block mx-auto mb-1" style="vertical-align: -.125em;" width="24" height="24"><use xlink:href="#daftar"/></svg>
                            Hasil
                        </a>
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
                <button type="button" class="btn">{{Auth::user()->officer->name}}</button>
                @endif
            </div>
            <!--LOGIN / REGISTER-->
            <div class="text-end">
                @if (Auth::check())
                <a href="/dashboard" type="button" class="btn btn-light text-dark me-2">Dashboard</a>
                <button type="button" class="btn me-2 btn-outline-danger" data-bs-toggle="modal" data-bs-target="#modallogout">Logout</button>
                @else
                <a href="/login" type="button" class="btn btn-light text-dark me-2">Login</a>
                @endif
            </div>
        </div>
    </div>
</header>
