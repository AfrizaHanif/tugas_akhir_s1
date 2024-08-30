@if (Request::is('admin/analysis'))
<h1 class="text-center mb-4">Analisis SAW</h1>
@elseif (Request::is('admin/analysis/latest'))
    @if (!empty($latest_per))
    <h1 class="text-center mb-4">Analisis SAW ({{ $latest_per->month }} {{ $latest_per->year }})</h1>
    @endif
@elseif (Request::is('admin/analysis/*'))
<h1 class="text-center mb-4">Analisis SAW ({{ $select_period->period_name }})</h1>
@endif
@include('Templates.Includes.Components.alert')
<!--MENU-->
<p>
    <div class="dropdown">
        <!--PERIOD PICKER-->
        <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="bi bi-calendar-range"></i>
            Pilih Periode
        </button>
        <ul class="dropdown-menu">
            @if (!empty($latest_per))
            <li>
                <a class="dropdown-item" href="{{ route('admin.analysis.saw') }}">
                    Sekarang
                </a>
            </li>
            @else
            <li>
                <button class="dropdown-item" disabled>
                    Sekarang
                </button>
            </li>
            @endif
            <li>
                <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#modal-saw-periods">
                    Sebelumnya
                </a>
            </li>
        </ul>
        <!--HELP-->
        <a class="btn btn-secondary" data-bs-toggle="offcanvas" href="#offcanvas-help" role="button" aria-controls="offcanvas-help">
            <i class="bi bi-question-lg"></i>
            Bantuan
        </a>
    </div>
</p>
<!--NOTICE (WHEN PERIOD IS NOT PICKED UP)-->
@if (Request::is('admin/analysis'))
<div class="alert alert-info" role="alert">
    <i class="bi bi-info-circle-fill"></i> <strong>INFO</strong>
    <br/>
    Untuk melihat hasil analisis di setiap periode, klik pilih periode untuk melihat hasil analisis.
</div>
<div class="alert alert-warning" role="alert">
    <i class="bi bi-exclamation-triangle-fill"></i> <b>PERHATIAN</b>
    <br/>
    Pastikan seluruh data input di setiap pegawai telah terisi. Cek status di halaman input apakah pegawai tersebut telah terinput atau belum.
</div>
@endif
<!--ANALYSIS RESULT-->
@if (Request::is('admin/analysis/*'))
    <!--SIMILAR RESULT DETECTION ALERT-->
    @if ($matrix != array_unique($matrix))
    <div class="alert alert-warning" role="alert">
        Terdapat Hasil Matrix yang memiliki angka yang sama (Dua atau lebih).
    </div>
    @endif
<!--DETAILS-->
<div class="accordion" id="accordion-details">
    <!--OFFICERS-->
    <div class="accordion-item">
        <h2 class="accordion-header">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-officer" aria-expanded="false" aria-controls="collapse-officer">
                Pegawai Yang Terlibat untuk Analisis
            </button>
        </h2>
        <div id="collapse-officer" class="accordion-collapse collapse" data-bs-parent="#accordion-details">
            <div class="accordion-body">
                <table class="table table-hover table-bordered">
                    <thead>
                        <tr class="table-primary">
                            <th>Kode</th>
                            <th>Nama Pegawai</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($officers as $officer)
                        <tr>
                            <td>{{ $officer->id_officer }}</td>
                            @if (Request::is('admin/analysis/latest'))
                            <td>{{ $officer->name }}</td>
                            @elseif (Request::is('admin/analysis/*'))
                            <td>{{ $officer->officer_name }}</td>
                            @endif
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="table-group-divider table-secondary">
                        <tr>
                            <td colspan="2">Total Data: <b>{{ count($officers) }}</b> Pegawai</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
    <!--CRITERIAS-->
    <div class="accordion-item">
        <h2 class="accordion-header">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-criteria" aria-expanded="false" aria-controls="collapse-criteria">
                Kriteria Yang Terlibat untuk Analisis
            </button>
        </h2>
        <div id="collapse-criteria" class="accordion-collapse collapse" data-bs-parent="#accordion-details">
            <div class="accordion-body">
                <table class="table table-hover table-bordered">
                    <thead>
                        <tr class="table-primary">
                            <th>Kode</th>
                            <th>Nama Kriteria</th>
                            <th>Nama Sub Kriteria</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($subcriterias as $subcriteria)
                        <tr>
                            <td>{{ $subcriteria->id_criteria }}</td>
                            @if (Request::is('admin/analysis/latest'))
                            <td>{{ $subcriteria->category->name }}</td>
                            <td>{{ $subcriteria->name }}</td>
                            @elseif (Request::is('admin/analysis/*'))
                            <td>{{ $subcriteria->category_name }}</td>
                            <td>{{ $subcriteria->criteria_name }}</td>
                            @endif
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="table-group-divider table-secondary">
                        <tr>
                            <td colspan="3">Total Data: <b>{{ count($subcriterias) }}</b> Sub Kriteria</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
