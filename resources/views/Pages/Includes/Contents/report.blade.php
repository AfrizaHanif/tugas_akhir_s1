<h1 class="text-center mb-4">Data Laporan</h1>
@include('Templates.Includes.Components.alert')
<!--MENU-->
<p>
    <!--HELP-->
    <a class="btn btn-secondary" data-bs-toggle="offcanvas" href="#offcanvas-help" role="button" aria-controls="offcanvas-help">
        <i class="bi bi-question-lg"></i>
        Bantuan
    </a>
</p>
<div class="row g-2">
    <!--MAIN CONTENT-->
    <div class="col-md-7">
        <div class="accordion" id="accordionExample">
            @if (Auth::check() && Auth::user()->part != "Pegawai")
            <!--OFFICERS RECORD-->
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-officer" aria-expanded="false" aria-controls="collapse-officer">
                        Laporan Pegawai
                    </button>
                </h2>
                <div id="collapse-officer" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                    <div class="accordion-body">
                        <div class="row align-items-center">
                            <div class="col">
                                Download
                            </div>
                            <div class="col">
                                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                    <a href="{{ route('admin.reports.officers') }}" type="button" class="btn btn-danger" target="_blank" rel="noopener noreferrer">
                                        <i class="bi bi-filetype-pdf"></i>
                                        PDF
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--ANALYSIS REPORT-->
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-analysis" aria-expanded="false" aria-controls="collapse-analysis">
                        Laporan Analisis
                    </button>
                </h2>
                <div id="collapse-analysis" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                    <div class="accordion-body">
                        <div class="accordion" id="accordion-analysis">
                            @forelse ($h_years as $h_year)
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-analysis-{{ $h_year->period_year }}" aria-expanded="true" aria-controls="collapse-analysis-{{ $h_year->period_year }}">
                                        {{ $h_year->period_year }}
                                    </button>
                                </h2>
                                <div id="collapse-analysis-{{ $h_year->period_year }}" class="accordion-collapse collapse" data-bs-parent="#accordion-analysis">
                                    <div class="accordion-body">
                                        @forelse ($h_months->where('period_year', $h_year->period_year) as $h_month)
                                        <div class="row align-items-center pt-1">
                                            <div class="col">
                                                {{ $h_month->period_month }}
                                            </div>
                                            <div class="col">
                                                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                                    <a href="{{ route('admin.reports.analysis', ['month'=>$h_month->period_month,'year'=>$h_year->period_year]) }}" type="button" class="btn btn-danger" target="_blank" rel="noopener noreferrer">
                                                        <i class="bi bi-filetype-pdf"></i>
                                                        PDF
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        @empty
                                        <div class="row align-items-center">
                                            <div class="col">
                                                <div class="alert alert-danger" role="alert">
                                                    <i class="bi bi-x-octagon-fill"></i> <strong>ERROR</strong>
                                                    <br/>
                                                    Tidak Ada Laporan
                                                </div>
                                            </div>
                                        </div>
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                            @empty
                            <div class="row align-items-center">
                                <div class="col">
                                    <div class="alert alert-danger" role="alert">
                                        <i class="bi bi-x-octagon-fill"></i> <strong>ERROR</strong>
                                        <br/>
                                        Tidak Ada Laporan
                                    </div>
                                </div>
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
            <!--TEAM RESULT REPORT-->
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-team-result" aria-expanded="false" aria-controls="collapse-team-result">
                        Laporan Nilai Akhir (Per Tim Teknis)
                    </button>
                </h2>
                <div id="collapse-team-result" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                    <div class="accordion-body">
                        <div class="accordion" id="accordion-team-result">
                            @forelse ($h_subteams as $h_subteam)
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-{{ $h_subteam->id_sub_team }}" aria-expanded="true" aria-controls="collapse-{{ $h_subteam->id_sub_team }}">
                                        {{ $h_subteam->sub_team_1_name }}
                                    </button>
                                </h2>
                                <div id="collapse-{{ $h_subteam->id_sub_team }}" class="accordion-collapse collapse" data-bs-parent="#accordion-team-result">
                                    <div class="accordion-body">
                                        <div class="accordion" id="accordion-team-result-{{ $h_subteam->id_sub_team }}">
                                            @forelse ($h_team_years->where('id_sub_team', $h_subteam->id_sub_team) as $h_year)
                                            <div class="accordion-item">
                                                <h2 class="accordion-header">
                                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-result-{{ $h_subteam->id_sub_team }}-{{ $h_year->period_year }}" aria-expanded="true" aria-controls="collapse-result-{{ $h_subteam->id_sub_team }}-{{ $h_year->period_year }}">
                                                        {{ $h_year->period_year }}
                                                    </button>
                                                </h2>
                                                <div id="collapse-result-{{ $h_subteam->id_sub_team }}-{{ $h_year->period_year }}" class="accordion-collapse collapse" data-bs-parent="#accordion-team-result-{{ $h_subteam->id_sub_team }}">
                                                    <div class="accordion-body">
                                                        @forelse ($h_months->where('period_year', $h_year->period_year) as $h_month)
                                                            @foreach ($h_scores->where('id_sub_team', $h_subteam->id_sub_team)->where('period_year', $h_year->period_year)->where('period_month', $h_month->period_month) as $h_score)
                                                            <div class="row align-items-center pt-1">
                                                                <div class="col">
                                                                    {{ $h_score->period_month }}
                                                                </div>
                                                                <div class="col">
                                                                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                                                        <a href="{{ route('admin.reports.teamresult', ['subteam'=>$h_score->id_sub_team,'month'=>$h_score->period_month,'year'=>$h_score->period_year]) }}" type="button" class="btn btn-danger" target="_blank" rel="noopener noreferrer">
                                                                            <i class="bi bi-filetype-pdf"></i>
                                                                            PDF
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            @endforeach
                                                        @empty
                                                        <div class="row align-items-center">
                                                            <div class="col">
                                                                <div class="alert alert-danger" role="alert">
                                                                    <i class="bi bi-x-octagon-fill"></i> <strong>ERROR</strong>
                                                                    <br/>
                                                                    Tidak Ada Laporan
                                                                </div>
                                                            </div>
                                                        </div>
                                                        @endforelse
                                                    </div>
                                                </div>
                                            </div>
                                            @empty
                                            <div class="row align-items-center">
                                                <div class="col">
                                                    <div class="alert alert-danger" role="alert">
                                                        <i class="bi bi-x-octagon-fill"></i> <strong>ERROR</strong>
                                                        <br/>
                                                        Tidak Ada Laporan
                                                    </div>
                                                </div>
                                            </div>
                                            @endforelse
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <div class="row align-items-center">
                                <div class="col">
                                    <div class="alert alert-danger" role="alert">
                                        <i class="bi bi-x-octagon-fill"></i> <strong>ERROR</strong>
                                        <br/>
                                        Tidak Ada Laporan
                                    </div>
                                </div>
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
            <!--EOTM REPORT-->
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-result" aria-expanded="false" aria-controls="collapse-result">
                        Laporan Karyawan Terbaik
                    </button>
                </h2>
                <div id="collapse-result" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                    <div class="accordion-body">
                        <div class="accordion" id="accordion-result">
                            @forelse ($h_years as $h_year)
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-result-{{ $h_year->period_year }}" aria-expanded="true" aria-controls="collapse-result-{{ $h_year->period_year }}">
                                        {{ $h_year->period_year }}
                                    </button>
                                </h2>
                                <div id="collapse-result-{{ $h_year->period_year }}" class="accordion-collapse collapse" data-bs-parent="#accordion-result">
                                    <div class="accordion-body">
                                        @forelse ($h_months->where('period_year', $h_year->period_year) as $h_month)
                                        <div class="row align-items-center pt-1">
                                            <div class="col">
                                                {{ $h_month->period_month }}
                                            </div>
                                            <div class="col">
                                                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                                    <a href="{{ route('admin.reports.result', ['month'=>$h_month->period_month,'year'=>$h_year->period_year]) }}" type="button" class="btn btn-danger" target="_blank" rel="noopener noreferrer">
                                                        <i class="bi bi-filetype-pdf"></i>
                                                        PDF
                                                    </a>
                                                    @if (Auth::check())
                                                    <a href="{{ route('admin.reports.certificate', ['month'=>$h_month->period_month,'year'=>$h_year->period_year]) }}" type="button" class="btn btn-primary" target="_blank" rel="noopener noreferrer">
                                                        <i class="bi bi-patch-check"></i>
                                                        Sertifikat
                                                    </a>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        @empty
                                        <div class="row align-items-center">
                                            <div class="col">
                                                <div class="alert alert-danger" role="alert">
                                                    <i class="bi bi-x-octagon-fill"></i> <strong>ERROR</strong>
                                                    <br/>
                                                    Tidak Ada Laporan
                                                </div>
                                            </div>
                                        </div>
                                        @endforelse
                                    </div>
                                </div>
                            </div>@empty
                            <div class="row align-items-center">
                                <div class="col">
                                    <div class="alert alert-danger" role="alert">
                                        <i class="bi bi-x-octagon-fill"></i> <strong>ERROR</strong>
                                        <br/>
                                        Tidak Ada Laporan
                                    </div>
                                </div>
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
            @endif
            @if (Auth::check() && Auth::user()->part == "Pegawai")
            <!--EOTM REPORT-->
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-score" aria-expanded="false" aria-controls="collapse-score">
                        Laporan Nilai
                    </button>
                </h2>
                <div id="collapse-score" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                    <div class="accordion-body">
                        <div class="accordion" id="accordion-score">
                            @forelse ($h_years as $h_year)
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-score-{{ $h_year->period_year }}" aria-expanded="true" aria-controls="collapse-score-{{ $h_year->period_year }}">
                                        {{ $h_year->period_year }}
                                    </button>
                                </h2>
                                <div id="collapse-score-{{ $h_year->period_year }}" class="accordion-collapse collapse" data-bs-parent="#accordion-score">
                                    <div class="accordion-body">
                                        @forelse ($h_months->where('period_year', $h_year->period_year) as $h_month)
                                        <div class="row align-items-center pt-1">
                                            <div class="col">
                                                {{ $h_month->period_month }}
                                            </div>
                                            <div class="col">
                                                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                                    <a href="{{ route('officer.reports.score', ['month'=>$h_month->period_month,'year'=>$h_year->period_year]) }}" type="button" class="btn btn-danger" target="_blank" rel="noopener noreferrer">
                                                        <i class="bi bi-filetype-pdf"></i>
                                                        PDF
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        @empty
                                        <div class="row align-items-center">
                                            <div class="col">
                                                <div class="alert alert-danger" role="alert">
                                                    <i class="bi bi-x-octagon-fill"></i> <strong>ERROR</strong>
                                                    <br/>
                                                    Tidak Ada Laporan
                                                </div>
                                            </div>
                                        </div>
                                        @endforelse
                                    </div>
                                </div>
                            </div>@empty
                            <div class="row align-items-center">
                                <div class="col">
                                    <div class="alert alert-danger" role="alert">
                                        <i class="bi bi-x-octagon-fill"></i> <strong>ERROR</strong>
                                        <br/>
                                        Tidak Ada Laporan
                                    </div>
                                </div>
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
    <!--INFO-->
    <div class="col-md-5">
        <div class="position-sticky" style="top: 0rem;">
            <div class="alert alert-info" role="alert">
                <i class="bi bi-info-circle-fill"></i> <strong>INFO</strong>
                <br/>
                <ol>
                    <li>Laporan ini dapat dilihat oleh semua pegawai.</li>
                    <li>Khusus hasil analisis dan karyawan terbaik, laporan tersebut dapat tersedia apabila proses penentuan karyawan terbaik selesai.</li>
                    @if (Auth::user()->part == 'Admin' || Auth::user()->part == 'KBPS')
                    <li>Sertifikat hanya dapat diunduh oleh Kepemimpinan dan Kepala BPS Jawa Timur.</li>
                    <li>Untuk mengganti template sertifikat, hubungi bagian <strong>Developer</strong> untuk mengatur letak tulisan.</li>
                    @endif
                    <li>Laporan dapat berubah sewaktu-waktu secara otomatis.</li>
                    <li>Perlu diingat, hasil penentuan karyawan terbaik tidak dapat diganggu gugat.</li>
                </ol>
            </div>
        </div>
    </div>
</div>


