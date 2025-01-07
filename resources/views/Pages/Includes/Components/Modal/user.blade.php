<!--CREATE USER-->
<div class="modal modal-lg fade" id="modal-usr-create" data-bs-backdrop="static" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Tambah Pengguna</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="close-usr-create"></button>
                </div>
                <div class="modal-body">
                    @if (Auth::user()->part == 'Admin')
                    <form id="form-usr-create" action="{{ route('admin.masters.users.store') }}" method="POST" enctype="multipart/form-data" id="form-usr-create">
                    @else
                    <form id="form-usr-create" action="{{ route('developer.masters.users.store') }}" method="POST" enctype="multipart/form-data" id="form-usr-create">
                    @endif
                        @csrf
                        <div class="row justify-content-center g-4">
                            <div class="col-md-7">
                                <div class="position-sticky" style="top: 0rem;">
                                    @if (Session::get('modal_redirect') == 'modal-usr-create')
                                    @include('Templates.Includes.Components.alert')
                                    @endif
                                    <div class="mb-3">
                                        <label for="id_employee" class="form-label">Karyawan</label>
                                        <select class="form-select" id="id_employee" name="id_employee" required>
                                            <option selected disabled value="">---Pilih Karyawan---</option>
                                            @foreach ($employees as $employee)
                                                @if (empty($users->where('id_employee', $employee->id_employee)->first()))
                                                <option value="{{ $employee->id_employee }}" {{ old('employee') ==  $employee->id_employee ? 'selected' : null }}>({{ $employee->id_employee }}) {{ $employee->name }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="position-sticky" style="top: 0rem;">
                                    <div class="alert alert-info" role="alert">
                                        <i class="bi bi-info-circle-fill"></i> <strong>CARA PENGISIAN</strong>
                                        <ol>
                                            <li>Satu karyawan hanya dapat memakai satu user.</li>
                                            <li>Bagian:</li>
                                            <ol>
                                                <li>Kepegawaian: Pilih Admin</li>
                                                <li>Ketua BPS Jatim: Pilih KBPS</li>
                                                <li>Karyawan Biasa: Pilih Karyawan</li>
                                            </ol>
                                            <li>Untuk KBPS, hanya dapat dipakai oleh satu karyawan</li>
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
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Ubah Pengguna ({{ $user->employee->name }})</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @if (Auth::user()->part == 'Admin')
                    <form id="form-usr-update-{{ $user->id_user }}" action="{{ route('admin.masters.users.update', $user->id_user) }}" method="POST" enctype="multipart/form-data">
                    @else
                    <form id="form-usr-update-{{ $user->id_user }}" action="{{ route('developer.masters.users.update', $user->id_user) }}" method="POST" enctype="multipart/form-data">
                    @endif
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
                                                <li>Karyawan Biasa: Pilih Karyawan</li>
                                            </ol>
                                            <li>Untuk KBPS, hanya dapat dipakai oleh satu karyawan</li>
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
                @if (Auth::user()->part == 'Admin')
                <form id="form-usr-delete-{{ $user->id_user }}" action="{{ route('admin.masters.users.destroy', $user->id_user) }}" method="POST" enctype="multipart/form-data">
                @else
                <form id="form-usr-delete-{{ $user->id_user }}" action="{{ route('developer.masters.users.destroy', $user->id_user) }}" method="POST" enctype="multipart/form-data">
                @endif
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
<!--RESET PASSWORD-->
<div class="modal fade" id="modal-usr-password-{{ $user->id_user }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Reset Password Pengguna ({{ $user->username }})</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                @if (Auth::user()->part == 'Admin')
                <form id="form-usr-password-{{ $user->id_user }}" action="{{ route('admin.masters.users.password', $user->id_user) }}" method="POST" enctype="multipart/form-data">
                @else
                <form id="form-usr-password-{{ $user->id_user }}" action="{{ route('developer.masters.users.password', $user->id_user) }}" method="POST" enctype="multipart/form-data">
                @endif
                    @csrf
                    <div class="alert alert-warning" role="alert">
                        <i class="bi bi-exclamation-triangle-fill"></i> <b>PERHATIAN</b>
                        <br/>
                        Apakah anda ingin reset ulang password dari pengguna dengan nama <b>{{ $user->username }}</b>?
                        <ul>
                            <li>Setelah reset dilakukan, informasikan kepada pemilik akun tersebut bahwa password telah direset ulang menjadi "bps3500".</li>
                        </ul>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#modal-dep-view">
                    <i class="bi bi-backspace"></i>
                    Tidak
                </button>
                <button type="submit" form="form-usr-password-{{ $user->id_user }}" class="btn btn-danger">
                    <i class="bi bi-check-lg"></i>
                    Ya
                </button>
            </div>
        </div>
    </div>
</div>
@endforeach
