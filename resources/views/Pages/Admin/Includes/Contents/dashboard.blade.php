<h1 class="text-center mb-4">Selamat Datang, {{ Auth::user()->officer->name }}</h1>
<div class="row align-items-md-stretch">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-10">
                        @if (Auth::user()->part == "Admin")
                        <h4 class="card-title">Presensi Terinput</h4>
                        @elseif (Auth::user()->part == "KBU" || Auth::user()->part == "KTT")
                        <h4 class="card-title">Kinerja Terinput</h4>
                        @elseif (Auth::user()->part == "KBPS")
                        <h4 class="card-title">Pending Confirm</h4>
                        @endif
                    </div>
                    <div class="col-2 d-grid gap-2 d-md-flex justify-content-md-end">
                        @if (Auth::user()->part == "Admin")
                        <h4>{{ count($presences->where('id_period', $latest->id_period)->whereIn('status', ['Pending', 'In Review', 'Final', 'Need Fix'])) }}/{{ count($input_off) ?? '-' }}</h4>
                        @elseif (Auth::user()->part == "KBU" || Auth::user()->part == "KTT")
                        <h4>{{ count($performances->where('id_period', $latest->id_period)->whereIn('status', ['Pending', 'In Review', 'Final', 'Need Fix'])) }}/{{ count($input_off) ?? '-' }}</h4>
                        @elseif (Auth::user()->part == "KBPS")
                        <h4>{{ count($scores->where('id_period', $latest->id_period)->where('status', 'Pending')) ?? '-' }}/{{ count($input_off) }}</h4>
                        @endif
                    </div>
                </div>
            </div>
            <div class="card-footer text-body-secondary">
                <div class="row align-items-center">
                    <div class="col-9">
                        Periode: {{ $latest->month ?? 'Belum Aktif' }} {{ $latest->year ?? '' }}
                    </div>
                    <div class="col-3 d-grid gap-2 d-md-flex justify-content-md-end">
                        @if (Auth::user()->part == "Admin")
                        <a href="{{ route('admin.inputs.presences.index') }}" type="button" class="btn btn-primary btn-sm">Lihat</a>
                        @elseif (Auth::user()->part == "KBU")
                        <a href="{{ route('admin.inputs.kbu.performances.index') }}" type="button" class="btn btn-primary btn-sm">Lihat</a>
                        @elseif (Auth::user()->part == "KTT")
                        <a href="{{ route('admin.inputs.kbu.performances.index') }}" type="button" class="btn btn-primary btn-sm">Lihat</a>
                        @elseif (Auth::user()->part == "KBPS")
                        <a href="{{ route('admin.inputs.scores.index') }}" type="button" class="btn btn-primary btn-sm">Lihat</a>
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
                        <h4>{{ count($presences->where('id_period', $latest->id_period)->where('status', 'Need Fix')) ?? '-' }}</h4>
                        @elseif (Auth::user()->part == "KBU" || Auth::user()->part == "KTT")
                        <h4>{{ count($performances->where('id_period', $latest->id_period)->where('status', 'Need Fix')) ?? '-' }}</h4>
                        @elseif (Auth::user()->part == "KBPS")
                        <h4>{{ count($scores->where('id_period', $latest->id_period)->where('status', 'Rejected')) ?? '-' }}</h4>
                        @endif
                    </div>
                </div>
            </div>
            <div class="card-footer text-body-secondary">
                <div class="row align-items-center">
                    <div class="col-9">
                        Periode: {{ $latest->month ?? 'Belum Aktif' }} {{ $latest->year ?? '' }}
                    </div>
                    <div class="col-3 d-grid gap-2 d-md-flex justify-content-md-end">
                        @if (Auth::user()->part == "Admin")
                        <a href="{{ route('admin.inputs.presences.index') }}" type="button" class="btn btn-primary btn-sm">Lihat</a>
                        @elseif (Auth::user()->part == "KBU")
                        <a href="{{ route('admin.inputs.kbu.performances.index') }}" type="button" class="btn btn-primary btn-sm">Lihat</a>
                        @elseif (Auth::user()->part == "KTT")
                        <a href="{{ route('admin.inputs.kbu.performances.index') }}" type="button" class="btn btn-primary btn-sm">Lihat</a>
                        @elseif (Auth::user()->part == "KBPS")
                        <a href="{{ route('admin.inputs.scores.index') }}" type="button" class="btn btn-primary btn-sm">Lihat</a>
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
                        <h4 class="card-title">Pegawai Memilih</h4>
                    </div>
                    <div class="col-2 d-grid gap-2 d-md-flex justify-content-md-end">
                        <h4>{{ count($check->where('id_period', $latest->id_period)) ?? '-' }}/{{ count($officers) }}</h4>
                    </div>
                </div>
            </div>
            <div class="card-footer text-body-secondary">
                <div class="row align-items-center">
                    <div class="col-9">
                        Periode: {{ $latest->month ?? 'Belum Aktif' }} {{ $latest->year ?? '' }}
                    </div>
                    <div class="col-3 d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="{{ route('admin.inputs.votes.index') }}/{{ $latest->id_period ?? ''}}" type="button" class="btn btn-primary btn-sm">Lihat</a>
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
                <div class="col-md-2">
                    <img src="{{ url('Images/Portrait/'.$latest_best) }}" onerror="this.onerror=null; this.src='{{ asset('Images/Default/Portrait.png') }}'" class="img-fluid" style="border-top-left-radius:7px" alt="...">
                </div>
                <div class="col-md-10">
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
            <img src="{{ asset('Images/Test.webp') }}" class="card-img object-fit-cover" style="display: block; height: 165px; width: 100%;" alt="...">
            <div class="card-img-overlay" style="background:linear-gradient(to right, rgba(0,0,0,1), rgba(0,0,0,0));">
                <h5 class="card-title">Panduan Penggunaan</h5>
                <p class="card-text">Apakah anda baru pertama kali memakai aplikasi ini? Klik tombol ini untuk melihat panduan penggunaan.</p>
                <button type="button" class="btn btn-primary btn-sm">Buka Panduan</button>
            </div>
        </div>
    </div>
</div>
<br/>
