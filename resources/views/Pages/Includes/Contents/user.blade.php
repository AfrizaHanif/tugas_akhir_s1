<h1 class="text-center mb-4">Data Pengguna</h1>
@if (Session::get('code_alert') == 1)
@include('Templates.Includes.Components.alert')
@endif
<!--ALERT IF USERS COUNT NOT EQUALS EMPLOYEES COUNT-->
@if (count($users) != count($employees))
<div class="alert alert-warning fade show" role="alert">
    <i class="bi bi-exclamation-triangle-fill"></i> <strong>PERHATIAN</strong>
    <br/>
    Ada beberapa karyawan yang tidak memiliki akun pengguna untuk melakukan login. Silahkan periksa kembali karyawan mana yang belum memiliki akun pengguna.
</div>
@endif
<!--MENU-->
<p>
    <!--ADD USER-->
    @if (count($users) == count($employees)) <!--IF ALL OFFICERS HAS USERS-->
    <span class="d-inline-block" tabindex="0" data-bs-toggle="tooltip" data-bs-title="Semua pegawai telah memiliki pengguna">
        <a class="btn btn-secondary disabled">
            <i class="bi bi-person-add"></i>
            Tambah Pengguna
        </a>
    </span>
    @else
    <a class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-usr-create">
        <i class="bi bi-person-add"></i>
        Tambah Pengguna
    </a>
    @endif
    <!--HELP-->
    <a class="btn btn-secondary" data-bs-toggle="offcanvas" href="#offcanvas-help" role="button" aria-controls="offcanvas-help">
        <i class="bi bi-question-lg"></i>
        Bantuan
    </a>
</p>
<!--TABLE-->
<table class="table table-hover table-bordered">
    <thead>
        <tr class="table-primary">
            <th class="col-1" scope="col">#</th>
            <th scope="col">Username</th>
            <th scope="col">NIP</th>
            <th scope="col">Nama Pengguna</th>
            <th scope="col">Bagian</th>
            <th class="col-1" scope="col">Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($users as $user)
        <tr>
            <th scope="row">{{ $loop->iteration }}</th>
            <td>{{ $user->username }}</td>
            <td>{{ $user->employee->id_employee }}</td>
            <td>{{ $user->employee->name }}</td>
            <td>{{ $user->part }}</td>
            <td>
                <div class="dropdown">
                    @if (Auth::user()->id_user == $user->id_user) <!--LOGGED IN USER CANNOT DELETE USER ITSELF-->
                    <span class="d-inline-block" tabindex="0" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-title="Tidak dapat menghapus akun anda sendiri">
                        <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false" disabled>
                            <i class="bi bi-menu-button-fill"></i>
                        </button>
                    </span>
                    @else
                    <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-menu-button-fill"></i>
                    </button>
                    @endif
                    <ul class="dropdown-menu mx-0 shadow w-table-menu">
                        <li>
                            <a class="dropdown-item d-flex gap-2 align-items-center" href="#" data-bs-toggle="modal" data-bs-target="#modal-usr-password-{{ $user->id_user }}">
                                <svg class="bi" width="16" height="16" style="vertical-align: -.125em;"><use xlink:href="#password"/></svg>
                                Reset Password
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item d-flex gap-2 align-items-center" href="#" data-bs-toggle="modal" data-bs-target="#modal-usr-delete-{{ $user->id_user }}">
                                <svg class="bi" width="16" height="16" style="vertical-align: -.125em;"><use xlink:href="#delete"/></svg>
                                Delete
                            </a>
                        </li>
                    </ul>
                </div>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="7">Tidak ada Periode yang terdaftar</td>
        </tr>
        @endforelse
    </tbody>
    <tfoot class="table-group-divider table-secondary">
        <tr>
            <td colspan="7">Total Data: <b>{{ $users->count() }}</b> Pengguna dari <b>{{ $employees->count() }}</b> Karyawan</td>
        </tr>
    </tfoot>
</table>
