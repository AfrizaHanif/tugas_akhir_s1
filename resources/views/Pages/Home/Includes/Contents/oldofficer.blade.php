<h1 class="text-center mb-4">Data Pegawai</h1>
<!--SEARCH FORM-->
<p>
    <form action="{{ route('officers.search') }}" method="GET">
        <div class="input-group mb-3">
            <span class="input-group-text" id="officer-search"><i class="bi bi-search"></i></span>
            @if (Request::is('officers/search*'))
            <input type="search" id="search" name="search" class="typeahead form-control" placeholder="Ketik untuk mencari pegawai, lalu tekan enter atau klik cari" aria-label="Search" aria-describedby="officer-search" value="{{ $search }}">
            @else
            <input type="search" id="search" name="search" class="typeahead form-control" placeholder="Ketik untuk mencari pegawai, lalu tekan enter atau klik cari" aria-label="Search" aria-describedby="officer-search">
            @endif
            <button class="btn btn-outline-primary" type="submit" id="officer-search">Cari</button>
        </div>
    </form>
</p>
@if (Request::is('officers/search*'))
<!--SEARCH PAGE-->
<!--TABLE-->
<table class="table table-hover table-bordered">
    <thead>
        <tr class="table-primary">
            <th class="col-1" scope="col">#</th>
            <th scope="col">Nama</th>
            <th scope="col">Jabatan</th>
            <th scope="col">Jenis Kelamin</th>
            <th class="col-1" scope="col">Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($officers as $officer)
        <tr>
            <th scope="row">{{ $loop->iteration }}</th>
            <td>{{ $officer->name }}</td>
            <td>{{ $officer->department->name }}</td>
            <td>{{ $officer->gender }}</td>
            <td>
                <div class="dropdown">
                    <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-menu-button-fill"></i>
                    </button>
                    <ul class="dropdown-menu mx-0 shadow w-table-menu">
                        <li>
                            <a class="dropdown-item d-flex gap-2 align-items-center"  href="#" data-bs-toggle="modal" data-bs-target="#modal-off-view-{{ $officer->id_officer }}"><svg class="bi" width="16" height="16" style="vertical-align: -.125em;"><use xlink:href="#view"/></svg>
                                Detail
                            </a>
                        </li>
                    </ul>
                </div>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="7">Tidak ada Pegawai yang terdaftar</td>
        </tr>
        @endforelse
    </tbody>
    <tfoot class="table-group-divider table-secondary">
        <tr>
            <td colspan="7">Total Data: <b>{{ $officers->count() }}</b> Pegawai</td>
        </tr>
    </tfoot>
