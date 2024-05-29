@if (Request::is('admin/inputs/presences*'))
<h1 class="text-center mb-4">Data Kehadiran Pegawai</h1>
@elseif (Request::is('admin/inputs/kbu/performances') || Request::is('admin/inputs/ktt/performances') || Request::is('admin/inputs/kbps/performances'))
<h1 class="text-center mb-4">Data Prestasi Kerja</h1>
@endif
@include('Templates.Includes.Components.alert')
<div class="row g-2">
    <!--SIDEBAR-->
    <div class="col-md-3">
        <div class="position-sticky" style="top: 2rem;">
            <!--MENU-->
            <p>
                <a class="btn btn-secondary" data-bs-toggle="offcanvas" href="#offcanvas-help" role="button" aria-controls="offcanvas-help">
                    <i class="bi bi-question-lg"></i>
                    Bantuan
                </a>
            </p>
            <!--NAVIGATION-->
            <div class="nav flex-column nav-pills me-3" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                <!--PERIODS-->
                @if (!empty($latest_per))
                    @if ($latest_per->status == 'Scoring')
                    <button class="nav-link active text-start" id="pills-latest-tab" data-bs-toggle="pill" data-bs-target="#pills-latest" type="button" role="tab" aria-controls="pills-latest" aria-selected="true">
                        <i class="bi bi-hourglass-split"></i> {{ $latest_per->name }}
                    </button>
                    @else
                    <button class="nav-link active text-start" id="pills-complete-tab" data-bs-toggle="pill" data-bs-target="#pills-complete" type="button" role="tab" aria-controls="pills-complete" aria-selected="true">
                        Not Running
                    </button>
                    @endif
                @else
                <button class="nav-link active text-start" id="pills-empty-tab" data-bs-toggle="pill" data-bs-target="#pills-empty" type="button" role="tab" aria-controls="pills-empty" aria-selected="true">
                    Not Running
                </button>
                @endif
                <hr/>
                <!--HISTORY-->
                @forelse ($history_per as $period)
                <button class="nav-link text-start" id="pills-{{ $period->id_period }}-tab" data-bs-toggle="pill" data-bs-target="#pills-{{ $period->id_period }}" type="button" role="tab" aria-controls="pills-{{ $period->id_period }}" aria-selected="false">
                    <i class="bi bi-check-lg"></i> {{ $period->period_name }}
                </button>
                @empty
                <div class="alert alert-danger" role="alert">
                    <i class="bi bi-x-octagon-fill"></i> <strong>ERROR</strong></br>
                    Hasil Rekap belum tersedia. Silahkan selesaikan proses Karyawan Terbaik terlebih dahulu untuk melihat rekap.
                </div>
                @endforelse
            </div>
            <br/>
        </div>
    </div>
    <!--MAIN CONTENT-->
    <div class="col-md-9">
        <div class="tab-content" id="v-pills-tabContent">
            <!--CURRENT PERIOD-->
            @if (!empty($latest_per))
                @if ($latest_per->status == 'Scoring')
                <div class="tab-pane fade show active" id="pills-latest" role="tabpanel" aria-labelledby="pills-latest-tab" tabindex="0">
                    <h2>{{ $latest_per->name }}</h2>
                    <!--MENU-->
                    <p>
                        <div class="row g-3 align-items-center pb-0">
                            <!--DATA CHECKER-->
                            <div class="col-auto">
                                <label for="tahun_saw_dl" class="col-form-label">Lihat Data</label>
                            </div>
                            <div class="col-auto">
                                <div class="btn-group" role="group" aria-label="Basic example">
                                    <a class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#modal-inp-view-{{ $latest_per->id_period }}">
                                        <i class="bi bi-file-spreadsheet"></i>
                                        Hanya Data Ini
                                    </a>
                                    <a class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#modal-all-view-{{ $latest_per->id_period }}">
                                        <i class="bi bi-database"></i>
                                        Semua Data
                                    </a>
                                </div>
                            </div>
                        </div>
                    </p>
                    <!--NOTICE-->
                    @if (Request::is('admin/inputs/kbps/performances'))
                    <div class="alert alert-info" role="alert">
                        Pengisian nilai untuk kepemimpinan (Kepala Bagian Umum dan Ketua Tim Teknis) hanya ditujukan untuk kebutuhan rekap dan tidak diikutkan dalam nominasi pemilihan karyawan terbaik.
                    </div>
                    @endif
                    @if (count($status->where('id_period', $latest_per->id_period)->where('status', 'Need Fix')) != 0)
                    <div class="alert alert-danger" role="alert">
                        Terdapat nilai yang ditolak oleh Kepala BPS Jawa Timur. Mohon untuk segera melakukan revisi agar dapat diverifikasi ulang.
                    </div>
                    @else
                        @foreach ($periods->where('id_period', $latest_per->id_period) as $period)
                            @if ($period->status == 'Voting')
                            <div class="alert alert-success" role="alert">
                                Proses Penilaian pada periode ini telah selesai. Silahkan melakukan pemilihan karyawan terbaik di halaman Voting
                            </div>
                            @endif
                        @endforeach
                    @endif
                    <!--TABLE-->
                    <table class="table table-hover table-bordered">
                        <thead>
                            <tr class="table-primary">
                                <th rowspan="2" class="col-1" scope="col">#</th>
                                <th rowspan="2" scope="col">Nama</th>
                                <th rowspan="2" scope="col">Jabatan</th>
                                <th colspan="2" scope="col">Status</th>
                                <th rowspan="2" class="col-1" scope="col">Action</th>
                            </tr>
                            <tr class="table-primary">
                                <th>Isi</th>
                                <th>Valid</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($officers as $officer)
                            <tr>
                                <th scope="row">{{ $loop->iteration }}</th>
                                <td>{{ $officer->name }}</td>
                                <td>{{ $officer->department->name }}</td>
                                <td>
                                    @if (Request::is('admin/inputs/presences'))
                                        @if ($countsub == 0)
                                        <span class="badge text-bg-secondary">Kriteria Kosong</span>
                                        @elseif ($presences->where('id_officer', $officer->id_officer)->where('id_period', $latest_per->id_period)->count() == $countsub)
                                        <span class="badge text-bg-primary">Terisi Semua</span>
                                        @elseif ($presences->where('id_officer', $officer->id_officer)->where('id_period', $latest_per->id_period)->count() == 0)
                                        <span class="badge text-bg-danger">Tidak Terisi</span>
                                        @else
                                        <span class="badge text-bg-warning">Terisi Sebagian</span>
                                        @endif
                                    @elseif (Request::is('admin/inputs/kbu/performances') || Request::is('admin/inputs/ktt/performances') || Request::is('admin/inputs/kbps/performances'))
                                        @if ($countsub == 0)
                                        <span class="badge text-bg-secondary">Kriteria Kosong</span>
                                        @elseif ($performances->where('id_officer', $officer->id_officer)->where('id_period', $latest_per->id_period)->count() == $countsub)
                                        <span class="badge text-bg-primary">Terisi Semua</span>
                                        @elseif ($performances->where('id_officer', $officer->id_officer)->where('id_period', $latest_per->id_period)->count() == 0)
                                        <span class="badge text-bg-danger">Tidak Terisi</span>
                                        @else
                                        <span class="badge text-bg-warning">Terisi Sebagian</span>
                                        @endif
                                    @endif
                                </td>
                                @forelse ($status->where('id_officer', $officer->id_officer)->where('id_period', $latest_per->id_period) as $s)
                                    @if ($officer->is_lead == 'No')
                                        @if ($s->status == 'Pending')
                                        <td><span class="badge text-bg-primary">Belum Diperiksa</span></td>
                                        @elseif ($s->status == 'In Review')
                                        <td><span class="badge text-bg-warning">Dalam Pemeriksaan</span></td>
                                        @elseif ($s->status == 'Final')
                                        <td><span class="badge text-bg-success">Hasil Akhir</span></td>
                                        @elseif ($s->status == 'Need Fix')
                                        <td><span class="badge text-bg-danger">Perlu Perbaikan</span></td>
                                        @endif
                                    @else
                                    <td><span class="badge text-bg-secondary">Tidak Dihitung</span></td>
                                    @endif
                                @empty
                                <td><span class="badge text-bg-secondary">Blank</span></td>
                                @endforelse
                                <td>
                                    <div class="dropdown">
                                        @forelse ($status->where('id_officer', $officer->id_officer)->where('id_period', $latest_per->id_period) as $s)
                                            @if ($s->status == 'Pending' || $s->status == 'Need Fix')
                                            <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="bi bi-menu-button-fill"></i>
                                            </button>
                                            @elseif ($s->status == 'In Review')
                                            <span class="d-inline-block" tabindex="0" data-bs-toggle="tooltip" data-bs-title="Penilaian tersebut sedang dalam pemeriksaan.">
                                                <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false" disabled>
                                                    <i class="bi bi-menu-button-fill"></i>
                                                </button>
                                            </span>
                                            @elseif ($s->status == 'Not Included')
                                            <span class="d-inline-block" tabindex="0" data-bs-toggle="tooltip" data-bs-title="Penilaian tersebut telah dikunci dan tidak diikutkan dalam proses validasi.">
                                                <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false" disabled>
                                                    <i class="bi bi-menu-button-fill"></i>
                                                </button>
                                            </span>
                                            @else
                                            <span class="d-inline-block" tabindex="0" data-bs-toggle="tooltip" data-bs-title="Penilaian tersebut sudah disetujui sebagai hasil akhir dan tidak dapat diubah kembali.">
                                                <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false" disabled>
                                                    <i class="bi bi-menu-button-fill"></i>
                                                </button>
                                            </span>
                                            @endif
                                        @empty
                                        <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="bi bi-menu-button-fill"></i>
                                        </button>
                                        @endforelse
                                        <ul class="dropdown-menu mx-0 shadow w-table-menu">
                                            <li>
                                                @if (Request::is('admin/inputs/presences'))
                                                    @if ($presences->where('id_officer', $officer->id_officer)->where('id_period', $latest_per->id_period)->count() != 0)
                                                    <a class="dropdown-item d-flex gap-2 align-items-center"  href="#" data-bs-toggle="modal" data-bs-target="#modal-inp-view-{{ $latest_per->id_period }}-{{ $officer->id_officer }}"><svg class="bi" width="16" height="16" style="vertical-align: -.125em;"><use xlink:href="#view"/></svg>
                                                        Lihat Data
                                                    </a>
                                                    <a class="dropdown-item d-flex gap-2 align-items-center"  href="#" data-bs-toggle="modal" data-bs-target="#modal-inp-update-{{ $latest_per->id_period }}-{{ $officer->id_officer }}"><svg class="bi" width="16" height="16" style="vertical-align: -.125em;"><use xlink:href="#update"/></svg>
                                                        Ubah Data
                                                    </a>
                                                    <a class="dropdown-item d-flex gap-2 align-items-center"  href="#" data-bs-toggle="modal" data-bs-target="#modal-inp-delete-{{ $latest_per->id_period }}-{{ $officer->id_officer }}"><svg class="bi" width="16" height="16" style="vertical-align: -.125em;"><use xlink:href="#delete"/></svg>
                                                        Hapus Data
                                                    </a>
                                                    @else
                                                    <a class="dropdown-item d-flex gap-2 align-items-center"  href="#" data-bs-toggle="modal" data-bs-target="#modal-inp-create-{{ $latest_per->id_period }}-{{ $officer->id_officer }}"><svg class="bi" width="16" height="16" style="vertical-align: -.125em;"><use xlink:href="#create"/></svg>
                                                        Tambah Data
                                                    </a>
                                                    @endif
                                                @elseif (Request::is('admin/inputs/kbu/performances') || Request::is('admin/inputs/ktt/performances') || Request::is('admin/inputs/kbps/performances'))
                                                    @if ($performances->where('id_officer', $officer->id_officer)->where('id_period', $latest_per->id_period)->count() != 0)
                                                    <a class="dropdown-item d-flex gap-2 align-items-center"  href="#" data-bs-toggle="modal" data-bs-target="#modal-inp-view-{{ $latest_per->id_period }}-{{ $officer->id_officer }}"><svg class="bi" width="16" height="16" style="vertical-align: -.125em;"><use xlink:href="#view"/></svg>
                                                        Lihat Data
                                                    </a>
                                                    <a class="dropdown-item d-flex gap-2 align-items-center"  href="#" data-bs-toggle="modal" data-bs-target="#modal-inp-update-{{ $latest_per->id_period }}-{{ $officer->id_officer }}"><svg class="bi" width="16" height="16" style="vertical-align: -.125em;"><use xlink:href="#update"/></svg>
                                                        Ubah Data
                                                    </a>
                                                    <a class="dropdown-item d-flex gap-2 align-items-center"  href="#" data-bs-toggle="modal" data-bs-target="#modal-inp-delete-{{ $latest_per->id_period }}-{{ $officer->id_officer }}"><svg class="bi" width="16" height="16" style="vertical-align: -.125em;"><use xlink:href="#delete"/></svg>
                                                        Hapus Data
                                                    </a>
                                                    @else
                                                    <a class="dropdown-item d-flex gap-2 align-items-center"  href="#" data-bs-toggle="modal" data-bs-target="#modal-inp-create-{{ $latest_per->id_period }}-{{ $officer->id_officer }}"><svg class="bi" width="16" height="16" style="vertical-align: -.125em;"><use xlink:href="#create"/></svg>
                                                        Tambah Data
                                                    </a>
                                                    @endif
                                                @endif
                                            </li>
                                        </ul>
                                    </div>
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
                                <td colspan="10">Total Data: <b>{{ $officers->count() }}</b> Pegawai</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                @else
                <div class="tab-pane fade show active" id="pills-complete" role="tabpanel" aria-labelledby="pills-complete-tab" tabindex="0">
                    <div class="alert alert-info" role="alert">
                        Proses penilaian telah selesai pada periode ini. Silahkan melakukan pemilihan karyawan terbaik di halaman <strong>Voting</strong>.
                    </div>
                </div>
                @endif
            @else
            <div class="tab-pane fade show active" id="pills-empty" role="tabpanel" aria-labelledby="pills-empty-tab" tabindex="0">
                <div class="alert alert-danger" role="alert">
                    <i class="bi bi-x-octagon-fill"></i> <strong>ERROR</strong></br>
                    Tidak ada periode yang sedang berjalan. Untuk menjalankan Proses Karyawan Terbaik, kunjungi halaman Periode, lalu klik Mulai pada periode yang dipilih untuk memulai.
                </div>
            </div>
            @endif
            <!--OLD PERIODS-->
            @foreach ($history_per as $period)
            <div class="tab-pane fade" id="pills-{{ $period->id_period }}" role="tabpanel" aria-labelledby="pills-{{ $period->id_period }}-tab" tabindex="0">
                <!--HEADING WITH MENU-->
                <h2>{{ $period->period_name }}</h2>
                <p>
                    <div class="row g-3 align-items-center">
                        <!--DATA CHECKER-->
                        <div class="col-auto">
                            <label for="tahun_saw_dl" class="col-form-label">Lihat Data</label>
                        </div>
                        <div class="col-auto">
                            <div class="btn-group" role="group" aria-label="Basic example">
                                <a class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#modal-old-inp-view-{{ $period->id_period }}">
                                    <i class="bi bi-file-spreadsheet"></i>
                                    Hanya Data Ini
                                </a>
                                <a class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#modal-old-all-view-{{ $period->id_period }}">
                                    <i class="bi bi-database"></i>
                                    Semua Data
                                </a>
                            </div>
                        </div>
                    </div>
                </p>
                <!--TABLE-->
                <table class="table table-hover table-bordered">
                    <thead>
                        <tr class="table-primary">
                            <th rowspan="2" class="col-1" scope="col">#</th>
                            <th rowspan="2" scope="col">Nama</th>
                            <th rowspan="2" scope="col">Jabatan</th>
                            <th rowspan="2" class="col-1" scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($hofficer as $officer)
                        <tr>
                            <th scope="row">{{ $loop->iteration }}</th>
                            <td>{{ $officer->officer_name }}</td>
                            <td>{{ $officer->officer_department }}</td>
                            <td>
                                <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#modal-old-inp-view-{{ $period->id_period }}-{{ $officer->id_officer }}">
                                    <i class="bi bi-info-circle"></i>
                                </button>
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
                            <td colspan="10">Total Data: <b>{{ $hofficer->count() }}</b> Pegawai</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            @endforeach
        </div>
    </div>
</div>
