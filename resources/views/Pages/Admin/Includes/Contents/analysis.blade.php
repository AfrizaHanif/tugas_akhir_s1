@if (Request::is('admin/analysis/saw*'))
<h1 class="text-center mb-4">Analisis SAW</h1>
@elseif (Request::is('admin/analysis/wp*'))
<h1 class="text-center mb-4">Analisis WP</h1>
@endif
@include('Templates.Includes.Components.alert')
<p>
    @if (Request::is('admin/analysis/saw*'))
    <a class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-saw-periods">
        <i class="bi bi-folder-plus"></i>
        Pilih Periode
    </a>
    @elseif (Request::is('admin/analysis/wp*'))
    <a class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-wp-periods">
        <i class="bi bi-folder-plus"></i>
        Pilih Periode
    </a>
    @endif
    <a class="btn btn-secondary" data-bs-toggle="offcanvas" href="#offcanvas-help" role="button" aria-controls="offcanvas-help">
        <i class="bi bi-question-lg"></i>
        Bantuan
    </a>
</p>
@if (Request::is('admin/analysis/saw') || Request::is('admin/analysis/wp'))
<div class="alert alert-info" role="alert">
    Untuk melihat hasil analisis di setiap periode, klik pilih periode untuk melihat hasil analisis.
</div>
<div class="alert alert-warning" role="alert">
    <strong>PERHATIAN:</strong> Pastikan seluruh data input di setiap pegawai telah terisi. Cek status di halaman input apakah pegawai tersebut telah terinput atau belum.
