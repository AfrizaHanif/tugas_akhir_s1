<h1 style="text-align:center;">Laporan Kriteria</h1>
<p>Tanggal Pembaharuan: {{ now() }}</p>
@foreach ($parts as $part)
<h2>{{ $part->name }}</h2>
<table id="table">
    <thead>
        <tr>
            <th scope="col">Kode</th>
            <th scope="col">Nama</th>
            <th scope="col">Jabatan</th>
            <th scope="col">Jenis Kelamin</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($officers->where('id_part', $part->id_part) as $officer)
        <tr>
            <td>{{ $officer->id_officer }}</td>
            <td>{{ $officer->name }}</td>
            <td>{{ $officer->department->name }}</td>
            <td>{{ $officer->gender }}</td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td colspan="4">Total Data: <b>{{ $officers->where('id_part', $part->id_part)->count() }}</b> Pegawai dari Bagian {{ $part->name }}</td>
        </tr>
    </tfoot>
</table>
@endforeach
