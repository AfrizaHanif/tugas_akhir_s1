<h1 class="text-center mb-4">Data Pengguna</h1>
@if (Session::get('code_alert') == 1)
@include('Templates.Includes.Components.alert')
@endif
<!--MENU-->
<p>
    <!--ADD USER-->
    <a class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-usr-create">
        <i class="bi bi-person-add"></i>
        Tambah Pengguna
    </a>
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
            <th scope="col">User Name</th>
            <th scope="col">E-Mail</th>
            <th scope="col">Pengguna Akun</th>
            <th scope="col">Bagian</th>
            <th class="col-1" scope="col">Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($users as $user)
        <tr>
            <th scope="row">{{ $loop->iteration }}</th>
            <td>{{ $user->username }}</td>
            <td>{{ $user->email }}</td>
            <td>{{ $user->officer->name }}</td>
            <td>{{ $user->part }}</td>
            <td>
                <div class="dropdown">
                    <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-menu-button-fill"></i>
                    </button>
                    <ul class="dropdown-menu mx-0 shadow w-table-menu">
                        <li>
                            <a class="dropdown-item d-flex gap-2 align-items-center" href="#" data-bs-toggle="modal" data-bs-target="#modal-usr-update-{{ $user->id_user }}"><svg class="bi" width="16" height="16" style="vertical-align: -.125em;"><use xlink:href="#update"/></svg>
                                Edit
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item d-flex gap-2 align-items-center" href="#" data-bs-toggle="modal" data-bs-target="#modal-usr-delete-{{ $user->id_user }}"><svg class="bi" width="16" height="16" style="vertical-align: -.125em;"><use xlink:href="#delete"/></svg>
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
            <td colspan="7">Total Data: <b>{{ $users->count() }}</b> Pengguna</td>
        </tr>
    </tfoot>
</table>
