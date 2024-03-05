<h1 class="text-center mb-4">Pegawai BPS Jawa Timur</h1>
@include('Templates.Includes.Components.alert')
@if (Auth::user()->part == "Admin")
<p>
    <div class="btn-group" role="group" aria-label="Basic example">
        <a class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-prt-create">
            <i class="bi bi-folder-plus"></i>
            Tambah Bagian
        </a>
    </div>
    <div class="btn-group" role="group" aria-label="Basic example">
        <a class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#modal-dep-view">
            <i class="bi bi-diagram-2"></i>
            Lihat Jabatan
        </a>
    </div>
</p>
@endif
<div class="row">
    <div class="col-md-3">
        <div class="position-sticky" style="top: 2rem;">
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
