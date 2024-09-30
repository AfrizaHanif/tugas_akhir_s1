<h1 class="text-center mb-4">Selamat Datang, {{ Auth::user()->officer->name }}</h1>
<!--SCORE ANT VOTE ALERT (OPT: REMOVE)-->
@if (Auth::user()->part != "Dev")
    @if (!empty($latest_per->progress_status))
        @if ($latest_per->progress_status == 'Scoring' || $latest_per->progress_status == 'Validating')
        @elseif ($latest_per->progress_status == 'Voting')
            @if ($vote_check->where('id_period', $latest_per->id_period)->where('id_officer', Auth::user()->id_officer)->count() == 0)
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                Anda belum melakukan voting pemilihan karyawan terbaik. Silahkan buka halaman <strong>Voting</strong> untuk memilih karyawan.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @elseif ($vote_check->where('id_period', $latest_per->id_period)->where('id_officer', Auth::user()->id_officer)->count() == count($vote_criterias))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                Terima kasih anda telah melakukan voting pemilihan karyawan terbaik. Mohon menunggu pengumuman hasil pemilihan karyawan terbaik.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @else
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                Anda telah melakukan voting sebagian. Silahkan melanjutkan voting pemilihan karyawan terbaik di halaman <strong>Voting</strong>.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif
        @endif
    @endif
@endif

