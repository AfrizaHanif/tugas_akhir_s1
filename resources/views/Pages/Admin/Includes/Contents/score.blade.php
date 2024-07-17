<h1 class="text-center mb-4">Hasil Perhitungan</h1>
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
            <div class="nav flex-column nav-pills me-3" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                <!--PERIOD NAV-->
                @if(!empty($latest_per))
                    @if ($latest_per->status == 'Scoring')
                    <button class="nav-link active text-start" id="pills-latest-tab" data-bs-toggle="pill" data-bs-target="#pills-latest" type="button" role="tab" aria-controls="pills-latest" aria-selected="true">
                        {{ $latest_per->name }}
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
                <!--HISTORY NAV-->
                @forelse ($history_per as $period)
                <button class="nav-link text-start" id="pills-{{ $period->id_period }}-tab" data-bs-toggle="pill" data-bs-target="#pills-{{ $period->id_period }}" type="button" role="tab" aria-controls="pills-{{ $period->id_period }}" aria-selected="false">
                    @if ($period->status == "Voting")
                    {{ $period->period_name }} <span class="badge bg-secondary">Voting</span>
                    @elseif ($period->status == "Finished")
                    {{ $period->period_name }} <span class="badge bg-secondary">Selesai</span>
                    @else
                    {{ $period->period_name }}
                    @endif
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
                    <div class="row align-items-center pb-2">
                        <div class="col-5">
                            <h2>{{ $latest_per->name }}</h2>
                        </div>
                        <!--MENU-->
                        <div class="col-7 d-grid gap-2 d-md-flex justify-content-md-end">
                            <div class="row g-3 align-items-center">
                                <!--GET DATA-->
                                <div class="col-auto pe-0">
                                    @if ($latest_per->status == "Voting" || $latest_per->status == "Finished")
                                    <span class="d-inline-block" tabindex="0" data-bs-toggle="tooltip" data-bs-title="Proses Karyawan Terbaik sudah selesai dan tidak dapat melakukan ambil data.">
                                    <a class="btn btn-primary disabled">
                                        <i class="bi bi-database-down"></i>
                                        Ambil data
                                    </a>
                                    </span>
                                    @elseif ($scores->where('id_period', $latest_per->id_period)->where('status', 'Rejected')->count() >= 1)
                                    <span class="d-inline-block" tabindex="0" data-bs-toggle="tooltip" data-bs-title="Terdapat nilai yang ditolak. Pastikan nilai tersebut telah direvisi sebelum melakukan update data.">
                                        <a class="btn btn-primary disabled">
                                            <i class="bi bi-database-down"></i>
                                            Ambil data
                                        </a>
                                        </span>
                                    @else
                                    <a class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-scr-get-{{ $latest_per->id_period }}">
                                        <i class="bi bi-database-down"></i>
                                        Ambil data
                                    </a>
                                    @endif
                                </div>
                                <!--FINISH-->
                                <div class="col-auto pe-0">
                                    <div class="btn-group" role="group" aria-label="Basic example">
                                        @if ($latest_per->status == "Voting" || $latest_per->status == "Finished")
                                        <span class="d-inline-block" tabindex="0" data-bs-toggle="tooltip" data-bs-title="Proses Karyawan Terbaik sudah selesai.">
                                            <a class="btn btn-success disabled">
                                                <i class="bi bi-clipboard2-check"></i>
                                                Selesai
                                            </a>
                                        </span>
                                        @elseif ($scores->where('id_period', $latest_per->id_period)->where('status', 'Accepted')->count() == $officers->count())
                                        <a class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modal-scr-finish-{{ $latest_per->id_period }}">
                                            <i class="bi bi-clipboard2-check"></i>
                                            Selesai
                                        </a>
                                        @else
                                        <span class="d-inline-block" tabindex="0" data-bs-toggle="tooltip" data-bs-title="Pastikan seluruh hasil akhir pegawai sudah disetujui.">
                                            <a class="btn btn-success disabled">
                                                <i class="bi bi-clipboard2-check"></i>
                                                Selesai
                                            </a>
                                        </span>
                                        @endif
                                    </div>
                                </div>
                                <!--QUICK VERIFY & DATA CHECKER-->
                                <div class="col-auto">
                                    <div class="dropdown">
                                        <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="bi bi-menu-button-fill"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <!--CHECK DATA-->
                                            <li>
                                                <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#modal-all-view-{{ $latest_per->id_period }}">
                                                    <i class="bi bi-database"></i>
                                                    Cek Data
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#modal-stt-view-{{ $latest_per->id_period }}">
                                                    <i class="bi bi-ui-checks-grid"></i>
                                                    Cek Status
                                                </a>
                                            </li>
                                            <li><hr class="dropdown-divider"></li>
                                            <!--QUICK VERIFY-->
                                            @if ($latest_per->status == "Voting" || $latest_per->status == "Finished" || $scores->where('id_period', $latest_per->id_period)->whereIn('status', ['Rejected', 'Revised'])->count() != 0 || $scores->where('id_period', $latest_per->id_period)->count() == 0)
                                                @if ($latest_per->status == "Voting" || $latest_per->status == "Finished")
                                                <span class="d-inline-block" tabindex="0" data-bs-toggle="tooltip" data-bs-title="Proses Karyawan Terbaik sudah selesai.">
                                                @elseif ($scores->where('id_period', $latest_per->id_period)->whereIn('status', ['Rejected', 'Revised'])->count() != 0)
                                                <span class="d-inline-block" tabindex="0" data-bs-toggle="tooltip" data-bs-title="Terdapat nilai yang ditolak / direvisi.">
                                                @elseif ($scores->where('id_period', $latest_per->id_period)->count() == 0)
                                                <span class="d-inline-block" tabindex="0" data-bs-toggle="tooltip" data-bs-title="Data belum diambil. Silahkan ambil terlebih dahulu.">
                                                @endif
                                                    <li>
                                                        <a class="dropdown-item disabled" href="#" data-bs-toggle="modal" data-bs-target="#modal-scr-yesall-{{ $latest_per->id_period }}">
                                                            <svg class="bi" width="16" height="16" style="vertical-align: -.125em;"><use xlink:href="#yes"/></svg>
                                                            Setuju Semua
                                                        </a>
                                                    </li><li>
                                                        <a class="dropdown-item disabled" href="#" data-bs-toggle="modal" data-bs-target="#modal-scr-noall-{{ $latest_per->id_period }}">
                                                            <svg class="bi" width="16" height="16" style="vertical-align: -.125em;"><use xlink:href="#no"/></svg>
                                                            Tolak Semua
                                                        </a>
                                                    </li>
                                                </span>
                                            @elseif ($scores->where('id_period', $latest_per->id_period)->where('status', 'Accepted')->count() == $officers->count())
                                            <li>
                                                <a class="dropdown-item disabled" href="#" data-bs-toggle="modal" data-bs-target="#modal-scr-yesall-{{ $latest_per->id_period }}">
                                                    <svg class="bi" width="16" height="16" style="vertical-align: -.125em;"><use xlink:href="#yes"/></svg>
                                                    Setuju Semua
                                                </a>
                                            </li><li>
                                                <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#modal-scr-noall-{{ $latest_per->id_period }}">
                                                    <svg class="bi" width="16" height="16" style="vertical-align: -.125em;"><use xlink:href="#no"/></svg>
                                                    Tolak Semua
                                                </a>
                                            </li>
                                            @elseif ($scores->where('id_period', $latest_per->id_period)->where('status', 'Accepted')->count() != $officers->count())
                                            <li>
                                                <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#modal-scr-yesall-{{ $latest_per->id_period }}">
                                                    <svg class="bi" width="16" height="16" style="vertical-align: -.125em;"><use xlink:href="#yes"/></svg>
                                                    Setuju Semua
                                                </a>
                                            </li><li>
                                                <a class="dropdown-item disabled" href="#" data-bs-toggle="modal" data-bs-target="#modal-scr-noall-{{ $latest_per->id_period }}">
                                                    <svg class="bi" width="16" height="16" style="vertical-align: -.125em;"><use xlink:href="#no"/></svg>
                                                    Tolak Semua
                                                </a>
                                            </li>
                                            @else
                                            <li>
                                                <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#modal-scr-yesall-{{ $latest_per->id_period }}">
                                                    <svg class="bi" width="16" height="16" style="vertical-align: -.125em;"><use xlink:href="#yes"/></svg>
                                                    Setuju Semua
                                                </a>
                                            </li><li>
                                                <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#modal-scr-noall-{{ $latest_per->id_period }}">
                                                    <svg class="bi" width="16" height="16" style="vertical-align: -.125em;"><use xlink:href="#no"/></svg>
                                                    Tolak Semua
                                                </a>
                                            </li>
                                            @endif
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--TABLE-->
                    <table class="table table-hover table-bordered">
                        <thead>
                            <tr class="table-primary">
                                <th class="col-1" scope="col">#</th>
                                <th scope="col">Nama</th>
                                <th scope="col">Hasil Akhir</th>
                                <th class="col-3" scope="col">Status</th>
                                <th class="col-1" scope="col">Setuju?</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($scores->where('id_period', $latest_per->id_period) as $score)
                            <tr>
                                <th scope="row">{{ $loop->iteration }}</th>
                                <td>{{ $score->officer->name }}</td>
                                <td>{{ $score->final_score }}</td>
                                <td>
                                    @if ($score->status == 'Pending')
                                    <span class="badge text-bg-warning">Menunggu Persetujuan</span>
                                    @elseif ($score->status == 'Accepted')
                                    <span class="badge text-bg-success">Disetujui</span>
                                    @elseif ($score->status == 'Rejected')
                                    <span class="badge text-bg-danger">Ditolak</span>
                                    @elseif ($score->status == 'Revised')
                                    <span class="badge text-bg-primary">Telah Diperbaiki</span>
                                    @else
                                    <span class="badge text-bg-secondary">Blank</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="dropdown">
                                        @if ($latest_per->status == 'Finish' || $latest_per->status == 'Voting' || $score->status == 'Revised' || $score->status == 'Rejected')
                                            @if ($latest_per->status == 'Finish' || $latest_per->status == 'Voting')
                                            <span class="d-inline-block" tabindex="0" data-bs-toggle="tooltip" data-bs-title="Proses Karyawan Terbaik sudah selesai.">
                                                <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false" disabled>
                                                    <i class="bi bi-menu-button-fill"></i>
                                                </button>
                                            </span>
                                            @elseif ($score->status == 'Rejected')
                                            <span class="d-inline-block" tabindex="0" data-bs-toggle="tooltip" data-bs-title="Persetujuan telah ditutup karena nilai tersebut telah ditolak.">
                                                <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false" disabled>
                                                    <i class="bi bi-menu-button-fill"></i>
                                                </button>
                                            </span>
                                            @elseif ($score->status == 'Revised')
                                            <span class="d-inline-block" tabindex="0" data-bs-toggle="tooltip" data-bs-title="Klik Ambil Data agar dapat melakukan persetujuan nilai.">
                                                <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false" disabled>
                                                    <i class="bi bi-menu-button-fill"></i>
                                                </button>
                                            </span>
                                            @endif
                                        @else
                                        <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="bi bi-menu-button-fill"></i>
                                        </button>
                                        @endif
                                        <ul class="dropdown-menu mx-0 shadow w-table-menu">
                                            <li>
                                                @if ($score->status == 'Accepted')
                                                <a href="#" class="dropdown-item d-flex gap-2 align-items-center disabled"><svg class="bi" width="16" height="16" style="vertical-align: -.125em;"><use xlink:href="#yes"/></svg>
                                                    Ya
                                                </a>
                                                @else
                                                <a class="dropdown-item d-flex gap-2 align-items-center" href="#" data-bs-toggle="modal" data-bs-target="#modal-scr-yes-{{ $latest_per->id_period }}-{{ $score->id }}"><svg class="bi" width="16" height="16" style="vertical-align: -.125em;"><use xlink:href="#yes"/></svg>
                                                    Ya
                                                </a>
                                                @endif
                                                <a class="dropdown-item d-flex gap-2 align-items-center" href="#" data-bs-toggle="modal" data-bs-target="#modal-scr-no-{{ $latest_per->id_period }}-{{ $score->id }}"><svg class="bi" width="16" height="16" style="vertical-align: -.125em;"><use xlink:href="#no"/></svg>
                                                    Tidak
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5">Silahkan klik Ambil Data terlebih dahulu untuk mendapatkan data.</td>
                            </tr>
                            @endforelse
                        </tbody>
                        <tfoot>
                            <tfoot class="table-group-divider table-secondary">
                                <tr>
                                    <td colspan="10">Total Data: <b>{{ $scores->where('id_period', $latest_per->id_period)->count() }}</b> Pegawai</td>
                                </tr>
                            </tfoot>
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
                    Proses penilaian belum dimulai. Mohon menghubungi Kepegawaian untuk mengetahui lebih lanjut.
                </div>
            </div>
            @endif
            <!--HISTORY-->
            @foreach ($history_per as $period)
            <div class="tab-pane fade" id="pills-{{ $period->id_period }}" role="tabpanel" aria-labelledby="pills-{{ $period->id_period }}-tab" tabindex="0">
                <h2>{{ $period->period_name }}</h2>
                <table class="table table-hover table-bordered">
                    <thead>
                        <tr class="table-primary">
                            <th class="col-1" scope="col">#</th>
                            <th scope="col">Nama</th>
                            <th scope="col">Hasil Akhir</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($hscore->where('id_period', $period->id_period) as $score)
                        <tr>
                            <th scope="row">{{ $loop->iteration }}</th>
                            <td>{{ $score->officer_name }}</td>
                            <td>{{ $score->final_score }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5">Silahkan klik Ambil Data terlebih dahulu untuk mendapatkan data.</td>
                        </tr>
                        @endforelse
                    </tbody>
                    <tfoot>
                        <tfoot class="table-group-divider table-secondary">
                            <tr>
                                <td colspan="10">Total Data: <b>{{ $hscore->where('id_period', $period->id_period)->count() }}</b> Pegawai</td>
                            </tr>
                        </tfoot>
                    </tfoot>
                </table>
            </div>
            @endforeach
        </div>
    </div>
</div>
