<!--CREATE USER-->
<div class="modal modal-lg fade" id="modal-usr-create" data-bs-backdrop="static" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Tambah Pengguna</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="close-usr-create"></button>
                </div>
                <div class="modal-body">
                    <form id="form-usr-create" action="{{ route('admin.masters.users.store') }}" method="POST" enctype="multipart/form-data" id="form-usr-create">
                        @csrf
                        <div class="row justify-content-center g-4">
                            <div class="col-md-7">
                                <div class="position-sticky" style="top: 0rem;">
                                    @if (Session::get('modal_redirect') == 'modal-usr-create')
                                    @include('Templates.Includes.Components.alert')
                                    @endif
                                    <div class="mb-3">
                                        <label for="officer" class="form-label">Pegawai</label>
                                        <select class="form-select" id="officer" name="officer" required>
                                            <option selected disabled value="">---Pilih Pegawai---</option>
                                            @foreach ($officers as $officer)
                                                @if (empty($users->where('nip', $officer->id_officer)->first()))
                                                <option value="{{ $officer->id_officer }}" {{ old('officer') ==  $officer->id_officer ? 'selected' : null }}>({{ $officer->id_officer }}) {{ $officer->name }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="part" class="form-label">Bagian / Jenis Akun</label>
                                        <select class="form-select" id="part" name="part" required>
                                            <option selected disabled value="">---Pilih Bagian / Jenis Akun---</option>
                                            <option value="Pegawai" {{ old('part') == 'Pegawai' ? 'selected' : null }}>Pegawai</option>
                                            <option value="Admin" {{ old('part') == 'Admin' ? 'selected' : null }}>Administrator (Kepegawaian)</option>
                                            <option value="KBPS" {{ old('part') == 'KBPS' ? 'selected' : null }}>Kepala BPS Jawa Timur</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="position-sticky" style="top: 0rem;">
                                    <div class="alert alert-info" role="alert">
                                        <i class="bi bi-info-circle-fill"></i> <strong>CARA PENGISIAN</strong>
                                        <ol>
                                            <li>Satu pegawai hanya dapat memakai satu user.</li>
                                            <li>Bagian:</li>
                                            <ol>
                                                <li>Kepegawaian: Pilih Admin</li>
                                                <li>Ketua BPS Jatim: Pilih KBPS</li>
                                                <li>Pegawai Biasa: Pilih Pegawai</li>
                                            </ol>
                                            <li>Untuk KBPS, hanya dapat dipakai oleh satu pegawai</li>
                                            <li>Password default: "bps3500"</li>
                                        </ol>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">
                        <i class="bi bi-x-lg"></i>
                        Batal
                    </button>
                    <button type="submit"form="form-usr-create"  class="btn btn-primary">
                        <i class="bi bi-plus-lg"></i>
                        Tambah
                    </button>
                </div>
        </div>
    </div>
</div>
@foreach ($users as $user)
<!--UPDATE USER-->
<div class="modal modal-lg fade" id="modal-usr-update-{{ $user->id_user }}" data-bs-backdrop="static" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Ubah Pengguna ({{ $user->name }})</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="form-usr-update-{{ $user->id_user }}" action="{{ route('admin.masters.users.update', $user->id_user) }}" method="POST" enctype="multipart/form-data">
                        @csrf @method('PUT')
                        <div class="row justify-content-center g-4">
                            <div class="col-md-7">
                                <div class="position-sticky" style="top: 0rem;">
                                    @if (Session::get('modal_redirect') == 'modal-usr-update')
                                    @include('Templates.Includes.Components.alert')
                                    @endif
                                    <div class="mb-3">
                                        <label for="password" class="form-label">Reset Password?</label>
                                        <br/>
                                        <div class="btn-group" role="group" aria-label="Basic radio toggle button group" style="display: flex;">
                                            <input type="radio" class="btn-check" name="password" id="password_no-{{ $user->id_user }}" value="no" checked>
                                            <label class="btn btn-outline-primary" for="password_no-{{ $user->id_user }}" style="flex: 1">Tidak</label>
                                            <input type="radio" class="btn-check" name="password" id="password_yes-{{ $user->id_user }}" value="yes">
                                            <label class="btn btn-outline-danger" for="password_yes-{{ $user->id_user }}" style="flex: 1">Ya</label>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="part" class="form-label">Bagian / Jenis Akun</label>
                                        <select class="form-select" id="part" name="part" required>
                                            <option selected disabled value="">---Pilih Bagian / Jenis Akun---</option>
                                            <option value="Pegawai" {{ $user->part == 'Pegawai' ? 'selected' : null }}>Pegawai</option>
                                            <option value="Admin" {{ $user->part == 'Admin' ? 'selected' : null }}>Administrator (Kepegawaian)</option>
                                            <option value="KBPS" {{ $user->part == 'KBPS' ? 'selected' : null }}>Kepala BPS Jawa Timur</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="position-sticky" style="top: 0rem;">
                                    <div class="alert alert-info" role="alert">
                                        <i class="bi bi-info-circle-fill"></i> <strong>CARA PENGISIAN</strong>
                                        <ol>
                                            <li>Bagian:</li>
                                            <ol>
                                                <li>Kepegawaian: Pilih Admin</li>
                                                <li>Ketua BPS Jatim: Pilih KBPS</li>
                                                <li>Pegawai Biasa: Pilih Pegawai</li>
                                            </ol>
                                            <li>Untuk KBPS, hanya dapat dipakai oleh satu pegawai</li>
                                        </ol>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">
                        <i class="bi bi-x-lg"></i>
                        Batal
                    </button>
                    <button type="submit" form="form-usr-update-{{ $user->id_user }}" class="btn btn-primary">
                        <i class="bi bi-pencil"></i>
                        Ubah
                    </button>
                </div>
        </div>
    </div>
</div>
<!--DELETE USER-->
<div class="modal fade" id="modal-usr-delete-{{ $user->id_user }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Hapus Pengguna</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="form-usr-delete-{{ $user->id_user }}" action="{{ route('admin.masters.users.destroy', $user->id_user) }}" method="POST" enctype="multipart/form-data">
                    @csrf @method('DELETE')
                    <div class="alert alert-warning" role="alert">
                        <i class="bi bi-exclamation-triangle-fill"></i> <b>PERHATIAN</b>
                        <br/>
                        Apakah anda ingin menghapus Pengguna dengan nama user <b>{{ $user->username }}</b>?
                        <ul>
                            <li>Pengguna yang sudah dihapus tidak dapat melakukan login ke aplikasi ini.</li>
                        </ul>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#modal-dep-view">
                    <i class="bi bi-backspace"></i>
                    Tidak
                </button>
                <button type="submit" form="form-usr-delete-{{ $user->id_user }}" class="btn btn-danger">
                    <i class="bi bi-check-lg"></i>
                    Ya
                </button>
            </div>
        </div>
    </div>
</div>
@endforeach