<!--CARDS-->
@if (Auth::user()->part == "Admin")
<div class="row row-cols-1 row-cols-md-3 align-items-md-stretch g-4">
    <!--DATA INPUT COUNTER CARD-->
    <div class="col">
        <div class="card h-100">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-10">
                        <h4 class="card-title">Data Terinput</h4>
                    </div>
                    <div class="col-2 d-grid gap-2 d-md-flex justify-content-md-end">
                        @if (!empty($latest_per->id_period))
                        <h4>{{ count($count->where('id_period', $latest_per->id_period)->whereIn('status', ['Pending', 'In Review', 'Final', 'Need Fix', 'Fixed'])) }}/{{ count($input_off) ?? '-' }}</h4>
                        @else
                        <h4>-/{{ count($input_off) }}</h4>
                        @endif
                    </div>
                </div>
            </div>
            @if (!empty($latest_per->id_period))
            <div class="progress-stacked" style="border-radius: 0px; height: 5px">
                <div class="progress" role="progressbar" aria-label="Basic example" aria-valuenow="{{ count($count->where('id_period', $latest_per->id_period)->whereIn('status', ['Pending', 'In Review', 'Final', 'Need Fix', 'Fixed'])) }}" aria-valuemin="0" aria-valuemax="{{ count($input_off) }}" style="width: {{ (count($count->where('id_period', $latest_per->id_period)->whereIn('status', ['Pending', 'In Review', 'Final', 'Need Fix', 'Fixed']))*100)/count($input_off) }}%">
                    <div class="progress-bar bg-success progress-bar-striped progress-bar-animated" ></div>
                </div>
            </div>
            @else
            <div class="progress" role="progressbar" aria-label="Basic example" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="border-radius: 0px; height: 5px">
                <div class="progress-bar progress-bar-striped progress-bar-animated" style="width: 0%"></div>
            </div>
            @endif
            <div class="card-footer text-body-secondary">
                <div class="row align-items-center">
                    <div class="col-9">
                        Periode: {{ $latest_per->month ?? 'Belum Aktif' }} {{ $latest_per->year ?? '' }}
                    </div>
                    <div class="col-3 d-grid gap-2 d-md-flex justify-content-md-end">
                        @if (!empty($latest_per->id_period))
                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modal-inp-view-{{ $latest_per->id_period }}">Cek</button>
                        @else
                        <button type="button" class="btn btn-secondary btn-sm" disabled>Cek</button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--REJECTED INPUT COUNTER CARD-->
    <div class="col">
        <div class="card h-100">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-10">
                        <h4 class="card-title">Nilai Ditolak</h4>
                    </div>
                    <div class="col-2 d-grid gap-2 d-md-flex justify-content-md-end">
                        @if (!empty($latest_per->id_period))
                        <h4>{{ count($count->where('id_period', $latest_per->id_period)->where('status', 'Need Fix')) ?? '-' }}</h4>
                        @else
                        <h4>-</h4>
                        @endif
                    </div>
                </div>
            </div>
            @if (!empty($latest_per->id_period))
            <div class="progress-stacked" style="border-radius: 0px; height: 5px">
                <div class="progress" role="progressbar" aria-label="Basic example" aria-valuenow="{{ count($scores->where('id_period', $latest_per->id_period)->where('status', 'Revised')) }}" aria-valuemin="0" aria-valuemax="{{ count($input_off) }}" style="width: {{ (count($scores->where('id_period', $latest_per->id_period)->where('status', 'Revised'))*100)/count($input_off) }}%">
                    <div class="progress-bar progress-bar-striped progress-bar-animated"></div>
                </div>
                <div class="progress" role="progressbar" aria-label="Basic example" aria-valuenow="{{ count($count->where('id_period', $latest_per->id_period)->where('status', 'Need Fix')) }}" aria-valuemin="0" aria-valuemax="{{ count($input_off) }}" style="width: {{ (count($count->where('id_period', $latest_per->id_period)->where('status', 'Need Fix'))*100)/count($input_off) }}%">
                    <div class="progress-bar bg-danger progress-bar-striped progress-bar-animated"></div>
                </div>
            </div>
            @else
            <div class="progress" role="progressbar" aria-label="Basic example" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="border-radius: 0px; height: 5px">
                <div class="progress-bar progress-bar-striped progress-bar-animated" style="width: 0%"></div>
            </div>
            @endif
            <div class="card-footer text-body-secondary">
                <div class="row align-items-center">
                    <div class="col-9">
                        Periode: {{ $latest_per->month ?? 'Belum Aktif' }} {{ $latest_per->year ?? '' }}
                    </div>
                    <div class="col-3 d-grid gap-2 d-md-flex justify-content-md-end">
                        @if (!empty($latest_per->id_period))
                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modal-inp-reject-{{ $latest_per->id_period }}">Cek</button>
                        @else
                        <button type="button" class="btn btn-secondary btn-sm" disabled>Cek</button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--PERIOD STATUS CARD-->
    <div class="col">
        <div class="card h-100">
            <div class="card-body">
                <div class="row align-items-center">
                    @if (!empty($latest_per))
                        @if ($latest_per->progress_status == 'Scoring')
                        <h4 class="card-title">Status: Aktif</h4>
                        @elseif ($latest_per->progress_status == 'Validating')
                        <h4 class="card-title">Status: Verifikasi</h4>
                        @endif
                    @else
                    <h4 class="card-title">Status: Belum Aktif</h4>
                    @endif
                </div>
            </div>
            <div class="card-footer text-body-secondary">
                <div class="row align-items-center">
                    <div class="col-9">
                        Periode: {{ $latest_per->month ?? 'Belum Aktif' }} {{ $latest_per->year ?? '' }}
                    </div>
                    <div class="col-3 d-grid gap-2 d-md-flex justify-content-md-end">
                        <a type="button" href="/admin/masters/periods" class="btn btn-primary btn-sm">Cek</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<br/>
