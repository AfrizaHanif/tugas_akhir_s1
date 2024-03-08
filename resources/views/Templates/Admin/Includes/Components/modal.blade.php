@if (Request::is('masters/officers'))
<div class="modal fade" id="modal-prt-create" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('masters.parts.store') }}" method="POST" enctype="multipart/form-data">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Tambah Bagian</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @if (Session::get('modal_redirect') == 'modal-prt-create')
                    @include('Templates.Includes.Components.alert')
                    @endif
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Bagian</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">
                        <i class="bi bi-x-lg"></i>
                        Batal
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-plus-lg"></i>
                        Tambah
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

    @foreach ($parts as $part)
    <div class="modal fade" id="modal-prt-update-{{ $part->id_part }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('masters.parts.update', $part->id_part) }}" method="POST" enctype="multipart/form-data">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Ubah Bagian ({{ $part->id_part }})</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        @if (Session::get('modal_redirect') == 'modal-prt-update')
                        @include('Templates.Includes.Components.alert')
                        @endif
                        @csrf @method('PUT')
                        <div class="mb-3">
                            <label for="name" class="form-label">Nama Bagian</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ $part->name }}" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">
                            <i class="bi bi-x-lg"></i>
                            Batal
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-plus-lg"></i>
                            Ubah
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-prt-delete-{{ $part->id_part }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('masters.parts.destroy', $part->id_part) }}" method="POST" enctype="multipart/form-data">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Hapus Data Bagian ({{ $part->id_part}})</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-warning" role="alert">
                            <i class="bi bi-exclamation-triangle-fill"></i> <b>PERHATIAN</b>
                            <br/>
                            Apakah anda ingin menghapus Bagian?
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="bi bi-x-lg"></i>
                            Tidak
                        </button>
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="bi bi-check-lg"></i>
                            Ya
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal modal-xl fade" id="modal-off-create-{{ $part->id_part }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('masters.officers.store') }}" method="POST" enctype="multipart/form-data">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Tambah Data Pegawai Bagian {{ $part->name }}</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        @if (Session::get('modal_redirect') == 'modal-off-create')
                        @include('Templates.Includes.Components.alert')
                        @endif
                        @csrf
                        <div class="mb-3">
                            <label for="nip_bps" class="form-label">NIP BPS</label>
                            <input type="number" class="form-control" id="nip_bps" name="nip_bps" value="{{ old('nip_bps') }}" disabled>
                        </div>
                        <div class="mb-3">
                            <label for="nip" class="form-label">NIP</label>
                            <input type="number" class="form-control" id="nip" name="nip" value="{{ old('nip') }}" disabled>
                        </div>
                        <div class="mb-3">
                            <label for="name" class="form-label">Nama Pegawai</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="org_code" class="form-label">Kode Organisasi</label>
                            <input type="number" class="form-control" id="org_code" name="org_code" value="{{ old('org_code') }}" disabled>
                        </div>
                        <div class="mb-3">
                            <label for="id_department" class="form-label">Jabatan</label>
                            <select class="form-select" id="id_department" name="id_department" required>
                                <option selected disabled>---Pilih Jabatan---</option>
                                @foreach ($departments as $department)
                                <option value="{{ $department->id_department }}" {{ old('id_department') ==  $department->id_department ? 'selected' : null }}>{{ $department->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <input type="text" class="form-control" id="id_part" name="id_part" value="{{ $part->id_part }}" hidden>
                        </div>
                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <input type="text" class="form-control" id="status" name="status" value="{{ old('status') }}" disabled>
                        </div>
                        <div class="mb-3">
                            <label for="last_group" class="form-label">Golongan Akhir</label>
                            <input type="text" class="form-control" id="last_group" name="last_group" value="{{ old('last_group') }}" disabled>
                        </div>
                        <div class="mb-3">
                            <label for="last_education" class="form-label">Pendidikan Terakhir</label>
                            <select class="form-select" id="last_education" name="last_education" disabled>
                                <option selected disabled>---Pilih Status Kerja---</option>
                                <option value="SD" {{ old('last_education') == 'SD' ? 'selected' : null }}>SD</option>
                                <option value="SMP" {{ old('last_education') == 'SMP' ? 'selected' : null }}>SMP</option>
                                <option value="SMA" {{ old('last_education') == 'SMA' ? 'selected' : null }}>SMA</option>
                                <option value="D1/D2/D3" {{ old('last_education') == 'D1/D2/D3' ? 'selected' : null }}>D1/D2/D3</option>
                                <option value="D4" {{ old('last_education') == 'D4' ? 'selected' : null }}>D4</option>
                                <option value="S1" {{ old('last_education') == 'S1' ? 'selected' : null }}>S1</option>
                                <option value="S2" {{ old('last_education') == 'S2' ? 'selected' : null }}>S2</option>
                                <option value="S3" {{ old('last_education') == 'S3' ? 'selected' : null }}>S3</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="place_birth" class="form-label">Tempat Lahir</label>
                            <input type="text" class="form-control" id="place_birth" name="place_birth" value="{{ old('place_birth') }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="date_birth" class="form-label">Tanggal Lahir</label>
                            <input type="date" class="form-control" id="date_birth" name="date_birth" value="{{ old('date_birth') }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="gender" class="form-label">Jenis Kelamin</label>
                            <select class="form-select" id="gender" name="gender" required>
                                <option selected disabled>---Pilih Jenis Kelamin---</option>
                                <option value="Laki-Laki" {{ old('gender') == 'Laki-Laki' ? 'selected' : null }}>Laki-Laki</option>
                                <option value="Perempuan" {{ old('gender') == 'Perempuan' ? 'selected' : null }}>Perempuan</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="religion" class="form-label">Agama</label>
                            <select class="form-select" id="religion" name="religion" required>
                                <option selected disabled>---Pilih Agama---</option>
                                <option value="Islam" {{ old('religion') == 'Islam' ? 'selected' : null }}>Islam</option>
                                <option value="Kristen" {{ old('religion') == 'Kristen' ? 'selected' : null }}>Kristen</option>
                                <option value="Budha" {{ old('religion') == 'Budha' ? 'selected' : null }}>Budha</option>
                                <option value="Hindu" {{ old('religion') == 'Hindu' ? 'selected' : null }}>Hindu</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="photo" class="form-label">Foto (Pas Foto)</label>
                            <div class="input-group">
                                <input type="file" class="form-control" name="photo" id="photo">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">
                            <i class="bi bi-x-lg"></i>
                            Batal
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-plus-lg"></i>
                            Tambah
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endforeach

    @foreach ($officers as $officer)
    <div class="modal modal-xl fade" id="modal-off-update-{{ $officer->id_officer }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('masters.officers.update', $officer->id_officer) }}" method="POST" enctype="multipart/form-data">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Ubah Data Pegawai ({{ $officer->id_officer }})</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        @if (Session::get('modal_redirect') == 'modal-off-update')
                        @include('Templates.Includes.Components.alert')
                        @endif
                        @csrf @method('PUT')
                        <div class="mb-3">
                            <label for="nip_bps" class="form-label">NIP BPS</label>
                            <input type="number" class="form-control" id="nip_bps" name="nip_bps" value="{{ $officer->nip_bps }}" disabled>
                        </div>
                        <div class="mb-3">
                            <label for="nip" class="form-label">NIP</label>
                            <input type="number" class="form-control" id="nip" name="nip" value="{{ $officer->nip }}" disabled>
                        </div>
                        <div class="mb-3">
                            <label for="name" class="form-label">Nama Pegawai</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ $officer->name }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="org_code" class="form-label">Kode Organisasi</label>
                            <input type="number" class="form-control" id="org_code" name="org_code" value="{{ $officer->org_code }}" disabled>
                        </div>
                        <div class="mb-3">
                            <label for="id_department" class="form-label">Jabatan</label>
                            <select class="form-select" id="id_department" name="id_department" required>
                                <option selected disabled>---Pilih Jabatan---</option>
                                @foreach ($departments as $department)
                                <option value="{{ $department->id_department }}" {{ $officer->id_department ==  $department->id_department ? 'selected' : null }}>{{ $department->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="id_part" class="form-label">Bagian</label>
                            <select class="form-select" id="id_part" name="id_part" required>
                                <option selected disabled>---Pilih Bagian---</option>
                                @foreach ($parts as $part)
                                <option value="{{ $part->id_part }}" {{ $officer->id_part ==  $part->id_part ? 'selected' : null }}>{{ $part->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <input type="text" class="form-control" id="status" name="status" value="{{ $officer->status }}" disabled>
                        </div>
                        <div class="mb-3">
                            <label for="last_group" class="form-label">Golongan Akhir</label>
                            <input type="text" class="form-control" id="last_group" name="last_group" value="{{ $officer->last_group }}" disabled>
                        </div>
                        <div class="mb-3">
                            <label for="last_education" class="form-label">Pendidikan Terakhir</label>
                            <select class="form-select" id="last_education" name="last_education" disabled>
                                <option selected disabled>---Pilih Status Kerja---</option>
                                <option value="SD" {{ $officer->last_education == 'SD' ? 'selected' : null }}>SD</option>
                                <option value="SMP" {{ $officer->last_education == 'SMP' ? 'selected' : null }}>SMP</option>
                                <option value="SMA" {{ $officer->last_education == 'SMA' ? 'selected' : null }}>SMA</option>
                                <option value="D1/D2/D3" {{ $officer->last_education == 'D1/D2/D3' ? 'selected' : null }}>D1/D2/D3</option>
                                <option value="D4" {{ $officer->last_education == 'D4' ? 'selected' : null }}>D4</option>
                                <option value="S1" {{ $officer->last_education == 'S1' ? 'selected' : null }}>S1</option>
                                <option value="S2" {{ $officer->last_education == 'S2' ? 'selected' : null }}>S2</option>
                                <option value="S3" {{ $officer->last_education == 'S3' ? 'selected' : null }}>S3</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="place_birth" class="form-label">Tempat Lahir</label>
                            <input type="text" class="form-control" id="place_birth" name="place_birth" value="{{ $officer->place_birth }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="date_birth" class="form-label">Tanggal Lahir</label>
                            <input type="date" class="form-control" id="date_birth" name="date_birth" value="{{ $officer->date_birth }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="gender" class="form-label">Jenis Kelamin</label>
                            <select class="form-select" id="gender" name="gender" required>
                                <option selected disabled>---Pilih Jenis Kelamin---</option>
                                <option value="Laki-Laki" {{ $officer->gender == 'Laki-Laki' ? 'selected' : null }}>Laki-Laki</option>
                                <option value="Perempuan" {{ $officer->gender == 'Perempuan' ? 'selected' : null }}>Perempuan</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="religion" class="form-label">Agama</label>
                            <select class="form-select" id="religion" name="religion" required>
                                <option selected disabled>---Pilih Agama---</option>
                                <option value="Islam" {{ $officer->religion == 'Islam' ? 'selected' : null }}>Islam</option>
                                <option value="Kristen" {{ $officer->religion == 'Kristen' ? 'selected' : null }}>Kristen</option>
                                <option value="Budha" {{ $officer->religion == 'Budha' ? 'selected' : null }}>Budha</option>
                                <option value="Hindu" {{ $officer->religion == 'Hindu' ? 'selected' : null }}>Hindu</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">
                            <i class="bi bi-x-lg"></i>
                            Batal
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-plus-lg"></i>
                            Ubah
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-off-delete-{{ $officer->id_officer }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('masters.officers.destroy', $officer->id_officer) }}" method="POST" enctype="multipart/form-data">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Hapus Data Pegawai ({{ $officer->id_officer}})</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-warning" role="alert">
                            <i class="bi bi-exclamation-triangle-fill"></i> <b>PERHATIAN</b>
                            <br/>
                            Apakah anda ingin menghapus Pegawai tersebut?
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="bi bi-x-lg"></i>
                            Tidak
                        </button>
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="bi bi-check-lg"></i>
                            Ya
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endforeach

<div class="modal fade" id="modal-dep-view" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Daftar Jabatan</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table class="table table-hover table-bordered">
                    <thead>
                        <tr class="table-primary">
                            <th class="col-1" scope="col">#</th>
                            <th scope="col">Nama Jabatan</th>
                            <th class="col-1" scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($departments as $department)
                        <tr>
                            <th scope="row">{{ $loop->iteration }}</th>
                            <td>{{ $department->name }}</td>
                            <td>
                                <div class="dropdown">
                                    <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="bi bi-menu-button-fill"></i>
                                    </button>
                                    <ul class="dropdown-menu mx-0 shadow w-table-menu">
                                        <li>
                                            <a class="dropdown-item d-flex gap-2 align-items-center" href="#" data-bs-toggle="modal" data-bs-target="#modal-dep-update-{{ $department->id_department }}"><svg class="bi" width="16" height="16" style="vertical-align: -.125em;"><use xlink:href="#update"/></svg>
                                                Edit
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item d-flex gap-2 align-items-center" href="#" data-bs-toggle="modal" data-bs-target="#modal-dep-delete-{{ $department->id_department }}"><svg class="bi" width="16" height="16" style="vertical-align: -.125em;"><use xlink:href="#delete"/></svg>
                                                Delete
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7">Tidak ada Jabatan yang terdaftar</td>
                        </tr>
                        @endforelse
                    </tbody>
                    <tfoot class="table-group-divider table-secondary">
                        <tr>
                            <td colspan="7">Total Data: <b>{{ $departments->count() }}</b> Jabatan</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-lg"></i>
                    Tutup
                </button>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-dep-create">
                    <i class="bi bi-node-plus"></i>
                    Tambah
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-dep-create" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('masters.departments.store') }}" method="POST" enctype="multipart/form-data">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Tambah Jabatan</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @if (Session::get('modal_redirect') == 'modal-dep-create')
                    @include('Templates.Includes.Components.alert')
                    @endif
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Jabatan</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="description">Deskripsi</label>
                        <textarea class="form-control" name="description" id="description" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#modal-dep-view">
                        <i class="bi bi-backspace"></i>
                        Kembali
                    </button>
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">
                        <i class="bi bi-x-lg"></i>
                        Batal
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-plus-lg"></i>
                        Tambah
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

    @foreach ($departments as $department)
    <div class="modal fade" id="modal-dep-update-{{ $department->id_department }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('masters.departments.update', $department->id_department) }}" method="POST" enctype="multipart/form-data">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Ubah Jabatan ({{ $department->id_department }})</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        @if (Session::get('modal_redirect') == 'modal-dep-update')
                        @include('Templates.Includes.Components.alert')
                        @endif
                        @csrf @method('PUT')
                        <div class="mb-3">
                            <label for="name" class="form-label">Nama Jabatan</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ $department->name }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="description">Deskripsi</label>
                            <textarea class="form-control" name="description" id="description" rows="3">{{ $department->description }}</textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#modal-dep-view">
                            <i class="bi bi-backspace"></i>
                            Kembali
                        </button>
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">
                            <i class="bi bi-x-lg"></i>
                            Batal
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-plus-lg"></i>
                            Ubah
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-dep-delete-{{ $department->id_department }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('masters.departments.destroy', $department->id_department) }}" method="POST" enctype="multipart/form-data">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Hapus Data Jabatan ({{ $department->id_department}})</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-warning" role="alert">
                            <i class="bi bi-exclamation-triangle-fill"></i> <b>PERHATIAN</b>
                            <br/>
                            Apakah anda ingin menghapus Jabatan tersebut?
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#modal-dep-view">
                            <i class="bi bi-backspace"></i>
                            Tidak
                        </button>
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="bi bi-check-lg"></i>
                            Ya
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endforeach
@endif

@if (Request::is('masters/users'))
<div class="modal fade" id="modal-usr-create" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('masters.users.store') }}" method="POST" enctype="multipart/form-data">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Tambah Pengguna</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @if (Session::get('modal_redirect') == 'modal-usr-create')
                    @include('Templates.Includes.Components.alert')
                    @endif
                    @csrf
                    <div class="mb-3">
                        <label for="username" class="form-label">User Name</label>
                        <input type="text" class="form-control" id="username" name="username" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">E-Mail</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <div class="mb-3">
                        <label for="part" class="form-label">Bagian / Jenis Akun</label>
                        <select class="form-select" id="part" name="part" required>
                            <option selected disabled>---Pilih Bagian / Jenis Akun---</option>
                            <option value="Admin" {{ old('part') == 'Admin' ? 'selected' : null }}>Administrator (Kepegawaian)</option>
                            <option value="KBU" {{ old('part') == 'KBU' ? 'selected' : null }}>Kepala Bagian Umum</option>
                            <option value="KTT" {{ old('part') == 'KTT' ? 'selected' : null }}>Ketua Tim Teknis</option>
                            <option value="KBPS" {{ old('part') == 'KBPS' ? 'selected' : null }}>Kepala BPS Jawa Timur</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="id_officer" class="form-label">Pegawai / Pengguna Akun</label>
                        <select class="form-select" id="id_officer" name="id_officer" required>
                            <option selected disabled>---Pilih Pegawai---</option>
                            @foreach ($officers as $officer)
                            <option value="{{ $officer->id_officer }}" {{ old('id_officer') ==  $officer->id_officer ? 'selected' : null }}>{{ $officer->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">
                        <i class="bi bi-x-lg"></i>
                        Batal
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-plus-lg"></i>
                        Tambah
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

    @foreach ($users as $user)
    <div class="modal fade" id="modal-usr-update-{{ $user->id_user }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('masters.users.update', $user->id_user) }}" method="POST" enctype="multipart/form-data">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Ubah Pengguna ({{ $user->username }})</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        @if (Session::get('modal_redirect') == 'modal-usr-update')
                        @include('Templates.Includes.Components.alert')
                        @endif
                        @csrf @method('PUT')
                        <div class="mb-3">
                            <label for="username" class="form-label">User Name</label>
                            <input type="text" class="form-control" id="username" name="username" value="{{ $user->username }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">E-Mail</label>
                            <input type="email" class="form-control" id="email" name="email" value="{{ $user->email }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <div class="mb-3">
                            <label for="part" class="form-label">Bagian / Jenis Akun</label>
                            <select class="form-select" id="part" name="part" required>
                                <option selected disabled>---Pilih Bagian / Jenis Akun---</option>
                                <option value="Admin" {{ $user->part == 'Admin' ? 'selected' : null }}>Administrator (Kepegawaian)</option>
                                <option value="KBU" {{ $user->part == 'KBU' ? 'selected' : null }}>Kepala Bagian Umum</option>
                                <option value="KTT" {{ $user->part == 'KTT' ? 'selected' : null }}>Ketua Tim Teknis</option>
                                <option value="KBPS" {{ $user->part == 'KBPS' ? 'selected' : null }}>Kepala BPS Jawa Timur</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="id_officer" class="form-label">Pegawai / Pengguna Akun</label>
                            <select class="form-select" id="id_officer" name="id_officer" required>
                                <option selected disabled>---Pilih Pegawai---</option>
                                @foreach ($officers as $officer)
                                <option value="{{ $officer->id_officer }}" {{ $user->id_officer ==  $officer->id_officer ? 'selected' : null }}>{{ $officer->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">
                            <i class="bi bi-x-lg"></i>
                            Batal
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-plus-lg"></i>
                            Ubah
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-usr-delete-{{ $user->id_user }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('masters.users.destroy', $user->id_user) }}" method="POST" enctype="multipart/form-data">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Hapus Pengguna ({{ $user->username}})</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-warning" role="alert">
                            <i class="bi bi-exclamation-triangle-fill"></i> <b>PERHATIAN</b>
                            <br/>
                            Apakah anda ingin menghapus Pengguna tersebut?
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#modal-dep-view">
                            <i class="bi bi-backspace"></i>
                            Tidak
                        </button>
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="bi bi-check-lg"></i>
                            Ya
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endforeach
@endif

@if (Request::is('masters/criterias'))
<div class="modal fade" id="modal-crt-create" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('masters.criterias.store') }}" method="POST" enctype="multipart/form-data">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Tambah Kriteria</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @if (Session::get('modal_redirect') == 'modal-crt-create')
                    @include('Templates.Includes.Components.alert')
                    @endif
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Kriteria</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="type" class="form-label">Jenis Kriteria (Untuk Dimasukkan ke Tabel)</label>
                        <select class="form-select" id="type" name="type" required>
                            <option selected disabled>---Pilih Jenis Kriteria---</option>
                            <option value="Kehadiran" {{ old('type') == 'Kehadiran' ? 'selected' : null }}>Kehadiran</option>
                            <option value="Prestasi Kerja" {{ old('type') == 'Prestasi Kerja' ? 'selected' : null }}>Prestasi Kerja</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">
                        <i class="bi bi-x-lg"></i>
                        Batal
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-plus-lg"></i>
                        Tambah
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

    @foreach ($criterias as $criteria)
    <div class="modal fade" id="modal-crt-update-{{ $criteria->id_criteria }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('masters.criterias.update', $criteria->id_criteria) }}" method="POST" enctype="multipart/form-data">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Ubah Kriteria ({{ $criteria->id_criteria }})</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        @if (Session::get('modal_redirect') == 'modal-crt-update')
                        @include('Templates.Includes.Components.alert')
                        @endif
                        @csrf @method('PUT')
                        <div class="mb-3">
                            <label for="name" class="form-label">Nama Kriteria</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ $criteria->name }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="type" class="form-label">Jenis Kriteria (Untuk Dimasukkan ke Tabel)</label>
                            <select class="form-select" id="type" name="type" required>
                                <option selected disabled>---Pilih Jenis Kriteria---</option>
                                <option value="Kehadiran" {{ $criteria->type == 'Kehadiran' ? 'selected' : null }}>Kehadiran</option>
                                <option value="Prestasi Kerja" {{ $criteria->type == 'Prestasi Kerja' ? 'selected' : null }}>Prestasi Kerja</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">
                            <i class="bi bi-x-lg"></i>
                            Batal
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-plus-lg"></i>
                            Ubah
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-crt-delete-{{ $criteria->id_criteria }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('masters.criterias.destroy', $criteria->id_criteria) }}" method="POST" enctype="multipart/form-data">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Hapus Kriteria ({{ $criteria->id_criteria}})</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-warning" role="alert">
                            <i class="bi bi-exclamation-triangle-fill"></i> <b>PERHATIAN</b>
                            <br/>
                            Apakah anda ingin menghapus Kriteria?
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="bi bi-x-lg"></i>
                            Tidak
                        </button>
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="bi bi-check-lg"></i>
                            Ya
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-sub-create-{{ $criteria->id_criteria }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('masters.subcriterias.store') }}" method="POST" enctype="multipart/form-data">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Tambah Sub Kriteria</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        @if (Session::get('modal_redirect') == 'modal-sub-create')
                        @include('Templates.Includes.Components.alert')
                        @endif
                        @csrf
                        <div class="mb-3">
                            <label for="id_criteria" class="form-label">Kode Kriteria</label>
                            <input type="text" class="form-control" id="id_criteria" name="id_criteria" value="{{ $criteria->id_criteria }}" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="name" class="form-label">Nama Sub Kriteria</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="weight" class="form-label">Bobot</label>
                            <input type="text" class="form-control" id="weight" name="weight" required>
                        </div>
                        <div class="mb-3">
                            <label for="attribute" class="form-label">Atribut</label>
                            <select class="form-select" id="attribute" name="attribute" required>
                                <option selected disabled>---Pilih Atribut---</option>
                                <option value="Benefit" {{ old('attribute') == 'Benefit' ? 'selected' : null }}>Benefit</option>
                                <option value="Cost" {{ old('attribute') == 'Cost' ? 'selected' : null }}>Cost</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="level" class="form-label">Tingkat Kepentingan</label>
                            <select class="form-select" id="level" name="level" required>
                                <option selected disabled>---Pilih Atribut---</option>
                                <option value="1" {{ old('level') == '1' ? 'selected' : null }}>1. Sama Penting</option>
                                <option value="3" {{ old('level') == '3' ? 'selected' : null }}>3. Cukup Penting</option>
                                <option value="5" {{ old('level') == '5' ? 'selected' : null }}>5. Lebih Penting</option>
                                <option value="7" {{ old('level') == '7' ? 'selected' : null }}>7. Sangat Lebih Penting</option>
                                <option value="9" {{ old('level') == '9' ? 'selected' : null }}>9. Mutlak Lebih Penting</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="need" class="form-label">Apakah Dibutuhkan untuk Proses Karyawan Terbaik?</label>
                            <select class="form-select" id="need" name="need" required>
                                <option selected disabled>---Pilih---</option>
                                <option value="Ya" {{ old('attribute') == 'Ya' ? 'selected' : null }}>Ya</option>
                                <option value="Tidak" {{ old('attribute') == 'Tidak' ? 'selected' : null }}>Tidak</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">
                            <i class="bi bi-x-lg"></i>
                            Batal
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-plus-lg"></i>
                            Tambah
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

        @foreach ($subcriterias->where('id_criteria', $criteria->id_criteria) as $subcriteria)
        <div class="modal fade" id="modal-sub-update-{{ $subcriteria->id_sub_criteria }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{ route('masters.subcriterias.update', $subcriteria->id_sub_criteria) }}" method="POST" enctype="multipart/form-data">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="exampleModalLabel">Ubah Sub Kriteria ({{ $subcriteria->id_sub_criteria }})</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            @if (Session::get('modal_redirect') == 'modal-sub-update')
                            @include('Templates.Includes.Components.alert')
                            @endif
                            @csrf @method('PUT')
                            <div class="mb-3">
                                <label for="name" class="form-label">Nama Sub Kriteria</label>
                                <input type="text" class="form-control" id="name" name="name" value="{{ $subcriteria->name }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="weight" class="form-label">Bobot</label>
                                <input type="text" class="form-control" id="weight" name="weight" value="{{ $subcriteria->weight }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="attribute" class="form-label">Atribut</label>
                                <select class="form-select" id="attribute" name="attribute" required>
                                    <option selected disabled>---Pilih Atribut---</option>
                                    <option value="Benefit" {{ $subcriteria->attribute == 'Benefit' ? 'selected' : null }}>Benefit</option>
                                    <option value="Cost" {{ $subcriteria->attribute == 'Cost' ? 'selected' : null }}>Cost</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="level" class="form-label">Tingkat Kepentingan</label>
                                <select class="form-select" id="level" name="level" required>
                                    <option selected disabled>---Pilih Atribut---</option>
                                    <option value="1" {{ $subcriteria->level == '1' ? 'selected' : null }}>1. Sama Penting</option>
                                    <option value="3" {{ $subcriteria->level == '3' ? 'selected' : null }}>3. Cukup Penting</option>
                                    <option value="5" {{ $subcriteria->level == '5' ? 'selected' : null }}>5. Lebih Penting</option>
                                    <option value="7" {{ $subcriteria->level == '7' ? 'selected' : null }}>7. Sangat Lebih Penting</option>
                                    <option value="9" {{ $subcriteria->level == '9' ? 'selected' : null }}>9. Mutlak Lebih Penting</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="need" class="form-label">Apakah Dibutuhkan untuk Proses Karyawan Terbaik?</label>
                                <select class="form-select" id="need" name="need" required>
                                    <option selected disabled>---Pilih---</option>
                                    <option value="Ya" {{ $subcriteria->need == 'Ya' ? 'selected' : null }}>Ya</option>
                                    <option value="Tidak" {{ $subcriteria->need == 'Tidak' ? 'selected' : null }}>Tidak</option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">
                                <i class="bi bi-x-lg"></i>
                                Batal
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-plus-lg"></i>
                                Ubah
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="modal fade" id="modal-sub-delete-{{ $subcriteria->id_sub_criteria }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{ route('masters.subcriterias.destroy', $subcriteria->id_sub_criteria) }}" method="POST" enctype="multipart/form-data">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="exampleModalLabel">Hapus Sub Kriteria ({{ $subcriteria->id_sub_criteria}})</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="alert alert-warning" role="alert">
                                <i class="bi bi-exclamation-triangle-fill"></i> <b>PERHATIAN</b>
                                <br/>
                                Apakah anda ingin menghapus Sub Kriteria?
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                <i class="bi bi-x-lg"></i>
                                Tidak
                            </button>
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-danger">
                                <i class="bi bi-check-lg"></i>
                                Ya
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    @endforeach
@endif

