@if (Request::is('inputs/presences'))
<h1 class="text-center mb-4">Data Kehadiran</h1>
@elseif (Request::is('inputs/kbu/performances') || Request::is('inputs/ktt/performances'))
<h1 class="text-center mb-4">Data Prestasi Kerja</h1>
@endif
@include('Templates.Includes.Components.alert')
<div class="row">
    <div class="col-md-3">
        <div class="position-sticky" style="top: 2rem;">
            <div class="nav flex-column nav-pills me-3" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                @if (Request::is('inputs/presences'))
                    @forelse ($periods as $period)
                    <button class="nav-link {{ $loop->first ? 'active' : '' }}" id="pills-{{ $period->id_period }}-tab" data-bs-toggle="pill" data-bs-target="#pills-{{ $period->id_period }}" type="button" role="tab" aria-controls="pills-{{ $period->id_period }}" aria-selected="{{ $loop->first ? 'true' : 'false' }}">
                        {{ $period->name }}
                    </button>
                    @empty
                    <button class="nav-link active" id="pills-empty-tab" data-bs-toggle="pill" data-bs-target="#pills-empty" type="button" role="tab" aria-controls="pills-empty" aria-selected="true">
                        Empty
                    </button>
                    @endforelse
                @elseif (Request::is('inputs/kbu/performances') || Request::is('inputs/ktt/performances'))
                    @forelse ($periods as $period)
                    <button class="nav-link {{ $loop->first ? 'active' : '' }}" id="pills-{{ $period->id_period }}-tab" data-bs-toggle="pill" data-bs-target="#pills-{{ $period->id_period }}" type="button" role="tab" aria-controls="pills-{{ $period->id_period }}" aria-selected="{{ $loop->first ? 'true' : 'false' }}">
                        {{ $period->name }}
                    </button>
                    @empty
                    <button class="nav-link active" id="pills-empty-tab" data-bs-toggle="pill" data-bs-target="#pills-empty" type="button" role="tab" aria-controls="pills-empty" aria-selected="true">
                        Empty
                    </button>
                    @endforelse
                @endif
            </div>
            <br/>
        </div>
    </div>
    <div class="col-md-9">
        <div class="tab-content" id="v-pills-tabContent">
            @forelse ($periods as $period)
            <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" id="pills-{{ $period->id_period }}" role="tabpanel" aria-labelledby="pills-{{ $period->id_period }}-tab" tabindex="0">
                <h2>{{ $period->name }}</h2>
                <p>
                    <div class="row g-3 align-items-center">
                        <div class="col-auto">
                            <label for="tahun_saw_dl" class="col-form-label">Lihat Data</label>
                        </div>
                        <div class="col-auto">
                            <div class="btn-group" role="group" aria-label="Basic example">
                                <a class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-inp-view-{{ $period->id_period }}">
                                    <i class="bi bi-file-spreadsheet"></i>
                                    Hanya Data Ini
                                </a>
                                <a class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-all-view-{{ $period->id_period }}">
                                    <i class="bi bi-database"></i>
                                    Semua Data
                                </a>
                            </div>
                        </div>
                    </div>
                </p>
                <table class="table table-hover table-bordered">
                    <thead>
                        <tr class="table-primary">
                            <th rowspan="2" class="col-1" scope="col">#</th>
                            <th rowspan="2" scope="col">Nama</th>
                            <th rowspan="2" scope="col">Jabatan</th>
                            <th colspan="2" scope="col">Status</th>
                            <th rowspan="2" class="col-1" scope="col">Action</th>
                        </tr>
                        <tr class="table-primary">
                            <th>Isi</th>
                            <th>Valid</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($officers as $officer)
                        <tr>
                            <th scope="row">{{ $loop->iteration }}</th>
                            <td>{{ $officer->name }}</td>
                            <td>{{ $officer->department->name }}</td>
                            <td>
                                @if (Request::is('inputs/presences'))
                                    @if ($countsub == 0)
                                    <span class="badge text-bg-secondary">Kriteria Kosong</span>
                                    @elseif ($presences->where('id_officer', $officer->id_officer)->where('id_period', $period->id_period)->count() == $countsub)
                                    <span class="badge text-bg-primary">Terisi Semua</span>
                                    @elseif ($presences->where('id_officer', $officer->id_officer)->where('id_period', $period->id_period)->count() == 0)
                                    <span class="badge text-bg-danger">Tidak Terisi</span>
                                    @else
                                    <span class="badge text-bg-warning">Terisi Sebagian</span>
                                    @endif
                                @elseif (Request::is('inputs/kbu/performances') || Request::is('inputs/ktt/performances'))
                                    @if ($countsub == 0)
                                    <span class="badge text-bg-secondary">Kriteria Kosong</span>
                                    @elseif ($performances->where('id_officer', $officer->id_officer)->where('id_period', $period->id_period)->count() == $countsub)
                                    <span class="badge text-bg-primary">Terisi Semua</span>
                                    @elseif ($performances->where('id_officer', $officer->id_officer)->where('id_period', $period->id_period)->count() == 0)
                                    <span class="badge text-bg-danger">Tidak Terisi</span>
                                    @else
                                    <span class="badge text-bg-warning">Terisi Sebagian</span>
                                    @endif
                                @endif
                            </td>
                            @forelse ($status->where('id_officer', $officer->id_officer)->where('id_period', $period->id_period) as $s)
                                @if ($s->status == 'Pending')
                                <td><span class="badge text-bg-primary">Belum Diperiksa</span></td>
                                @elseif ($s->status == 'In Review')
                                <td><span class="badge text-bg-warning">Dalam Pemeriksaan</span></td>
                                @elseif ($s->status == 'Final')
                                <td><span class="badge text-bg-success">Hasil Akhir</span></td>
                                @elseif ($s->status == 'Need Fix')
                                <td><span class="badge text-bg-danger">Perlu Perbaikan</span></td>
                                @endif
                            @empty
                            <td><span class="badge text-bg-secondary">Blank</span></td>
                            @endforelse
                            <td>
                                <div class="dropdown">
                                    @forelse ($status->where('id_officer', $officer->id_officer)->where('id_period', $period->id_period) as $s)
                                        @if ($s->status == 'Pending' || $s->status == 'Need Fix')
                                        <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="bi bi-menu-button-fill"></i>
                                        </button>
                                        @elseif ($s->status == 'In Review')
                                        <span class="d-inline-block" tabindex="0" data-bs-toggle="tooltip" data-bs-title="Penilaian tersebut sedang dalam pemeriksaan.">
                                            <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false" disabled>
                                                <i class="bi bi-menu-button-fill"></i>
                                            </button>
                                        </span>
                                        @else
                                        <span class="d-inline-block" tabindex="0" data-bs-toggle="tooltip" data-bs-title="Penilaian tersebut sudah disetujui sebagai hasil akhir dan tidak dapat diubah kembali.">
                                            <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false" disabled>
                                                <i class="bi bi-menu-button-fill"></i>
                                            </button>
                                        </span>
                                        @endif
                                    @empty
                                    <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="bi bi-menu-button-fill"></i>
                                    </button>
                                    @endforelse
                                    <ul class="dropdown-menu mx-0 shadow w-table-menu">
                                        <li>
                                            @if ($presences->where('id_officer', $officer->id_officer)->where('id_period', $period->id_period)->count() != 0 || $performances->where('id_officer', $officer->id_officer)->where('id_period', $period->id_period)->count() != 0)
                                            <a class="dropdown-item d-flex gap-2 align-items-center"  href="#" data-bs-toggle="modal" data-bs-target="#modal-inp-view-{{ $period->id_period }}-{{ $officer->id_officer }}"><svg class="bi" width="16" height="16" style="vertical-align: -.125em;"><use xlink:href="#view"/></svg>
                                                Lihat Data
                                            </a>
                                            <a class="dropdown-item d-flex gap-2 align-items-center"  href="#" data-bs-toggle="modal" data-bs-target="#modal-inp-update-{{ $period->id_period }}-{{ $officer->id_officer }}"><svg class="bi" width="16" height="16" style="vertical-align: -.125em;"><use xlink:href="#update"/></svg>
                                                Ubah Data
                                            </a>
                                            <a class="dropdown-item d-flex gap-2 align-items-center"  href="#" data-bs-toggle="modal" data-bs-target="#modal-inp-delete-{{ $period->id_period }}-{{ $officer->id_officer }}"><svg class="bi" width="16" height="16" style="vertical-align: -.125em;"><use xlink:href="#delete"/></svg>
                                                Hapus Data
                                            </a>
                                            @else
                                            <a class="dropdown-item d-flex gap-2 align-items-center"  href="#" data-bs-toggle="modal" data-bs-target="#modal-inp-create-{{ $period->id_period }}-{{ $officer->id_officer }}"><svg class="bi" width="16" height="16" style="vertical-align: -.125em;"><use xlink:href="#create"/></svg>
                                                Tambah Data
                                            </a>
                                            @endif
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10">Tidak ada Pegawai yang terdaftar</td>
                        </tr>
                        @endforelse
                    </tbody>
                    <tfoot class="table-group-divider table-secondary">
                        <tr>
                            <td colspan="10">Total Data: <b>{{ $officers->count() }}</b> Pegawai</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            @empty
            <div class="tab-pane fade show active" id="pills-empty" role="tabpanel" aria-labelledby="pills-empty-tab" tabindex="0">
                <div class="alert alert-danger" role="alert">
                    <p>Tidak ada data yang terdaftar.</p>
                </div>
            </div>
            @endforelse
        </div>
    </div>
</div>
