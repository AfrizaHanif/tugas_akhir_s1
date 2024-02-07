<h1 class="text-center mb-4">Periode</h1>
@include('Pages.Admin.Includes.Components.alert')
<p>
    <div class="btn-group" role="group" aria-label="Basic example">
        <a class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-per-create">
            <i class="bi bi-folder-plus"></i>
            Tambah Periode
        </a>
    </div>
</p>
<table class="table table-hover table-bordered">
    <thead>
        <tr class="table-primary">
            <th class="col-1" scope="col">#</th>
            <th scope="col">Nama</th>
            <th scope="col">Status</th>
            <th class="col-1" scope="col">Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($periods as $period)
        <tr>
            <th scope="row">{{ $loop->iteration }}</th>
            <td>{{ $period->name }}</td>
            <td>
                @if ($period->status == "Finish")
                <span class="badge text-bg-success">Selesai</span>
                @elseif ($period->status == "Skip")
                <span class="badge text-bg-secondary">Dilewatkan</span>
                @elseif ($period->status == "In Progress")
                <span class="badge text-bg-primary">Dalam Proses</span>
                @endif
            </td>
            <td>
                <div class="dropdown">
                    <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-menu-button-fill"></i>
                    </button>
                    <ul class="dropdown-menu mx-0 shadow w-table-menu">
                        <li>
                            @if ($period->status == "In Progress")
                            <a class="dropdown-item d-flex gap-2 align-items-center" href="#" data-bs-toggle="modal" data-bs-target="#modal-per-skip-{{ $period->id_period }}"><svg class="bi" width="16" height="16" style="vertical-align: -.125em;"><use xlink:href="#skip"/></svg>
                                Lewati
                            </a>
                            @endif
                            <a class="dropdown-item d-flex gap-2 align-items-center" href="#" data-bs-toggle="modal" data-bs-target="#modal-per-delete-{{ $period->id_period }}"><svg class="bi" width="16" height="16" style="vertical-align: -.125em;"><use xlink:href="#delete"/></svg>
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
            <td colspan="7">Total Data: <b>{{ $periods->count() }}</b> Periode</td>
        </tr>
    </tfoot>
</table>