@if (Request::is('masters/vote-criterias'))
<div class="modal fade" id="modal-vcr-create" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('masters.vote-criterias.store') }}" method="POST" enctype="multipart/form-data">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Tambah Kriteria</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @if (Session::get('modal_redirect') == 'modal-vcr-create')
                    @include('Templates.Includes.Components.alert')
                    @endif
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Kriteria</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">
                        <i class="bi bi-x-lg"></i>
                        Batal
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-plus-lg"></i>
                        Tambah
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

    @foreach ($criterias as $criteria)
    <div class="modal fade" id="modal-vcr-update-{{ $criteria->id_vote_criteria }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('masters.vote-criterias.update', $criteria->id_vote_criteria) }}" method="POST" enctype="multipart/form-data">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Ubah Kriteria ({{ $criteria->id_vote_criteria }})</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        @if (Session::get('modal_redirect') == 'modal-vcr-update')
                        @include('Templates.Includes.Components.alert')
                        @endif
                        @csrf @method('PUT')
                        <div class="mb-3">
                            <label for="name" class="form-label">Nama Kriteria</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ $criteria->name }}" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">
                            <i class="bi bi-x-lg"></i>
                            Batal
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-plus-lg"></i>
                            Ubah
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-vcr-delete-{{ $criteria->id_vote_criteria }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('masters.vote-criterias.destroy', $criteria->id_vote_criteria) }}" method="POST" enctype="multipart/form-data">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Hapus Kriteria ({{ $criteria->id_vote_criteria}})</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-warning" role="alert">
                            <i class="bi bi-exclamation-triangle-fill"></i> <b>PERHATIAN</b>
                            <br/>
                            Apakah anda ingin menghapus Kriteria?
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="bi bi-x-lg"></i>
                            Tidak
                        </button>
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="bi bi-check-lg"></i>
                            Ya
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endforeach
@endif

