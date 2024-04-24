<h1 class="text-center mb-4">Pegawai BPS Jawa Timur</h1>
@if (Session::get('code_alert') == 1)
@include('Templates.Includes.Components.alert')
@endif
<p>
    <form action="{{ route('admin.masters.officers.search') }}" method="GET">
        <div class="input-group mb-3">
            <span class="input-group-text" id="officer-search"><i class="bi bi-search"></i></span>
            @if (Request::is('admin/masters/officers/search*'))
            <input type="search" id="search" name="search" class="typeahead form-control" placeholder="Ketik untuk mencari pegawai, lalu tekan enter atau klik cari" aria-label="Search" aria-describedby="officer-search" value="{{ $search }}">
            <a class="btn btn-outline-secondary" type="button" href="{{ route('admin.masters.officers.index') }}">Kembali</a>
            @else
            <input type="search" id="search" name="search" class="typeahead form-control" placeholder="Ketik untuk mencari pegawai, lalu tekan enter atau klik cari" aria-label="Search" aria-describedby="officer-search">
            @endif
            <button class="btn btn-outline-primary" type="submit" id="officer-search">Cari</button>
        </div>
    </form>
</p>
@if (Request::is('admin/masters/officers/search*'))
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
                        @if (Auth::user()->part == "Admin")
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item d-flex gap-2 align-items-center"  href="#" data-bs-toggle="modal" data-bs-target="#modal-off-update-{{ $officer->id_officer }}"><svg class="bi" width="16" height="16" style="vertical-align: -.125em;"><use xlink:href="#update"/></svg>
                                Edit
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item d-flex gap-2 align-items-center" href="#" data-bs-toggle="modal" data-bs-target="#modal-off-delete-{{ $officer->id_officer }}"><svg class="bi" width="16" height="16" style="vertical-align: -.125em;"><use xlink:href="#delete"/></svg>
                                Delete
                            </a>
                        </li>
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
<div class="row">
    <div class="col-md-3">
        <div class="position-sticky" style="top: 2rem;">
            <div class="dropdown pb-3">
                @if (Auth::user()->part == "Admin")
                <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-menu-button-fill"></i>
                </button>
                <ul class="dropdown-menu">
                    <li>
                        <a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#modal-prt-create">
                            <i class="bi bi-folder-plus"></i>
                            Tambah Bagian
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#modal-dep-view">
                            <i class="bi bi-diagram-2"></i>
                            Lihat Jabatan
                        </a>
                    </li>
                </ul>
                @endif
                <a class="btn btn-secondary" data-bs-toggle="offcanvas" href="#offcanvas-help" role="button" aria-controls="offcanvas-help">
                    <i class="bi bi-question-lg"></i>
                    Bantuan
                </a>
            </div>
            <div class="nav flex-column nav-pills me-3" id="v-pills-tab" role="tablist" aria-orientation="vertical">
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
    <div class="col-md-9">
        <div class="tab-content" id="v-pills-tabContent">
            @forelse ($parts as $part)
            <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" id="pills-{{ $part->id_part }}" role="tabpanel" aria-labelledby="pills-{{ $part->id_part }}-tab" tabindex="0">
                <h2>{{ $part->name }}</h2>
                @if (Auth::user()->part == "Admin")
                <p>
                    <div class="btn-group" role="group" aria-label="Basic example">
                        <a class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-off-create-{{ $part->id_part }}">
                            <i class="bi bi-person-plus"></i>
                            Tambah Pegawai
                        </a>
                    </div>
                    @if ($part->name != "Kepemimpinan")
                    <div class="btn-group" role="group" aria-label="Basic example">
                        <a class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#modal-prt-update-{{ $part->id_part }}">
                            <i class="bi bi-pencil"></i>
                            Ubah Bagian
                        </a>
                    </div>
                    <div class="btn-group" role="group" aria-label="Basic example">
                        <a class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#modal-prt-delete-{{ $part->id_part }}">
                            <i class="bi bi-folder-minus"></i>
                            Hapus Bagian
                        </a>
                    </div>
                    @endif

                </p>
                @endif
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
                        @forelse ($officers->where('id_part', $part->id_part) as $officer)
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
                                        @if (Auth::user()->part == "Admin")
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <a class="dropdown-item d-flex gap-2 align-items-center"  href="#" data-bs-toggle="modal" data-bs-target="#modal-off-update-{{ $officer->id_officer }}"><svg class="bi" width="16" height="16" style="vertical-align: -.125em;"><use xlink:href="#update"/></svg>
                                                Edit
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item d-flex gap-2 align-items-center" href="#" data-bs-toggle="modal" data-bs-target="#modal-off-delete-{{ $officer->id_officer }}"><svg class="bi" width="16" height="16" style="vertical-align: -.125em;"><use xlink:href="#delete"/></svg>
                                                Delete
                                            </a>
                                        </li>
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
                            <td colspan="7">Total Data: <b>{{ $officers->where('id_part', $part->id_part)->count() }}</b> Pegawai</td>
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
