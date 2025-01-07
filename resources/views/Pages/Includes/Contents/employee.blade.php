<h1 class="text-center mb-4">Karyawan BPS Jawa Timur</h1>
@if (Session::get('code_alert') == 1)
@include('Templates.Includes.Components.alert')
@endif
<!--SEARCH FORM-->
<p>
    @if (!empty(Auth::user()->part))
        @if (Request::is('employees*'))
        <form action="{{ route('employees.search') }}" method="GET">
        @elseif (Request::is('developer/masters/employees*') && Auth::user()->part == "Dev")
        <form action="{{ route('developer.masters.employees.search') }}" method="GET">
        @elseif (Request::is('admin/masters/employees*') && Auth::user()->part == "Admin" || Auth::user()->part == "KBPS")
        <form action="{{ route('admin.masters.employees.search') }}" method="GET">
        @elseif (Request::is('employee/employees*') && Auth::user()->part == "Karyawan")
        <form action="{{ route('employee.employees.search') }}" method="GET">
        @endif
    @else
    <form action="{{ route('employees.search') }}" method="GET">
    @endif
        <div class="input-group mb-3">
            <span class="input-group-text" id="employee-search"><i class="bi bi-search"></i></span>
            @if (Request::is('employees/search*') || Request::is('admin/masters/employees/search*') || Request::is('developer/masters/employees/search*') || Request::is('employee/employees/search*'))
            <input type="search" id="search" name="search" class="typeahead form-control" placeholder="Ketik untuk mencari karyawan, lalu tekan enter atau klik cari" aria-label="Search" aria-describedby="employee-search" value="{{ $search }}">
                @if (!empty(Auth::user()->part))
                    @if (Request::is('employees*'))
                    <a class="btn btn-outline-secondary" type="button" href="{{ route('employees.index') }}">Kembali</a>
                    @elseif (Request::is('developer/masters/employees*') && Auth::user()->part == "Dev")
                    <a class="btn btn-outline-secondary" type="button" href="{{ route('developer.masters.employees.index') }}">Kembali</a>
                    @elseif (Request::is('admin/masters/employees*') && Auth::user()->part == "Admin" || Auth::user()->part == "KBPS")
                    <a class="btn btn-outline-secondary" type="button" href="{{ route('admin.masters.employees.index') }}">Kembali</a>
                    @elseif (Request::is('employee/employees*') && Auth::user()->part == "Karyawan")
                    <a class="btn btn-outline-secondary" type="button" href="{{ route('employee.employees.index') }}">Kembali</a>
                    @endif
                @else
                <a class="btn btn-outline-secondary" type="button" href="{{ route('employees.index') }}">Kembali</a>
                @endif
            @else
            <input type="search" id="search" name="search" class="typeahead form-control" placeholder="Ketik untuk mencari karyawan, lalu tekan enter atau klik cari" aria-label="Search" aria-describedby="employee-search">
            @endif
            <button class="btn btn-outline-primary" type="submit" id="employee-search">Cari</button>
        </div>
    </form>
