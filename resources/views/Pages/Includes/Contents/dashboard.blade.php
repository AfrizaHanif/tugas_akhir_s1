<h1 class="text-center mb-4">Selamat Datang, {{ Auth::user()->officer->name }}</h1>
<!--SCORE ANT VOTE ALERT-->
@if (!empty($latest_per->status))
    @if ($latest_per->status == 'Scoring')
    @elseif ($latest_per->status == 'Voting')
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

@if (Auth::user()->part != "Pegawai")
<div class="row align-items-md-stretch">
    <!--DATA INPUT COUNTER CARD-->
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-10">
                        @if (Auth::user()->part == "Admin")
                        <h4 class="card-title">Presensi Terinput</h4>
                        @elseif (Auth::user()->part == "KBU" || Auth::user()->part == "KTT")
                        <h4 class="card-title">Nilai Terinput</h4>
                        @elseif (Auth::user()->part == "KBPS")
                        <h4 class="card-title">Pending Confirm</h4>
                        @endif
                    </div>
                    <div class="col-2 d-grid gap-2 d-md-flex justify-content-md-end">
                        @if (!empty($latest_per->id_period))
                            @if (Auth::user()->part == "Admin")
                            <h4>{{ count($count_pre->where('id_period', $latest_per->id_period)->whereIn('status', ['Pending', 'In Review', 'Final', 'Need Fix'])) }}/{{ count($input_off) ?? '-' }}</h4>
                            @elseif (Auth::user()->part == "KBU" || Auth::user()->part == "KTT")
                            <h4>{{ count($count_per->where('id_period', $latest_per->id_period)->whereIn('status', ['Pending', 'In Review', 'Final', 'Need Fix'])) }}/{{ count($input_off) ?? '-' }}</h4>
                            @elseif (Auth::user()->part == "KBPS")
                            <h4>{{ count($scores->where('id_period', $latest_per->id_period)->where('status', 'Pending')) ?? '-' }}/{{ count($input_off) }}</h4>
                            @endif
                        @else
                        <h4>-/{{ count($input_off) }}</h4>
                        @endif
                    </div>
                </div>
            </div>
            <div class="card-footer text-body-secondary">
                <div class="row align-items-center">
                    <div class="col-9">
                        Periode: {{ $latest_per->month ?? 'Belum Aktif' }} {{ $latest_per->year ?? '' }}
                    </div>
                    <div class="col-3 d-grid gap-2 d-md-flex justify-content-md-end">
                        @if (!empty($latest_per->id_period))
                            @if (Auth::user()->part == "KBPS")
                            <a href="{{ route('admin.inputs.scores.index') }}" type="button" class="btn btn-primary btn-sm">Lihat</a>
                            @else
                            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modal-inp-view-{{ $latest_per->id_period }}">Lihat</button>
                            @endif
                        @else
                        <button type="button" class="btn btn-secondary btn-sm" disabled>Lihat</button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--REJECTED INPUT COUNTER CARD-->
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-10">
                        <h4 class="card-title">Nilai Ditolak</h4>
                    </div>
                    <div class="col-2 d-grid gap-2 d-md-flex justify-content-md-end">
                        @if (!empty($latest_per->id_period))
                            @if (Auth::user()->part == "Admin")
                            <h4>{{ count($count_pre->where('id_period', $latest_per->id_period)->where('status', 'Need Fix')) ?? '-' }}</h4>
                            @elseif (Auth::user()->part == "KBU" || Auth::user()->part == "KTT")
                            <h4>{{ count($count_per->where('id_period', $latest_per->id_period)->where('status', 'Need Fix')) ?? '-' }}</h4>
                            @elseif (Auth::user()->part == "KBPS")
                            <h4>{{ count($scores->where('id_period', $latest_per->id_period)->where('status', 'Rejected')) ?? '-' }}</h4>
                            @endif
                        @else
                        <h4>-</h4>
                        @endif
                    </div>
                </div>
            </div>
            <div class="card-footer text-body-secondary">
                <div class="row align-items-center">
                    <div class="col-9">
                        Periode: {{ $latest_per->month ?? 'Belum Aktif' }} {{ $latest_per->year ?? '' }}
                    </div>
                    <div class="col-3 d-grid gap-2 d-md-flex justify-content-md-end">
                        @if (!empty($latest_per->id_period))
                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modal-inp-reject-{{ $latest_per->id_period }}">Lihat</button>
                        @else
                        <button type="button" class="btn btn-secondary btn-sm" disabled>Lihat</button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--VOTE COUNTER CARD-->
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-10">
                        <h4 class="card-title">Pegawai Memilih</h4>
                    </div>
                    <div class="col-2 d-grid gap-2 d-md-flex justify-content-md-end">
                        @if (!empty($latest_per->id_period))
                        <h4>{{ count($check->where('id_period', $latest_per->id_period)) ?? '-' }}/{{ count($vote_officer) }}</h4>
                        @else
                        <h4>-/{{ count($vote_officer) }}</h4>
                        @endif
                    </div>
                </div>
            </div>
            <div class="card-footer text-body-secondary">
                <div class="row align-items-center">
                    <div class="col-9">
                        Periode: {{ $latest_per->month ?? 'Belum Aktif' }} {{ $latest_per->year ?? '' }}
                    </div>
                    <div class="col-3 d-grid gap-2 d-md-flex justify-content-md-end">
                        @if (!empty($latest_per->id_period))
                            @if ($latest_per->status == 'Voting')
                            <a href="{{ route('admin.inputs.votes.vote', $latest_per->id_period) }}" type="button" class="btn btn-primary btn-sm">Lihat</a>
                            @else
                            <button type="button" class="btn btn-secondary btn-sm" disabled>Lihat</button>
                            @endif
                        @else
                        <button type="button" class="btn btn-secondary btn-sm" disabled>Lihat</button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<br/>
