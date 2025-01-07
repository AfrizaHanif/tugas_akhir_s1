<img src="{{ public_path('Images/Logo/BPS Black.png') }}" style="max-width: 40%;">
<h1 style="text-align:center;">Laporan Daftar Karyawan</h1>
<p>Tanggal Pembaharuan: {{ now() }}</p>
@foreach ($parts as $part)
<h2>{{ $part->name }}</h2>
    @foreach ($teams->where('id_part', $part->id_part) as $team)
        @foreach ($subteams->where('id_team', $team->id_team) as $subteam)
        @if (count($employees->where('id_sub_team_1', $subteam->id_sub_team)) != 0)
        <h3>{{ $team->name }}</h3>
        <h4>{{ $subteam->name }}</h4>
        <table id="table-employee">
            <thead>
                <tr>
                    <th scope="col">NIP</th>
                    <th scope="col">Nama</th>
                    <th scope="col">Tim Cadangan</th>
                    <th scope="col">Jenis Kelamin</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($employees->where('id_sub_team_1', $subteam->id_sub_team) as $employee)
                <tr>
                    <td>{{ $employee->id_employee }}</td>
                    <td>{{ $employee->name }}</td>
                    <td>{{ $employee->subteam_2->name ?? 'Tidak Ada'}}</td>
                    <td>{{ $employee->gender }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="4">Tidak ada Karyawan yang terdaftar di jabatan ini</td>
                </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="4">Total Data: <b>{{ $employees->where('id_sub_team_1', $subteam->id_sub_team)->count() }}</b> Karyawan</td>
                </tr>
            </tfoot>
        </table>
        @endif
        @endforeach
    @endforeach
@endforeach
