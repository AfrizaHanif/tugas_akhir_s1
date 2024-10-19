<img src="{{ public_path('Images/Logo/BPS Black.png') }}" style="max-width: 40%;">
<h1 style="text-align:center;">Laporan Nilai</h1>
<h2 style="text-align:center;">Periode {{ $periods->period_name }}</h2>
<p>Nama Pegawai: {{ Auth::user()->name }}</p>
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
<p><b>PERHATIAN</b> Nilai tersebut tidak dapat dikomplain.</p>
