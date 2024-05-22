@if (Request::is('admin') || Request::is('officer'))
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
                                        @if (Auth::user()->part == "Admin")
                                            @if ($presences->where('id_officer', $officer->id_officer)->where('id_period', $latest_per->id_period ?? '')->count() == $countsub)
                                            <span class="badge text-bg-primary">Terisi Semua</span>
                                            @elseif ($presences->where('id_officer', $officer->id_officer)->where('id_period', $latest_per->id_period ?? '')->count() == 0)
                                            <span class="badge text-bg-danger">Tidak Terisi</span>
                                            @else
                                            <span class="badge text-bg-warning">Terisi Sebagian</span>
                                            @endif
                                        @elseif (Auth::user()->part == "KBU" || Auth::user()->part == "KTT")
                                            @if ($performances->where('id_officer', $officer->id_officer)->where('id_period', $latest_per->id_period ?? '')->count() == $countsub)
                                            <span class="badge text-bg-primary">Terisi Semua</span>
                                            @elseif ($performances->where('id_officer', $officer->id_officer)->where('id_period', $latest_per->id_period ?? '')->count() == 0)
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
                    @if (Auth::user()->part == "Admin")
                    <a type="button" href="{{ route('admin.inputs.presences.officers.index') }}" class="btn btn-primary">
                    @elseif (Auth::user()->part == "KBU" || Auth::user()->part == "KTT")
                    <a type="button" href="{{ route('admin.inputs.kbu.performances.index') }}" class="btn btn-primary">
                    @else
                    <a type="button" href="{{ route('admin.inputs.kbu.performances.index') }}" class="btn btn-primary">
                    @endif
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
                                    <th scope="col">Bagian</th>
                                    <th scope="col">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($reject_offs as $officer)
                                <tr>
                                    <th scope="row">{{ $loop->iteration }}</th>
                                    <td>{{ $officer->name }}</td>
                                    <td>{{ $officer->department->name }}</td>
                                    <td>{{ $officer->department->part->name }}</td>
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
                    @if (Auth::user()->part == "Admin")
                    <a type="button" href="{{ route('admin.inputs.presences.officers.index') }}" class="btn btn-primary">
                    @elseif (Auth::user()->part == "KBU" || Auth::user()->part == "KTT")
                    <a type="button" href="{{ route('admin.inputs.kbu.performances.index') }}" class="btn btn-primary">
                    @else
                    <a type="button" href="{{ route('admin.inputs.scores.index') }}" class="btn btn-primary">
                    @endif
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
<!--PREVIOUS EMPLOYEE OF THE MONTH-->
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
                                <td>{{ $voteresult->period->month }} {{ $voteresult->period->year }}</td>
                                <td>{{ $voteresult->officer->name }}</td>
                                <td>{{ $voteresult->officer->department->name }}</td>
                                @if (Auth::user()->part != "Pegawai")
                                <td>{{ $voteresult->final_score }}</td>
                                @endif
                            </tr>
                            @endforeach
                        @endforeach
                        </tbody>
                        <tfoot class="table-group-divider table-secondary">
                            <tr>
                                <td colspan="5">Total Data: <b>{{ $voteresults->count() }}</b> Data</td>
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
<div class="modal modal-lg fade" id="modal-score-{{ $latest_per->id_period ?? '' }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Daftar Calon Karyawan Terbaik ({{ $latest_per->month ?? '' }} {{ $latest_per->year ?? '' }})</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    @if (!empty($latest_per->id_period))
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
                            @foreach ($scoreresults->where('id_period', $latest_per->id_period) as $scoreresult)
                                <tr>
                                    <th scope="row">{{ $loop->iteration }}</th>
                                    <td>{{ $scoreresult->officer->name }}</td>
                                    <td>{{ $scoreresult->officer->department->name }}</td>
                                    @if (Auth::user()->part != "Pegawai")
                                    <td>{{ $scoreresult->final_score }}</td>
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="table-group-divider table-secondary">
                            <tr>
                                <td colspan="5">Total Data: <b>{{ $scoreresults->where('id_period', $latest_per->id_period)->count() }}</b> Data</td>
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
@endif
@if (Request::is('admin/inputs/votes/*') || Request::is('officer/votes/*'))
<!--VOTE CHECKER PER PART-->
<div class="modal modal-xl fade" id="modal-chk-view-{{ $prd_select->id_period }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Cek Pegawai ({{ $prd_select->name }})</h1>
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
                                <th colspan="{{ $criterias->count() }}" scope="col">Kriteria</th>
                                <th rowspan="2" scope="col">Status</th>
                            </tr>
                            <tr class="table-secondary">
                                @foreach ($criterias as $criteria)
                                <th scope="col">{{ $criteria->name }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($fil_offs as $officer)
                            <tr>
                                <th scope="row">{{ $loop->iteration }}</th>
                                <td>{{ $officer->name }}</td>
                                <td>{{ $officer->department->name }}</td>
                                @foreach ($criterias as $criteria)
                                <td>
                                    @if ($checks->where('id_officer', $officer->id_officer)->where('id_period', $prd_select->id_period)->where('id_vote_criteria', $criteria->id_vote_criteria)->count() == 0)
                                    <span class="badge text-bg-danger">Belum Memilih</span>
                                    @else
                                    <span class="badge text-bg-success">Sudah Memilih</span>
                                    @endif
                                </td>
                                @endforeach
                                <td>
                                    @if ($checks->where('id_officer', $officer->id_officer)->where('id_period', $prd_select->id_period)->count() == 0)
                                    <span class="badge text-bg-danger">Belum Memilih</span>
                                    @elseif ($checks->where('id_officer', $officer->id_officer)->where('id_period', $prd_select->id_period)->count() < $criterias->count())
                                    <span class="badge text-bg-warning">Sebagian Memilih</span>
                                    @else
                                    <span class="badge text-bg-success">Sudah Memilih</span>
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
<!--VOTE CHECKER (ALL)-->
<div class="modal modal-xl fade" id="modal-chk-all-{{ $prd_select->id_period }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Cek Pegawai ({{ $prd_select->name }})</h1>
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
                                <th colspan="{{ $criterias->count() }}" scope="col">Kriteria</th>
                                <th rowspan="2" scope="col">Status</th>
                            </tr>
                            <tr class="table-secondary">
                                @foreach ($criterias as $criteria)
                                <th scope="col">{{ $criteria->name }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($officers as $officer)
                            <tr>
                                <th scope="row">{{ $loop->iteration }}</th>
                                <td>{{ $officer->name }}</td>
                                <td>{{ $officer->department->name }}</td>
                                @foreach ($criterias as $criteria)
                                <td>
                                    @if ($checks->where('id_officer', $officer->id_officer)->where('id_period', $prd_select->id_period)->where('id_vote_criteria', $criteria->id_vote_criteria)->count() == 0)
                                    <span class="badge text-bg-danger">Belum Memilih</span>
                                    @else
                                    <span class="badge text-bg-success">Sudah Memilih</span>
                                    @endif
                                </td>
                                @endforeach
                                <td>
                                    @if ($checks->where('id_officer', $officer->id_officer)->where('id_period', $prd_select->id_period)->count() == 0)
                                    <span class="badge text-bg-danger">Belum Memilih</span>
                                    @elseif ($checks->where('id_officer', $officer->id_officer)->where('id_period', $prd_select->id_period)->count() < $criterias->count())
                                    <span class="badge text-bg-warning">Sebagian Memilih</span>
                                    @else
                                    <span class="badge text-bg-success">Sudah Memilih</span>
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

    @foreach ($criterias as $criteria)
        @foreach ($votes->where('id_period', $prd_select->id_period)->where('id_vote_criteria', $criteria->id_vote_criteria) as $vote)
        <!--SELECT VOTE-->
        <div class="modal fade" id="modal-vte-select-{{ $prd_select->id_period }}-{{ $vote->id_officer }}-{{ $criteria->id_vote_criteria }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    @if (Auth::check() && Auth::user()->part != 'Pegawai')
                    <form action="{{ route('admin.inputs.votes.select', ['period'=>$prd_select->id_period, 'officer'=>$vote->id_officer, 'criteria'=>$criteria->id_vote_criteria]) }}" method="POST" enctype="multipart/form-data">
                    @else
                    <form action="{{ route('officer.votes.select', ['period'=>$prd_select->id_period, 'officer'=>$vote->id_officer, 'criteria'=>$criteria->id_vote_criteria]) }}" method="POST" enctype="multipart/form-data">
                    @endif
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="exampleModalLabel">Pilih Pegawai ({{ $vote->id_officer }}) ({{ $criteria->id_vote_criteria }})</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            @csrf
                            <div class="mb-3">
                                <div class="col">
                                    <input type="text" class="form-control" id="id" name="id" value="{{ $vote->id_officer }}" hidden>
                                </div>
                            </div>
                            <div class="alert alert-info" role="alert">
                                <i class="bi bi-info-circle-fill"></i> <b>INFO</b>
                                <br/>
                                Pegawai yang dipilih: {{$vote->officer->name}}
                            </div>
                            <div class="alert alert-warning" role="alert">
                                <i class="bi bi-exclamation-triangle-fill"></i> <b>PERHATIAN</b>
                                <br/>
                                Apakah anda yakin untuk memilih pegawai tersebut? Harap diperhatikan bahwa setelah melakukan pemilihan, anda tidak dapat mengubah atau membatalkan pilihan anda.
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                <i class="bi bi-x-lg"></i>
                                Tidak
                            </button>
                            <button type="submit" class="btn btn-primary">
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
