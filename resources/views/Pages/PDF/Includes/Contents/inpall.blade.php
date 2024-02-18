<h1 style="text-align:center;">Laporan Penilaian</h1>
<p>Periode: {{ $month }} {{ $year }}</p>
<p>Tanggal Pembaharuan: {{ now() }}</p>
@foreach ($officers as $officer)
<h2>{{ $officer->name }}</h2>
<h3>Kehadiran</h3>
<table id="table">
    <tr>
        <th scope="row">Nama Kriteria</th>
        <th scope="row">Nilai</th>
    </tr>
    @foreach ($subcritprs as $prs)
        @foreach ($presences->where('id_sub_criteria', $prs->id_sub_criteria)->where('id_officer', $officer->id_officer) as $presence)
        <tr>
            <td>{{ $prs->name }}</td>
            <td>
                @if ($prs->need == 'Ya')
                <b>{{ $presence->input }}</b>
                @else
                {{ $presence->input }}
                @endif
            </td>
        </tr>
        @endforeach
    @endforeach
</table>
<h3>Prestasi Kerja</h3>
<table id="table">
    <tr>
        <th scope="row">Nama Kriteria</th>
        <th scope="row">Nilai</th>
    </tr>
    @foreach ($subcritprf as $prf)
        @foreach ($performances->where('id_sub_criteria', $prf->id_sub_criteria)->where('id_officer', $officer->id_officer) as $performance)
        <tr>
            <td>{{ $prf->name }}</td>
            <td>
                @if ($prf->need == 'Ya')
                <b>{{ $performance->input }}</b>
                @else
                {{ $performance->input }}
                @endif
            </td>
        </tr>
        @endforeach
    @endforeach
</table>
<hr/>
@endforeach
