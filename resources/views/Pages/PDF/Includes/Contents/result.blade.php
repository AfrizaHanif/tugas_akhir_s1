<img src="{{ public_path('Images/Logo/BPS Black.png') }}" style="max-width: 40%;">
<h1 style="text-align:center;">Laporan Karyawan Terbaik</h1>
<h2 style="text-align:center;">Periode {{ $periods->period->name }}</h3>
<p>Tanggal Pembaharuan: {{ now() }}
<table id="table-result">
    <thead>
        <tr>
            <th>#</th>
            <th>Nama</th>
            <th>Jabatan</th>
            <th>Tim Teknis Utama</th>
            <th>Nilai Akhir</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($results->take(3) as $result)
        <tr>
            <th>{{ $loop->iteration }}</th>
            <td>{{ $result->employee_name }}</td>
            <td>{{ $result->employee_position }}</td>
            <td>{{ $result->sub_team_1_name }}</td>
            <td>{{ $result->final_score }}</td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td colspan="5">Total Data: <b>{{ $results->take(3)->count() }}</b> Karyawan</td>
        </tr>
    </tfoot>
</table>
<p><b>CATATAN:</b></p>
<ul>
    <li>Karyawan yang diambil pada laporan ini adalah tiga karyawan dengan hasil akhir terbaik.</li>
    <li>Jika angka akhir pada ranking pertama sama, maka akan dipilih dengan nilai CKP terbaik.</li>
</ul>
<p><b>PERHATIAN</b> Hasil akhir tersebut merupakan hasil final dan tidak dapat dikomplain.</p>
