<h1 style="text-align:center;">Laporan Karyawan Terbaik</h1>
<p>Periode: {{ $month }} {{ $year }}</p>
<p>Tanggal Pembaharuan: {{ now() }}</p>
<table id="table">
    <thead>
        <tr>
            <th>#</th>
            <th>Nama</th>
            <th>Hasil Akhir</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($results as $result)
        <tr>
            <th>{{ $loop->iteration }}</th>
            <td>{{ $result->officer->name }}</td>
            <td>{{ $result->final_score }}</td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td colspan="4">Total Data: <b>{{ $results->count() }}</b> Pegawai</td>
        </tr>
    </tfoot>
</table>