</p>
<!--SEARCH PAGE-->
@if (Request::is('employees/search*') || Request::is('admin/masters/employees/search*') || Request::is('developer/masters/employees/search*') || Request::is('employee/employees/search*'))
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
        @forelse ($employees as $employee)
        <tr>
            <th scope="row">{{ $loop->iteration }}</th>
            <td>{{ $employee->name }}</td>
            <td>{{ $employee->position->name }}</td>
            <td>{{ $employee->gender }}</td>
            <td>
                <div class="dropdown">
                    <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-menu-button-fill"></i>
                    </button>
                    <ul class="dropdown-menu mx-0 shadow w-table-menu">
                        <li>
                            <a class="dropdown-item d-flex gap-2 align-items-center"  href="#" data-bs-toggle="modal" data-bs-target="#modal-emp-view-{{ $employee->id_employee }}"><svg class="bi" width="16" height="16" style="vertical-align: -.125em;"><use xlink:href="#view"/></svg>
                                Detail
                            </a>
                        </li>
                        @if (!empty(Auth::user()->part))
                            @if (Request::is('admin/masters/employees*') && Auth::user()->part == "Admin" || Request::is('developer/masters/employees*') && Auth::user()->part == "Dev")
                            <li><hr class="dropdown-divider"></li>
                                @if ($employee->status == 'Active')
                                <li>
                                    <a class="dropdown-item d-flex gap-2 align-items-center"  href="#" data-bs-toggle="modal" data-bs-target="#modal-emp-preupd-{{ $employee->id_employee }}"><svg class="bi" width="16" height="16" style="vertical-align: -.125em;"><use xlink:href="#update"/></svg>
                                        Edit
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item d-flex gap-2 align-items-center" href="#" data-bs-toggle="modal" data-bs-target="#modal-emp-retire-{{ $employee->id_employee }}"><svg class="bi" width="16" height="16" style="vertical-align: -.125em;"><use xlink:href="#delete"/></svg>
                                        Nonaktifkan
                                    </a>
                                </li>
                                @elseif ($employee->status == 'Not Active')
                                <li>
                                    <a class="dropdown-item d-flex gap-2 align-items-center disabled"  href="#"><svg class="bi" width="16" height="16" style="vertical-align: -.125em;"><use xlink:href="#update"/></svg>
                                        Edit
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item d-flex gap-2 align-items-center" href="#" data-bs-toggle="modal" data-bs-target="#modal-emp-delete-{{ $employee->id_employee }}"><svg class="bi" width="16" height="16" style="vertical-align: -.125em;"><use xlink:href="#delete"/></svg>
                                        Delete
                                    </a>
                                </li>
                                @endif
                            @endif
                        @endif
                    </ul>
                </div>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="7">Tidak ada Karyawan yang terdaftar</td>
        </tr>
        @endforelse
    </tbody>
    <tfoot class="table-group-divider table-secondary">
        <tr>
            <td colspan="7">Total Data: <b>{{ $employees->count() }}</b> Karyawan</td>
        </tr>
    </tfoot>
