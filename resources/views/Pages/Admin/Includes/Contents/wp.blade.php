<h1 class="text-center mb-4">Analisis WP Periode</h1>
@include('Pages.Admin.Includes.Components.alert')
<p>
    <div class="btn-group" role="group" aria-label="Basic example">
        <a class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-wp-view">
            <i class="bi bi-folder-plus"></i>
            Pilih Periode
        </a>
    </div>
</p>

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
                            <th scope="col">{{ $crit->id_sub_criteria }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($alternatives as $alt)
                        <tr>
                            <td>{{ $alt->id_officer }}</td>
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
                            <th scope="col">{{ $crit->id_sub_criteria }}</th>
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
                            <td>{{ $sqrt1 }}</td>
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
                            <th scope="row">{{ $sqrt1 }}</th>
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
<br/>
