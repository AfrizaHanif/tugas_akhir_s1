<h1 style="text-align:center;">Laporan Karyawan Terbaik</h1>
<p>Periode: {{ $periods->period_name  }}</p>
<p>Tanggal Pembaharuan: {{ now() }}</p>
<table id="table-result">
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
            <td>{{ $result->officer_name }}</td>
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
<p>Jika angka akhir pada ranking pertama sama, maka akan dipilih dengan umur tertua (Senior)</p>
