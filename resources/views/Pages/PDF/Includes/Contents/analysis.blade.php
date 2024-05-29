<h1 style="text-align:center;">Laporan Analisis SAW</h1>
<p>Periode: {{ $month }} {{ $year }}</p>
<p>Tanggal Pembaharuan: {{ now() }}</p>
<h2>Pegawai</h2>
<table id="table">
    <thead>
        <tr>
            <th>Kode</th>
            <th>Nama Pegawai</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($officers as $officer)
        <tr>
            <td>{{ $officer->id_officer }}</td>
            <td>{{ $officer->officer_name }}</td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td colspan="2">Total Data: <b>{{ count($officers) }}</b> Pegawai</td>
        </tr>
    </tfoot>
</table>
<h2>Hasil Kuesioner</h2>
<table id="table">
    <thead>
        <tr>
            <th scope="col"></th>
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
<h2>Normalisasi</h2>
<table id="table">
    <thead>
        <tr class="table-primary">
            <th scope="col"></th>
            @foreach ($criterias as $crit)
            <th scope="col">{{ $crit->id_sub_criteria }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @forelse ($normal as $n1 => $value1)
        <tr>
            <td>{{ $n1 }}</td>
            @foreach ($value1 as $n2 => $value2)
            <td>
                {{ number_format($value2,3) }}
            </td>
            @endforeach
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
<h2>Matrix</h2>
<table id="table">
    <thead>
        <tr class="table-primary">
            <th scope="col"></th>
            @foreach ($criterias as $crit)
            <th scope="col">{{ $crit->id_sub_criteria }}</th>
            @endforeach
            <th rowspan="2">Matrix</th>
        </tr>
        <tr>
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
            <td>{{ $r1 }}</td>
            @foreach ($value1 as $r2 => $value2)
            <td>{{ number_format($value2,3) }}</td>
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
<h2>Ranking</h2>
<table id="table">
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
            <th scope="row">{{$officers->where('id_officer', $sqrt1)->first()->officer_name ?? ''}} ({{ $sqrt1 }})</th>
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
