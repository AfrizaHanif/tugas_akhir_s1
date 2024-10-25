<h1 class="text-center mb-4">Periode</h1>
@if (Session::get('code_alert') == 1)
@include('Templates.Includes.Components.alert')
@endif
<!--MENU-->
<p>
    <!--ADD PERIOD-->
    <a class="btn btn-primary" href="#" role="button" data-bs-toggle="modal" data-bs-target="#modal-per-create">
        <i class="bi bi-calendar-plus"></i>
        Tambah Periode
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
            <th rowspan="2" class="col-1" scope="col">#</th>
            <th rowspan="2" scope="col">Nama</th>
            <th rowspan="2" class="col-2" scope="col">Hari Aktif Kerja</th>
            <th colspan="2" scope="col">Status</th>
            <th rowspan="2" class="col-1" scope="col">Action</th>
        </tr>
        <tr class="table-primary">
            <th class="col-2" scope="col">Proses</th>
            <th class="col-2" scope="col">Import</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($periods as $period)
        <tr>
            <th scope="row">{{ $loop->iteration }}</th>
            <td>{{ $period->name }}</td>
            <td>{{ $period->active_days }}</td>
            <td>
                @if ($period->progress_status == "Finished")
                <span class="badge text-bg-success">Selesai</span>
                @elseif ($period->progress_status == "Skipped")
                <span class="badge text-bg-secondary">Dilewatkan</span>
                @elseif ($period->progress_status == "Scoring")
                <span class="badge text-bg-primary">Dalam Penilaian</span>
                @elseif ($period->progress_status == "Verifying")
                <span class="badge text-bg-primary">Dalam Verifikasi</span>
                @elseif ($period->progress_status == "Pending")
                <span class="badge text-bg-warning">Pending</span>
                @endif
            </td>
            <td>
                @if ($period->import_status == "Clear")
                <span class="badge text-bg-success">Sudah Dikonversi</span>
                @elseif ($period->import_status == "Few Clear")
                <span class="badge text-bg-warning">Beberapa Dikonversi</span>
                @elseif ($period->import_status == "Not Clear")
                <span class="badge text-bg-warning">Belum Dikonversi</span>
                @elseif ($period->import_status == "No Data")
                <span class="badge text-bg-danger">Belum Ada Data</span>
                @endif
            </td>
            <td>
                <div class="dropdown">
                    @if ($period->progress_status == "Finished" || $period->progress_status == "Verifying" ||$period->progress_status == "Skipped")
                        @if ($period->progress_status == "Finished")
                        <span class="d-inline-block" tabindex="0" data-bs-toggle="tooltip" data-bs-title="Tidak dapat mengubah periode ini karena proses pemilihan karyawan terbaik pada periode ini sudah selesai.">
                        @elseif ($period->progress_status == "Verifying")
                        <span class="d-inline-block" tabindex="0" data-bs-toggle="tooltip" data-bs-title="Tidak dapat mengubah periode ini karena sedang dalam verifikasi penilaian.">
                        @elseif ($period->progress_status == "Skipped")
                        <span class="d-inline-block" tabindex="0" data-bs-toggle="tooltip" data-bs-title="Tidak dapat mengubah periode ini karena periode ini tidak dilakukan pemilihan karyawan terbaik.">
                        @endif
                            <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false" disabled>
                            <i class="bi bi-menu-button-fill"></i>
                        </button>
                    </span>
                    @elseif ($period->progress_status == "Pending" || $period->progress_status == "Scoring")
                    <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-menu-button-fill"></i>
                    </button>
                    @endif
                    <ul class="dropdown-menu mx-0 shadow w-table-menu">
                        <li>
                            @if ($period->progress_status == "Pending" || $period->progress_status == "Scoring")
                            <a class="dropdown-item d-flex gap-2 align-items-center" href="#" data-bs-toggle="modal" data-bs-target="#modal-per-update-{{ $period->id_period }}"><svg class="bi" width="16" height="16" style="vertical-align: -.125em;"><use xlink:href="#update"/></svg>
                                Ubah Hari Aktif
                            </a>
                            @endif
                            @if ($period->progress_status == "Pending")
                            <li><hr class="dropdown-divider"></li>
                            <a class="dropdown-item d-flex gap-2 align-items-center" href="#" data-bs-toggle="modal" data-bs-target="#modal-per-start-{{ $period->id_period }}"><svg class="bi" width="16" height="16" style="vertical-align: -.125em;"><use xlink:href="#start"/></svg>
                                Mulai
                            </a>
                            <a class="dropdown-item d-flex gap-2 align-items-center" href="#" data-bs-toggle="modal" data-bs-target="#modal-per-delete-{{ $period->id_period }}"><svg class="bi" width="16" height="16" style="vertical-align: -.125em;"><use xlink:href="#delete"/></svg>
                                Delete
                            </a>
                            @endif
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
