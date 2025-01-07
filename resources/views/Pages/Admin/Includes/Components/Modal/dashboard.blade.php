@if (Auth::user()->part == "Admin")
<!--INPUT CHECKER PER PERIOD-->
<div class="modal modal-lg fade" id="modal-inp-view-{{ $latest_per->id_period ?? '' }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Daftar Nilai Terinput ({{ $latest_per->name ?? '' }})</h1>
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
                                <th colspan="2" scope="col">Status</th>
                            </tr>
                            <tr class="table-secondary">
                                <th scope="col">Isi</th>
                                <th scope="col">Konversi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($employees as $employee)
                            <tr>
                                <th scope="row">{{ $loop->iteration }}</th>
                                <td>{{ $employee->name }}</td>
                                <td>{{ $employee->position->name }}</td>
                                @if ($countsub != 0)
                                <td>
                                    @if (!empty($inputs->where('id_employee', $employee->id_employee)->where('id_period', $latest_per->id_period ?? '')))
                                        @if ($inputs->where('id_employee', $employee->id_employee)->where('id_period', $latest_per->id_period ?? '')->count() == $countsub)
                                        <span class="badge text-bg-primary">Terisi Semua</span>
                                        @elseif ($inputs->where('id_employee', $employee->id_employee)->where('id_period', $latest_per->id_period ?? '')->count() == 0)
                                        <span class="badge text-bg-danger">Tidak Terisi</span>
                                        @else
                                        <span class="badge text-bg-warning">Terisi Sebagian</span>
                                        @endif
                                    @else
                                    <span class="badge text-bg-danger">Tidak Terisi</span>
                                    @endif
                                </td>
                                <td>
                                    @if (!empty($inputs->where('id_employee', $employee->id_employee)->where('id_period', $latest_per->id_period ?? '')))
                                        @if ($inputs->where('id_employee', $employee->id_employee)->where('id_period', $latest_per->id_period ?? '')->where('status', 'Not Converted')->count() >= 1 && $inputs->where('id_employee', $employee->id_employee)->where('id_period', $latest_per->id_period ?? '')->where('status', 'Pending')->count() >= 1)
                                        <span class="badge text-bg-warning">Sebagian Dikonversi</span>
                                        @else
                                            @if (!empty($latest_per))
                                                @forelse ($status->where('id_employee', $employee->id_employee)->where('id_period', $latest_per->id_period) as $s)
                                                    @if ($s->status == 'Not Converted')
                                                    <span class="badge text-bg-warning">Belum Dikonversi</span>
                                                    @else
                                                    <span class="badge text-bg-success">Telah Dikonversi</span>
                                                    @endif
                                                @empty
                                                <span class="badge text-bg-secondary">Belum Ada Data</span>
                                                @endforelse
                                            @else
                                            <span class="badge text-bg-secondary">Belum Ada Data</span>
                                            @endif
                                        @endif
                                    @else
                                    <span class="badge text-bg-secondary">Belum Ada Data</span>
                                    @endif
                                </td>
                                @endif
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
                </div>
            </div>
            <div class="modal-footer">
                <a type="button" href="{{ route('admin.inputs.data.index') }}" class="btn btn-primary">
                    <i class="bi bi-box-arrow-in-right"></i>
                    Ke Halaman
                </a>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-lg"></i>
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>
@endif
@if (Auth::user()->part == "KBPS")
<!--PROGRESS PENDING PER PERIOD-->
<div class="modal modal-lg fade" id="modal-prg-view-{{ $latest_per->id_period ?? '' }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Pending Proses Penilaian ({{ $latest_per->name ?? '' }})</h1>
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
                                <th rowspan="2" scope="col">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($progress_offs as $employee)
                            <tr>
                                <th scope="row">{{ $loop->iteration }}</th>
                                <td>{{ $employee->name }}</td>
                                <td>{{ $employee->position->name }}</td>
                                <td>
                                    @if (!empty($latest_per))
                                        @if ($inputs->where('id_employee', $employee->id_employee)->where('id_period', $latest_per->id_period ?? '')->where('status', 'Not Converted')->count() >= 1 && $inputs->where('id_employee', $employee->id_employee)->where('id_period', $latest_per->id_period ?? '')->where('status', 'Pending')->count() >= 1)
                                        <span class="badge text-bg-warning">Perlu Perhatian</span>
                                        @else
                                            @if (!empty($inputs->where('id_employee', $employee->id_employee)->where('id_period', $latest_per->id_period ?? '')))
                                                @if ($inputs->where('id_employee', $employee->id_employee)->where('id_period', $latest_per->id_period ?? '')->count() == $countsub && $latest_per->import_status == 'Clear')
                                                <span class="badge text-bg-success">Siap Diambil</span>
                                                @else
                                                    @foreach ($input_lists->where('id_employee', $employee->id_employee)->where('id_period', $latest_per->id_period ?? '') as $input)
                                                        @if ($input->status == 'Not Converted')
                                                        <span class="badge text-bg-warning">Belum Dikonversi</span>
                                                        @else
                                                        <span class="badge text-bg-warning">Belum Siap Diambil</span>
                                                        @endif
                                                    @endforeach
                                                @endif
                                            @else
                                            <span class="badge text-bg-secondary">Blank</span>
                                            @endif
                                        @endif
                                    @else
                                    <span class="badge text-bg-secondary">Blank</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="10">Tidak ada Data</td>
                            </tr>
                            @endforelse
                        </tbody>
                        <tfoot class="table-group-divider table-secondary">
                            <tr>
                                <td colspan="20">Total Data: <b>{{ $progress_offs->count() }}</b> Data</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <a type="button" href="{{ route('admin.inputs.validate.index') }}" class="btn btn-primary">
                    <i class="bi bi-box-arrow-in-right"></i>
                    Ke Halaman
                </a>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-lg"></i>
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>
<!--SCORE CONFIRM PENDING PER PERIOD-->
<div class="modal modal-lg fade" id="modal-scr-view-{{ $latest_per->id_period ?? '' }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Pending Persetujuan Penilaian ({{ $latest_per->name ?? '' }})</h1>
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
                                <th rowspan="2" scope="col">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($acc_offs as $employee)
                            <tr>
                                <th scope="row">{{ $loop->iteration }}</th>
                                <td>{{ $employee->name }}</td>
                                <td>{{ $employee->position->name }}</td>
                                <td>
                                    @if (!empty($latest_per))
                                        @if ($inputs->where('id_employee', $employee->id_employee)->where('id_period', $latest_per->id_period ?? '')->where('status', 'Not Converted')->count() >= 1 && $inputs->where('id_employee', $employee->id_employee)->where('id_period', $latest_per->id_period ?? '')->where('status', 'Pending')->count() >= 1)
                                        <span class="badge text-bg-warning">Perlu Perhatian</span>
                                        @else
                                            @if (!empty($inputs->where('id_employee', $employee->id_employee)->where('id_period', $latest_per->id_period ?? '')))
                                                @if ($inputs->where('id_employee', $employee->id_employee)->where('id_period', $latest_per->id_period ?? '')->count() == $countsub)
                                                    @forelse ($input_lists->where('id_employee', $employee->id_employee)->where('id_period', $latest_per->id_period ?? '') as $input)
                                                        @if ($input->status == 'Pending' || $input->status == 'Fixed')
                                                        <span class="badge text-bg-primary">Siap Diperiksa</span>
                                                        @elseif ($input->status == 'In Review')
                                                        <span class="badge text-bg-warning">Belum Diperiksa</span>
                                                        @else
                                                        <span class="badge text-bg-secondary">Blank</span>
                                                        @endif
                                                    @empty
                                                    <span class="badge text-bg-secondary">Blank</span>
                                                    @endforelse
                                                @else
                                                <span class="badge text-bg-warning">Belum Siap Diperiksa</span>
                                                @endif
                                            @else
                                            <span class="badge text-bg-secondary">Blank</span>
                                            @endif
                                        @endif
                                    @else
                                    <span class="badge text-bg-secondary">Blank</span>
                                    @endif

                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="10">Tidak ada Data</td>
                            </tr>
                            @endforelse
                        </tbody>
                        <tfoot class="table-group-divider table-secondary">
                            <tr>
                                <td colspan="20">Total Data: <b>{{ $acc_offs->count() }}</b> Data</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <a type="button" href="{{ route('admin.inputs.validate.index') }}" class="btn btn-primary">
                    <i class="bi bi-box-arrow-in-right"></i>
                    Ke Halaman
                </a>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-lg"></i>
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>
@endif
@if (Auth::user()->part == "Admin" || Auth::user()->part == "KBPS")
<!--REJECTED INPUT CHECKER PER PERIOD-->
<div class="modal modal-lg fade" id="modal-inp-reject-{{ $latest_per->id_period ?? '' }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Daftar Nilai Ditolak ({{ $latest_per->name ?? '' }})</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                        <thead>
                            <tr class="table-primary">
                                <th class="col-1" scope="col">#</th>
                                <th scope="col">Nama</th>
                                <th scope="col">Jabatan</th>
                                <th scope="col">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($reject_offs as $employee)
                            <tr>
                                <th scope="row">{{ $loop->iteration }}</th>
                                <td>{{ $employee->name }}</td>
                                <td>{{ $employee->position->name }}</td>
                                <td>
                                    @foreach ($scores->where('id_employee', $employee->id_employee)->where('id_period', $latest_per->id_period ?? '') as $score)
                                    @if ($score->status == 'Rejected')
                                    <span class="badge text-bg-danger">Ditolak</span>
                                    @elseif ($score->status == 'Revised')
                                    <span class="badge text-bg-primary">Telah Diperbaiki</span>
                                    @else
                                    <span class="badge text-bg-secondary">Blank</span>
                                    @endif
                                    @endforeach
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="10">Tidak ada Karyawan yang memiliki nilai yang ditolak atau nilai yang telah direvisi</td>
                            </tr>
                            @endforelse
                        </tbody>
                        <tfoot class="table-group-divider table-secondary">
                            <tr>
                                <td colspan="20">Total Data: <b>{{ $reject_offs->count() }}</b> Data</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <a type="button" href="{{ route('admin.inputs.data.index') }}" class="btn btn-primary">
                    <i class="bi bi-box-arrow-in-right"></i>
                    Ke Halaman
                </a>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-lg"></i>
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>
@endif
<!--LATEST EMPLOYEE OF THE MONTH-->
<div class="modal modal-lg fade" id="modal-best" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Daftar Riwayat Karyawan Terbaik</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                        <thead>
                            <tr class="table-primary">
                                <th class="col-1" scope="col">#</th>
                                <th scope="col">Periode</th>
                                <th scope="col">Nama</th>
                                <th scope="col">Jabatan</th>
                                @if (Auth::user()->part != "Karyawan")
                                <th scope="col">Nilai Akhir</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                        @foreach ($periods->where('progress_status', 'Finished') as $period)
                        <tr>
                            <th scope="row">{{ $loop->iteration }}</th>
                            @foreach ($voteresults->where('id_period', $period->id_period) as $voteresult)
                            <td>{{ $voteresult->period->name }}</td>
                            <td>{{ $voteresult->employee_name }}</td>
                            <td>{{ $voteresult->employee_position }}</td>
                                @if (Auth::user()->part != "Karyawan")
                                <td>{{ $voteresult->final_score }}</td>
                                @endif
                            @endforeach
                        </tr>
                        @endforeach
                        </tbody>
                        <tfoot class="table-group-divider table-secondary">
                            <tr>
                                <td colspan="5">Total Data: <b>{{ count($voteresults) }}</b> Data</td>
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
<!--TOP 3 EMPLOYEES-->
<div class="modal modal-lg fade" id="modal-score-{{ $history_prd->id_period ?? '' }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Nilai Akhir Terbaik Saat Ini ({{ $history_prd->period->name ?? ''}})</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    @if (!empty($history_prd))
                    <table class="table table-hover table-bordered">
                        <thead>
                            <tr class="table-primary">
                                <th class="col-1" scope="col">#</th>
                                <th scope="col">Nama</th>
                                <th scope="col">Jabatan</th>
                                @if (Auth::user()->part != "Karyawan")
                                <th scope="col">Nilai Akhir</th>
                                <th scope="col">Nilai Kedua</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($scoreresults->where('id_period', $history_prd->id_period) as $scoreresult)
                                <tr>
                                    <th scope="row">{{ $loop->iteration }}</th>
                                    <td>{{ $scoreresult->employee_name }}</td>
                                    <td>{{ $scoreresult->employee_position }}</td>
                                    <td>{{ $scoreresult->final_score }}</td>
                                    <td>{{ $scoreresult->second_score }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="table-group-divider table-secondary">
                            <tr>
                                <td colspan="5">Total Data: <b>{{ $scoreresults->where('id_period', $history_prd->id_period)->count() }}</b> Data</td>
                            </tr>
                        </tfoot>
                    </table>
                    @else
                    @endif
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
