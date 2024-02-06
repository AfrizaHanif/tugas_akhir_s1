@if (Request::is('inputs/beta/presences') || Request::is('inputs/beta/performances'))
@foreach ($periods as $period)
<div class="modal modal-xl fade" id="modal-all-view-{{ $period->id_period }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Detail Seluruh Data ({{ $period->name }})</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                        <thead>
                            <tr class="table-primary">
                                <th rowspan="3" class="col-1" scope="col">#</th>
                                <th rowspan="3" scope="col">Nama</th>
                                <th rowspan="3" scope="col">Jabatan</th>
                                <th colspan="{{ $countprs + $countprf }}" scope="col">Kriteria</th>
                                <th colspan="2" rowspan="2" scope="col">Status</th>
                            </tr>
                            <tr class="table-primary">
                                <th colspan="{{ $countprs }}" scope="col">Kehadiran</th>
                                <th colspan="{{ $countprf }}" scope="col">Prestasi Kerja</th>
                            </tr>
                            <tr class="table-primary">
                                @foreach ($subcritprs as $scprs)
                                <th>{{ $scprs->name }}</th>
                                @endforeach
                                @foreach ($subcritprf as $scprf)
                                <th>{{ $scprf->name }}</th>
                                @endforeach
                                <th scope="col">Kehadiran</th>
                                <th scope="col">Prestasi Kerja</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($officers as $officer)
                            <tr>
                                <th scope="row">{{ $loop->iteration }}</th>
                                <td>{{ $officer->name }}</td>
                                <td>{{ $officer->department->name }}</td>
                                @foreach ($subcritprs as $scprs)
                                    @forelse ($presences->where('id_sub_criteria', $scprs->id_sub_criteria)->where('id_officer', $officer->id_officer)->where('id_period', $period->id_period) as $presence)
                                    <td>{{ $presence->input }}</td>
                                    @empty
                                        <td>0</td>
                                    @endforelse
                                @endforeach
                                @foreach ($subcritprf as $scprf)
                                    @forelse ($performances->where('id_sub_criteria', $scprf->id_sub_criteria)->where('id_officer', $officer->id_officer)->where('id_period', $period->id_period) as $performance)
                                    <td>{{ $performance->input }}</td>
                                    @empty
                                        <td>0</td>
                                    @endforelse
                                @endforeach
                                <td>
                                    @if ($presences->where('id_officer', $officer->id_officer)->where('id_period', $period->id_period)->count() == $countprs)
                                    <span class="badge text-bg-primary">Terisi Semua</span>
                                    @elseif ($presences->where('id_officer', $officer->id_officer)->where('id_period', $period->id_period)->count() == 0)
                                    <span class="badge text-bg-danger">Tidak Terisi</span>
                                    @else
                                    <span class="badge text-bg-warning">Terisi Sebagian</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($performances->where('id_officer', $officer->id_officer)->where('id_period', $period->id_period)->count() == $countprf)
                                    <span class="badge text-bg-primary">Terisi Semua</span>
                                    @elseif ($performances->where('id_officer', $officer->id_officer)->where('id_period', $period->id_period)->count() == 0)
                                    <span class="badge text-bg-danger">Tidak Terisi</span>
                                    @else
                                    <span class="badge text-bg-warning">Terisi Sebagian</span>
                                    @endif
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
                                <td colspan="20">Total Data: <b>{{ $officers->count() }}</b> Pegawai</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-lg"></i>
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal modal-xl fade" id="modal-inp-view-{{ $period->id_period }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Detail Data Kehadiran ({{ $period->name }})</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                        <thead>
                            <tr class="table-primary">
                                <th rowspan="2" class="col-1" scope="col">#</th>
                                <th rowspan="2" scope="col">Nama</th>
                                <th rowspan="2" scope="col">Jabatan</th>
                                @if ($countsub != 0)
                                <th colspan="{{ $countsub }}" scope="col">Kriteria</th>
                                @else
                                <th rowspan="2" scope="col">Kriteria</th>
                                @endif
                                <th rowspan="2" scope="col">Status</th>
                            </tr>
                            <tr class="table-primary">
                                @foreach ($subcriterias as $subcriteria)
                                <th>{{ $subcriteria->name }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($officers as $officer)
                            <tr>
                                <th scope="row">{{ $loop->iteration }}</th>
                                <td>{{ $officer->name }}</td>
                                <td>{{ $officer->department->name }}</td>
                                @if ($countsub != 0)
                                    @foreach ($subcriterias as $subcriteria)
                                        @if (Request::is('inputs/beta/presences'))
                                            @forelse ($presences->where('id_sub_criteria', $subcriteria->id_sub_criteria)->where('id_officer', $officer->id_officer)->where('id_period', $period->id_period) as $presence)
                                                <td>{{ $presence->input }}</td>
                                            @empty
                                                <td>0</td>
                                            @endforelse
                                        @elseif (Request::is('inputs/beta/performances'))
                                            @forelse ($performances->where('id_sub_criteria', $subcriteria->id_sub_criteria)->where('id_officer', $officer->id_officer)->where('id_period', $period->id_period) as $performance)
                                                <td>{{ $performance->input }}</td>
                                            @empty
                                                <td>0</td>
                                            @endforelse
                                        @endif
                                    @endforeach
                                @else
                                <td colspan="3">
                                    <span class="badge text-bg-secondary">Kriteria Kosong</span>
                                </td>
                                @endif
                                @if ($countsub != 0)
                                <td>
                                    @if (Request::is('inputs/beta/presences'))
                                        @if ($presences->where('id_officer', $officer->id_officer)->where('id_period', $period->id_period)->count() == $countsub)
                                        <span class="badge text-bg-primary">Terisi Semua</span>
                                        @elseif ($presences->where('id_officer', $officer->id_officer)->where('id_period', $period->id_period)->count() == 0)
                                        <span class="badge text-bg-danger">Tidak Terisi</span>
                                        @else
                                        <span class="badge text-bg-warning">Terisi Sebagian</span>
                                        @endif
                                    @elseif (Request::is('inputs/beta/performances'))
                                        @if ($performances->where('id_officer', $officer->id_officer)->where('id_period', $period->id_period)->count() == $countsub)
                                        <span class="badge text-bg-primary">Terisi Semua</span>
                                        @elseif ($performances->where('id_officer', $officer->id_officer)->where('id_period', $period->id_period)->count() == 0)
                                        <span class="badge text-bg-danger">Tidak Terisi</span>
                                        @else
                                        <span class="badge text-bg-warning">Terisi Sebagian</span>
                                        @endif
                                    @endif
                                </td>
                                @endif
                            </tr>
                            @empty
                            <tr>
                                <td colspan="10">Tidak ada Pegawai yang terdaftar</td>
                            </tr>
                            @endforelse
                        </tbody>
                        <tfoot class="table-group-divider table-secondary">
                            <tr>
                                <td colspan="20">Total Data: <b>{{ $officers->count() }}</b> Pegawai</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-lg"></i>
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>

    @foreach ($officers as $officer)
    <div class="modal fade" id="modal-inp-create-{{ $period->id_period }}-{{ $officer->id_officer }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                @if (Request::is('inputs/beta/presences'))
                <form action="{{ route('inputs.beta.presences.store') }}" method="POST" enctype="multipart/form-data">
                @elseif (Request::is('inputs/beta/performances'))
                <form action="{{ route('inputs.beta.performances.store') }}" method="POST" enctype="multipart/form-data">
                @endif
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Tambah Data Kehadiran ({{ $officer->name }})</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        @csrf
                        <div class="row mb-3">
                            <div class="col">
                                <label for="id_officer" class="form-label">Kode Pegawai</label>
                                <input type="text" class="form-control" id="id_officer" name="id_officer" value="{{ $officer->id_officer }}" readonly>
                            </div>
                            <div class="col">
                                <label for="id_period" class="form-label">Kode Periode</label>
                                <input type="text" class="form-control" id="id_period" name="id_period" value="{{ $period->id_period }}" readonly>
                            </div>
                        </div>
                        <hr/>
                        @forelse ($subcriterias as $subcriteria)
                        <div class="mb-3">
                            <label for="{{ $subcriteria->id_sub_criteria }}" class="form-label">{{ $subcriteria->name }}</label>
                            <input type="number" class="form-control" id="{{ $subcriteria->id_sub_criteria }}" name="{{ $subcriteria->id_sub_criteria }}" min="0" max="31" required>
                        </div>
                        @empty
                        <div class="alert alert-danger" role="alert">
                            Tidak ada data sub kriteria untuk Data Kehadiran
                        </div>
                        @endforelse
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">
                            <i class="bi bi-x-lg"></i>
                            Batal
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-plus-lg"></i>
                            Tambah
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-inp-update-{{ $period->id_period }}-{{ $officer->id_officer }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                @if (Request::is('inputs/beta/presences'))
                <form action="{{ route('inputs.beta.presences.update', $officer->id_officer) }}" method="POST" enctype="multipart/form-data">
                @elseif (Request::is('inputs/beta/performances'))
                <form action="{{ route('inputs.beta.performances.update', $officer->id_officer) }}" method="POST" enctype="multipart/form-data">
                @endif
                    <div class="modal-header">
                        @if (Request::is('inputs/beta/presences'))
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Ubah Data Kehadiran ({{ $officer->id_officer }})</h1>
                        @elseif (Request::is('inputs/beta/performances'))
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Ubah Data Prestasi Kerja ({{ $officer->id_officer }})</h1>
                        @endif
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        @csrf @method('PUT')
                        <div class="row mb-3">
                            <div class="col">
                                <label for="id_officer" class="form-label">Kode Pegawai</label>
                                <input type="text" class="form-control" id="id_officer" name="id_officer" value="{{ $officer->id_officer }}" readonly>
                            </div>
                            <div class="col">
                                <label for="id_period" class="form-label">Kode Periode</label>
                                <input type="text" class="form-control" id="id_period" name="id_period" value="{{ $period->id_period }}" readonly>
                            </div>
                        </div>
                        <hr/>
                        @forelse ($subcriterias as $subcriteria)
                            @if (Request::is('inputs/beta/presences'))
                            @forelse ($presences->where('id_sub_criteria', $subcriteria->id_sub_criteria)->where('id_officer', $officer->id_officer)->where('id_period', $period->id_period) as $presence)
                            <div class="mb-3">
                                <label for="{{ $subcriteria->id_sub_criteria }}" class="form-label">{{ $subcriteria->name }}</label>
                                <input type="number" class="form-control" id="{{ $subcriteria->id_sub_criteria }}" name="{{ $subcriteria->id_sub_criteria }}" value="{{ $presence->input }}" min="0" max="31" required>
                            </div>
                            @empty
                            <div class="mb-3">
                                <label for="{{ $subcriteria->id_sub_criteria }}" class="form-label">{{ $subcriteria->name }}</label>
                                <input type="number" class="form-control" id="{{ $subcriteria->id_sub_criteria }}" name="{{ $subcriteria->id_sub_criteria }}" min="0" max="31" required>
                            </div>
                            @endforelse
                            @elseif (Request::is('inputs/beta/performances'))
                            @forelse ($performances->where('id_sub_criteria', $subcriteria->id_sub_criteria)->where('id_officer', $officer->id_officer)->where('id_period', $period->id_period) as $performance)
                            <div class="mb-3">
                                <label for="{{ $subcriteria->id_sub_criteria }}" class="form-label">{{ $subcriteria->name }}</label>
                                <input type="number" class="form-control" id="{{ $subcriteria->id_sub_criteria }}" name="{{ $subcriteria->id_sub_criteria }}" value="{{ $performance->input }}" min="0" max="31" required>
                            </div>
                            @empty
                            <div class="mb-3">
                                <label for="{{ $subcriteria->id_sub_criteria }}" class="form-label">{{ $subcriteria->name }}</label>
                                <input type="number" class="form-control" id="{{ $subcriteria->id_sub_criteria }}" name="{{ $subcriteria->id_sub_criteria }}" min="0" max="31" required>
                            </div>
                            @endforelse
                            @endif
                        @empty
                        <div class="alert alert-danger" role="alert">
                            Tidak ada data sub kriteria untuk Data Kehadiran
                        </div>
                        @endforelse
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">
                            <i class="bi bi-x-lg"></i>
                            Batal
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-plus-lg"></i>
                            Ubah
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-inp-view-{{ $period->id_period }}-{{ $officer->id_officer }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Ubah Data Kehadiran ({{ $officer->id_officer }})</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table class="table">
                    @foreach ($subcriterias as $subcriteria)
                        @if (Request::is('inputs/beta/presences'))
                            @forelse ($presences->where('id_sub_criteria', $subcriteria->id_sub_criteria)->where('id_officer', $officer->id_officer)->where('id_period', $period->id_period) as $presence)
                            <tr>
                                <th scope="row">{{ $subcriteria->name }}</th>
                                <td>
                                    @if ($subcriteria->need == 'Ya')
                                    <b>{{ $presence->input }}</b>
                                    @else
                                    {{ $presence->input }}
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <th scope="row">{{ $subcriteria->name }}</th>
                                <td>
                                    @if ($subcriteria->need == 'Ya')
                                    <b>0</b>
                                    @else
                                    0
                                    @endif
                                </td>
                            </tr>
                            @endforelse
                        @elseif (Request::is('inputs/beta/performances'))
                            @forelse ($performances->where('id_sub_criteria', $subcriteria->id_sub_criteria)->where('id_officer', $officer->id_officer)->where('id_period', $period->id_period) as $performance)
                            <tr>
                                <th scope="row">{{ $subcriteria->name }}</th>
                                <td>
                                    @if ($subcriteria->need == 'Ya')
                                    <b>{{ $performance->input }}</b>
                                    @else
                                    {{ $performance->input }}
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <th scope="row">{{ $subcriteria->name }}</th>
                                <td>
                                    @if ($subcriteria->need == 'Ya')
                                    <b>0</b>
                                    @else
                                    0
                                    @endif
                                </td>
                            </tr>
                            @endforelse
                        @endif
                    @endforeach
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-lg"></i>
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-inp-delete-{{ $period->id_period }}-{{ $officer->id_officer }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                @if (Request::is('inputs/beta/presences'))
                <form action="{{ route('inputs.beta.presences.destroy', $officer->id_officer) }}" method="POST" enctype="multipart/form-data">
                @elseif (Request::is('inputs/beta/performances'))
                <form action="{{ route('inputs.beta.performances.destroy', $officer->id_officer) }}" method="POST" enctype="multipart/form-data">
                @endif
                    <div class="modal-header">
                        @if (Request::is('inputs/beta/presences'))
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Hapus Data Kehadiran ({{ $officer->id_officer}})</h1>
                        @elseif (Request::is('inputs/beta/performances'))
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Hapus Data Prestasi Kerja ({{ $officer->id_officer}})</h1>
                        @endif
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row mb-3">
                            <div class="col">
                                <label for="id_officer" class="form-label">Kode Pegawai</label>
                                <input type="text" class="form-control" id="id_officer" name="id_officer" value="{{ $officer->id_officer }}" readonly>
                            </div>
                            <div class="col">
                                <label for="id_period" class="form-label">Kode Periode</label>
                                <input type="text" class="form-control" id="id_period" name="id_period" value="{{ $period->id_period }}" readonly>
                            </div>
                        </div>
                        <hr/>
                        <div class="alert alert-warning" role="alert">
                            <i class="bi bi-exclamation-triangle-fill"></i> <b>PERHATIAN</b>
                            <br/>
                            Apakah anda ingin menghapus data tersebut? Ini akan berpengaruh dengan total penilaian.
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#modal-dep-view">
                            <i class="bi bi-backspace"></i>
                            Tidak
                        </button>
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="bi bi-check-lg"></i>
                            Ya
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endforeach
@endforeach
@endif