<br/>
    @if (Request::is('admin/analysis/*'))
    <!--SAW ANALYSIS RESULT-->
    <div class="accordion" id="accordion">
        <!--LIST OF INPUTS-->
        <div class="accordion-item">
            <h2 class="accordion-header">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-hasil" aria-expanded="true" aria-controls="collapse-hasil">
                    Hasil Kuesioner
                </button>
            </h2>
            <div id="collapse-hasil" class="accordion-collapse collapse" data-bs-parent="#accordion">
                <div class="accordion-body">
                        <div class="table-responsive">
                        <table class="table table-hover table-bordered">
                            <thead>
                                <tr class="table-primary">
                                    <th scope="col"></th>
                                    @foreach ($criterias as $crit)
                                    <th scope="col">
                                        @if (Request::is('admin/analysis/latest'))
                                        <span data-bs-toggle="tooltip" data-bs-title="{{ $subcriterias->where('id_criteria', $crit->id_criteria)->first()->name }}">
                                        {{ $crit->id_criteria }}
                                        </span>
                                        @elseif (Request::is('admin/analysis/*'))
                                        <span data-bs-toggle="tooltip" data-bs-title="{{ $subcriterias->where('id_criteria', $crit->id_criteria)->first()->criteria_name }}">
                                        {{ $crit->id_criteria }}
                                        </span>
                                        @endif
                                    </th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($alternatives as $alt)
                                <tr>
                                    <td>
                                        @if (Request::is('admin/analysis/latest'))
                                        <span data-bs-toggle="tooltip" data-bs-title="{{ $officers->where('id_officer', $alt->id_officer)->first()->name ?? '' }}">
                                            {{ $alt->id_officer }}
                                        </span>
                                        @elseif (Request::is('admin/analysis/*'))
                                        <span data-bs-toggle="tooltip" data-bs-title="{{ $officers->where('id_officer', $alt->id_officer)->first()->officer_name ?? '' }}">
                                            {{ $alt->id_officer }}
                                        </span>
                                        @endif
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
        </div>
        <!--NORMALIZATION-->
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
                                    <th scope="col"></th>
                                    @foreach ($criterias as $crit)
                                    <th scope="col">
                                        @if (Request::is('admin/analysis/latest'))
                                        <span data-bs-toggle="tooltip" data-bs-title="{{ $subcriterias->where('id_criteria', $crit->id_criteria)->first()->name }}">
                                            {{ $crit->id_criteria }}
                                        </span>
                                        @elseif (Request::is('admin/analysis/*'))
                                        <span data-bs-toggle="tooltip" data-bs-title="{{ $subcriterias->where('id_criteria', $crit->id_criteria)->first()->criteria_name }}">
                                            {{ $crit->id_criteria }}
                                        </span>
                                        @endif
                                    </th>
                                    @endforeach
                                    <tr class="table-secondary">
                                        <th scope="col">Atribut</th>
                                        @foreach ($subcriterias as $subcriteria)
                                        <th>{{ $subcriteria->attribute }}</th>
                                        @endforeach
                                    </tr>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($normal as $n1 => $value1)
                                <tr>
                                    <td>
                                        @if (Request::is('admin/analysis/latest'))
                                        <span data-bs-toggle="tooltip" data-bs-title="{{ $officers->where('id_officer', $n1)->first()->name }}">
                                        {{ $n1 }}
                                        </span>
                                        @elseif (Request::is('admin/analysis/*'))
                                        <span data-bs-toggle="tooltip" data-bs-title="{{ $officers->where('id_officer', $n1)->first()->officer_name }}">
                                        {{ $n1 }}
                                        </span>
                                        @endif
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
        <!--MATRIX-->
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
                                        @if (Request::is('admin/analysis/latest'))
                                        <span data-bs-toggle="tooltip" data-bs-title="{{ $subcriterias->where('id_criteria', $crit->id_criteria)->first()->name }}">
                                            {{ $crit->id_criteria }}
                                        </span>
                                        @elseif (Request::is('admin/analysis/*'))
                                        <span data-bs-toggle="tooltip" data-bs-title="{{ $subcriterias->where('id_criteria', $crit->id_criteria)->first()->criteria_name }}">
                                            {{ $crit->id_criteria }}
                                        </span>
                                        @endif
                                    </th>
                                    @endforeach
                                    <th rowspan="2">Matrix</th>
                                </tr>
                                <tr class="table-secondary">
                                    <th scope="col">Bobot (%)</th>
                                    @foreach ($subcriterias as $crit)
                                    <th>{{ $crit->weight * 100 }}%</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @php $no = 1;@endphp
                                @foreach ($mx_hasil as $r1 => $value1)
                                <tr>
                                    <td>
                                        @if (Request::is('admin/analysis/latest'))
                                        <span data-bs-toggle="tooltip" data-bs-title="{{ $officers->where('id_officer', $r1)->first()->name }}">
                                        {{ $r1 }}
                                        </span>
                                        @elseif (Request::is('admin/analysis/*'))
                                        <span data-bs-toggle="tooltip" data-bs-title="{{ $officers->where('id_officer', $r1)->first()->officer_name }}">
                                        {{ $r1 }}
                                        </span>
                                        @endif
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
        <!--RANKS-->
        <div class="accordion-item">
            <h2 class="accordion-header">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-ranking" aria-expanded="false" aria-controls="collapse-ranking">
                    Ranking
                </button>
            </h2>
            <div id="collapse-ranking" class="accordion-collapse collapse" data-bs-parent="#accordion">
                <div class="accordion-body">
                    <div class="alert alert-info" role="alert">
                        <i class="bi bi-info-circle-fill"></i> <strong>INFO</strong>
                        <br/>
                        @if (Request::is('admin/analysis/latest'))
                        Jika terdapat nilai akhir yang sama pada peringkat pertama, maka yang akan dipilih adalah nilai terbaik dari kriteria <strong>{{ $set_crit->name }}</strong>. Silahkan menunggu hasil dari pemilihan Karyawan Terbaik.
                        @elseif (Request::is('admin/analysis/*'))
                        Jika terdapat nilai akhir yang sama pada peringkat pertama, maka yang akan dipilih adalah nilai terbaik dari kriteria <strong>{{ $h_set_crit->criteria_name }}</strong> yang dapat dilihat di halaman Karyawan Terbaik pada halaman utama dan dashboard.
                        @endif
                    </div>
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
                                        @if (Request::is('admin/analysis/latest'))
                                        {{$officers->where('id_officer', $sqrt1)->first()->name ?? ''}} ({{ $sqrt1 }})
                                        @elseif (Request::is('admin/analysis/*'))
                                        {{$officers->where('id_officer', $sqrt1)->first()->officer_name ?? ''}} ({{ $sqrt1 }})
                                        @endif
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
    @endif
@endif
<br/>