</div>
@endif
@if (Request::is('admin/analysis/saw/*'))
<div class="accordion" id="accordion">
    <div class="accordion-item">
        <h2 class="accordion-header">
            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-hasil" aria-expanded="true" aria-controls="collapse-hasil">
                Hasil Kuesioner
            </button>
        </h2>
        <div id="collapse-hasil" class="accordion-collapse collapse show" data-bs-parent="#accordion">
            <div class="accordion-body">
                <table class="table table-hover table-bordered">
                    <thead>
                        <tr class="table-primary">
                            <th scope="col">Nama Alternatif</th>
                            @foreach ($criterias as $crit)
                            <th scope="col">
                                <span data-bs-toggle="tooltip" data-bs-title="{{ $subcriterias->where('id_sub_criteria', $crit->id_sub_criteria)->first()->name }}">
                                {{ $crit->id_sub_criteria }}
                                </span>
                            </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($alternatives as $alt)
                        <tr>
                            <td>
                                <span data-bs-toggle="tooltip" data-bs-title="{{ $officers->where('id_officer', $alt->id_officer)->first()->name }}">
                                    {{ $alt->id_officer }}
                                </span>
                            </td>
                            @if (count($inputs) > 0)
                                @forelse ($inputs->where('id_officer', $alt->id_officer) as $input)
                                    <td>{{ $input->input }}</td>
                                @empty
                                    <td>0</td>
                                @endforelse
                            @endif
                        </tr>
                        @empty

                        @endforelse
                    </tbody>
                    <tfoot class="table-group-divider table-secondary">
                        <tr>
                            <td colspan="{{count($criterias)+1}}">Total Data: <b>{{ count($alternatives) }}</b> Data</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
    <div class="accordion-item">
        <h2 class="accordion-header">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-normal" aria-expanded="false" aria-controls="collapse-normal">
                Normalisasi
            </button>
        </h2>
        <div id="collapse-normal" class="accordion-collapse collapse" data-bs-parent="#accordion">
            <div class="accordion-body">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                        <thead>
                            <tr class="table-primary">
                                <th scope="col">Alternatif / Kriteria</th>
                                @foreach ($criterias as $crit)
                                <th scope="col">
                                    <span data-bs-toggle="tooltip" data-bs-title="{{ $subcriterias->where('id_sub_criteria', $crit->id_sub_criteria)->first()->name }}">
                                        {{ $crit->id_sub_criteria }}
                                    </span>
                                </th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($normal as $n1 => $value1)
                            <tr>
                                <td>
                                    <span data-bs-toggle="tooltip" data-bs-title="{{ $officers->where('id_officer', $n1)->first()->name }}">
                                    {{ $n1 }}
                                    </span>
                                </td>
                                @forelse ($value1 as $n2 => $value2)
                                <td>
                                    {{ number_format($value2,3) }}
                                </td>
                                @empty
                                <td>0</td>
                                @endforelse
                            </tr>
                            @empty

                            @endforelse
                        </tbody>
                        <tfoot class="table-group-divider table-secondary">
                            <tr>
                                <td colspan="{{count($criterias)+2}}">Total Data: <b>{{ count($alternatives) }}</b> Data</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="accordion-item">
        <h2 class="accordion-header">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-matrix" aria-expanded="false" aria-controls="collapse-matrix">
                Matrix
            </button>
        </h2>
        <div id="collapse-matrix" class="accordion-collapse collapse" data-bs-parent="#accordion">
            <div class="accordion-body">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                        <thead>
                            <tr class="table-primary">
                                <th scope="col"></th>
                                @foreach ($criterias as $crit)
                                <th scope="col">
                                    <span data-bs-toggle="tooltip" data-bs-title="{{ $subcriterias->where('id_sub_criteria', $crit->id_sub_criteria)->first()->name }}">
                                        {{ $crit->id_sub_criteria }}
                                    </span>
                                </th>
                                @endforeach
                                <th rowspan="2">Matrix</th>
                            </tr>
                            <tr class="table-secondary">
                                <th scope="col">Bobot (%)</th>
                                @foreach ($criterias as $crit)
                                <th>{{ $crit->weight * 100 }}%</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @php $no = 1;@endphp
                            @foreach ($mx_hasil as $r1 => $value1)
                            <tr>
                                <td>
                                    <span data-bs-toggle="tooltip" data-bs-title="{{ $officers->where('id_officer', $r1)->first()->name }}">
                                    {{ $r1 }}
                                    </span>
                                </td>
                                @forelse ($value1 as $r2 => $value2)
                                <td>{{ number_format($value2,3) }}</td>
                                @empty
                                <td>0</td>
                                @endforelse
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="table-group-divider table-secondary">
                            <tr>
                                <td colspan="{{count($criterias)+3}}">Total Bobot: <b>{{ round((float)$criterias->sum('weight') * 100 ) }}%</b> dari <b>{{ count($alternatives) }}</b> Data</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="accordion-item">
        <h2 class="accordion-header">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-ranking" aria-expanded="false" aria-controls="collapse-ranking">
                Ranking
            </button>
        </h2>
        <div id="collapse-ranking" class="accordion-collapse collapse" data-bs-parent="#accordion">
            <div class="accordion-body">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                        <thead>
                            <tr class="table-primary">
                                <th scope="col">Nama Alternatif</th>
                                <th scope="col">Matrix</th>
                                <th scope="col">Rank</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $no = 1;@endphp
                            @foreach ($matrix as $sqrt1 => $valsqrt1)
                            <tr>
                                <th scope="row">
                                    {{$officers->where('id_officer', $sqrt1)->first()->name}} ({{ $sqrt1 }})
                                </th>
                                <td>{{ number_format($valsqrt1,3) }}</td>
                                <td>{{ $no++ }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="table-group-divider table-secondary">
                            <tr>
                                <td colspan="{{count($criterias)+2}}">Total Data: <b>{{ count($alternatives) }}</b> Data</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@elseif (Request::is('admin/analysis/wp/*'))
<div class="accordion" id="accordion">
    <div class="accordion-item">
        <h2 class="accordion-header">
            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-hasil" aria-expanded="true" aria-controls="collapse-hasil">
                Hasil Kuesioner
            </button>
        </h2>
        <div id="collapse-hasil" class="accordion-collapse collapse show" data-bs-parent="#accordion">
            <div class="accordion-body">
                <table class="table table-hover table-bordered">
                    <thead>
                        <tr class="table-primary">
                            <th scope="col">Nama Alternatif</th>
                            @foreach ($criterias as $crit)
                            <th scope="col">
                                <span data-bs-toggle="tooltip" data-bs-title="{{ $subcriterias->where('id_sub_criteria', $crit->id_sub_criteria)->first()->name }}">
                                {{ $crit->id_sub_criteria }}
                                </span>
                            </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($alternatives as $alt)
                        <tr>
                            <td>
                                <span data-bs-toggle="tooltip" data-bs-title="{{ $officers->where('id_officer', $alt->id_officer)->first()->name }}">
                                    {{ $alt->id_officer }}
                                </span>
                            </td>
                            @if (count($inputs) > 0)
                                @foreach ($inputs->where('id_officer', $alt->id_officer) as $input)
                                <td>
                                    {{ $input->input }}
                                </td>
                                @endforeach
                            @endif
                        </tr>
                        @empty

                        @endforelse
                    </tbody>
                    <tfoot class="table-group-divider table-secondary">
                        <tr>
                            <td colspan="{{count($criterias)+1}}">Total Data: <b>{{ count($alternatives) }}</b> Data</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
    <div class="accordion-item">
        <h2 class="accordion-header">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-hitung" aria-expanded="false" aria-controls="collapse-hitung">
                Pangkat, S, V
            </button>
        </h2>
        <div id="collapse-hitung" class="accordion-collapse collapse" data-bs-parent="#accordion">
            <div class="accordion-body">
                <table class="table table-hover table-bordered">
                    <thead>
                        <tr class="table-primary">
                            <th scope="col"></th>
                            @foreach ($criterias as $crit)
                            <th scope="col">
                                <span data-bs-toggle="tooltip" data-bs-title="{{ $subcriterias->where('id_sub_criteria', $crit->id_sub_criteria)->first()->name }}">
                                    {{ $crit->id_sub_criteria }}
                                </span>
                            </th>
                            @endforeach
                            <th rowspan="3">S</th>
                            <th rowspan="3">V</th>
                        </tr>
                        <tr>
                            <th scope="col">Bobot (%)</th>
                            @foreach ($criterias as $crit)
                            <th>{{ $crit->weight * 100 }}%</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($v_hasil as $sqrt1 => $valsqrt1)
                        <tr>
                            <td>
                                <span data-bs-toggle="tooltip" data-bs-title="{{ $officers->where('id_officer', $sqrt1)->first()->name }}">
                                {{ $sqrt1 }}
                                </span>
                            </td>
                            @foreach ($valsqrt1 as $sqrt2 => $valsqrt2)
                            <td>{{ number_format($valsqrt2,3) }}</td>
                            @endforeach
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="table-group-divider table-secondary">
                        <tr>
                            <td colspan="{{count($criterias)+3}}">Total Bobot: <b>{{ round((float)$criterias->sum('weight') * 100 ) }}%</b> dari <b>{{ count($alternatives) }}</b> Data</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
    <div class="accordion-item">
        <h2 class="accordion-header">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-rank" aria-expanded="false" aria-controls="collapse-rank">
                Ranking
            </button>
        </h2>
        <div id="collapse-rank" class="accordion-collapse collapse" data-bs-parent="#accordion">
            <div class="accordion-body">
                <table class="table table-hover table-bordered">
                    <thead>
                        <tr class="table-primary">
                            <th scope="col">Nama Alternatif</th>
                            <th scope="col">V</th>
                            <th scope="col">Rank</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $no = 1;@endphp
                        @foreach ($v as $sqrt1 => $valsqrt1)
                        <tr>
                            <th scope="row">
                                {{$officers->where('id_officer', $sqrt1)->first()->name}} ({{ $sqrt1 }})
                            </th>
                            <td>{{ number_format($valsqrt1,3) }}</td>
                            <td>{{ $no++ }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="table-group-divider table-secondary">
                        <tr>
                            <td colspan="{{count($criterias)+4}}">Total Data: <b>{{ count($alternatives) }}</b> Data</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
@endif
<br/>
