<h1 class="text-center mb-4">Periode</h1>
@include('Templates.Includes.Components.alert')
<p>
    <a class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-per-create">
        <i class="bi bi-folder-plus"></i>
        Tambah Periode
    </a>
    <a class="btn btn-secondary" data-bs-toggle="offcanvas" href="#offcanvas-help" role="button" aria-controls="offcanvas-help">
        <i class="bi bi-question-lg"></i>
        Bantuan
    </a>
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
                @if ($period->status == "Finished")
                <span class="badge text-bg-success">Selesai</span>
                @elseif ($period->status == "Skipped")
                <span class="badge text-bg-secondary">Dilewatkan</span>
                @elseif ($period->status == "Scoring")
                <span class="badge text-bg-primary">Dalam Penilaian</span>
                @elseif ($period->status == "Voting")
                <span class="badge text-bg-primary">Dalam Pemilihan</span>
                @elseif ($period->status == "Pending")
                <span class="badge text-bg-warning">Pending</span>
                @endif
            </td>
            <td>
                <div class="dropdown">
                    @if ($period->status != "Pending")
                        @if ($period->status == "Finished")
                        <span class="d-inline-block" tabindex="0" data-bs-toggle="tooltip" data-bs-title="Tidak dapat mengubah periode ini karena proses pemilihan karyawan terbaik pada periode ini sudah selesai.">
                        @elseif ($period->status == "Voting")
                        <span class="d-inline-block" tabindex="0" data-bs-toggle="tooltip" data-bs-title="Tidak dapat mengubah periode ini karena sedang dalam pemilihan karyawan terbaik.">
                        @elseif ($period->status == "Scoring")
                        <span class="d-inline-block" tabindex="0" data-bs-toggle="tooltip" data-bs-title="Tidak dapat mengubah periode ini karena sedang dalam penilaian seluruh pegawai.">
                        @elseif ($period->status == "Skipped")
                        <span class="d-inline-block" tabindex="0" data-bs-toggle="tooltip" data-bs-title="Tidak dapat mengubah periode ini karena periode ini tidak dilakukan pemilihan karyawan terbaik.">
                        @endif
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
                            @if ($period->status == "Pending")
                            <a class="dropdown-item d-flex gap-2 align-items-center" href="#" data-bs-toggle="modal" data-bs-target="#modal-per-skip-{{ $period->id_period }}"><svg class="bi" width="16" height="16" style="vertical-align: -.125em;"><use xlink:href="#skip"/></svg>
                                Lewati
                            </a>
                            @elseif ($period->status == "Voting")
                            <a class="dropdown-item d-flex gap-2 align-items-center" href="#" data-bs-toggle="modal" data-bs-target="#modal-per-finish-{{ $period->id_period }}"><svg class="bi" width="16" height="16" style="vertical-align: -.125em;"><use xlink:href="#finish"/></svg>
                                Selesai
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