@endif
<div class="row align-items-md-stretch">
    <!--RESULT CARD-->
    <div class="col-md-6">
        <div id="carousel-results" class="carousel slide">
            <div class="carousel-indicators" style="bottom: -20px; filter: invert(100%)">
                <button type="button" data-bs-target="#carousel-results" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                <button type="button" data-bs-target="#carousel-results" data-bs-slide-to="1" aria-label="Slide 2"></button>
            </div>
            <div class="carousel-inner">
                <!--VOTE RESULT CARD-->
                <div class="carousel-item active">
                    <div class="card">
                        <div class="row g-0">
                            <div class="col-auto">
                                @if (!empty($latest_best->officer->photo))
                                <img src="{{ url('Images/Portrait/'.$latest_best->officer->photo) }}" onerror="this.onerror=null; this.src='{{ asset('Images/Default/Portrait.png') }}'" class="img-fluid" style="height:140px;border-top-left-radius:7px;" alt="...">
                                @else
                                <img src="{{ url('Images/Portrait/'.$latest_best) }}" onerror="this.onerror=null; this.src='{{ asset('Images/Default/Portrait.png') }}'" class="img-fluid" style="height:140px;border-top-left-radius:7px;" alt="...">
                                @endif
                            </div>
                            <div class="col-auto">
                                <div class="card-body">
                                    <h4 class="card-title">Karyawan Terbaik Saat Ini</h4>
                                    <h5 class="card-text">{{ $latest_best->officer->name ?? 'Belum Ada' }}</h5>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-body-secondary">
                            <div class="row align-items-center">
                                <div class="col-9 px-4">
                                    Periode: {{ $latest_best->period->month ?? 'Not Available' }} {{ $latest_best->period->year ?? '' }}
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
                <!--SCORE RESULT CARD-->
                <div class="carousel-item">
                    <div class="card">
                        <div class="row g-0">
                            <div class="col-auto">
                                <div class="card-body px-4" style="height:140px">
                                    <h4 class="card-title">Calon Karyawan Terbaik</h4>
                                    <ol class="card-text">
                                        @if (!empty($latest_per->id_period))
                                            @forelse ($latest_top3->where('id_period', $latest_per->id_period) as $latest)
                                            <li>{{ $latest->officer->name}}</li>
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
                                    Periode: {{ $latest_per->month ?? 'Belum Aktif' }} {{ $latest_per->year ?? '' }}
                                </div>
                                <div class="col-3 px-4 d-grid gap-2 d-md-flex justify-content-md-end">
                                    @if (!empty($latest_per->id_period))
                                        @if ($latest_per->status == 'Voting')
                                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modal-score-{{ $latest_per->id_period }}">Lihat</button>
                                        @else
                                        <button type="button" class="btn btn-secondary btn-sm" disabled>Lihat</button>
                                        @endif
                                    @else
                                    <button type="button" class="btn btn-secondary btn-sm" disabled>Lihat</button>
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
    <!--INFO CARD-->
    <div class="col-md-6">
        <div id="carouselExampleIndicators" class="carousel slide carousel-fade">
            <div class="carousel-indicators" style="bottom: -20px;">
                <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1" aria-label="Slide 2"></button>
                <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2" aria-label="Slide 3"></button>
            </div>
            <div class="carousel-inner">
                <div class="carousel-item active" style="position: relative !important;">
                    <img src="{{ asset('Images/Test.webp') }}" class="d-block object-fit-cover rounded-3" style="display:block; height:185px; width:100%;" alt="...">
                    <div class="carousel-overlay rounded-3"></div>
                    <div class="carousel-caption d-none d-md-block" style="top:0; bottom:auto; left:6%; right:6%; text-align:left;">
                        <h5 class="card-title">Panduan Penggunaan</h5>
                        <p class="card-text">Apakah anda baru pertama kali memakai aplikasi ini? Klik tombol ini untuk melihat panduan penggunaan.</p>
                        <button type="button" class="btn btn-primary btn-sm">Buka Panduan</button>
                    </div>
                </div>
                <div class="carousel-item">
                    <img src="{{ asset('Images/Test.webp') }}" class="d-block object-fit-cover rounded-3" style="display:block; height:185px; width:100%;" alt="...">
                    <div class="carousel-overlay rounded-3"></div>
                </div>
                <div class="carousel-item">
                    <img src="{{ asset('Images/Test.webp') }}" class="d-block object-fit-cover rounded-3" style="display:block; height:185px; width:100%;" alt="...">
                    <div class="carousel-overlay rounded-3"></div>
                </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev" style="width: 5%">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next" style="width: 5%">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </div>
</div>
<br/>
