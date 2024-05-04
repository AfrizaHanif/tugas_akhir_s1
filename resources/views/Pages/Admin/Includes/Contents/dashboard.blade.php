<h1 class="text-center mb-4">Selamat Datang, {{ Auth::user()->officer->name }}</h1>
@if ($period->status == 'Scoring')

@elseif ($period->status == 'Voting')
    @if ($vote_check->where('id_period', $period->id_period)->where('id_officer', Auth::user()->id_officer)->count() == 0)
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        Anda belum melakukan voting pemilihan karyawan terbaik. Silahkan buka halaman <strong>Voting</strong> untuk memilih karyawan.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @elseif ($vote_check->where('id_period', $period->id_period)->where('id_officer', Auth::user()->id_officer)->count() == count($vote_criterias))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        Terima kasih anda telah melakukan voting pemilihan karyawan terbaik. Mohon menunggu pengumuman hasil pemilihan karyawan terbaik.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @else
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
        Anda melakukan voting sebagian. Silahkan melanjutkan voting pemilihan karyawan terbaik di halaman <strong>Voting</strong>.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif
@endif
<div class="row align-items-md-stretch">
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
                        @if (Auth::user()->part == "Admin")
                        <h4>{{ count($count_pre->where('id_period', $period->id_period)->whereIn('status', ['Pending', 'In Review', 'Final', 'Need Fix'])) }}/{{ count($input_off) ?? '-' }}</h4>
                        @elseif (Auth::user()->part == "KBU" || Auth::user()->part == "KTT")
                        <h4>{{ count($count_per->where('id_period', $period->id_period)->whereIn('status', ['Pending', 'In Review', 'Final', 'Need Fix'])) }}/{{ count($input_off) ?? '-' }}</h4>
                        @elseif (Auth::user()->part == "KBPS")
                        <h4>{{ count($scores->where('id_period', $period->id_period)->where('status', 'Pending')) ?? '-' }}/{{ count($input_off) }}</h4>
                        @endif
                    </div>
                </div>
            </div>
            <div class="card-footer text-body-secondary">
                <div class="row align-items-center">
                    <div class="col-9">
                        Periode: {{ $period->month ?? 'Belum Aktif' }} {{ $period->year ?? '' }}
                    </div>
                    <div class="col-3 d-grid gap-2 d-md-flex justify-content-md-end">
                        @if (Auth::user()->part == "KBPS")
                        <a href="{{ route('admin.inputs.scores.index') }}" type="button" class="btn btn-primary btn-sm">Lihat</a>
                        @else
                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modal-inp-view-{{ $period->id_period }}">Lihat</button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-10">
                        <h4 class="card-title">Nilai Ditolak</h4>
                    </div>
                    <div class="col-2 d-grid gap-2 d-md-flex justify-content-md-end">
                        @if (Auth::user()->part == "Admin")
                        <h4>{{ count($count_pre->where('id_period', $period->id_period)->where('status', 'Need Fix')) ?? '-' }}</h4>
                        @elseif (Auth::user()->part == "KBU" || Auth::user()->part == "KTT")
                        <h4>{{ count($count_per->where('id_period', $period->id_period)->where('status', 'Need Fix')) ?? '-' }}</h4>
                        @elseif (Auth::user()->part == "KBPS")
                        <h4>{{ count($scores->where('id_period', $period->id_period)->where('status', 'Rejected')) ?? '-' }}</h4>
                        @endif
                    </div>
                </div>
            </div>
            <div class="card-footer text-body-secondary">
                <div class="row align-items-center">
                    <div class="col-9">
                        Periode: {{ $period->month ?? 'Belum Aktif' }} {{ $period->year ?? '' }}
                    </div>
                    <div class="col-3 d-grid gap-2 d-md-flex justify-content-md-end">
                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modal-inp-reject-{{ $period->id_period }}">Lihat</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-10">
                        <h4 class="card-title">Pegawai Memilih</h4>
                    </div>
                    <div class="col-2 d-grid gap-2 d-md-flex justify-content-md-end">
                        <h4>{{ count($check->where('id_period', $period->id_period)) ?? '-' }}/{{ count($vote_officer) }}</h4>
                    </div>
                </div>
            </div>
            <div class="card-footer text-body-secondary">
                <div class="row align-items-center">
                    <div class="col-9">
                        Periode: {{ $period->month ?? 'Belum Aktif' }} {{ $period->year ?? '' }}
                    </div>
                    <div class="col-3 d-grid gap-2 d-md-flex justify-content-md-end">
                        @if ($period->status == 'Voting')
                        <a href="{{ route('admin.inputs.votes.vote', $period->id_period) }}" type="button" class="btn btn-primary btn-sm">Lihat</a>
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
<div class="row align-items-md-stretch">
    <div class="col-md-6">
        <div class="card mb-3">
            <div class="row g-0">
                <div class="col-auto">
                    <img src="{{ url('Images/Portrait/'.$latest_best) }}" onerror="this.onerror=null; this.src='{{ asset('Images/Default/Portrait.png') }}'" class="img-fluid" style="height:140px;border-top-left-radius:7px;" alt="...">
                </div>
                <div class="col-9">
                    <div class="card-body">
                        <h4 class="card-title">Karyawan Terbaik Saat Ini</h4>
                        <h5 class="card-text">{{ $latest_best->id_officer ?? 'Belum Ada' }}</h5>
                    </div>
                </div>
            </div>
            <div class="card-footer text-body-secondary">
                <div class="row align-items-center">
                    <div class="col-9">
                        Periode: {{ $latest_best->period->month ?? 'Not Available' }} {{ $latest_best->period->year ?? '' }}
                    </div>
                    <div class="col-3 d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="{{ route('admin.results') }}" type="button" class="btn btn-primary btn-sm">Lihat</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card text-bg-dark">
            <img src="{{ asset('Images/Test.webp') }}" class="card-img object-fit-cover" style="display:block; height:185px; width:100%;" alt="...">
            <div class="card-img-overlay" style="background:linear-gradient(to right, rgba(0,0,0,1), rgba(0,0,0,0));">
                <h5 class="card-title">Panduan Penggunaan</h5>
                <p class="card-text">Apakah anda baru pertama kali memakai aplikasi ini? Klik tombol ini untuk melihat panduan penggunaan.</p>
                <button type="button" class="btn btn-primary btn-sm">Buka Panduan</button>
            </div>
        </div>
    </div>
</div>
<br/>
