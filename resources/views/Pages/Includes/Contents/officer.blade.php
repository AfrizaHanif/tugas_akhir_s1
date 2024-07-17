<h1 class="text-center mb-4">Pegawai BPS Jawa Timur</h1>
@if (Session::get('code_alert') == 1)
@include('Templates.Includes.Components.alert')
@endif
<!--SEARCH FORM-->
<p>
    @if (!empty(Auth::user()->part))
        @if (Request::is('officers*'))
        <form action="{{ route('officers.search') }}" method="GET">
        @elseif (Request::is('developer/masters/officers*') && Auth::user()->part == "Dev")
        <form action="{{ route('developer.masters.officers.search') }}" method="GET">
        @elseif (Request::is('admin/masters/officers*') && Auth::user()->part == "Admin")
        <form action="{{ route('admin.masters.officers.search') }}" method="GET">
        @endif
    @else
    <form action="{{ route('officers.search') }}" method="GET">
    @endif
        <div class="input-group mb-3">
            <span class="input-group-text" id="officer-search"><i class="bi bi-search"></i></span>
            @if (Request::is('officers/search*') || Request::is('admin/masters/officers/search*') || Request::is('developer/masters/officers/search*'))
            <input type="search" id="search" name="search" class="typeahead form-control" placeholder="Ketik untuk mencari pegawai, lalu tekan enter atau klik cari" aria-label="Search" aria-describedby="officer-search" value="{{ $search }}">
            @if (!empty(Auth::user()->part))
                @if (Request::is('officers*'))
                <a class="btn btn-outline-secondary" type="button" href="{{ route('officers.index') }}">Kembali</a>
                @elseif (Request::is('developer/masters/officers*') && Auth::user()->part == "Dev")
                <a class="btn btn-outline-secondary" type="button" href="{{ route('developer.masters.officers.index') }}">Kembali</a>
                @elseif (Request::is('admin/masters/officers*') && Auth::user()->part == "Admin")
                <a class="btn btn-outline-secondary" type="button" href="{{ route('admin.masters.officers.index') }}">Kembali</a>
                @endif
            @else
            <a class="btn btn-outline-secondary" type="button" href="{{ route('officers') }}">Kembali</a>
            @endif
            @else
            <input type="search" id="search" name="search" class="typeahead form-control" placeholder="Ketik untuk mencari pegawai, lalu tekan enter atau klik cari" aria-label="Search" aria-describedby="officer-search">
            @endif
            <button class="btn btn-outline-primary" type="submit" id="officer-search">Cari</button>
        </div>
    </form>
</p>
<!--SEARCH PAGE-->
@if (Request::is('officers/search*') || Request::is('admin/masters/officers/search*') || Request::is('developer/masters/officers/search*'))
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
                        @if (!empty(Auth::user()->part))
                            @if (Request::is('admin/masters/officers*') && Auth::user()->part == "Admin")
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item d-flex gap-2 align-items-center"  href="#" data-bs-toggle="modal" data-bs-target="#modal-off-preupd-{{ $officer->id_officer }}"><svg class="bi" width="16" height="16" style="vertical-align: -.125em;"><use xlink:href="#update"/></svg>
                                    Edit
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item d-flex gap-2 align-items-center" href="#" data-bs-toggle="modal" data-bs-target="#modal-off-delete-{{ $officer->id_officer }}"><svg class="bi" width="16" height="16" style="vertical-align: -.125em;"><use xlink:href="#delete"/></svg>
                                    Delete
                                </a>
                            </li>
                            @endif
                        @endif
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
                    @if (!empty(Auth::user()->part))
                        @if (Request::is('admin/masters/officers*') && Auth::user()->part == "Admin")
                        <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-menu-button-fill"></i>
                        </button>
                        <ul class="dropdown-menu">
                            <li>
                                <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#modal-off-import">
                                    <i class="bi bi-file-earmark-arrow-up"></i>
                                    Import
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#modal-off-export">
                                    <i class="bi bi-file-earmark-arrow-down"></i>
                                    Export
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#modal-dep-view">
                                    <i class="bi bi-diagram-2"></i>
                                    Jabatan
                                </a>
                            </li>
                        </ul>
                        @endif
                    @endif
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
                        <!--MENU-->
                        @if (!empty(Auth::user()->part))
                            @if (Request::is('admin/masters/officers*') && Auth::user()->part == "Admin")
                                @if ($part->id_part == 'PRT-000')
                                <button class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#modal-off-precre-{{ $part->id_part }}" hidden>
                                    <i class="bi bi-person-plus"></i>
                                    Tambah Pegawai
                                </button>
                                <button class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#modal-tim-view-{{ $part->id_part }}" hidden>
                                    <i class="bi bi-gear"></i>
                                    Kelola Tim
                                </button>
                                @else
                                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-off-precre-{{ $part->id_part }}">
                                    <i class="bi bi-person-plus"></i>
                                    Tambah Pegawai
                                </button>
                                <button class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#modal-tim-view-{{ $part->id_part }}">
                                    <i class="bi bi-gear"></i>
                                    Kelola Tim
                                </button>
                                @endif
                            @endif
                        @endif
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
                                                @if (Request::is('admin/masters/officers*') && !empty(Auth::user()->part))
                                                    @if (Auth::user()->part == "Admin")
                                                    <li><hr class="dropdown-divider"></li>
                                                    <li>
                                                        <a class="dropdown-item d-flex gap-2 align-items-center"  href="#" data-bs-toggle="modal" data-bs-target="#modal-off-preupd-{{ $officer->id_officer }}"><svg class="bi" width="16" height="16" style="vertical-align: -.125em;"><use xlink:href="#update"/></svg>
                                                            Edit
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item d-flex gap-2 align-items-center" href="#" data-bs-toggle="modal" data-bs-target="#modal-off-delete-{{ $officer->id_officer }}"><svg class="bi" width="16" height="16" style="vertical-align: -.125em;"><use xlink:href="#delete"/></svg>
                                                            Delete
                                                        </a>
                                                    </li>
                                                    @endif
                                                @endif
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
                                    <td colspan="7">Total Data: <b>{{ $officers->where('id_sub_team_1', $subteam->id_sub_team)->count() }}</b> Pegawai</td>
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
