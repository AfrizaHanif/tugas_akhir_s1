<img src="{{ public_path('Images/Logo/BPS Black.png') }}" style="max-width: 30%;">
<h1 style="text-align:center;">Laporan Analisis Data</h1>
<h2 style="text-align:center;">Periode {{ $periods->period_name }}</h2>
<p>Tanggal Pembaharuan: {{ now() }}</p>
<h3>Pegawai yang Terlibat</h3>
<table id="table-analysis">
    <thead>
        <tr>
            <th>NIP</th>
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
<h3>Kriteria yang Terlibat</h3>
<table id="table-analysis">
    <thead>
        <tr>
            <th>Kode</th>
            <th>Nama Pegawai</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($subcriterias as $subcriteria)
        <tr>
            <td>{{ $subcriteria->id_criteria }}</td>
            <td>{{ $subcriteria->criteria_name }}</td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td colspan="2">Total Data: <b>{{ count($subcriterias) }}</b> Kriteria</td>
        </tr>
    </tfoot>
</table>
<hr>
<h3>Hasil Kuesioner</h3>
<table id="table-analysis">
    <thead>
        <tr>
            <th scope="col"></th>
            @foreach ($criterias as $crit)
            <th scope="col">{{ $crit->id_criteria }}</th>
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
<h3>Normalisasi</h3>
<table id="table-analysis">
    <thead>
        <tr class="table-primary">
            <th scope="col"></th>
            @foreach ($criterias as $crit)
            <th scope="col">{{ $crit->id_criteria }}</th>
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
<h3>Matrix</h3>
<table id="table-analysis">
    <thead>
        <tr class="table-primary">
            <th scope="col"></th>
            @foreach ($criterias as $crit)
            <th scope="col">{{ $crit->id_criteria }}</th>
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
<h3>Ranking</h3>
<table id="table-analysis">
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
<ul>
    <li>Dikarenakan bahwa terbatasnya tampilan laporan, maka nama Pegawai dan Kriteria diganti dengan kode Pegawai dan Kriteria. Silahkan cek pada Pegawai dan Kriteria yang terlibat untuk melihat kode dan nama tersebut.</li>
    <li>Analisis yang digunakan adalah analisis <b>SAW</b> untuk menentukan karyawan terbaik.</li>
</ul>