@if (Request::is('masters/periods'))
<div class="modal fade" id="modal-per-create" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('masters.periods.store') }}" method="POST" enctype="multipart/form-data">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Tambah Periode</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @if (Session::get('modal_redirect') == 'modal-per-create')
                    @include('Templates.Includes.Components.alert')
                    @endif
                    @csrf
                    <div class="mb-3">
                        <label for="month" class="form-label">Bulan</label>
                        <select class="form-select" id="month" name="month" required>
                            <option selected disabled>---Pilih Bulan---</option>
                            <option value="01" {{ old('month') == '01' ? 'selected' : null }}>Januari</option>
                            <option value="02" {{ old('month') == '02' ? 'selected' : null }}>Februari</option>
                            <option value="03" {{ old('month') == '03' ? 'selected' : null }}>Maret</option>
                            <option value="04" {{ old('month') == '04' ? 'selected' : null }}>April</option>
                            <option value="05" {{ old('month') == '05' ? 'selected' : null }}>Mei</option>
                            <option value="06" {{ old('month') == '06' ? 'selected' : null }}>Juni</option>
                            <option value="07" {{ old('month') == '07' ? 'selected' : null }}>Juli</option>
                            <option value="08" {{ old('month') == '08' ? 'selected' : null }}>Agustus</option>
                            <option value="09" {{ old('month') == '09' ? 'selected' : null }}>September</option>
                            <option value="10" {{ old('month') == '10' ? 'selected' : null }}>Oktober</option>
                            <option value="11" {{ old('month') == '11' ? 'selected' : null }}>November</option>
                            <option value="12" {{ old('month') == '12' ? 'selected' : null }}>Desember</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="year" class="form-label">Tahun</label>
                        <input type="number" class="form-control" id="year" name="year" min="2010" max="2099" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">
                        <i class="bi bi-x-lg"></i>
                        Batal
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-plus-lg"></i>
                        Tambah
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

    @foreach ($periods as $period)
    <div class="modal fade" id="modal-per-start-{{ $period->id_period }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('masters.periods.start', $period->id_period) }}" method="POST" enctype="multipart/form-data">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Mulai Proses Pemilihan Karyawan Terbaik ({{ $period->id_period}})</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        @csrf
                        <div class="alert alert-warning" role="alert">
                            <i class="bi bi-exclamation-triangle-fill"></i> <b>PERHATIAN</b>
                            <br/>
                            Apakah anda ingin memulai proses pemilihan karyawan terbaik? Anda tidak dapat melewatkan periode ini setelah proses tersebut dimulai.
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#modal-dep-view">
                            <i class="bi bi-backspace"></i>
                            Tidak
                        </button>
                        <button type="submit" class="btn btn-danger">
                            <i class="bi bi-check-lg"></i>
                            Ya
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-per-skip-{{ $period->id_period }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('masters.periods.skip', $period->id_period) }}" method="POST" enctype="multipart/form-data">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Lewati Periode ({{ $period->id_period}})</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        @csrf
                        <div class="alert alert-warning" role="alert">
                            <i class="bi bi-exclamation-triangle-fill"></i> <b>PERHATIAN</b>
                            <br/>
                            Apakah anda ingin melewati periode tersebut? Harap diperhatikan bahwa setelah melakukan proses tersebut anda tidak dapat membatalkan proses pelewatan periode tersebut.
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#modal-dep-view">
                            <i class="bi bi-backspace"></i>
                            Tidak
                        </button>
                        <button type="submit" class="btn btn-danger">
                            <i class="bi bi-check-lg"></i>
                            Ya
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-per-finish-{{ $period->id_period }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('masters.periods.finish', $period->id_period) }}" method="POST" enctype="multipart/form-data">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Selesai Proses Karyawan Terbaik ({{ $period->id_period}})</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        @csrf
                        <div class="alert alert-warning" role="alert">
                            <i class="bi bi-exclamation-triangle-fill"></i> <b>PERHATIAN</b>
                            <br/>
                            Apakah anda ingin menyelesaikan proses karyawan terbaik pada periode ini? Pastikan seluruh pegawai tersebut telah melakukan pemilihan pegawai yang akan dijadikan sebagai karyawan terbaik pada periode ini.
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#modal-dep-view">
                            <i class="bi bi-backspace"></i>
                            Tidak
                        </button>
                        <button type="submit" class="btn btn-danger">
                            <i class="bi bi-check-lg"></i>
                            Ya
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-per-delete-{{ $period->id_period }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('masters.periods.destroy', $period->id_period) }}" method="POST" enctype="multipart/form-data">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Hapus Periode ({{ $period->id_period}})</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-warning" role="alert">
                            <i class="bi bi-exclamation-triangle-fill"></i> <b>PERHATIAN</b>
                            <br/>
                            Apakah anda ingin menghapus periode tersebut?
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#modal-dep-view">
                            <i class="bi bi-backspace"></i>
                            Tidak
                        </button>
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="bi bi-check-lg"></i>
                            Ya
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endforeach
@endif

