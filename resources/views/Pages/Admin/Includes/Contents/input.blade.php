<h1 class="text-center mb-4">Data Input Pegawai</h1>
@include('Templates.Includes.Components.alert')
<div class="row g-2">
    <!--SIDEBAR-->
    <div class="col-md-3">
        <div class="position-sticky" style="top: 2rem;">
            <!--MENU-->
            <p>
                <!--HELP-->
                <a class="btn btn-secondary" data-bs-toggle="offcanvas" href="#offcanvas-help" role="button" aria-controls="offcanvas-help">
                    <i class="bi bi-question-lg"></i>
                    Bantuan
                </a>
            </p>
            <!--NAVIGATION-->
            <div class="nav flex-column nav-pills me-3" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                <!--PERIODS-->
                @if (!empty($latest_per))
                <button class="nav-link active text-start" id="pills-latest-tab" data-bs-toggle="pill" data-bs-target="#pills-latest" type="button" role="tab" aria-controls="pills-latest" aria-selected="true">
                    <i class="bi bi-hourglass-split"></i> {{ $latest_per->name }}
                </button>
                <hr/>
                @endif
                <!--HISTORY-->
                @forelse ($history_per as $period)
                @if (!empty($latest_per))
                <button class="nav-link text-start" id="pills-{{ $period->id_period }}-tab" data-bs-toggle="pill" data-bs-target="#pills-{{ $period->id_period }}" type="button" role="tab" aria-controls="pills-{{ $period->id_period }}" aria-selected="false">
                    <i class="bi bi-check-lg"></i> {{ $period->period_name }}
                </button>
                @else
                <button class="nav-link {{ $loop->first ? 'active' : '' }} text-start" id="pills-{{ $period->id_period }}-tab" data-bs-toggle="pill" data-bs-target="#pills-{{ $period->id_period }}" type="button" role="tab" aria-controls="pills-{{ $period->id_period }}" aria-selected="{{ $loop->first ? 'true' : 'false' }}">
                    <i class="bi bi-check-lg"></i> {{ $period->period_name }}
                </button>
                @endif
                @empty
                <div class="alert alert-warning" role="alert">
                    <i class="bi bi-exclamation-triangle-fill"></i> <strong>PERHATIAN</strong></br>
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
            <div class="tab-pane fade show active" id="pills-latest" role="tabpanel" aria-labelledby="pills-latest-tab" tabindex="0">
                <h2>{{ $latest_per->name}}</h2>
                <!--MENU-->
                <p>
                    <div class="row g-3 align-items-center pb-0">
                        <!--IMPORT DATA-->
                        <div class="col-auto">
                            <div class="btn-group" role="group" aria-label="Basic example">
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-inp-import-{{ $latest_per->id_period }}">
                                    <i class="bi bi-file-earmark-arrow-up"></i>
                                    Import
                                </button>
                                @if (!$inputs->isEmpty())
                                <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#modal-inp-export-{{ $latest_per->id_period }}">
                                    <i class="bi bi-file-earmark-arrow-down"></i>
                                    Export
                                </button>
                                @else
                                <button type="button" class="btn btn-secondary" disabled>
                                    <i class="bi bi-file-earmark-arrow-down"></i>
                                    Export
                                </button>
                                @endif
                            </div>
                        </div>
                        <!--MODIFY IMPORT DATA-->
                        <div class="col-auto">
                            <div class="btn-group" role="group" aria-label="Basic example">
                                <!--CONVERT DATA-->
                                @if ($status->where('id_period', $latest_per->id_period)->where('status', 'Not Converted')->count() >= 1)
                                <a class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modal-inp-convert-{{ $latest_per->id_period }}">
                                @else
                                <a class="btn btn-success disabled">
                                @endif
                                    <i class="bi bi-arrow-clockwise"></i>
                                    Convert
                                </a>
                                <!--REFRESH DATA-->
                                @if ($latest_per->progress_status == 'Scoring' && !$inputs->isEmpty() && $status->where('id_period', $latest_per->id_period)->where('status', 'Not Converted')->count() == 0)
                                <a class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#modal-inp-refresh-{{ $latest_per->id_period }}">
                                @else
                                <a class="btn btn-warning disabled">
                                @endif
                                    <i class="bi bi-arrow-repeat"></i>
                                    Refresh
                                </a>
                            </div>
                        </div>
                        <!--DELETE ALL DATA-->
                        <div class="col-auto">
                            @if (!$inputs->isEmpty())
                                @if ($status->where('id_period', $latest_per->id_period)->where('status', 'Need Fix')->count() >= 1)
                                <span class="d-inline-block" tabindex="0" data-bs-toggle="tooltip" data-bs-title="Tidak dapat melakukan penghapusan karena terdapat nilai yang ditolak.">
                                    <a class="btn btn-danger disabled">
                                        <i class="bi bi-trash3"></i>
                                        Hapus Semua
                                    </a>
                                </span>
                                @elseif ($status->where('id_period', $latest_per->id_period)->where('status', 'In Review')->count() >= 1)
                                <span class="d-inline-block" tabindex="0" data-bs-toggle="tooltip" data-bs-title="Penilaian tersebut sedang dalam pemeriksaan.">
                                    <a class="btn btn-danger disabled">
                                        <i class="bi bi-trash3"></i>
                                        Hapus Semua
                                    </a>
                                </span>
                                @elseif ($status->where('id_period', $latest_per->id_period)->where('status', 'Final')->count() >= 1)
                                <span class="d-inline-block" tabindex="0" data-bs-toggle="tooltip" data-bs-title="Tidak dapat melakukan penghapusan karena seluruh nilai sudah disetujui.">
                                    <a class="btn btn-danger disabled">
                                        <i class="bi bi-trash3"></i>
                                        Hapus Semua
                                    </a>
                                </span>
                                @else
                                <a class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#modal-inp-delete-{{ $latest_per->id_period }}">
                                    <i class="bi bi-trash3"></i>
                                    Hapus Semua
                                </a>
                                @endif
                            @else
                            <span class="d-inline-block" tabindex="0" data-bs-toggle="tooltip" data-bs-title="Tidak ada nilai yang terdaftar.">
                                <a class="btn btn-danger disabled">
                                    <i class="bi bi-trash3"></i>
                                    Hapus Semua
                                </a>
                            </span>
                            @endif
                        </div>
                        <!--DATA CHECKER-->
                        <div class="col-auto">
                            <a class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#modal-inp-view-{{ $latest_per->id_period }}">
                                <i class="bi bi-file-spreadsheet"></i>
                                Lihat Data
                            </a>
                        </div>
                    </div>
                </p>
                @if ($latest_per->import_status == 'Clear')
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
                            <th>Proses</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($officers as $officer)
                        <tr>
                            <th scope="row">{{ $loop->iteration }}</th>
                            <td>{{ $officer->name }}</td>
                            <td>{{ $officer->position->name }}</td>
                            <td>
                                @if ($countsub == 0)
                                <span class="badge text-bg-secondary">Kriteria Kosong</span>
                                @elseif ($inputs->where('id_officer', $officer->id_officer)->where('id_period', $latest_per->id_period)->count() == $countsub)
                                <span class="badge text-bg-primary">Terisi Semua</span>
                                @elseif ($inputs->where('id_officer', $officer->id_officer)->where('id_period', $latest_per->id_period)->count() == 0)
                                <span class="badge text-bg-danger">Tidak Terisi</span>
                                @else
                                <span class="badge text-bg-warning">Terisi Sebagian</span>
                                @endif
                            </td>
                            <td>
                                @if ($officer->is_lead == 'No')
                                @forelse ($status->where('id_officer', $officer->id_officer)->where('id_period', $latest_per->id_period) as $s)
                                    @if ($s->status == 'Pending')
                                    <span class="badge text-bg-primary">Belum Diperiksa</span>
                                    @elseif ($s->status == 'Not Converted')
                                    <span class="badge text-bg-warning">Menunggu Konversi</span>
                                    @elseif ($s->status == 'In Review')
                                    <span class="badge text-bg-warning">Dalam Pemeriksaan</span>
                                    @elseif ($s->status == 'Final')
                                    <span class="badge text-bg-success">Hasil Akhir</span>
                                    @elseif ($s->status == 'Need Fix')
                                    <span class="badge text-bg-danger">Perlu Perbaikan</span>
                                    @elseif ($s->status == 'Fixed')
                                    <span class="badge text-bg-primary">Telah Diperbaiki</span>
                                    @endif
                                @empty
                                <span class="badge text-bg-secondary">Blank</span>
                                @endforelse
                                @else
                                <span class="badge text-bg-secondary">Excluded</span>
                                @endif
                            </td>
                            <td>
                                @if ($officer->is_lead == 'No')
                                    @if ($inputs->where('id_officer', $officer->id_officer)->where('id_period', $latest_per->id_period)->count() != 0)
                                    <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#modal-inp-view-{{ $latest_per->id_period }}-{{ $officer->id_officer }}">
                                    @else
                                    <span class="d-inline-block" tabindex="0" data-bs-toggle="tooltip" data-bs-title="Pegawai tersebut belum memiliki data nilai.">
                                    <button type="button" class="btn btn-info" disabled>
                                    </span>
                                    @endif
                                @else
                                <span class="d-inline-block" tabindex="0" data-bs-toggle="tooltip" data-bs-title="Pegawai tersebut tidak terlibat dalam pemilihan Karyawan Terbaik.">
                                <button type="button" class="btn btn-secondary" disabled>
                                </span>
                                @endif
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
                            <td colspan="10">Total Data: <b>{{ $officers->count() }}</b> Pegawai</td>
                        </tr>
                    </tfoot>
                </table>
                @elseif ($latest_per->import_status == 'Not Clear')
                <div class="alert alert-warning" role="alert">
                    <i class="bi bi-exclamation-triangle-fill"></i> <b>PERHATIAN</b> </br>
                    Anda telah melakukan Import Data dan diperlukan pemeriksaan data yang telah dimasukkan ke dalam aplikasi ini.
                    <ul>
                        <li>Periksa data yang telah anda masukkan melalui modal <strong>Lihat Data</strong>. Jika sudah, silahkan lalukan konversi data dengan menekan tombol <strong>Convert</strong>.</li>
                        <li>Apabila ada kesalahan saat melakukan import, anda dapat melakukan <strong> Import Ulang</strong>. Perlu diperhatikan bahwa data yang telah dilakukan Import atau Konversi akan terhapus saat melakukan <strong>Import Ulang</strong>.</li>
                    </ul>
                </div>
                @endif
            </div>
            @endif
            <!--OLD PERIODS-->
            @foreach ($history_per as $hperiod)
            @if (!empty($latest_per))
            <div class="tab-pane fade" id="pills-{{ $hperiod->id_period }}" role="tabpanel" aria-labelledby="pills-{{ $hperiod->id_period }}-tab" tabindex="0">
            @else
            <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" id="pills-{{ $hperiod->id_period }}" role="tabpanel" aria-labelledby="pills-{{ $hperiod->id_period }}-tab" tabindex="0">
            @endif
                <!--HEADING WITH MENU-->
                <h2>{{ $hperiod->period_name }}</h2>
                <p>
                    <div class="row g-3 align-items-center">
                        <!--EXPORT DATA-->
                        <div class="col-auto">
                            <div class="btn-group" role="group" aria-label="Basic example">
                                <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#modal-old-inp-export-{{ $hperiod->id_period }}">
                                    <i class="bi bi-file-earmark-arrow-down"></i>
                                    Export
                                </button>
                            </div>
                        </div>
                        <!--DATA CHECKER-->
                        <div class="col-auto">
                            <a class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#modal-old-all-view-{{ $hperiod->id_period }}">
                                <i class="bi bi-file-spreadsheet"></i>
                                Lihat Data
                            </a>
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
                        @forelse ($hofficers->where('id_period', $hperiod->id_period) as $officer)
                        <tr>
                            <th scope="row">{{ $loop->iteration }}</th>
                            <td>{{ $officer->officer_name }}</td>
                            <td>{{ $officer->officer_position }}</td>
                            <td>
                                <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#modal-old-inp-view-{{ $hperiod->id_period }}-{{ $officer->id_officer }}">
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
                            <td colspan="10">Total Data: <b>{{ $hofficers->count() }}</b> Pegawai</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            @endforeach
        </div>
    </div>
</div>