</table>
{{$employees->withQueryString()->links()}}
@else
<!--MAIN PAGE-->
<div class="row g-2">
    <!--SIDEBAR-->
    <div class="col-md-3">
        <div class="position-sticky" style="top: 0rem;">
            <!--MENU-->
            <div class="dropdown pb-3">
                <div class="dropdown">
                    @if (!empty(Auth::user()->part))
                        @if (Request::is('admin/masters/employees*') && Auth::user()->part == "Admin" || Request::is('developer/masters/employees*') && Auth::user()->part == "Dev")
                        <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-gear"></i>
                            Kelola
                        </button>
                        <ul class="dropdown-menu">
                            <li>
                                <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#modal-emp-import">
                                    <i class="bi bi-file-earmark-arrow-up"></i>
                                    Import
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#modal-emp-export">
                                    <i class="bi bi-file-earmark-arrow-down"></i>
                                    Export
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#modal-dep-view">
                                    <i class="bi bi-diagram-2"></i>
                                    Kelola Jabatan
                                </a>
                            </li>
                        </ul>
                        @endif
                    @endif
                    @if (Auth::user()->part == 'Admin' || Auth::user()->part == 'Dev')
                    <span class="d-inline-block" tabindex="0" data-bs-toggle="tooltip" data-bs-title="Bantuan">
                        <a class="btn btn-secondary" data-bs-toggle="offcanvas" href="#offcanvas-help" role="button" aria-controls="offcanvas-help">
                            <i class="bi bi-question-lg"></i>
                        </a>
                    </span>
                    @else
                    <a class="btn btn-secondary" data-bs-toggle="offcanvas" href="#offcanvas-help" role="button" aria-controls="offcanvas-help">
                        <i class="bi bi-question-lg"></i>
                        Bantuan
                    </a>
                    @endif
                </div>
            </div>
            <!--PART NAV-->
            <div class="nav flex-column nav-pills me-3" id="parts-tab" role="tablist" aria-orientation="vertical">
                @forelse ($parts as $part)
                <button class="nav-link {{ $loop->first ? 'active' : '' }} text-start" id="pills-{{ $part->id_part }}-tab" data-bs-toggle="pill" data-bs-target="#pills-{{ $part->id_part }}" type="button" role="tab" aria-controls="pills-{{ $part->id_part }}" aria-selected="{{ $loop->first ? 'true' : 'false' }}">
                    {{ $part->name }}
                </button>
                @empty
                <button class="nav-link active text-start" id="pills-empty-tab" data-bs-toggle="pill" data-bs-target="#pills-empty" type="button" role="tab" aria-controls="pills-empty" aria-selected="true">
                    Empty
                </button>
                @endforelse
                <hr/>
                <button class="nav-link text-start" id="pills-inactive-tab" data-bs-toggle="pill" data-bs-target="#pills-inactive" type="button" role="tab" aria-controls="pills-inactive" aria-selected="false">
                    Tidak Aktif
                </button>
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
                            @if (Request::is('admin/masters/employees*') && Auth::user()->part == "Admin" || Request::is('developer/masters/employees*') && Auth::user()->part == "Dev") <!--ONLY ADMIN AND DEVELOPER CAN EDIT THIS DATA-->
                                @if ($part->id_part == 'PRT-001')
                                    @if (count($employees->where('id_position', 'POS-001')) > 0) <!--IF KEPALA BPS HAS BEEN REGISTERED-->
                                    <span class="d-inline-block" tabindex="0" data-bs-toggle="tooltip" data-bs-title="Kepala BPS Jawa Timur telah terdaftar.">
                                        <button class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#modal-emp-precre-{{ $part->id_part }}" disabled>
                                            <i class="bi bi-person-plus"></i>
                                            Tambah Karyawan
                                        </button>
                                    </span>
                                    @else
                                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-emp-precre-{{ $part->id_part }}">
                                        <i class="bi bi-person-plus"></i>
                                        Tambah Karyawan
                                    </button>
                                    @endif
                                    <span class="d-inline-block" tabindex="0" data-bs-toggle="tooltip" data-bs-title="Bagian ini hanya memiliki satu tim saja.">
                                        <button class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#modal-tim-view-{{ $part->id_part }}" disabled>
                                            <i class="bi bi-gear"></i>
                                            Kelola Tim
                                        </button>
                                    </span>
                                @else
                                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-emp-precre-{{ $part->id_part }}">
                                    <i class="bi bi-person-plus"></i>
                                    Tambah Karyawan
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
                            @if ($team->id_team == 'TIM-001') <!--PIMPINAN BPS HAS NO TEAM (ONLY ONE)-->
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
                                @forelse ($employees->where('id_sub_team_1', $subteam->id_sub_team)->where('status', 'Active') as $employee)
                                <tr>
                                    <th scope="row">{{ $loop->iteration }}</th>
                                    <td>{{ $employee->name }}</td>
                                    <td>{{ $employee->position->name }}</td>
                                    <td>{{ $employee->subteam_2->name ?? 'Tidak Ada'}}</td>
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="bi bi-menu-button-fill"></i>
                                            </button>
                                            <ul class="dropdown-menu mx-0 shadow w-table-menu">
                                                <li>
                                                    <a class="dropdown-item d-flex gap-2 align-items-center"  href="#" data-bs-toggle="modal" data-bs-target="#modal-emp-view-{{ $employee->id_employee }}"><svg class="bi" width="16" height="16" style="vertical-align: -.125em;"><use xlink:href="#view"/></svg>
                                                        Detail
                                                    </a>
                                                </li>
                                                @if (Request::is('admin/masters/employees*') || Request::is('developer/masters/employees*') && !empty(Auth::user()->part))
                                                    @if (Auth::user()->part == "Admin" || Auth::user()->part == "Dev") <!--ONLY ADMIN AND DEVELOPER CAN EDIT THIS DATA-->
                                                    <li><hr class="dropdown-divider"></li>
                                                    <li>
                                                        <a class="dropdown-item d-flex gap-2 align-items-center"  href="#" data-bs-toggle="modal" data-bs-target="#modal-emp-preupd-{{ $employee->id_employee }}"><svg class="bi" width="16" height="16" style="vertical-align: -.125em;"><use xlink:href="#update"/></svg>
                                                            Edit
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item d-flex gap-2 align-items-center" href="#" data-bs-toggle="modal" data-bs-target="#modal-emp-retire-{{ $employee->id_employee }}"><svg class="bi" width="16" height="16" style="vertical-align: -.125em;"><use xlink:href="#inactive"/></svg>
                                                            Nonaktifkan
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
                                    <td colspan="7">Tidak ada Karyawan yang terdaftar</td>
                                </tr>
                                @endforelse
                            </tbody>
                            <tfoot class="table-group-divider table-secondary">
                                <tr>
                                    <td colspan="7">Total Data: <b>{{ $employees->where('id_sub_team_1', $subteam->id_sub_team)->count() }}</b> Karyawan</td>
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
            <div class="tab-pane fade" id="pills-inactive" role="tabpanel" aria-labelledby="pills-inactive-tab" tabindex="0">
                <!--SUBHEADING-->
                <h2>Nonaktif Karyawan</h2>
                <!--TABLE-->
                <table class="table table-hover table-bordered">
                    <thead>
                        <tr class="table-primary">
                            <th class="col-1" scope="col">#</th>
                            <th scope="col">Nama</th>
                            <th scope="col">Jabatan</th>
                            <th scope="col">Tim Utama</th>
                            <th class="col-1" scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($employees->where('status', 'Not Active') as $emp_inactive)
                        <tr>
                            <th scope="row">{{ $loop->iteration }}</th>
                            <td>{{ $emp_inactive->name }}</td>
                            <td>{{ $emp_inactive->position->name }}</td>
                            <td>
                                {{ $emp_inactive->subteam_1->name ?? 'Tidak Ada'}}
                            </td>
                            <td>
                                <div class="dropdown">
                                    <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="bi bi-menu-button-fill"></i>
                                    </button>
                                    <ul class="dropdown-menu mx-0 shadow w-table-menu">
                                        <li>
                                            <a class="dropdown-item d-flex gap-2 align-items-center"  href="#" data-bs-toggle="modal" data-bs-target="#modal-emp-view-{{ $emp_inactive->id_employee }}"><svg class="bi" width="16" height="16" style="vertical-align: -.125em;"><use xlink:href="#view"/></svg>
                                                Detail
                                            </a>
                                        </li>
                                        @if (Request::is('admin/masters/employees*') || Request::is('developer/masters/employees*') && !empty(Auth::user()->part))
                                            @if (Auth::user()->part == "Admin" || Auth::user()->part == "Dev") <!--ONLY ADMIN AND DEVELOPER CAN EDIT THIS DATA-->
                                            <li><hr class="dropdown-divider"></li>
                                            <li>
                                                <a class="dropdown-item d-flex gap-2 align-items-center" href="#" data-bs-toggle="modal" data-bs-target="#modal-emp-delete-{{ $emp_inactive->id_employee }}"><svg class="bi" width="16" height="16" style="vertical-align: -.125em;"><use xlink:href="#delete"/></svg>
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
                            <td colspan="7">Tidak ada Karyawan yang telah dinonaktifkan</td>
                        </tr>
                        @endforelse
                    </tbody>
                    <tfoot class="table-group-divider table-secondary">
                        <tr>
                            <td colspan="7">Total Data: <b>{{ $employees->where('id_sub_team_1', $subteam->id_sub_team)->count() }}</b> Karyawan</td>
                        </tr>
                    </tfoot>
                </table>
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