@if (Request::is('inputs/presences') || Request::is('inputs/kbu/performances') || Request::is('inputs/ktt/performances'))
@foreach ($periods as $period)
<div class="modal modal-xl fade" id="modal-inp-view-{{ $period->id_period }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Detail Data Kehadiran ({{ $period->name }})</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                        <thead>
                            <tr class="table-primary">
                                <th rowspan="2" class="col-1" scope="col">#</th>
                                <th rowspan="2" scope="col">Nama</th>
                                <th rowspan="2" scope="col">Jabatan</th>
                                @if ($countsub != 0)
                                <th colspan="{{ $countsub }}" scope="col">Kriteria</th>
                                @else
                                <th rowspan="2" scope="col">Kriteria</th>
                                @endif
                                <th rowspan="2" scope="col">Status</th>
                            </tr>
                            <tr class="table-secondary">
                                @foreach ($subcriterias as $subcriteria)
                                <th>{{ $subcriteria->name }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($officers as $officer)
                            <tr>
                                <th scope="row">{{ $loop->iteration }}</th>
                                <td>{{ $officer->name }}</td>
                                <td>{{ $officer->department->name }}</td>
                                @if ($countsub != 0)
                                    @foreach ($subcriterias as $subcriteria)
                                        @if (Auth::user()->part == "Admin") <!--(Request::is('inputs/presences'))-->
                                            @forelse ($presences->where('id_sub_criteria', $subcriteria->id_sub_criteria)->where('id_officer', $officer->id_officer)->where('id_period', $period->id_period) as $presence)
                                                <td>{{ $presence->input }}</td>
                                            @empty
                                                <td>0</td>
                                            @endforelse
                                        @elseif (Request::is('inputs/kbu/performances') || Request::is('inputs/ktt/performances'))
                                            @forelse ($performances->where('id_sub_criteria', $subcriteria->id_sub_criteria)->where('id_officer', $officer->id_officer)->where('id_period', $period->id_period) as $performance)
                                                <td>{{ $performance->input }}</td>
                                            @empty
                                                <td>0</td>
                                            @endforelse
                                        @endif
                                    @endforeach
                                @else
                                <td colspan="3">
                                    <span class="badge text-bg-secondary">Kriteria Kosong</span>
                                </td>
                                @endif
                                @if ($countsub != 0)
                                <td>
                                    @if (Auth::user()->part == "Admin") <!--(Request::is('inputs/presences'))-->
                                        @if ($presences->where('id_officer', $officer->id_officer)->where('id_period', $period->id_period)->count() == $countsub)
                                        <span class="badge text-bg-primary">Terisi Semua</span>
                                        @elseif ($presences->where('id_officer', $officer->id_officer)->where('id_period', $period->id_period)->count() == 0)
                                        <span class="badge text-bg-danger">Tidak Terisi</span>
                                        @else
                                        <span class="badge text-bg-warning">Terisi Sebagian</span>
                                        @endif
                                    @elseif (Request::is('inputs/kbu/performances') || Request::is('inputs/ktt/performances'))
                                        @if ($performances->where('id_officer', $officer->id_officer)->where('id_period', $period->id_period)->count() == $countsub)
                                        <span class="badge text-bg-primary">Terisi Semua</span>
                                        @elseif ($performances->where('id_officer', $officer->id_officer)->where('id_period', $period->id_period)->count() == 0)
                                        <span class="badge text-bg-danger">Tidak Terisi</span>
                                        @else
                                        <span class="badge text-bg-warning">Terisi Sebagian</span>
                                        @endif
                                    @endif
                                </td>
                                @endif
                            </tr>
                            @empty
                            <tr>
                                <td colspan="10">Tidak ada Pegawai yang terdaftar</td>
                            </tr>
                            @endforelse
                        </tbody>
                        <tfoot class="table-group-divider table-secondary">
                            <tr>
                                <td colspan="20">Total Data: <b>{{ $officers->count() }}</b> Pegawai</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-lg"></i>
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>

    @foreach ($officers as $officer)
    <div class="modal fade" id="modal-inp-create-{{ $period->id_period }}-{{ $officer->id_officer }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                @if (Auth::user()->part == "Admin") <!--(Request::is('inputs/presences'))-->
                <form action="{{ route('inputs.presences.store') }}" method="POST" enctype="multipart/form-data">
                @elseif (Auth::user()->part == "KBU") <!--(Request::is('inputs/kbu/performances'))-->
                <form action="{{ route('inputs.kbu.performances.store') }}" method="POST" enctype="multipart/form-data">
                @elseif (Auth::user()->part == "KTT") <!--(Request::is('inputs/ktt/performances'))-->
                <form action="{{ route('inputs.ktt.performances.store') }}" method="POST" enctype="multipart/form-data">
                @endif
                    <div class="modal-header">
                        @if (Auth::user()->part == "Admin") <!--(Request::is('inputs/presences'))-->
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Tambah Data Kehadiran ({{ $officer->name }})</h1>
                        @elseif (Request::is('inputs/kbu/performances') || Request::is('inputs/ktt/performances'))
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Tambah Data Prestasi Kerja ({{ $officer->name }})</h1>
                        @endif
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        @csrf
                        <div class="row mb-3">
                            <div class="col">
                                <label for="id_officer" class="form-label">Kode Pegawai</label>
                                <input type="text" class="form-control" id="id_officer" name="id_officer" value="{{ $officer->id_officer }}" readonly>
                            </div>
                            <div class="col">
                                <label for="id_period" class="form-label">Kode Periode</label>
                                <input type="text" class="form-control" id="id_period" name="id_period" value="{{ $period->id_period }}" readonly>
                            </div>
                        </div>
                        <hr/>
                        @forelse ($subcriterias as $subcriteria)
                        <div class="mb-3">
                            <label for="{{ $subcriteria->id_sub_criteria }}" class="form-label">{{ $subcriteria->name }}</label>
                            @if (Request::is('inputs/presences'))
                            <input type="number" class="form-control" id="{{ $subcriteria->id_sub_criteria }}" name="{{ $subcriteria->id_sub_criteria }}" min="0" max="31" required>
                            @elseif (Request::is('inputs/kbu/performances') || Request::is('inputs/ktt/performances'))
                            <input type="number" class="form-control" id="{{ $subcriteria->id_sub_criteria }}" name="{{ $subcriteria->id_sub_criteria }}" min="0" max="100" required>
                            @endif
                        </div>
                        @empty
                        <div class="alert alert-danger" role="alert">
                            Tidak ada data sub kriteria untuk Data Kehadiran
                        </div>
                        @endforelse
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">
                            <i class="bi bi-x-lg"></i>
                            Batal
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-plus-lg"></i>
                            Tambah
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-inp-update-{{ $period->id_period }}-{{ $officer->id_officer }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                @if (Auth::user()->part == "Admin") <!--(Request::is('inputs/presences'))-->
                <form action="{{ route('inputs.presences.update', $officer->id_officer) }}" method="POST" enctype="multipart/form-data">
                @elseif (Auth::user()->part == "KBU") <!--(Request::is('inputs/kbu/performances'))-->
                <form action="{{ route('inputs.kbu.performances.update', $officer->id_officer) }}" method="POST" enctype="multipart/form-data">
                @elseif (Auth::user()->part == "KTT") <!--(Request::is('inputs/ktt/performances'))-->
                <form action="{{ route('inputs.ktt.performances.update', $officer->id_officer) }}" method="POST" enctype="multipart/form-data">
                @endif
                    <div class="modal-header">
                        @if (Auth::user()->part == "Admin") <!--(Request::is('inputs/presences'))-->
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Ubah Data Kehadiran ({{ $officer->id_officer }})</h1>
                        @elseif (Request::is('inputs/kbu/performances') || Request::is('inputs/ktt/performances'))
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Ubah Data Prestasi Kerja ({{ $officer->id_officer }})</h1>
                        @endif
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        @csrf @method('PUT')
                        <div class="row mb-3">
                            <div class="col">
                                <label for="id_officer" class="form-label">Kode Pegawai</label>
                                <input type="text" class="form-control" id="id_officer" name="id_officer" value="{{ $officer->id_officer }}" readonly>
                            </div>
                            <div class="col">
                                <label for="id_period" class="form-label">Kode Periode</label>
                                <input type="text" class="form-control" id="id_period" name="id_period" value="{{ $period->id_period }}" readonly>
                            </div>
                        </div>
                        <hr/>
                        @forelse ($subcriterias as $subcriteria)
                            @if (Request::is('inputs/presences'))
                            @forelse ($presences->where('id_sub_criteria', $subcriteria->id_sub_criteria)->where('id_officer', $officer->id_officer)->where('id_period', $period->id_period) as $presence)
                            <div class="mb-3">
                                <label for="{{ $subcriteria->id_sub_criteria }}" class="form-label">{{ $subcriteria->name }}</label>
                                <input type="number" class="form-control" id="{{ $subcriteria->id_sub_criteria }}" name="{{ $subcriteria->id_sub_criteria }}" value="{{ $presence->input }}" min="0" max="31" required>
                            </div>
                            @empty
                            <div class="mb-3">
                                <label for="{{ $subcriteria->id_sub_criteria }}" class="form-label">{{ $subcriteria->name }}</label>
                                <input type="number" class="form-control" id="{{ $subcriteria->id_sub_criteria }}" name="{{ $subcriteria->id_sub_criteria }}" min="0" max="31" required>
                            </div>
                            @endforelse
                            @elseif (Request::is('inputs/kbu/performances') || Request::is('inputs/ktt/performances'))
                            @forelse ($performances->where('id_sub_criteria', $subcriteria->id_sub_criteria)->where('id_officer', $officer->id_officer)->where('id_period', $period->id_period) as $performance)
                            <div class="mb-3">
                                <label for="{{ $subcriteria->id_sub_criteria }}" class="form-label">{{ $subcriteria->name }}</label>
                                <input type="number" class="form-control" id="{{ $subcriteria->id_sub_criteria }}" name="{{ $subcriteria->id_sub_criteria }}" value="{{ $performance->input }}" min="0" max="100" required>
                            </div>
                            @empty
                            <div class="mb-3">
                                <label for="{{ $subcriteria->id_sub_criteria }}" class="form-label">{{ $subcriteria->name }}</label>
                                <input type="number" class="form-control" id="{{ $subcriteria->id_sub_criteria }}" name="{{ $subcriteria->id_sub_criteria }}" min="0" max="100" required>
                            </div>
                            @endforelse
                            @endif
                        @empty
                        <div class="alert alert-danger" role="alert">
                            Tidak ada data sub kriteria untuk Data Kehadiran
                        </div>
                        @endforelse
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">
                            <i class="bi bi-x-lg"></i>
                            Batal
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-plus-lg"></i>
                            Ubah
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-inp-view-{{ $period->id_period }}-{{ $officer->id_officer }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Ubah Data Kehadiran ({{ $officer->id_officer }})</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table class="table">
                    @foreach ($subcriterias as $subcriteria)
                        @if (Auth::user()->part == "Admin") <!--(Request::is('inputs/presences'))-->
                            @forelse ($presences->where('id_sub_criteria', $subcriteria->id_sub_criteria)->where('id_officer', $officer->id_officer)->where('id_period', $period->id_period) as $presence)
                            <tr>
                                <th scope="row">{{ $subcriteria->name }}</th>
                                <td>
                                    @if ($subcriteria->need == 'Ya')
                                    <b>{{ $presence->input }}</b>
                                    @else
                                    {{ $presence->input }}
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <th scope="row">{{ $subcriteria->name }}</th>
                                <td>
                                    @if ($subcriteria->need == 'Ya')
                                    <b>0</b>
                                    @else
                                    0
                                    @endif
                                </td>
                            </tr>
                            @endforelse
                        @elseif (Request::is('inputs/kbu/performances') || Request::is('inputs/ktt/performances'))
                            @forelse ($performances->where('id_sub_criteria', $subcriteria->id_sub_criteria)->where('id_officer', $officer->id_officer)->where('id_period', $period->id_period) as $performance)
                            <tr>
                                <th scope="row">{{ $subcriteria->name }}</th>
                                <td>
                                    @if ($subcriteria->need == 'Ya')
                                    <b>{{ $performance->input }}</b>
                                    @else
                                    {{ $performance->input }}
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <th scope="row">{{ $subcriteria->name }}</th>
                                <td>
                                    @if ($subcriteria->need == 'Ya')
                                    <b>0</b>
                                    @else
                                    0
                                    @endif
                                </td>
                            </tr>
                            @endforelse
                        @endif
                    @endforeach
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-lg"></i>
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-inp-delete-{{ $period->id_period }}-{{ $officer->id_officer }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                @if (Auth::user()->part == "Admin") <!--(Request::is('inputs/presences'))-->
                <form action="{{ route('inputs.presences.destroy', $officer->id_officer) }}" method="POST" enctype="multipart/form-data">
                @elseif (Auth::user()->part == "KBU") <!--(Request::is('inputs/kbu/performances'))-->
                <form action="{{ route('inputs.kbu.performances.destroy', $officer->id_officer) }}" method="POST" enctype="multipart/form-data">
                @elseif (Auth::user()->part == "KTT") <!--(Request::is('inputs/ktt/performances'))-->
                <form action="{{ route('inputs.ktt.performances.destroy', $officer->id_officer) }}" method="POST" enctype="multipart/form-data">
                @endif
                    <div class="modal-header">
                        @if (Auth::user()->part == "Admin") <!--(Request::is('inputs/presences'))-->
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Hapus Data Kehadiran ({{ $officer->id_officer}})</h1>
                        @elseif (Request::is('inputs/kbu/performances') || Request::is('inputs/ktt/performances'))
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Hapus Data Prestasi Kerja ({{ $officer->id_officer}})</h1>
                        @endif
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row mb-3">
                            <div class="col">
                                <label for="id_officer" class="form-label">Kode Pegawai</label>
                                <input type="text" class="form-control" id="id_officer" name="id_officer" value="{{ $officer->id_officer }}" readonly>
                            </div>
                            <div class="col">
                                <label for="id_period" class="form-label">Kode Periode</label>
                                <input type="text" class="form-control" id="id_period" name="id_period" value="{{ $period->id_period }}" readonly>
                            </div>
                        </div>
                        <hr/>
                        <div class="alert alert-warning" role="alert">
                            <i class="bi bi-exclamation-triangle-fill"></i> <b>PERHATIAN</b>
                            <br/>
                            Apakah anda ingin menghapus data tersebut? Ini akan berpengaruh dengan total penilaian.
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#modal-dep-view">
                            <i class="bi bi-backspace"></i>
                            Tidak
                        </button>
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="bi bi-check-lg"></i>
                            Ya
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endforeach
@endforeach
@endif

@if (Request::is('inputs/presences') || Request::is('inputs/kbu/performances') || Request::is('inputs/ktt/performances') || Request::is('inputs/scores'))
    @foreach ($periods as $period)
    <div class="modal modal-xl fade" id="modal-all-view-{{ $period->id_period }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Detail Seluruh Data ({{ $period->name }})</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered">
                            <thead>
                                <tr class="table-primary">
                                    <th rowspan="4" class="col-1" scope="col">#</th>
                                    <th rowspan="4" scope="col">Nama</th>
                                    <th rowspan="4" scope="col">Jabatan</th>
                                    <th colspan="{{ $countprs + $countprf }}" scope="col">Kriteria</th>
                                    <th colspan="2" rowspan="3" scope="col">Status Data</th>
                                </tr>
                                <tr class="table-primary">
                                    <th colspan="{{ $countprs }}" scope="col">Data Kehadiran</th>
                                    <th colspan="{{ $countprf }}" scope="col">Data Prestasi Kerja</th>
                                </tr>
                                <tr class="table-primary">
                                    @foreach ($criterias as $criteria)
                                    <th colspan="{{ $allsubcriterias->where('id_criteria', $criteria->id_criteria)->count() }}" scope="col">{{ $criteria->name }}</th>
                                    @endforeach
                                </tr>
                                <tr class="table-secondary">
                                    @foreach ($subcritprs as $scprs)
                                    <th>{{ $scprs->name }}</th>
                                    @endforeach
                                    @foreach ($subcritprf as $scprf)
                                    <th>{{ $scprf->name }}</th>
                                    @endforeach
                                    <th scope="col">Data Kehadiran</th>
                                    <th scope="col">Data Prestasi Kerja</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($officers as $officer)
                                <tr>
                                    <th scope="row">{{ $loop->iteration }}</th>
                                    <td>{{ $officer->name }}</td>
                                    <td>{{ $officer->department->name }}</td>
                                    @foreach ($subcritprs as $scprs)
                                        @forelse ($presences->where('id_sub_criteria', $scprs->id_sub_criteria)->where('id_officer', $officer->id_officer)->where('id_period', $period->id_period) as $presence)
                                        <td>{{ $presence->input }}</td>
                                        @empty
                                            <td>0</td>
                                        @endforelse
                                    @endforeach
                                    @foreach ($subcritprf as $scprf)
                                        @forelse ($performances->where('id_sub_criteria', $scprf->id_sub_criteria)->where('id_officer', $officer->id_officer)->where('id_period', $period->id_period) as $performance)
                                        <td>{{ $performance->input }}</td>
                                        @empty
                                            <td>0</td>
                                        @endforelse
                                    @endforeach
                                    <td>
                                        @if ($presences->where('id_officer', $officer->id_officer)->where('id_period', $period->id_period)->count() == $countprs)
                                        <span class="badge text-bg-primary">Terisi Semua</span>
                                        @elseif ($presences->where('id_officer', $officer->id_officer)->where('id_period', $period->id_period)->count() == 0)
                                        <span class="badge text-bg-danger">Tidak Terisi</span>
                                        @else
                                        <span class="badge text-bg-warning">Terisi Sebagian</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($performances->where('id_officer', $officer->id_officer)->where('id_period', $period->id_period)->count() == $countprf)
                                        <span class="badge text-bg-primary">Terisi Semua</span>
                                        @elseif ($performances->where('id_officer', $officer->id_officer)->where('id_period', $period->id_period)->count() == 0)
                                        <span class="badge text-bg-danger">Tidak Terisi</span>
                                        @else
                                        <span class="badge text-bg-warning">Terisi Sebagian</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="10">Tidak ada Pegawai yang terdaftar</td>
                                </tr>
                                @endforelse
                            </tbody>
                            <tfoot class="table-group-divider table-secondary">
                                <tr>
                                    <td colspan="20">Total Data: <b>{{ $officers->count() }}</b> Pegawai</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-lg"></i>
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endforeach
@endif

@if (Request::is('inputs/scores'))
    @foreach ($periods as $period)
    <div class="modal modal-lg fade" id="modal-stt-view-{{ $period->id_period }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Cek Status ({{ $period->name }})</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered">
                            <thead>
                                <tr class="table-primary">
                                    <th class="col-1" scope="col">#</th>
                                    <th scope="col">Nama</th>
                                    <th scope="col">Jabatan</th>
                                    <th scope="col">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($officers as $officer)
                                <tr>
                                    <th scope="row">{{ $loop->iteration }}</th>
                                    <td>{{ $officer->name }}</td>
                                    <td>{{ $officer->department->name }}</td>
                                    <td>
                                        @forelse ($status->where('id_officer', $officer->id_officer)->where('id_period', $period->id_period) as $s)
                                            @if ($s->status == 'Pending')
                                            <span class="badge text-bg-primary">Belum Diperiksa</span>
                                            @elseif ($s->status == 'In Review')
                                            <span class="badge text-bg-warning">Dalam Pemeriksaan</span>
                                            @elseif ($s->status == 'Final')
                                            <span class="badge text-bg-success">Hasil Akhir</span>
                                            @elseif ($s->status == 'Need Fix')
                                            <span class="badge text-bg-danger">Perlu Perbaikan</span>
                                            @endif
                                        @empty
                                        <span class="badge text-bg-secondary">Blank</span>
                                        @endforelse
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="10">Tidak ada Pegawai yang terdaftar</td>
                                </tr>
                                @endforelse
                            </tbody>
                            <tfoot class="table-group-divider table-secondary">
                                <tr>
                                    <td colspan="20">Total Data: <b>{{ $officers->count() }}</b> Pegawai</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-lg"></i>
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-scr-finish-{{ $period->id_period }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('inputs.scores.finish', $period->id_period) }}" method="POST" enctype="multipart/form-data">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Kunci Data ({{ $period->name }})</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        @csrf
                        <div class="mb-3">
                            <div class="col">
                                <input type="text" class="form-control" id="id_period" name="id_period" value="{{ $period->id_period }}" hidden>
                            </div>
                        </div>
                        <div class="alert alert-warning" role="alert">
                            <i class="bi bi-exclamation-triangle-fill"></i> <b>PERHATIAN</b>
                            <br/>
                            Apakah anda telah selesai melakukan validasi dan mulai pelaksanaan voting? Proses ini akan mengunci perubahan yang ada di periode tersebut. Jika sudah dikunci, data tersebut tidak dapat diubah dan dihapus kembali untuk menghindari hal-hal yang tidak diinginkan.
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="bi bi-x-lg"></i>
                            Tidak
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg"></i>
                            Ya
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-scr-get-{{ $period->id_period }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('inputs.scores.get', $period->id_period) }}" method="POST" enctype="multipart/form-data">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Ambil Data ({{ $period->name}})</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        @csrf
                        <div class="mb-3">
                            <div class="col">
                                <input type="text" class="form-control" id="id_period" name="id_period" value="{{ $period->id_period }}" hidden>
                            </div>
                        </div>
                        <div class="alert alert-warning" role="alert">
                            <i class="bi bi-exclamation-triangle-fill"></i> <b>PERHATIAN</b>
                            <br/>
                            Apakah anda ingin mengambil data hasil analisis pada periode ini? Jika ya, data tersebut akan menghapus data sebelumnya dan menggantikan dengan yang baru.
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="bi bi-x-lg"></i>
                            Tidak
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg"></i>
                            Ya
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

        @foreach ($scores as $score)
        <div class="modal fade" id="modal-scr-yes-{{ $period->id_period }}-{{ $score->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{ route('inputs.scores.yes', $score->id) }}" method="POST" enctype="multipart/form-data">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="exampleModalLabel">Ambil Data ({{ $score->id}})</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            @csrf
                            <div class="mb-3">
                                <div class="col">
                                    <input type="text" class="form-control" id="id" name="id" value="{{ $score->id }}" hidden>
                                </div>
                            </div>
                            <div class="alert alert-warning" role="alert">
                                <i class="bi bi-exclamation-triangle-fill"></i> <b>PERHATIAN</b>
                                <br/>
                                Apakah anda ingin menyetujui hasil penilaian ini? Jika ya, data tersebut akan disimpan sebagai hasil akhir.
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                <i class="bi bi-x-lg"></i>
                                Tidak
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-lg"></i>
                                Ya
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="modal fade" id="modal-scr-no-{{ $period->id_period }}-{{ $score->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{ route('inputs.scores.no', $score->id) }}" method="POST" enctype="multipart/form-data">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="exampleModalLabel">Ambil Data ({{ $score->id}})</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            @csrf
                            <div class="mb-3">
                                <div class="col">
                                    <input type="text" class="form-control" id="id" name="id" value="{{ $score->id }}" hidden>
                                </div>
                            </div>
                            <div class="alert alert-warning" role="alert">
                                <i class="bi bi-exclamation-triangle-fill"></i> <b>PERHATIAN</b>
                                <br/>
                                Apakah anda ingin tidak menyetujui hasil penilaian ini? Jika ya, data tersebut akan dikembalikan oleh penilai.
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                <i class="bi bi-x-lg"></i>
                                Tidak
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-lg"></i>
                                Ya
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    @endforeach
@endif

@if (Request::is('inputs/votes/*'))
<div class="modal modal-xl fade" id="modal-chk-view-{{ $prd_select->id_period }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Cek Pegawai ({{ $prd_select->name }})</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                        <thead>
                            <tr class="table-primary">
                                <th rowspan="2" class="col-1" scope="col">#</th>
                                <th rowspan="2" scope="col">Nama</th>
                                <th rowspan="2" scope="col">Jabatan</th>
                                <th colspan="{{ $criterias->count() }}" scope="col">Kriteria</th>
                                <th rowspan="2" scope="col">Status</th>
                            </tr>
                            <tr class="table-secondary">
                                @foreach ($criterias as $criteria)
                                <th scope="col">{{ $criteria->name }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($fil_offs as $officer)
                            <tr>
                                <th scope="row">{{ $loop->iteration }}</th>
                                <td>{{ $officer->name }}</td>
                                <td>{{ $officer->department->name }}</td>
                                @foreach ($criterias as $criteria)
                                <td>
                                    @if ($checks->where('id_officer', $officer->id_officer)->where('id_period', $prd_select->id_period)->where('id_vote_criteria', $criteria->id_vote_criteria)->count() == 0)
                                    <span class="badge text-bg-danger">Belum Memilih</span>
                                    @else
                                    <span class="badge text-bg-success">Sudah Memilih</span>
                                    @endif
                                </td>
                                @endforeach
                                <td>
                                    @if ($checks->where('id_officer', $officer->id_officer)->where('id_period', $prd_select->id_period)->count() == 0)
                                    <span class="badge text-bg-danger">Belum Memilih</span>
                                    @elseif ($checks->where('id_officer', $officer->id_officer)->where('id_period', $prd_select->id_period)->count() < $criterias->count())
                                    <span class="badge text-bg-warning">Sebagian Memilih</span>
                                    @else
                                    <span class="badge text-bg-success">Sudah Memilih</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="10">Tidak ada Pegawai yang terdaftar</td>
                            </tr>
                            @endforelse
                        </tbody>
                        <tfoot class="table-group-divider table-secondary">
                            <tr>
                                <td colspan="20">Total Data: <b>{{ $officers->count() }}</b> Pegawai</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-lg"></i>
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal modal-xl fade" id="modal-chk-all-{{ $prd_select->id_period }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Cek Pegawai ({{ $prd_select->name }})</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                        <thead>
                            <tr class="table-primary">
                                <th rowspan="2" class="col-1" scope="col">#</th>
                                <th rowspan="2" scope="col">Nama</th>
                                <th rowspan="2" scope="col">Jabatan</th>
                                <th colspan="{{ $criterias->count() }}" scope="col">Kriteria</th>
                                <th rowspan="2" scope="col">Status</th>
                            </tr>
                            <tr class="table-secondary">
                                @foreach ($criterias as $criteria)
                                <th scope="col">{{ $criteria->name }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($officers as $officer)
                            <tr>
                                <th scope="row">{{ $loop->iteration }}</th>
                                <td>{{ $officer->name }}</td>
                                <td>{{ $officer->department->name }}</td>
                                @foreach ($criterias as $criteria)
                                <td>
                                    @if ($checks->where('id_officer', $officer->id_officer)->where('id_period', $prd_select->id_period)->where('id_vote_criteria', $criteria->id_vote_criteria)->count() == 0)
                                    <span class="badge text-bg-danger">Belum Memilih</span>
                                    @else
                                    <span class="badge text-bg-success">Sudah Memilih</span>
                                    @endif
                                </td>
                                @endforeach
                                <td>
                                    @if ($checks->where('id_officer', $officer->id_officer)->where('id_period', $prd_select->id_period)->count() == 0)
                                    <span class="badge text-bg-danger">Belum Memilih</span>
                                    @elseif ($checks->where('id_officer', $officer->id_officer)->where('id_period', $prd_select->id_period)->count() < $criterias->count())
                                    <span class="badge text-bg-warning">Sebagian Memilih</span>
                                    @else
                                    <span class="badge text-bg-success">Sudah Memilih</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="10">Tidak ada Pegawai yang terdaftar</td>
                            </tr>
                            @endforelse
                        </tbody>
                        <tfoot class="table-group-divider table-secondary">
                            <tr>
                                <td colspan="20">Total Data: <b>{{ $officers->count() }}</b> Pegawai</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-lg"></i>
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>
@endif

@if (Request::is('analysis/saw*'))
<div class="modal fade" id="modal-saw-periods" tabindex="-1" aria-labelledby="modalsaw" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="modalsaw">Pilih Periode (SAW)</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info" role="alert">
                    <i class="bi bi-info-circle-fill"></i> <strong>INFO</strong>
                    <br/>
                    Pilih tahun untuk melihat hasil analisis secara langsung. Fitur ini tidak memerlukan input.
                </div>
                <div class="row g-3 align-items-center">
                    <div class="col-auto">
                        <label for="tahun_saw_dl" class="col-form-label">Pilih Tahun</label>
                    </div>
                    <div class="col-auto dropend">
                        <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-calendar3-event-fill"></i>
                        </button>
                        <ul class="dropdown-menu" style="max-height: 180px; overflow-y: auto;">
                            @forelse ( $periods as $period )
                            <li><a class="dropdown-item" href="/analysis/saw/{{ $period->id_period }}">{{ $period->name }}</a></li>
                            @empty
                            <li><a class="dropdown-item disabled" href="#" aria-disabled="true">Tidak ada data</a></li>
                            @endforelse
                        </ul>
                    </div>
                    <div class="col-auto">
                        <span id="tahun_help_saw_dl" class="form-text">
                            Antara 2010 sampai sekarang
                        </span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endif

@if (Request::is('analysis/wp*'))
<div class="modal fade" id="modal-wp-periods" tabindex="-1" aria-labelledby="modalwp" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="modalwp">Pilih Periode (WP)</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info" role="alert">
                    <i class="bi bi-info-circle-fill"></i> <strong>INFO</strong>
                    <br/>
                    Pilih tahun untuk melihat hasil analisis secara langsung. Fitur ini tidak memerlukan input.
                </div>
                <div class="row g-3 align-items-center">
                    <div class="col-auto">
                        <label for="tahun_saw_dl" class="col-form-label">Pilih Tahun</label>
                    </div>
                    <div class="col-auto dropend">
                        <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-calendar3-event-fill"></i>
                        </button>
                        <ul class="dropdown-menu" style="max-height: 180px; overflow-y: auto;">
                            @forelse ( $periods as $period )
                            <li><a class="dropdown-item" href="/analysis/wp/{{ $period->id_period }}">{{ $period->name }}</a></li>
                            @empty
                            <li><a class="dropdown-item disabled" href="#" aria-disabled="true">Tidak ada data</a></li>
                            @endforelse
                        </ul>
                    </div>
                    <div class="col-auto">
                        <span id="tahun_help_saw_dl" class="form-text">
                            Antara 2010 sampai sekarang
                        </span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endif
