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
            @if (Auth::check() && Auth::user()->part != "Karyawan")
            <!--EMPLOYEES RECORD-->
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-employee" aria-expanded="false" aria-controls="collapse-employee">
                        Laporan Daftar Karyawan
                    </button>
                </h2>
                <div id="collapse-employee" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                    <div class="accordion-body">
                        <div class="row align-items-center">
                            <div class="col">
                                Download
                            </div>
                            <div class="col">
                                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                    <a href="{{ route('admin.reports.employees') }}" type="button" class="btn btn-danger" target="_blank" rel="noopener noreferrer">
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
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-analysis-{{ $h_year->year }}" aria-expanded="true" aria-controls="collapse-analysis-{{ $h_year->year }}">
                                        {{ $h_year->year }}
                                    </button>
                                </h2>
                                <div id="collapse-analysis-{{ $h_year->year }}" class="accordion-collapse collapse" data-bs-parent="#accordion-analysis">
                                    <div class="accordion-body">
                                        @forelse ($h_months->where('year', $h_year->year) as $h_month)
                                        <div class="row align-items-center pt-1">
                                            <div class="col">
                                                {{ $h_month->month }}
                                            </div>
                                            <div class="col">
                                                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                                    <a href="{{ route('admin.reports.analysis', ['month'=>$h_month->month,'year'=>$h_year->year]) }}" type="button" class="btn btn-danger" target="_blank" rel="noopener noreferrer">
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
                        <div class="row align-items-center">
                            <div class="col">
                                Pilih
                            </div>
                            <div class="col">
                                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                    <a href="#" type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-tim-rep-picker">
                                        <i class="bi bi-filetype-pdf"></i>
                                        Pilih
                                    </a>
                                </div>
                            </div>
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
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-result-{{ $h_year->year }}" aria-expanded="true" aria-controls="collapse-result-{{ $h_year->year }}">
                                        {{ $h_year->year }}
                                    </button>
                                </h2>
                                <div id="collapse-result-{{ $h_year->year }}" class="accordion-collapse collapse" data-bs-parent="#accordion-result">
                                    <div class="accordion-body">
                                        @forelse ($h_months->where('year', $h_year->year) as $h_month)
                                        <div class="row align-items-center pt-1">
                                            <div class="col">
                                                {{ $h_month->month }}
                                            </div>
                                            <div class="col">
                                                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                                    <a href="{{ route('admin.reports.result', ['month'=>$h_month->month,'year'=>$h_year->year]) }}" type="button" class="btn btn-danger" target="_blank" rel="noopener noreferrer">
                                                        <i class="bi bi-filetype-pdf"></i>
                                                        PDF
                                                    </a>
                                                    @if (Auth::check())
                                                    <a href="{{ route('admin.reports.certificate', ['month'=>$h_month->month,'year'=>$h_year->year]) }}" type="button" class="btn btn-primary" target="_blank" rel="noopener noreferrer">
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
            @if (Auth::check() && Auth::user()->part == "Karyawan")
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
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-score-{{ $h_year->year }}" aria-expanded="true" aria-controls="collapse-score-{{ $h_year->year }}">
                                        {{ $h_year->year }}
                                    </button>
                                </h2>
                                <div id="collapse-score-{{ $h_year->year }}" class="accordion-collapse collapse" data-bs-parent="#accordion-score">
                                    <div class="accordion-body">
                                        @forelse ($h_months->where('year', $h_year->year) as $h_month)
                                        <div class="row align-items-center pt-1">
                                            <div class="col">
                                                {{ $h_month->month }}
                                            </div>
                                            <div class="col">
                                                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                                    <a href="{{ route('employee.reports.score', ['month'=>$h_month->month,'year'=>$h_year->year]) }}" type="button" class="btn btn-danger" target="_blank" rel="noopener noreferrer">
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
                    <li>Laporan ini dapat dilihat oleh semua karyawan.</li>
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