@endif
@if (Auth::user()->part == "KBPS")
<div class="row row-cols-1 row-cols-md-3 align-items-md-stretch g-4">
    <!--DATA INPUT COUNTER CARD-->
    <div class="col">
        <div class="card h-100">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-10">
                        <h4 class="card-title">Pending Proses Penilaian</h4>
                    </div>
                    <div class="col-2 d-grid gap-2 d-md-flex justify-content-md-end">
                        @if (!empty($latest_per->id_period))
                        <h4>{{ count($count->where('id_period', $latest_per->id_period)->whereIn('status', ['Pending', 'Fixed'])) ?? '-' }}</h4>
                        @else
                        <h4>-</h4>
                        @endif
                    </div>
                </div>
            </div>
            @if (!empty($latest_per->id_period))
            <div class="progress-stacked" style="border-radius: 0px; height: 5px">
                <div class="progress" role="progressbar" aria-label="Basic example" aria-valuenow="{{ count($count->where('id_period', $latest_per->id_period)->whereIn('status', ['Pending', 'Fixed'])) }}" aria-valuemin="0" aria-valuemax="{{ count($input_off) }}" style="width: {{ (count($count->where('id_period', $latest_per->id_period)->whereIn('status', ['Pending', 'Fixed']))*100)/count($input_off) }}%">
                    <div class="progress-bar bg-primary progress-bar-striped progress-bar-animated"></div>
                </div>
            </div>
            @else
            <div class="progress" role="progressbar" aria-label="Basic example" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="border-radius: 0px; height: 5px">
                <div class="progress-bar progress-bar-striped progress-bar-animated" style="width: 0%"></div>
            </div>
            @endif
            <div class="card-footer text-body-secondary">
                <div class="row align-items-center">
                    <div class="col-9">
                        Periode: {{ $latest_per->month ?? 'Belum Aktif' }} {{ $latest_per->year ?? '' }}
                    </div>
                    <div class="col-3 d-grid gap-2 d-md-flex justify-content-md-end">
                        @if (!empty($latest_per->id_period))
                        <a href="{{ route('admin.inputs.validate.index') }}" type="button" class="btn btn-primary btn-sm">Cek</a>
                        @else
                        <button type="button" class="btn btn-secondary btn-sm" disabled>Cek</button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--PENDING CONFIRM-->
    <div class="col">
        <div class="card h-100">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-10">
                        <h4 class="card-title">Pending Persetujuan Penilaian</h4>
                    </div>
                    <div class="col-2 d-grid gap-2 d-md-flex justify-content-md-end">
                        @if (!empty($latest_per->id_period))
                        <h4>{{ count($count->where('id_period', $latest_per->id_period)->whereIn('status', ['Pending', 'In Review', 'Fixed'])) ?? '-' }}</h4>
                        @else
                        <h4>-</h4>
                        @endif
                    </div>
                </div>
            </div>
            @if (!empty($latest_per->id_period))
            <div class="progress-stacked" style="border-radius: 0px; height: 5px">
                <div class="progress" role="progressbar" aria-label="Basic example" aria-valuenow="{{ count($count->where('id_period', $latest_per->id_period)->whereIn('status', ['Pending', 'In Review', 'Fixed'])) }}" aria-valuemin="0" aria-valuemax="{{ count($input_off) }}" style="width: {{ (count($count->where('id_period', $latest_per->id_period)->whereIn('status', ['Pending', 'In Review', 'Fixed']))*100)/count($input_off) }}%">
                    <div class="progress-bar bg-warning progress-bar-striped progress-bar-animated"></div>
                </div>
            </div>
            @else
            <div class="progress" role="progressbar" aria-label="Basic example" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="border-radius: 0px; height: 5px">
                <div class="progress-bar progress-bar-striped progress-bar-animated" style="width: 0%"></div>
            </div>
            @endif
            <div class="card-footer text-body-secondary">
                <div class="row align-items-center">
                    <div class="col-9">
                        Periode: {{ $latest_per->month ?? 'Belum Aktif' }} {{ $latest_per->year ?? '' }}
                    </div>
                    <div class="col-3 d-grid gap-2 d-md-flex justify-content-md-end">
                        @if (!empty($latest_per->id_period))
                        <a href="{{ route('admin.inputs.validate.index') }}" type="button" class="btn btn-primary btn-sm">Cek</a>
                        @else
                        <button type="button" class="btn btn-secondary btn-sm" disabled>Cek</button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--REJECTED INPUT COUNTER CARD (KBPS)-->
    <div class="col">
        <div class="card h-100">
            <div class="card-body">
                <div class="row align-items-center h-100">
                    <div class="col-10">
                        <h4 class="card-title">Nilai Ditolak</h4>
                    </div>
                    <div class="col-2 d-grid gap-2 d-md-flex justify-content-md-end">
                        @if (!empty($latest_per))
                        <h4>{{ count($scores->where('id_period', $latest_per->id_period)->where('status', 'Rejected')) ?? '-' }}</h4>
                        @else
                        <h4>-</h4>
                        @endif
                    </div>
                </div>
            </div>
            @if (!empty($latest_per->id_period))
            <div class="progress-stacked" style="border-radius: 0px; height: 5px">
                <div class="progress" role="progressbar" aria-label="Basic example" aria-valuenow="{{ count($scores->where('id_period', $latest_per->id_period)->where('status', 'Revised')) }}" aria-valuemin="0" aria-valuemax="{{ count($input_off) }}" style="width: {{ (count($scores->where('id_period', $latest_per->id_period)->where('status', 'Revised'))*100)/count($input_off) }}%">
                    <div class="progress-bar progress-bar-striped progress-bar-animated"></div>
                </div>
                <div class="progress" role="progressbar" aria-label="Basic example" aria-valuenow="{{ count($scores->where('id_period', $latest_per->id_period)->where('status', 'Rejected')) }}" aria-valuemin="0" aria-valuemax="{{ count($input_off) }}" style="width: {{ (count($scores->where('id_period', $latest_per->id_period)->where('status', 'Rejected'))*100)/count($input_off) }}%">
                    <div class="progress-bar bg-danger progress-bar-striped progress-bar-animated"></div>
                </div>
            </div>
            @else
            <div class="progress" role="progressbar" aria-label="Basic example" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="border-radius: 0px; height: 5px">
                <div class="progress-bar progress-bar-striped progress-bar-animated" style="width: 0%"></div>
            </div>
            @endif
            <div class="card-footer text-body-secondary">
                <div class="row align-items-center">
                    <div class="col-9">
                        Periode: {{ $latest_per->month ?? 'Belum Aktif' }} {{ $latest_per->year ?? '' }}
                    </div>
                    <div class="col-3 d-grid gap-2 d-md-flex justify-content-md-end">
                        @if (!empty($latest_per->id_period))
                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modal-inp-reject-{{ $latest_per->id_period }}">Cek</button>
                        @else
                        <a type="button" class="btn btn-secondary btn-sm disabled">Cek</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<br/>
