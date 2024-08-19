@if (Auth::user()->part != "Pegawai")
<!--INPUT CHECKER PER PERIOD-->
<div class="modal modal-lg fade" id="modal-inp-view-{{ $latest_per->id_period ?? '' }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
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
                                <th rowspan="2" scope="col">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($officers as $officer)
                            <tr>
                                <th scope="row">{{ $loop->iteration }}</th>
                                <td>{{ $officer->name }}</td>
                                <td>{{ $officer->department->name }}</td>
                                @if ($countsub != 0)
                                <td>
                                    @if ($officer->is_lead == 'No')
                                        @if ($inputs->where('id_officer', $officer->id_officer)->where('id_period', $latest_per->id_period ?? '')->count() == $countsub)
                                        <span class="badge text-bg-primary">Terisi Semua</span>
                                        @elseif ($inputs->where('id_officer', $officer->id_officer)->where('id_period', $latest_per->id_period ?? '')->count() == 0)
                                        <span class="badge text-bg-danger">Tidak Terisi</span>
                                        @else
                                        <span class="badge text-bg-warning">Terisi Sebagian</span>
                                        @endif
                                    @else
                                        <span class="badge text-bg-secondary">Excluded</span>
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
<!--REJECTED INPUT CHECKER PER PERIOD-->
<div class="modal modal-lg fade" id="modal-inp-reject-{{ $latest_per->id_period ?? '' }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
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
                            @forelse ($reject_offs as $officer)
                            <tr>
                                <th scope="row">{{ $loop->iteration }}</th>
                                <td>{{ $officer->name }}</td>
                                <td>{{ $officer->department->name }}</td>
                                <td>
                                    @foreach ($scores->where('id_officer', $officer->id_officer)->where('id_period', $latest_per->id_period ?? '') as $score)
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
                                <td colspan="10">Tidak ada Pegawai yang memiliki nilai yang ditolak atau nilai yang ditolak telah direvisi</td>
                            </tr>
                            @endforelse
                        </tbody>
                        <tfoot class="table-group-divider table-secondary">
                            <tr>
                                <td colspan="20">Total Data: <b>{{ $reject_offs->count() }}</b> Pegawai</td>
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
    <div class="modal-dialog">
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
                                @if (Auth::user()->part != "Pegawai")
                                <th scope="col">Nilai Akhir</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                        @foreach ($periods as $period)
                            @foreach ($voteresults->where('id_period', $period->id_period) as $voteresult)
                            <tr>
                                <th scope="row">{{ $loop->iteration }}</th>
                                <td>{{ $voteresult->period_name }}</td>
                                <td>{{ $voteresult->officer_name }}</td>
                                <td>{{ $voteresult->officer_department }}</td>
                                @if (Auth::user()->part != "Pegawai")
                                <td>{{ $voteresult->final_score }}</td>
                                @endif
                            </tr>
                            @endforeach
                        @endforeach
                        </tbody>
                        <tfoot class="table-group-divider table-secondary">
                            <tr>
                                <td colspan="5">Total Data: <b>00</b> Data</td>
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
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Tiga Hasil Akhir Terbaik Saat Ini ({{ $history_prd->period_name ?? ''}})</h1>
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
                                @if (Auth::user()->part != "Pegawai")
                                <th scope="col">Nilai Akhir</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($scoreresults->where('id_period', $history_prd->id_period)->take(3) as $scoreresult)
                                <tr>
                                    <th scope="row">{{ $loop->iteration }}</th>
                                    <td>{{ $scoreresult->officer_name }}</td>
                                    <td>{{ $scoreresult->officer_department }}</td>
                                    <td>{{ $scoreresult->final_score }}</td>
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
<!--GUIDES-->
<div class="modal modal-lg fade" id="modal-idx-guide" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Petunjuk Pengguna (Back End)</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">
                        <i class="bi bi-x-lg"></i>
                        Tutup
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
