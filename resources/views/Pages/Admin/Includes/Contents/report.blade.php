<h1 class="text-center mb-4">Data Laporan</h1>
@include('Templates.Includes.Components.alert')
<div class="row">
    <div class="col-md-7">
        <div class="accordion" id="accordionExample">
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
                                    <a href="{{ route('reports.officers') }}" type="button" class="btn btn-danger" target="_blank" rel="noopener noreferrer">
                                        <i class="bi bi-filetype-pdf"></i>
                                        PDF
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-input" aria-expanded="false" aria-controls="collapse-input">
                        Laporan Penilaian
                    </button>
                </h2>
                <div id="collapse-input" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                    <div class="accordion-body">
                        <div class="accordion" id="accordion-input">
                            @foreach ($per_years as $per_year)
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-input-{{ $per_year->year }}" aria-expanded="true" aria-controls="collapse-input-{{ $per_year->year }}">
                                        {{ $per_year->year }}
                                    </button>
                                </h2>
                                <div id="collapse-input-{{ $per_year->year }}" class="accordion-collapse collapse" data-bs-parent="#accordion-input">
                                    <div class="accordion-body">
                                        @forelse ($periods->where('year', $per_year->year) as $period)
                                        <div class="row align-items-center">
                                            <div class="col">
                                                {{ $period->month }}
                                            </div>
                                            <div class="col">
                                                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                                    <a href="{{ route('reports.input.all', $period->id_period) }}" type="button" class="btn btn-danger" target="_blank" rel="noopener noreferrer">
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
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-input-single" aria-expanded="false" aria-controls="collapse-input-single">
                        Laporan Penilaian (Per Pegawai)
                    </button>
                </h2>
                <div id="collapse-input-single" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                    <div class="accordion-body">
                        <div class="accordion" id="accordion-input-single">
                            @foreach ($officers as $officer)
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-input-{{ $officer->id_officer }}" aria-expanded="true" aria-controls="collapse-input-{{ $officer->id_officer }}">
                                        {{ $officer->name }}
                                    </button>
                                </h2>
                                <div id="collapse-input-{{ $officer->id_officer }}" class="accordion-collapse collapse" data-bs-parent="#accordion-input-single">
                                    <div class="accordion-body">
                                        @forelse ($per_years as $per_year)
                                        <div class="row align-items-center">
                                            <div class="col">
                                                {{ $per_year->year }}
                                            </div>
                                            <div class="col">
                                                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                                    <div class="dropend">
                                                        <button class="btn btn-danger dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                            <i class="bi bi-filetype-pdf"></i>
                                                            PDF
                                                        </button>
                                                        <ul class="dropdown-menu">
                                                            @forelse ($periods->where('year', $per_year->year) as $period)
                                                            <li>
                                                                <a href="{{ route('reports.input.single', ['period'=>$period->id_period,'id'=>$officer->id_officer]) }}" class="dropdown-item" target="_blank" rel="noopener noreferrer">{{ $period->month }}</a>
                                                            </li>
                                                            @empty
                                                            <li>
                                                                Tidak Ada Laporan
                                                            </li>
                                                            @endforelse
                                                        </ul>
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
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
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
                                        <div class="row align-items-center">
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
                                        <div class="row align-items-center">
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
    <div class="col-md-5">
        <div class="position-sticky" style="top: 2rem;">
            <div class="alert alert-info" role="alert">
                <i class="bi bi-info-circle-fill"></i> <strong>INFO</strong>
                <br/>
                
            </div>
        </div>
    </div>
</div>


