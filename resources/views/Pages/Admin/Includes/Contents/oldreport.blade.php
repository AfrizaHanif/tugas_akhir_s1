<h1 class="text-center mb-4">Data Laporan</h1>
@include('Templates.Includes.Components.alert')
<!--MENU-->
<p>
    <a class="btn btn-secondary" data-bs-toggle="offcanvas" href="#offcanvas-help" role="button" aria-controls="offcanvas-help">
        <i class="bi bi-question-lg"></i>
        Bantuan
    </a>
</p>
<div class="row g-2">
    <!--MAIN CONTENT-->
    <div class="col-md-7">
        <div class="accordion" id="accordionExample">
            <!--EMPLOYEES RECORD-->
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-employee" aria-expanded="false" aria-controls="collapse-employee">
                        Laporan Karyawan
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
                                    <a href="{{ route('reports.employees') }}" type="button" class="btn btn-danger" target="_blank" rel="noopener noreferrer">
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
                            @foreach ($per_years as $per_year)
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-analysis-{{ $per_year->year }}" aria-expanded="true" aria-controls="collapse-analysis-{{ $per_year->year }}">
                                        {{ $per_year->year }}
                                    </button>
                                </h2>
                                <div id="collapse-analysis-{{ $per_year->year }}" class="accordion-collapse collapse" data-bs-parent="#accordion-analysis">
                                    <div class="accordion-body">
                                        @forelse ($periods->where('year', $per_year->year) as $period)
                                        <div class="row align-items-center pt-1">
                                            <div class="col">
                                                {{ $period->month }}
                                            </div>
                                            <div class="col">
                                                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                                    <a href="{{ route('reports.analysis', $period->id_period) }}" type="button" class="btn btn-danger" target="_blank" rel="noopener noreferrer">
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
                            @endforeach
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
                            @foreach ($per_years as $per_year)
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-result-{{ $per_year->year }}" aria-expanded="true" aria-controls="collapse-result-{{ $per_year->year }}">
                                        {{ $per_year->year }}
                                    </button>
                                </h2>
                                <div id="collapse-result-{{ $per_year->year }}" class="accordion-collapse collapse" data-bs-parent="#accordion-result">
                                    <div class="accordion-body">
                                        @forelse ($periods->where('year', $per_year->year) as $period)
                                        <div class="row align-items-center pt-1">
                                            <div class="col">
                                                {{ $period->month }}
                                            </div>
                                            <div class="col">
                                                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                                    <a href="{{ route('reports.result', $period->id_period) }}" type="button" class="btn btn-danger" target="_blank" rel="noopener noreferrer">
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
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--INFO-->
    <div class="col-md-5">
        <div class="position-sticky" style="top: 0rem;">
            <div class="alert alert-info" role="alert">
                <i class="bi bi-info-circle-fill"></i> <strong>INFO</strong>
                <br/>

            </div>
        </div>
    </div>
</div>