@endif
@if (Auth::user()->part == "Admin" || Auth::user()->part == "KBPS")
<div class="row align-items-md-stretch g-4">
    <div class="col-3">
    </div>
    <!--RESULT CARD-->
    <div class="col-6">
        <div id="carousel-results" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-indicators" style="bottom: -20px; filter: invert(100%)">
                <button type="button" data-bs-target="#carousel-results" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                <button type="button" data-bs-target="#carousel-results" data-bs-slide-to="1" aria-label="Slide 2"></button>
            </div>
            <div class="carousel-inner">
                <!--CHOOSEN CARD-->
                <div class="carousel-item active">
                    <div class="card">
                        <div class="row g-0">
                            <div class="col-auto">
                                @if (!empty($latest_best))
                                <img src="{{ url('Images/History/Portrait/'.$latest_best->officer_photo) }}" onerror="this.onerror=null; this.src='{{ asset('Images/Default/Portrait.png') }}'" class="img-fluid" style="height:140px;border-top-left-radius:7px;" alt="...">
                                @else
                                <img src="{{ url('Images/History/Portrait/'.$latest_best) }}" onerror="this.onerror=null; this.src='{{ asset('Images/Default/Portrait.png') }}'" class="img-fluid" style="height:140px;border-top-left-radius:7px;" alt="...">
                                @endif
                            </div>
                            <div class="col-auto">
                                <div class="card-body">
                                    <h4 class="card-title">Karyawan Terbaik Saat Ini</h4>
                                    <h5 class="card-text">{{ $latest_best->officer_name ?? 'Belum Ada' }}</h5>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-body-secondary">
                            <div class="row align-items-center">
                                <div class="col-9 px-4">
                                    Periode: {{ $latest_best->period_name ?? 'Belum Tersedia' }}
                                </div>
                                <div class="col-3 px-4 d-grid gap-2 d-md-flex justify-content-md-end">
                                    @if (count($voteresults) != 0)
                                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modal-best">Riwayat</button>
                                    @else
                                    <button type="button" class="btn btn-secondary btn-sm" disabled>Riwayat</button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--TOP 3 SCORES CARD-->
                <div class="carousel-item">
                    <div class="card">
                        <div class="row g-0">
                            <div class="col-auto">
                                <div class="card-body px-4" style="height:140px">
                                    <h4 class="card-title">Tiga Nilai Akhir Terbaik Saat Ini</h4>
                                    <ol class="card-text">
                                        @if (!empty($history_prd))
                                            @forelse ($latest_top3->where('id_period', $history_prd->id_period)->take(3) as $latest)
                                            <li>{{ $latest->officer_name}}</li>
                                            @empty
                                            <p>Belum Ada</p>
                                            @endforelse
                                        @else
                                        <p>Belum Ada</p>
                                        @endif
                                    </ol>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-body-secondary">
                            <div class="row align-items-center">
                                <div class="col-9 px-4">
                                    Periode: {{ $history_prd->period_name ?? 'Belum Aktif' }}
                                </div>
                                <div class="col-3 px-4 d-grid gap-2 d-md-flex justify-content-md-end">
                                    @if (!empty($history_prd))
                                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modal-score-{{ $history_prd->id_period }}">Details</button>
                                    @else
                                    <button type="button" class="btn btn-secondary btn-sm" disabled>Details</button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <button class="carousel-control-hover carousel-control-prev" type="button" data-bs-target="#carousel-results" data-bs-slide="prev" style="filter: invert(100%); width: 5%; height:185px">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-hover carousel-control-next" type="button" data-bs-target="#carousel-results" data-bs-slide="next" style="filter: invert(100%); width: 5%; height:185px">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </div>
    <div class="col-3">
    </div>
