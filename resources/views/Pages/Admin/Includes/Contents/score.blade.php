<h1 class="text-center mb-4">Hasil Perhitungan</h1>
@include('Templates.Includes.Components.alert')
<div class="row">
    <div class="col-md-3">
        <div class="position-sticky" style="top: 2rem;">
            <div class="nav flex-column nav-pills me-3" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                @forelse ($periods as $period)
                <button class="nav-link {{ $loop->first ? 'active' : '' }}" id="pills-{{ $period->id_period }}-tab" data-bs-toggle="pill" data-bs-target="#pills-{{ $period->id_period }}" type="button" role="tab" aria-controls="pills-{{ $period->id_period }}" aria-selected="{{ $loop->first ? 'true' : 'false' }}">
                    @if ($period->status == "Voting")
                    {{ $period->name }} <span class="badge bg-secondary">Voting</span>
                    @elseif ($period->status == "Finished")
                    {{ $period->name }} <span class="badge bg-secondary">Selesai</span>
                    @else
                    {{ $period->name }}
                    @endif
                </button>
                @empty
                <button class="nav-link active" id="pills-empty-tab" data-bs-toggle="pill" data-bs-target="#pills-empty" type="button" role="tab" aria-controls="pills-empty" aria-selected="true">
                    Empty
                </button>
                @endforelse
            </div>
            <br/>
        </div>
    </div>
    <div class="col-md-9">
        <div class="tab-content" id="v-pills-tabContent">
            @forelse ($periods as $period)
            <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" id="pills-{{ $period->id_period }}" role="tabpanel" aria-labelledby="pills-{{ $period->id_period }}-tab" tabindex="0">
                @if ($period->status == "Voting")
                <h2>{{ $period->name }} <span class="badge bg-warning">Voting</span></h2>
                @elseif ($period->status == "Finished")
                <h2>{{ $period->name }} <span class="badge bg-success">Selesai</span></h2>
                @else
                <h2>{{ $period->name }}</h2>
                @endif
                <p>
                    <div class="row g-3 align-items-center">
                        <div class="col-auto">
                            @if ($period->status == "Voting" || $period->status == "Finished")
                            <span class="d-inline-block" tabindex="0" data-bs-toggle="tooltip" data-bs-title="Proses Karyawan Terbaik sudah selesai dan tidak dapat melakukan ambil data.">
                            <a class="btn btn-primary disabled">
                                <i class="bi bi-database-down"></i>
                                Ambil data
                            </a>
                            </span>
                            @else
                            <a class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-res-get-{{ $period->id_period }}">
                                <i class="bi bi-database-down"></i>
                                Ambil data
                            </a>
                            @endif
                        </div>
                        <div class="col-auto">
                            <div class="btn-group" role="group" aria-label="Basic example">
                                <a class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-all-view-{{ $period->id_period }}">
                                    <i class="bi bi-database"></i>
                                    Cek Data
                                </a>
                                <a class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-stt-view-{{ $period->id_period }}">
                                    <i class="bi bi-ui-checks-grid"></i>
                                    Cek Status
                                </a>
                            </div>
                        </div>
                        <div class="col-auto">
                            <div class="btn-group" role="group" aria-label="Basic example">
                                @if ($period->status == "Voting" || $period->status == "Finished")
                                <span class="d-inline-block" tabindex="0" data-bs-toggle="tooltip" data-bs-title="Proses Karyawan Terbaik sudah selesai.">
                                    <a class="btn btn-success disabled">
                                        <i class="bi bi-clipboard2-check"></i>
                                        Selesai
                                    </a>
                                </span>
                                @elseif ($scores->where('id_period', $period->id_period)->where('status', 'Accepted')->count() == $officers->count())
                                <a class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modal-res-finish-{{ $period->id_period }}">
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
                    </div>
                </p>
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
                        @forelse ($scores->where('id_period', $period->id_period) as $score)
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
                                @else
                                <span class="badge text-bg-secondary">Blank</span>
                                @endif
                            </td>
                            <td>
                                <div class="dropdown">
                                    @if ($period->status == 'Finish' || $period->status == 'Voting')
                                    <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false" disabled>
                                        <i class="bi bi-menu-button-fill"></i>
                                    </button>
                                    @else
                                    <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="bi bi-menu-button-fill"></i>
                                    </button>
                                    @endif
                                    <ul class="dropdown-menu mx-0 shadow w-table-menu">
                                        <li>
                                            <a class="dropdown-item d-flex gap-2 align-items-center" href="#" data-bs-toggle="modal" data-bs-target="#modal-res-yes-{{ $period->id_period }}-{{ $score->id }}"><svg class="bi" width="16" height="16" style="vertical-align: -.125em;"><use xlink:href="#yes"/></svg>
                                                Ya
                                            </a>
                                            <a class="dropdown-item d-flex gap-2 align-items-center" href="#" data-bs-toggle="modal" data-bs-target="#modal-res-no-{{ $period->id_period }}-{{ $score->id }}"><svg class="bi" width="16" height="16" style="vertical-align: -.125em;"><use xlink:href="#no"/></svg>
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
                                <td colspan="10">Total Data: <b>{{ $scores->where('id_period', $period->id_period)->count() }}</b> Pegawai</td>
                            </tr>
                        </tfoot>
                    </tfoot>
                </table>
            </div>
            @empty
            <div class="tab-pane fade show active" id="pills-empty" role="tabpanel" aria-labelledby="pills-empty-tab" tabindex="0">
                <div class="alert alert-danger" role="alert">
                    <p>Tidak ada data yang terdaftar.</p>
                </div>
            </div>
            @endforelse
        </div>
    </div>
</div>
