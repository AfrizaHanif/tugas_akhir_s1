<h1 class="text-center mb-4">Hasil Perhitungan</h1>
@include('Pages.Admin.Includes.Components.alert')
<div class="row">
    <div class="col-md-3">
        <div class="position-sticky" style="top: 2rem;">
            <div class="nav flex-column nav-pills me-3" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                @forelse ($periods as $period)
                <button class="nav-link {{ $loop->first ? 'active' : '' }}" id="pills-{{ $period->id_period }}-tab" data-bs-toggle="pill" data-bs-target="#pills-{{ $period->id_period }}" type="button" role="tab" aria-controls="pills-{{ $period->id_period }}" aria-selected="{{ $loop->first ? 'true' : 'false' }}">
                    {{ $period->name }}
                </button>
                @empty
                <button class="nav-link active" id="pills-empty-tab" data-bs-toggle="pill" data-bs-target="#pills-empty" type="button" role="tab" aria-controls="pills-empty" aria-selected="true">
                    Empty
                </button>
                @endforelse
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
                            <div class="btn-group" role="group" aria-label="Basic example">
                                <a class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-res-get-{{ $period->id_period }}">
                                    <i class="bi bi-person-plus"></i>
                                    Ambil data
                                </a>
                            </div>
                        </div>
                        <div class="col-auto">
                            <div class="btn-group" role="group" aria-label="Basic example">
                                <a class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-all-view-{{ $period->id_period }}">
                                    <i class="bi bi-person-plus"></i>
                                    Cek Data
                                </a>
                                <a class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-stt-view-{{ $period->id_period }}">
                                    <i class="bi bi-person-plus"></i>
                                    Cek Status
                                </a>
                            </div>
                        </div>
                    </div>
                </p>
                <table class="table table-hover table-bordered">
                    <thead>
                        <tr class="table-primary">
                            <th class="col-1" scope="col">#</th>
                            <th scope="col">Nama</th>
                            <th scope="col">Hasil Akhir</th>
                            <th class="col-3 scope="col">Status</th>
                            <th class="col-1" scope="col">Setuju?</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($results->where('id_period', $period->id_period) as $result)
                        <tr>
                            <th scope="row">{{ $loop->iteration }}</th>
                            <td>{{ $result->officer->name }}</td>
                            <td>{{ $result->final_score }}</td>
                            <td>
                                @if ($result->status == 'Pending')
                                <span class="badge text-bg-warning">Menunggu Persetujuan</span>
                                @elseif ($result->status == 'Accepted')
                                <span class="badge text-bg-success">Disetujui</span>
                                @elseif ($result->status == 'Rejected')
                                <span class="badge text-bg-danger">Ditolak</span>
                                @else
                                <span class="badge text-bg-secondary">Blank</span>
                                @endif
                            </td>
                            <td>
                                <div class="dropdown">
                                    <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="bi bi-menu-button-fill"></i>
                                    </button>
                                    <ul class="dropdown-menu mx-0 shadow w-table-menu">
                                        <li>
                                            <a class="dropdown-item d-flex gap-2 align-items-center" href="#" data-bs-toggle="modal" data-bs-target="#modal-res-yes-{{ $period->id_period }}-{{ $result->id }}"><svg class="bi" width="16" height="16" style="vertical-align: -.125em;"><use xlink:href="#delete"/></svg>
                                                Ya
                                            </a>
                                            <a class="dropdown-item d-flex gap-2 align-items-center" href="#" data-bs-toggle="modal" data-bs-target="#modal-res-no-{{ $period->id_period }}-{{ $result->id }}"><svg class="bi" width="16" height="16" style="vertical-align: -.125em;"><use xlink:href="#delete"/></svg>
                                                Tidak
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5">Silahkan klik Ambil Data terlebih dahulu untuk mendapatkan data.</td>
                        </tr>
                        @endforelse
                    </tbody>
                    <tfoot>
                        <tfoot class="table-group-divider table-secondary">
                            <tr>
                                <td colspan="10">Total Data: <b>{{ $results->where('id_period', $period->id_period)->count() }}</b> Pegawai</td>
                            </tr>
                        </tfoot>
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
