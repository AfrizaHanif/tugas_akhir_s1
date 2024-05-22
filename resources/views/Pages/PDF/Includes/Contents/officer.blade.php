<h1 style="text-align:center;">Laporan Kriteria</h1>
<p>Tanggal Pembaharuan: {{ now() }}</p>
@foreach ($parts as $part)
<h2>{{ $part->name }}</h2>
    @foreach ($departments->where('id_part', $part->id_part) as $department)
        @if (count($officers->where('id_department', $department->id_department)) !=0)
        <h3>{{ $department->name }}</h3>
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
                @forelse ($officers->where('id_department', $department->id_department) as $officer)
                <tr>
                    <td>{{ $officer->id_officer }}</td>
                    <td>{{ $officer->name }}</td>
                    <td>{{ $officer->department->name }}</td>
                    <td>{{ $officer->gender }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="4">Tidak ada Pegawai yang terdaftar di jabatan ini</td>
                </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="4">Total Data: <b>{{ $officers->where('id_department', $department->id_department)->count() }}</b> Pegawai dari Jabatan {{ $department->name }} di Bagian {{ $part->name }}</td>
                </tr>
            </tfoot>
        </table>
        @endif
    @endforeach
@endforeach
