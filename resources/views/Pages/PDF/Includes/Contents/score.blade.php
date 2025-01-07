<img src="{{ public_path('Images/Logo/BPS Black.png') }}" style="max-width: 40%;">
<h1 style="text-align:center;">Laporan Nilai</h1>
<h2 style="text-align:center;">Periode {{ $periods->period->name }}</h2>
<p>Nama Karyawan: {{ Auth::user()->employee->name }}</p>
<p>Tanggal Pembaharuan: {{ now() }}</p>
<table id="table-score">
    <thead>
        <tr>
            <th>#</th>
            <th>Kriteria</th>
            <th>Nilai</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($inputs as $input)
        <tr>
            <th>{{ $loop->iteration }}</th>
            <td>{{ $input->criteria_name }}</td>
            <td>{{ $input->input_raw }}</td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td colspan="5">Total Data: <b>{{ $inputs->count() }}</b> Data</td>
        </tr>
    </tfoot>
</table>
<br/>
<table id="table-score">
    <thead>
        <tr>
            <th>Nilai Akhir</th>
            <th>Nilai Kedua</th>
            <th>Peringkat</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>{{ $summary->final_score }}</td>
            <td>{{ $summary->second_score }}</td>
            <td>{{ $summary->rank }}</td>
        </tr>
    </tbody>
</table>
<p><b>PERHATIAN</b> Nilai tersebut tidak dapat dikomplain.</p>
