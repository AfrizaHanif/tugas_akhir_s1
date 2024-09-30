<!--CREATE USER-->
<div class="modal modal-lg fade" id="modal-usr-create" data-bs-backdrop="static" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.masters.users.store') }}" method="POST" enctype="multipart/form-data" id="form-usr-create">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Tambah Pengguna</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="close-usr-create"></button>
                </div>
                <div class="modal-body">
                    @csrf
                    <div class="row justify-content-center g-4">
                        <div class="col-md-7">
                            <div class="position-sticky" style="top: 2rem;">
                                @if (Session::get('modal_redirect') == 'modal-usr-create')
                                @include('Templates.Includes.Components.alert')
                                @endif
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
                                        <option selected disabled value="">---Pilih Bagian / Jenis Akun---</option>
                                        <option value="Admin" {{ old('part') == 'Admin' ? 'selected' : null }}>Administrator (Kepegawaian)</option>
                                        <option value="KBPS" {{ old('part') == 'KBPS' ? 'selected' : null }}>Kepala BPS Jawa Timur</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="id_officer" class="form-label">Pegawai / Pengguna Akun</label>
                                    <select class="form-select" id="id_officer" name="id_officer" required>
                                        <option selected disabled value="">---Pilih Pegawai---</option>
                                        @foreach ($subteams as $subteam)
                                        @if (count($officers->where('id_sub_team_1', $subteam->id_sub_team)) != 0)
                                        <option disabled value="">//{{ $subteam->name}}</option>
                                            @foreach ($officers->where('id_sub_team_1', $subteam->id_sub_team) as $officer)
                                            <option value="{{ $officer->id_officer }}" {{ old('id_officer') ==  $officer->id_officer ? 'selected' : null }}>{{ $officer->name }}</option>
                                            @endforeach
                                        @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="position-sticky" style="top: 2rem;">
                                <div class="alert alert-info" role="alert">
                                    <i class="bi bi-info-circle-fill"></i> <strong>CARA PENGISIAN</strong>
                                    <ol>
                                        <li>Username dan E-Mail tidak boleh sama</li>
                                        <li>Bagian:</li>
                                        <ol>
                                            <li>Kepegawaian: Pilih Admin</li>
                                            <li>Ketua BPS Jatim: Pilih KBPS</li>
                                        </ol>
                                        <li>Untuk KBPS, hanya dapat dipakai oleh satu pegawai</li>
                                    </ol>
                                </div>
                            </div>
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
@foreach ($users as $user)
<!--UPDATE USER-->
<div class="modal modal-lg fade" id="modal-usr-update-{{ $user->id_user }}" data-bs-backdrop="static" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.masters.users.update', $user->id_user) }}" method="POST" enctype="multipart/form-data">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Ubah Pengguna ({{ $user->username }})</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @csrf @method('PUT')
                    <div class="row justify-content-center g-4">
                        <div class="col-md-7">
                            <div class="position-sticky" style="top: 2rem;">
                                @if (Session::get('modal_redirect') == 'modal-usr-update')
                                @include('Templates.Includes.Components.alert')
                                @endif
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
                                    <input type="password" class="form-control" id="password" name="password">
                                </div>
                                <div class="mb-3">
                                    <label for="part" class="form-label">Bagian / Jenis Akun</label>
                                    <select class="form-select" id="part" name="part" required>
                                        <option selected disabled value="">---Pilih Bagian / Jenis Akun---</option>
                                        <option value="Admin" {{ $user->part == 'Admin' ? 'selected' : null }}>Administrator (Kepegawaian)</option>
                                        <option value="KBPS" {{ $user->part == 'KBPS' ? 'selected' : null }}>Kepala BPS Jawa Timur</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="id_officer" class="form-label">Pegawai / Pengguna Akun</label>
                                    <select class="form-select" id="id_officer" name="id_officer" required>
                                        <option selected disabled value="">---Pilih Pegawai---</option>
                                        @foreach ($subteams as $subteam)
                                        @if (count($officers->where('id_sub_team_1', $subteam->id_sub_team)) != 0)
                                        <option disabled value="">//{{ $subteam->name}}</option>
                                            @foreach ($officers->where('id_sub_team_1', $subteam->id_sub_team) as $officer)
                                            <option value="{{ $officer->id_officer }}" {{ $user->id_officer ==  $officer->id_officer ? 'selected' : null }}>{{ $officer->name }}</option>
                                            @endforeach
                                        @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="position-sticky" style="top: 2rem;">
                                <div class="alert alert-info" role="alert">
                                    <i class="bi bi-info-circle-fill"></i> <strong>CARA PENGISIAN</strong>
                                    <ol>
                                        <li>Username dan E-Mail tidak boleh sama</li>
                                        <li>Bagian:</li>
                                        <ol>
                                            <li>Kepegawaian: Pilih Admin</li>
                                            <li>Ketua BPS Jatim: Pilih KBPS</li>
                                        </ol>
                                        <li>Untuk KBPS, hanya dapat dipakai oleh satu pegawai</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">
                        <i class="bi bi-x-lg"></i>
                        Batal
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-pencil"></i>
                        Ubah
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<!--DELETE USER-->
<div class="modal fade" id="modal-usr-delete-{{ $user->id_user }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.masters.users.destroy', $user->id_user) }}" method="POST" enctype="multipart/form-data">
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
