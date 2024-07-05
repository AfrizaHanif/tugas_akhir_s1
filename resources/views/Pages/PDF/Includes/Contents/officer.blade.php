<h1 style="text-align:center;">Laporan Kriteria</h1>
<p>Tanggal Pembaharuan: {{ now() }}</p>
@foreach ($parts as $part)
<h2>{{ $part->name }}</h2>
    @foreach ($teams->where('id_part', $part->id_part) as $team)
        @foreach ($subteams->where('id_team', $team->id_team) as $subteam)
        @if (count($officers->where('id_sub_team_1', $subteam->id_sub_team)) != 0)
        <h3>{{ $team->name }}</h3>
        <h4>{{ $subteam->name }}</h4>
        <table id="table-officer">
            <thead>
                <tr>
                    <th scope="col">Kode</th>
                    <th scope="col">Nama</th>
                    <th scope="col">Tim Cadangan</th>
                    <th scope="col">Jenis Kelamin</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($officers->where('id_sub_team_1', $subteam->id_sub_team) as $officer)
                <tr>
                    <td>{{ $officer->id_officer }}</td>
                    <td>{{ $officer->name }}</td>
                    <td>{{ $officer->subteam_2->name ?? 'Tidak Ada'}}</td>
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
                    <td colspan="4">Total Data: <b>{{ $officers->where('id_sub_team_1', $subteam->id_sub_team)->count() }}</b> Pegawai</td>
                </tr>
            </tfoot>
        </table>
        @endif
        @endforeach
    @endforeach
@endforeach
