<img src="{{ public_path('Images/Logo/BPS Black.png') }}" style="max-width: 40%;">
<h1 style="text-align:center;">Laporan Penilaian</h1>
<p>Periode: {{ $month }} {{ $year }}</p>
<p>Tanggal Pembaharuan: {{ now() }}</p>
<h1>Data Kehadiran</h1>
<table id="table">
    <thead>
        <tr>
            <th rowspan="2" scope="col">#</th>
            <th rowspan="2" scope="col">Nama</th>
            <th rowspan="2" scope="col">Jabatan</th>
            <th colspan="{{ $countprs }}" scope="col">Kriteria</th>
        </tr>
        <tr class="table-primary">
            @foreach ($subcritprs as $prs)
            <th scope="col">{{ $prs->name }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @forelse ($employees as $employee)
        <tr>
            <th scope="row">{{ $loop->iteration }}</th>
            <td>{{ $employee->name }}</td>
            <td>{{ $employee->position->name }}</td>
            @foreach ($subcritprs as $prs)
                @forelse ($presences->where('id_sub_criteria', $prs->id_sub_criteria)->where('id_employee', $employee->id_employee) as $presence)
                <td>{{ $presence->input }}</td>
                @empty
                    <td>0</td>
                @endforelse
            @endforeach
        </tr>
        @empty
        <tr>
            <td colspan="10">Tidak ada Karyawan yang terdaftar</td>
        </tr>
        @endforelse
    </tbody>
    <tfoot class="table-group-divider table-secondary">
        <tr>
            <td colspan="20">Total Data: <b>{{ $employees->count() }}</b> Karyawan</td>
        </tr>
    </tfoot>
</table>
<br/>
<h2>Data Prestasi Kerja</h2>
<table id="table">
    <thead>
        <tr>
            <th rowspan="2">#</th>
            <th rowspan="2">Nama</th>
            <th rowspan="2">Jabatan</th>
            <th colspan="{{ $countprf }}">Kriteria</th>
        </tr>
        <tr class="table-primary">
            @foreach ($subcritprf as $prf)
            <th>{{ $prf->name }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @forelse ($employees as $employee)
        <tr>
            <th scope="row">{{ $loop->iteration }}</th>
            <td>{{ $employee->name }}</td>
            <td>{{ $employee->position->name }}</td>
            @foreach ($subcritprf as $prf)
                @forelse ($performances->where('id_sub_criteria', $prf->id_sub_criteria)->where('id_employee', $employee->id_employee) as $performance)
                <td>{{ $performance->input }}</td>
                @empty
                <td>0</td>
                @endforelse
            @endforeach
        </tr>
        @empty
        <tr>
            <td colspan="10">Tidak ada Karyawan yang terdaftar</td>
        </tr>
        @endforelse
    </tbody>
    <tfoot class="table-group-divider table-secondary">
        <tr>
            <td colspan="20">Total Data: <b>{{ $employees->count() }}</b> Karyawan</td>
        </tr>
    </tfoot>
</table>