</div>
@endif
@if (Auth::user()->part == "Dev")
<div class="row row-cols-1 row-cols-md-3 align-items-md-stretch g-4">
    <!--OFFICERS COUNTER CARD-->
    <div class="col">
        <div class="card h-100">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-10">
                        <h4 class="card-title">Jumlah Pegawai</h4>
                    </div>
                    <div class="col-2 d-grid gap-2 d-md-flex justify-content-md-end">
                        <h4>{{ count($officers) }}</h4>
                    </div>
                </div>
            </div>
            <div class="card-footer text-body-secondary">
                <div class="row align-items-center">
                    <div class="col-9">

                    </div>
                    <div class="col-3 d-grid gap-2 d-md-flex justify-content-md-end">
                        <button type="button" class="btn btn-secondary btn-sm" disabled>Cek</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--USERS COUNTER CARD-->
    <div class="col">
        <div class="card h-100">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-10">
                        <h4 class="card-title">Jumlah Pengguna</h4>
                    </div>
                    <div class="col-2 d-grid gap-2 d-md-flex justify-content-md-end">
                        <h4>{{ count($users) }}</h4>
                    </div>
                </div>
            </div>
            <div class="card-footer text-body-secondary">
                <div class="row align-items-center">
                    <div class="col-9">

                    </div>
                    <div class="col-3 d-grid gap-2 d-md-flex justify-content-md-end">
                        <button type="button" class="btn btn-secondary btn-sm" disabled>Cek</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--MESSGES COUNTER CARD-->
    <div class="col">
        <div class="card h-100">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-10">
                        <h4 class="card-title">Jumlah Pesan</h4>
                    </div>
                    <div class="col-2 d-grid gap-2 d-md-flex justify-content-md-end">
                        <h4>{{ count($messages) }}</h4>
                    </div>
                </div>
            </div>
            <div class="card-footer text-body-secondary">
                <div class="row align-items-center">
                    <div class="col-9">

                    </div>
                    <div class="col-3 d-grid gap-2 d-md-flex justify-content-md-end">
                        <a type="button" class="btn btn-secondary btn-sm disabled">Cek</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
<br/>