</table>
{{$officers->withQueryString()->links()}}
@else
<!--MAIN PAGE-->
<div class="row g-2">
    <!--SIDEBAR-->
    <div class="col-md-3">
        <div class="position-sticky" style="top: 2rem;">
            <!--MENU-->
            <div class="dropdown pb-3">
                <div class="dropdown">
                    <!--HELP-->
                    <a class="btn btn-secondary" data-bs-toggle="offcanvas" href="#offcanvas-help" role="button" aria-controls="offcanvas-help">
                        <i class="bi bi-question-lg"></i>
                        Bantuan
                    </a>
                </div>
            </div>
            <!--PART NAV-->
            <div class="nav flex-column nav-pills me-3" id="parts-tab" role="tablist" aria-orientation="vertical">
                @forelse ($parts as $part)
                <button class="nav-link {{ $loop->first ? 'active' : '' }}" id="pills-{{ $part->id_part }}-tab" data-bs-toggle="pill" data-bs-target="#pills-{{ $part->id_part }}" type="button" role="tab" aria-controls="pills-{{ $part->id_part }}" aria-selected="{{ $loop->first ? 'true' : 'false' }}">
                    {{ $part->name }}
                </button>
                @empty
                <button class="nav-link active" id="pills-empty-tab" data-bs-toggle="pill" data-bs-target="#pills-empty" type="button" role="tab" aria-controls="pills-empty" aria-selected="true">
                    Empty
                </button>
                @endforelse
            </div>
            <br/>
        </div>
    </div>
    <!--MAIN CONTENT-->
    <div class="col-md-9">
        <div class="tab-content" id="parts-tabContent">
            @forelse ($parts as $part)
            <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" id="pills-{{ $part->id_part }}" role="tabpanel" aria-labelledby="pills-{{ $part->id_part }}-tab" tabindex="0">
                <!--HEADING WITH MENU-->
                <div class="row align-items-center">
                    <div class="col-6">
                        <h2>{{ $part->name }}</h2>
                    </div>
                    <div class="col-6 d-grid gap-2 d-md-flex justify-content-md-end">

                    </div>
                </div>
                <!--TABS-->
                <nav>
                    <div class="nav nav-pills" id="teams-tab" role="tablist">
                        @foreach ($teams->where('id_part', $part->id_part) as $team)
                            @if ($team->id_team == 'TIM-000')
                            <button class="nav-link {{ $loop->first ? 'active' : '' }}" id="{{ $part->id_part }}-{{ $team->id_team }}-tab" data-bs-toggle="tab" data-bs-target="#{{ $part->id_part }}-{{ $team->id_team }}-tab-pane" type="button" role="tab" aria-controls="{{ $part->id_part }}-{{ $team->id_team }}-tab-pane" aria-selected="{{ $loop->first ? 'true' : 'false' }}" hidden>{{ $team->name }}</button>
                            @else
                            <button class="nav-link {{ $loop->first ? 'active' : '' }}" id="{{ $part->id_part }}-{{ $team->id_team }}-tab" data-bs-toggle="tab" data-bs-target="#{{ $part->id_part }}-{{ $team->id_team }}-tab-pane" type="button" role="tab" aria-controls="{{ $part->id_part }}-{{ $team->id_team }}-tab-pane" aria-selected="{{ $loop->first ? 'true' : 'false' }}">{{ $team->name }}</button>
                            @endif
                        @endforeach
                    </div>
                </nav>
                <div class="tab-content" id="teams-tabContent">
                    @foreach ($teams->where('id_part', $part->id_part) as $team)
                    <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }} pt-2" id="{{ $part->id_part }}-{{ $team->id_team }}-tab-pane" role="tabpanel" aria-labelledby="{{ $part->id_part }}-{{ $team->id_team }}-tab" tabindex="0">
                        @forelse ($subteams->where('id_team', $team->id_team) as $subteam)
                        <!--SUBHEADING-->
                        <h4 class="pb-2">{{ $subteam->name }}</h4>
                        <!--TABLE-->
                        <table class="table table-hover table-bordered">
                            <thead>
                                <tr class="table-primary">
                                    <th class="col-1" scope="col">#</th>
                                    <th scope="col">Nama</th>
                                    <th scope="col">Jabatan</th>
                                    <th scope="col">Tim Cadangan</th>
                                    <th class="col-1" scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($officers->where('id_sub_team_1', $subteam->id_sub_team) as $officer)
                                <tr>
                                    <th scope="row">{{ $loop->iteration }}</th>
                                    <td>{{ $officer->name }}</td>
                                    <td>{{ $officer->department->name }}</td>
                                    <td>{{ $officer->subteam_2->name ?? 'Tidak Ada'}}</td>
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="bi bi-menu-button-fill"></i>
                                            </button>
                                            <ul class="dropdown-menu mx-0 shadow w-table-menu">
                                                <li>
                                                    <a class="dropdown-item d-flex gap-2 align-items-center"  href="#" data-bs-toggle="modal" data-bs-target="#modal-off-view-{{ $officer->id_officer }}"><svg class="bi" width="16" height="16" style="vertical-align: -.125em;"><use xlink:href="#view"/></svg>
                                                        Detail
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7">Tidak ada Pegawai yang terdaftar</td>
                                </tr>
                                @endforelse
                            </tbody>
                            <tfoot class="table-group-divider table-secondary">
                                <tr>
                                    <td colspan="7">Total Data: <b>{{ $officers->where('id_part', $part->id_part)->count() }}</b> Pegawai</td>
                                </tr>
                            </tfoot>
                        </table>
                        @empty
                        <div class="alert alert-danger" role="alert">
                            Tidak ada tim yang terdaftar.
                        </div>
                        @endforelse
                    </div>
                    @endforeach
                </div>
            </div>
            @empty
            <div class="tab-pane fade show active" id="pills-empty" role="tabpanel" aria-labelledby="pills-empty-tab" tabindex="0">
                <div class="alert alert-danger" role="alert">
                    <p>Tidak ada data yang terdaftar.</p>
                </div>
            </div>
            @endforelse
        </div>
    </div>
</div>
@endif

