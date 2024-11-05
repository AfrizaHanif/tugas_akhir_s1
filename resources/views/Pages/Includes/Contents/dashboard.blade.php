<h1 class="text-center mb-4">Selamat Datang, {{ Auth::user()->name }}</h1>
<!--SCORE ANT VOTE ALERT (OPT: REMOVE)-->
@if (Auth::user()->part != "Dev")
    @if (!empty($latest_per->progress_status))
        @if ($latest_per->progress_status == 'Scoring' || $latest_per->progress_status == 'Verifying')
        @elseif ($latest_per->progress_status == 'Voting')
            @if ($vote_check->where('id_period', $latest_per->id_period)->where('id_officer', Auth::user()->id_officer)->count() == 0)
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                Anda belum melakukan voting pemilihan karyawan terbaik. Silahkan buka halaman <strong>Voting</strong> untuk memilih karyawan.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @elseif ($vote_check->where('id_period', $latest_per->id_period)->where('id_officer', Auth::user()->id_officer)->count() == count($vote_criterias))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                Terima kasih anda telah melakukan voting pemilihan karyawan terbaik. Mohon menunggu pengumuman hasil pemilihan karyawan terbaik.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @else
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                Anda telah melakukan voting sebagian. Silahkan melanjutkan voting pemilihan karyawan terbaik di halaman <strong>Voting</strong>.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif
        @endif
    @endif
@endif

<!--ALERTS-->
@if (!empty($latest_per))
    <!--ADMIN ALERT-->
    @if (Auth::user()->part == "Admin")
        @if ($inputs->count() == 0)
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            Tidak ada nilai yang terdaftar di periode ini. Silahkan lakukan import data nilai di halaman <b>Data Input</b>.
        </div>
        @elseif ($latest_per->import_status == 'Few Clear')
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            Terdapat sebagian nilai yang tidak dapat dikonversi. Segera lakukan pemeriksaan setiap data crips di halaman <b>Kriteria</b>.
        </div>
        @elseif (count($count->where('id_period', $latest_per->id_period)->whereIn('status', ['Not Converted'])))
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            Data nilai yang telah diimport belum dilakukan konversi. Segera lakukan konversi data nilai di halaman <b>Data Input</b>.
        </div>
        @elseif (($inputs->count()) != ($officers->count() * $subcriterias->count()))
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            Terdapat beberapa pegawai yang belum memiliki nilai yang lengkap. Silahkan lakukan import data nilai yang kurang di halaman <b>Data Input</b>.
        </div>
        @elseif (count($scores->where('id_period', $latest_per->id_period)->where('status', 'Rejected')))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            Terdapat beberapa pegawai yang nilai akhirnya ditolak. Segera lakukan revisi dan import ulang di halaman <b>Data Input</b>.
        </div>
        @elseif (count($scores->where('id_period', $latest_per->id_period)->where('status', 'Revised')) >= 1 && count($scores->where('id_period', $latest_per->id_period)->where('status', 'Rejected')) >= 1)
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            Terdapat nilai yang telah direvisi dan belum direvisi. Segera lakukan revisi dan import ulang di halaman <b>Data Input</b>.
        </div>
        @elseif (count($count->where('id_period', $latest_per->id_period)->whereIn('status', ['Pending'])))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            Seluruh data nilai telah dilakukan import dan konversi. Segera menghubungi <b>Kepala BPS Jawa Timur</b> untuk melakukan <b>Verifikasi Nilai</b>.
        </div>
        @elseif (($scores->where('id_period', $latest_per->id_period)->whereIn('status', ['Revised'])->count() >= 1))
        <div class="alert alert-primary alert-dismissible fade show" role="alert">
            Seluruh data nilai telah dilakukan import dan konversi. Segera menghubungi <b>Kepala BPS Jawa Timur</b> untuk melakukan <b>Verifikasi Nilai Ulang</b>.
        </div>
        @endif
    @endif
    <!--KBPS ALERT-->
    @if (Auth::user()->part == "KBPS")
        @if ($latest_per->progress_status == 'Verifying')
            @if ($scores->where('id_period', $latest_per->id_period)->whereIn('status', ['Accepted'])->count() == count($officers))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                Seluruh nilai akhir telah disetujui semua. Segera lakukan penyelesaian proses penentuan karyawan terbaik di halaman <b>Verifikasi Input</b>.
            </div>
            @elseif (($scores->where('id_period', $latest_per->id_period)->whereIn('status', ['Revised'])->count() >= 1))
            <div class="alert alert-primary alert-dismissible fade show" role="alert">
                Seluruh nilai yang ditolak telah direvisi. Segera lakukan pengambilan data nilai akhir di halaman <b>Verifikasi Input</b>.
            </div>
            @elseif (($scores->where('id_period', $latest_per->id_period)->whereIn('status', ['Pending'])->count() >= 1))
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                Seluruh / sebagian nilai belum dilakukan pemeriksan nilai akhir. Segera lakukan verifikasi nilai di halaman <b>Verifikasi Input</b>.
            </div>
            @endif
        @else
            @if (($inputs->count()) != ($officers->count() * $subcriterias->count()))
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                Terdapat beberapa pegawai yang belum memiliki nilai yang lengkap. Silahkan hubungi <b>Kepegawaian</b> untuk memeriksa data nilai yang kurang.
            </div>
            @elseif (count($count->where('id_period', $latest_per->id_period)->whereIn('status', ['Pending'])) == count($input_off))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                Seluruh data nilai telah dilakukan import dan konversi. Silahkan melakukan pengambilan data nilai akhir di halaman <b>Verifikasi Nilai</b>.
            </div>
            @endif
        @endif
    @endif
    <!--OFFICER ALERT-->
    @if (Auth::user()->part == "Pegawai")
        @if (!empty($latest_per))
            @if ($latest_per->progress_status == 'Scoring' || $latest_per->progress_status == 'Verifying')
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                Selama proses penentuan karyawan terbaik berlangsung, data nilai dpat berubah sewaktu-waktu.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif
        @endif
    @endif
@else
    @if (Auth::user()->part == "Admin")

    @endif
@endif

<!--CARDS-->
@if (Auth::user()->part == "Admin")
<div class="row row-cols-1 row-cols-md-3 align-items-md-stretch g-4">
    <!--DATA INPUT COUNTER CARD-->
    <div class="col">
        @if (!empty($latest_per))
            @if ($inputs->count() == 0)
            <div class="card text-bg-danger h-100">
            @elseif (count($count->where('id_period', $latest_per->id_period)->whereIn('status', ['Not Converted'])) >= 1 || ($inputs->count()) != ($officers->count() * $subcriterias->count()))
            <div class="card text-bg-warning h-100">
            @elseif (count($count->where('id_period', $latest_per->id_period)->whereIn('status', ['Pending', 'In Review', 'Final', 'Need Fix', 'Fixed'])) == count($input_off))
            <div class="card border-success h-100">
            @elseif (count($count->where('id_period', $latest_per->id_period)->whereIn('status', ['Pending', 'In Review', 'Final', 'Need Fix', 'Fixed'])) >= 1)
            <div class="card border-primary h-100">
            @else
            <div class="card h-100">
            @endif
        @else
        <div class="card h-100">
        @endif
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-10">
                        <h4 class="card-title">Data Terinput</h4>
                    </div>
                    <div class="col-2 d-grid gap-2 d-md-flex justify-content-md-end">
                        @if (!empty($latest_per))
                            @if ($latest_per->import_status == 'Few Clear')
                            <h4><i class="bi bi-exclamation-triangle-fill"></i></h4>
                            @else
                            <h4>{{ count($count->where('id_period', $latest_per->id_period)->whereIn('status', ['Not Converted', 'Pending', 'In Review', 'Final', 'Need Fix', 'Fixed'])) }}/{{ count($input_off) ?? '-' }}</h4>
                            @endif
                        @else
                        <h4>-/{{ count($input_off) }}</h4>
                        @endif
                    </div>
                </div>
            </div>
            @if (!empty($latest_per))
            <div class="progress-stacked" style="border-radius: 0px; height: 5px">
                @if ($latest_per->import_status == 'Few Clear')
                <div class="progress" role="progressbar" aria-label="Basic example" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
                    <div class="progress-bar bg-warning progress-bar-striped progress-bar-animated" ></div>
                </div>
                @else
                <div class="progress" role="progressbar" aria-label="Basic example" aria-valuenow="{{ count($count->where('id_period', $latest_per->id_period)->whereIn('status', ['Not Converted'])) }}" aria-valuemin="0" aria-valuemax="{{ count($input_off) }}" style="width: {{ (count($count->where('id_period', $latest_per->id_period)->whereIn('status', ['Not Converted']))*100)/count($input_off) }}%">
                    @if (count($count->where('id_period', $latest_per->id_period)->whereIn('status', ['Not Converted'])) >= 1)
                    <div class="progress-bar bg-dark progress-bar-striped progress-bar-animated" ></div>
                    @elseif (count($count->where('id_period', $latest_per->id_period)->whereIn('status', ['Pending', 'In Review', 'Final', 'Need Fix', 'Fixed'])) >= 1 || ($inputs->count()) != ($officers->count() * $subcriterias->count()))
                    <div class="progress-bar bg-dark progress-bar-striped progress-bar-animated" ></div>
                    @else
                    <div class="progress-bar bg-warning progress-bar-striped progress-bar-animated"></div>
                    @endif
                </div>
                <div class="progress" role="progressbar" aria-label="Basic example" aria-valuenow="{{ count($count->where('id_period', $latest_per->id_period)->whereIn('status', ['Pending', 'In Review', 'Final', 'Need Fix', 'Fixed'])) }}" aria-valuemin="0" aria-valuemax="{{ count($input_off) }}" style="width: {{ (count($count->where('id_period', $latest_per->id_period)->whereIn('status', ['Pending', 'In Review', 'Final', 'Need Fix', 'Fixed']))*100)/count($input_off) }}%">
                    @if (($inputs->count()) != ($officers->count() * $subcriterias->count()))
                    <div class="progress-bar bg-dark progress-bar-striped progress-bar-animated"></div>
                    @elseif (count($count->where('id_period', $latest_per->id_period)->whereIn('status', ['Not Converted'])) >= 1)
                    <div class="progress-bar bg-warning bg-opacity-50 progress-bar-striped progress-bar-animated"></div>
                    @else
                    <div class="progress-bar bg-success progress-bar-striped progress-bar-animated"></div>
                    @endif
                </div>
                @endif
            </div>
            @else
            <div class="progress" role="progressbar" aria-label="Basic example" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="border-radius: 0px; height: 5px">
                <div class="progress-bar progress-bar-striped progress-bar-animated" style="width: 0%"></div>
            </div>
            @endif
            @if (!empty($latest_per))
                @if ($inputs->count() == 0)
                <div class="card-footer">
                @elseif (count($count->where('id_period', $latest_per->id_period)->whereIn('status', ['Not Converted'])) >= 1 || ($inputs->count()) != ($officers->count() * $subcriterias->count()))
                <div class="card-footer">
                @elseif (count($count->where('id_period', $latest_per->id_period)->whereIn('status', ['Pending', 'In Review', 'Final', 'Need Fix', 'Fixed'])) == count($input_off))
                <div class="card-footer text-body-secondary">
                @elseif (count($count->where('id_period', $latest_per->id_period)->whereIn('status', ['Pending', 'In Review', 'Final', 'Need Fix', 'Fixed'])) >= 1)
                <div class="card-footer text-body-secondary">
                @else
                <div class="card-footer text-body-secondary">
                @endif
            @else
            <div class="card-footer text-body-secondary">
            @endif
                <div class="row align-items-center">
                    <div class="col-9">
                        Periode: {{ $latest_per->month ?? 'Belum Aktif' }} {{ $latest_per->year ?? '' }}
                    </div>
                    <div class="col-3 d-grid gap-2 d-md-flex justify-content-md-end">
                        @if (!empty($latest_per))
                            @if ($inputs->count() == 0)
                            <button type="button" class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#modal-inp-view-{{ $latest_per->id_period }}">Cek</button>
                            @elseif (count($count->where('id_period', $latest_per->id_period)->whereIn('status', ['Not Converted'])) >= 1 || ($inputs->count()) != ($officers->count() * $subcriterias->count()))
                            <button type="button" class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#modal-inp-view-{{ $latest_per->id_period }}">Cek</button>
                            @elseif (count($count->where('id_period', $latest_per->id_period)->whereIn('status', ['Pending', 'In Review', 'Final', 'Need Fix', 'Fixed'])) == count($input_off))
                            <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#modal-inp-view-{{ $latest_per->id_period }}">Cek</button>
                            @elseif (count($count->where('id_period', $latest_per->id_period)->whereIn('status', ['Pending', 'In Review', 'Final', 'Need Fix', 'Fixed'])) >= 1)
                            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modal-inp-view-{{ $latest_per->id_period }}">Cek</button>
                            @else
                            <button type="button" class="btn btn-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#modal-inp-view-{{ $latest_per->id_period }}">Cek</button>
                            @endif
                        @else
                        <button type="button" class="btn btn-secondary btn-sm" disabled>Cek</button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--REJECTED INPUT COUNTER CARD-->
    <div class="col">
        @if (!empty($latest_per))
            @if ($latest_per->progress_status == 'Verifying')
                @if (count($scores->where('id_period', $latest_per->id_period)->where('status', 'Revised')) >= 1 && count($scores->where('id_period', $latest_per->id_period)->where('status', 'Rejected')) >= 1)
                <div class="card text-bg-danger h-100">
                @elseif (count($scores->where('id_period', $latest_per->id_period)->where('status', 'Revised')) >= 1)
                <div class="card border-primary h-100">
                @elseif (count($scores->where('id_period', $latest_per->id_period)->where('status', 'Rejected')) >= 1)
                <div class="card text-bg-danger h-100">
                @else
                <div class="card border-success h-100">
                @endif
            @else
            <div class="card border-secondary h-100">
            @endif
        @else
        <div class="card h-100">
        @endif
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-10">
                        <h4 class="card-title">Nilai Ditolak</h4>
                    </div>
                    <div class="col-2 d-grid gap-2 d-md-flex justify-content-md-end">
                        @if (!empty($latest_per))
                        <h4>{{ count($scores->where('id_period', $latest_per->id_period)->where('status', 'Rejected')) ?? '-' }}</h4>
                        @else
                        <h4>-</h4>
                        @endif
                    </div>
                </div>
            </div>
            @if (!empty($latest_per))
            <div class="progress-stacked" style="border-radius: 0px; height: 5px">
                <div class="progress" role="progressbar" aria-label="Basic example" aria-valuenow="{{ count($scores->where('id_period', $latest_per->id_period)->where('status', 'Revised')) }}" aria-valuemin="0" aria-valuemax="{{ count($input_off) }}" style="width: {{ (count($scores->where('id_period', $latest_per->id_period)->where('status', 'Revised'))*100)/count($input_off) }}%">
                    @if (count($scores->where('id_period', $latest_per->id_period)->where('status', 'Revised')) >= 1 && count($scores->where('id_period', $latest_per->id_period)->where('status', 'Rejected')) >= 1)
                    <div class="progress-bar bg-primary progress-bar-striped progress-bar-animated" style="--bs-bg-opacity: .5;"></div>
                    @else
                    <div class="progress-bar progress-bar-striped progress-bar-animated"></div>
                    @endif
                </div>
                <div class="progress" role="progressbar" aria-label="Basic example" aria-valuenow="{{ count($scores->where('id_period', $latest_per->id_period)->where('status', 'Rejected')) }}" aria-valuemin="0" aria-valuemax="{{ count($input_off) }}" style="width: {{ (count($scores->where('id_period', $latest_per->id_period)->where('status', 'Rejected'))*100)/count($input_off) }}%">
                    @if ($latest_per->progress_status == 'Verifying' && count($scores->where('id_period', $latest_per->id_period)->where('status', 'Rejected')) >= 1)
                    <div class="progress-bar bg-danger progress-bar-striped progress-bar-animated" style="--bs-bg-opacity: .5;"></div>
                    @else
                    <div class="progress-bar bg-danger progress-bar-striped progress-bar-animated"></div>
                    @endif
                </div>
            </div>
            @else
            <div class="progress" role="progressbar" aria-label="Basic example" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="border-radius: 0px; height: 5px">
                <div class="progress-bar progress-bar-striped progress-bar-animated" style="width: 0%"></div>
            </div>
            @endif
            @if (!empty($latest_per))
                @if ($latest_per->progress_status == 'Verifying')
                    @if (count($scores->where('id_period', $latest_per->id_period)->where('status', 'Revised')) >= 1 && count($scores->where('id_period', $latest_per->id_period)->where('status', 'Rejected')) >= 1)
                    <div class="card-footer">
                    @elseif (count($scores->where('id_period', $latest_per->id_period)->where('status', 'Revised')) >= 1)
                    <div class="card-footer text-body-secondary">
                    @elseif (count($scores->where('id_period', $latest_per->id_period)->where('status', 'Rejected')) >= 1)
                    <div class="card-footer">
                    @else
                    <div class="card-footer text-body-secondary">
                    @endif
                @else
                <div class="card-footer text-body-secondary">
                @endif
            @else
            <div class="card-footer text-body-secondary">
            @endif
                <div class="row align-items-center">
                    <div class="col-9">
                        Periode: {{ $latest_per->month ?? 'Belum Aktif' }} {{ $latest_per->year ?? '' }}
                    </div>
                    <div class="col-3 d-grid gap-2 d-md-flex justify-content-md-end">
                        @if (!empty($latest_per))
                            @if ($latest_per->progress_status == 'Verifying')
                                @if (count($scores->where('id_period', $latest_per->id_period)->where('status', 'Revised')) >= 1 && count($scores->where('id_period', $latest_per->id_period)->where('status', 'Rejected')) >= 1)
                                <button type="button" class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#modal-inp-reject-{{ $latest_per->id_period }}">Cek</button>
                                @elseif (count($scores->where('id_period', $latest_per->id_period)->where('status', 'Revised')) >= 1)
                                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modal-inp-reject-{{ $latest_per->id_period }}">Cek</button>
                                @elseif (count($scores->where('id_period', $latest_per->id_period)->where('status', 'Rejected')) >= 1)
                                <button type="button" class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#modal-inp-reject-{{ $latest_per->id_period }}">Cek</button>
                                @else
                                <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#modal-inp-reject-{{ $latest_per->id_period }}">Cek</button>
                                @endif
                            @else
                            <button type="button" class="btn btn-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#modal-inp-reject-{{ $latest_per->id_period }}">Cek</button>
                            @endif
                        @else
                        <button type="button" class="btn btn-secondary btn-sm" disabled>Cek</button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--PERIOD STATUS CARD-->
    <div class="col">
        @if (!empty($latest_per))
            @if ($latest_per->progress_status == 'Scoring')
            <div class="card border-primary h-100">
            @elseif ($latest_per->progress_status == 'Verifying')
            <div class="card border-warning h-100">
            @else
            <div class="card h-100">
            @endif
        @else
        <div class="card text-bg-danger h-100">
        @endif
            <div class="card-body">
                <div class="row align-items-center">
                    @if (!empty($latest_per))
                        @if ($latest_per->progress_status == 'Scoring')
                        <h4 class="card-title">Dalam Penilaian</h4>
                        @elseif ($latest_per->progress_status == 'Verifying')
                        <h4 class="card-title">Dalam Verifikasi</h4>
                        @endif
                    @else
                    <h4 class="card-title">Periode Belum Aktif</h4>
                    @endif
                </div>
            </div>
            @if (!empty($latest_per))
                @if ($latest_per->progress_status == 'Scoring')
                <div class="card-footer text-body-secondary">
                @elseif ($latest_per->progress_status == 'Verifying')
                <div class="card-footer text-body-secondary">
                @else
                <div class="card-footer text-body-secondary">
                @endif
            @else
            <div class="card-footer">
            @endif
                <div class="row align-items-center">
                    <div class="col-9">
                        Periode: {{ $latest_per->month ?? 'Belum Aktif' }} {{ $latest_per->year ?? '' }}
                    </div>
                    <div class="col-3 d-grid gap-2 d-md-flex justify-content-md-end">
                        @if (!empty($latest_per))
                            @if ($latest_per->progress_status == 'Scoring')
                            <a type="button" href="/admin/masters/periods" class="btn btn-primary btn-sm">Cek</a>
                            @elseif ($latest_per->progress_status == 'Verifying')
                            <a type="button" href="/admin/masters/periods" class="btn btn-warning btn-sm">Cek</a>
                            @else
                            <a type="button" href="/admin/masters/periods" class="btn btn-secondary btn-sm">Cek</a>
                            @endif
                        @else
                        <a type="button" href="/admin/masters/periods" class="btn btn-light btn-sm">Cek</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<br/>
@endif
@if (Auth::user()->part == "KBPS")
<div class="row row-cols-1 row-cols-md-3 align-items-md-stretch g-4">
    <!--PROGRESS PENDING CARD-->
    <div class="col">
        @if (!empty($latest_per))
            @if ($latest_per->progress_status == 'Verifying')
                @if (count($count->where('id_period', $latest_per->id_period)->whereIn('status', ['Fixed'])) >= 1)
                <div class="card text-bg-success h-100">
                @elseif (count($count->where('id_period', $latest_per->id_period)->whereIn('status', ['Not Converted'])) >= 1 || ($inputs->count()) != ($officers->count() * $subcriterias->count()))
                <div class="card border-warning h-100">
                @else
                <div class="card border-success h-100">
                @endif
            @elseif ($latest_per->progress_status == 'Scoring')
                @if ($inputs->count() == 0)
                <div class="card border-danger h-100">
                @elseif (count($count->where('id_period', $latest_per->id_period)->whereIn('status', ['Not Converted'])) >= 1 || ($inputs->count()) != ($officers->count() * $subcriterias->count()))
                <div class="card border-warning h-100">
                @elseif (count($count->where('id_period', $latest_per->id_period)->whereIn('status', ['Pending'])) == count($input_off))
                <div class="card text-bg-success h-100">
                @else
                <div class="card border-primary h-100">
                @endif
            @else
            <div class="card border-success h-100">
            @endif
        @else
        <div class="card h-100">
        @endif
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-10">
                        <h4 class="card-title">Pending Proses Penilaian</h4>
                    </div>
                    <div class="col-2 d-grid gap-2 d-md-flex justify-content-md-end">
                        @if (!empty($latest_per))
                        <h4>{{ count($count->where('id_period', $latest_per->id_period)->whereIn('status', ['Pending', 'Fixed', 'Not Converted'])) ?? '-' }}</h4>
                        @else
                        <h4>-</h4>
                        @endif
                    </div>
                </div>
            </div>
            @if (!empty($latest_per))
            <div class="progress-stacked" style="border-radius: 0px; height: 5px">
                <div class="progress" role="progressbar" aria-label="Basic example" aria-valuenow="{{ count($count->where('id_period', $latest_per->id_period)->whereIn('status', ['Pending', 'Fixed', 'Not Converted'])) }}" aria-valuemin="0" aria-valuemax="{{ count($input_off) }}" style="width: {{ (count($count->where('id_period', $latest_per->id_period)->whereIn('status', ['Pending', 'Fixed', 'Not Converted']))*100)/count($input_off) }}%">
                    @if ($latest_per->progress_status == 'Verifying')
                        @if (count($count->where('id_period', $latest_per->id_period)->whereIn('status', ['Fixed'])) >= 1)
                        <div class="progress-bar bg-success bg-opacity-50 progress-bar-striped progress-bar-animated"></div>
                        @elseif (count($count->where('id_period', $latest_per->id_period)->whereIn('status', ['Not Converted'])) >= 1 || ($inputs->count()) != ($officers->count() * $subcriterias->count()))
                        <div class="progress-bar bg-warning progress-bar-striped progress-bar-animated"></div>
                        @else
                        <div class="progress-bar bg-success progress-bar-striped progress-bar-animated"></div>
                        @endif
                    @elseif ($latest_per->progress_status == 'Scoring')
                        @if ($inputs->count() == 0)
                        <div class="progress-bar bg-danger progress-bar-striped progress-bar-animated"></div>
                        @elseif (count($count->where('id_period', $latest_per->id_period)->whereIn('status', ['Not Converted'])) >= 1 || ($inputs->count()) != ($officers->count() * $subcriterias->count()))
                        <div class="progress-bar bg-warning progress-bar-striped progress-bar-animated"></div>
                        @elseif (count($count->where('id_period', $latest_per->id_period)->whereIn('status', ['Pending'])) == count($input_off))
                        <div class="progress-bar bg-success bg-opacity-50 progress-bar-striped progress-bar-animated"></div>
                        @else
                        <div class="progress-bar bg-primary progress-bar-striped progress-bar-animated"></div>
                        @endif
                    @else
                    <div class="progress-bar bg-primary progress-bar-striped progress-bar-animated"></div>
                    @endif
                </div>
            </div>
            @else
            <div class="progress" role="progressbar" aria-label="Basic example" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="border-radius: 0px; height: 5px">
                <div class="progress-bar progress-bar-striped progress-bar-animated" style="width: 0%"></div>
            </div>
            @endif
            @if (!empty($latest_per))
                @if ($latest_per->progress_status == 'Verifying')
                    @if (count($count->where('id_period', $latest_per->id_period)->whereIn('status', ['Fixed'])) >= 1)
                    <div class="card-footer">
                    @elseif (count($count->where('id_period', $latest_per->id_period)->whereIn('status', ['Not Converted'])) >= 1 || ($inputs->count()) != ($officers->count() * $subcriterias->count()))
                    <div class="card-footer text-body-secondary">
                    @else
                    <div class="card-footer text-body-secondary">
                    @endif
                @elseif ($latest_per->progress_status == 'Scoring')
                    @if ($inputs->count() == 0)
                    <div class="card-footer text-body-secondary">
                    @elseif (count($count->where('id_period', $latest_per->id_period)->whereIn('status', ['Not Converted'])) >= 1 || ($inputs->count()) != ($officers->count() * $subcriterias->count()))
                    <div class="card-footer text-body-secondary">
                    @elseif (count($count->where('id_period', $latest_per->id_period)->whereIn('status', ['Pending'])) == count($input_off))
                    <div class="card-footer">
                    @else
                    <div class="card-footer text-body-secondary">
                    @endif
                @else
                <div class="card-footer text-body-secondary">
                @endif
            @else
            <div class="card-footer text-body-secondary">
            @endif
                <div class="row align-items-center">
                    <div class="col-9">
                        Periode: {{ $latest_per->month ?? 'Belum Aktif' }} {{ $latest_per->year ?? '' }}
                    </div>
                    <div class="col-3 d-grid gap-2 d-md-flex justify-content-md-end">
                        @if (!empty($latest_per))
                            @if ($latest_per->progress_status == 'Verifying')
                                @if (count($count->where('id_period', $latest_per->id_period)->whereIn('status', ['Fixed'])) >= 1)
                                <button type="button" class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#modal-prg-view-{{ $latest_per->id_period }}">Cek</button>
                                @elseif (count($count->where('id_period', $latest_per->id_period)->whereIn('status', ['Not Converted'])) >= 1 || ($inputs->count()) != ($officers->count() * $subcriterias->count()))
                                <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#modal-prg-view-{{ $latest_per->id_period }}">Cek</button>
                                @else
                                <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#modal-prg-view-{{ $latest_per->id_period }}">Cek</button>
                                @endif
                            @elseif ($latest_per->progress_status == 'Scoring')
                                @if ($inputs->count() == 0)
                                <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#modal-prg-view-{{ $latest_per->id_period }}">Cek</button>
                                @elseif (count($count->where('id_period', $latest_per->id_period)->whereIn('status', ['Not Converted'])) >= 1 || ($inputs->count()) != ($officers->count() * $subcriterias->count()))
                                <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#modal-prg-view-{{ $latest_per->id_period }}">Cek</button>
                                @elseif (count($count->where('id_period', $latest_per->id_period)->whereIn('status', ['Pending'])) == count($input_off))
                                <button type="button" class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#modal-prg-view-{{ $latest_per->id_period }}">Cek</button>
                                @else
                                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modal-prg-view-{{ $latest_per->id_period }}">Cek</button>
                                @endif
                            @else
                            <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#modal-prg-view-{{ $latest_per->id_period }}">Cek</button>
                            @endif
                        @else
                        <button type="button" class="btn btn-secondary btn-sm" disabled>Cek</button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--SCORE CONFIRM PENDING CARD-->
    <div class="col">
        @if (!empty($latest_per))
            @if ($latest_per->progress_status == 'Verifying')
                @if (count($count->where('id_period', $latest_per->id_period)->whereIn('status', ['In Review'])) >= 1 && count($count->where('id_period', $latest_per->id_period)->whereIn('status', ['Fixed'])) >= 1)
                <div class="card border-warning h-100">
                @elseif (count($count->where('id_period', $latest_per->id_period)->whereIn('status', ['In Review'])) >= 1)
                <div class="card text-bg-warning h-100">
                @elseif ($scores->where('id_period', $latest_per->id_period)->whereIn('status', ['Accepted'])->count() == count($officers))
                <div class="card border-success h-100">
                @else
                <div class="card border-secondary h-100">
                @endif
            @elseif ($latest_per->progress_status == 'Scoring')
            <div class="card border-secondary h-100">
            @else
            <div class="card border-success h-100">
            @endif
        @else
        <div class="card h-100">
        @endif
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-10">
                        <h4 class="card-title">Pending Persetujuan Penilaian</h4>
                    </div>
                    <div class="col-2 d-grid gap-2 d-md-flex justify-content-md-end">
                        @if (!empty($latest_per))
                        <h4>{{ count($count->where('id_period', $latest_per->id_period)->whereIn('status', ['Pending', 'In Review', 'Fixed'])) ?? '-' }}</h4>
                        @else
                        <h4>-</h4>
                        @endif
                    </div>
                </div>
            </div>
            @if (!empty($latest_per))
            <div class="progress-stacked" style="border-radius: 0px; height: 5px">
                <div class="progress" role="progressbar" aria-label="Basic example" aria-valuenow="{{ count($count->where('id_period', $latest_per->id_period)->whereIn('status', ['Pending', 'In Review', 'Fixed'])) }}" aria-valuemin="0" aria-valuemax="{{ count($input_off) }}" style="width: {{ (count($count->where('id_period', $latest_per->id_period)->whereIn('status', ['Pending', 'In Review', 'Fixed']))*100)/count($input_off) }}%">
                    @if ($latest_per->progress_status == 'Verifying')
                        @if (count($count->where('id_period', $latest_per->id_period)->whereIn('status', ['In Review'])) >= 1 && count($count->where('id_period', $latest_per->id_period)->whereIn('status', ['Fixed'])) >= 1)
                        <div class="progress-bar bg-warning progress-bar-striped progress-bar-animated"></div>
                        @elseif (count($count->where('id_period', $latest_per->id_period)->whereIn('status', ['In Review'])) >= 1)
                        <div class="progress-bar bg-dark progress-bar-striped progress-bar-animated"></div>
                        @else
                        <div class="progress-bar bg-warning progress-bar-striped progress-bar-animated"></div>
                        @endif
                    @elseif ($latest_per->progress_status == 'Scoring')
                    <div class="progress-bar bg-warning progress-bar-striped progress-bar-animated"></div>
                    @else
                    <div class="progress-bar bg-warning progress-bar-striped progress-bar-animated"></div>
                    @endif
                </div>
            </div>
            @else
            <div class="progress" role="progressbar" aria-label="Basic example" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="border-radius: 0px; height: 5px">
                <div class="progress-bar progress-bar-striped progress-bar-animated" style="width: 0%"></div>
            </div>
            @endif
            @if (!empty($latest_per))
                @if ($latest_per->progress_status == 'Verifying')
                    @if (count($count->where('id_period', $latest_per->id_period)->whereIn('status', ['In Review'])) >= 1 && count($count->where('id_period', $latest_per->id_period)->whereIn('status', ['Fixed'])) >= 1)
                    <div class="card-footer text-body-secondary">
                    @elseif (count($count->where('id_period', $latest_per->id_period)->whereIn('status', ['In Review'])) >= 1)
                    <div class="card-footer">
                    @elseif ($scores->where('id_period', $latest_per->id_period)->whereIn('status', ['Accepted'])->count() == count($officers))
                    <div class="card-footer text-body-secondary">
                    @else
                    <div class="card-footer text-body-secondary">
                    @endif
                @elseif ($latest_per->progress_status == 'Scoring')
                <div class="card-footer text-body-secondary">
                @else
                <div class="card-footer text-body-secondary">
                @endif
            @else
            <div class="card-footer text-body-secondary">
            @endif
                <div class="row align-items-center">
                    <div class="col-9">
                        Periode: {{ $latest_per->month ?? 'Belum Aktif' }} {{ $latest_per->year ?? '' }}
                    </div>
                    <div class="col-3 d-grid gap-2 d-md-flex justify-content-md-end">
                        @if (!empty($latest_per))
                            @if ($latest_per->progress_status == 'Verifying')
                                @if (count($count->where('id_period', $latest_per->id_period)->whereIn('status', ['In Review'])) >= 1 && count($count->where('id_period', $latest_per->id_period)->whereIn('status', ['Fixed'])) >= 1)
                                <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#modal-scr-view-{{ $latest_per->id_period }}">Cek</button>
                                @elseif (count($count->where('id_period', $latest_per->id_period)->whereIn('status', ['In Review'])) >= 1)
                                <button type="button" class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#modal-scr-view-{{ $latest_per->id_period }}">Cek</button>
                                @elseif ($scores->where('id_period', $latest_per->id_period)->whereIn('status', ['Accepted'])->count() == count($officers))
                                <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#modal-scr-view-{{ $latest_per->id_period }}">Cek</button>
                                @else
                                <button type="button" class="btn btn-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#modal-scr-view-{{ $latest_per->id_period }}">Cek</button>
                                @endif
                            @elseif ($latest_per->progress_status == 'Scoring')
                            <button type="button" class="btn btn-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#modal-scr-view-{{ $latest_per->id_period }}">Cek</button>
                            @else
                            <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#modal-scr-view-{{ $latest_per->id_period }}">Cek</button>
                            @endif
                        @else
                        <button type="button" class="btn btn-secondary btn-sm" disabled>Cek</button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--REJECTED INPUT COUNTER CARD (KBPS)-->
    <div class="col">
        @if (!empty($latest_per))
            @if ($latest_per->progress_status == 'Verifying')
                @if (count($scores->where('id_period', $latest_per->id_period)->where('status', 'Revised')) >= 1 && count($scores->where('id_period', $latest_per->id_period)->where('status', 'Rejected')) >= 1)
                <div class="card border-danger h-100">
                @elseif (count($scores->where('id_period', $latest_per->id_period)->where('status', 'Revised')) >= 1)
                <div class="card text-bg-primary h-100">
                @elseif (count($scores->where('id_period', $latest_per->id_period)->where('status', 'Rejected')) >= 1)
                <div class="card border-danger h-100">
                @else
                <div class="card border-success h-100">
                @endif
            @else
            <div class="card border-secondary h-100">
            @endif
        @else
        <div class="card h-100">
        @endif
            <div class="card-body">
                <div class="row align-items-center h-100">
                    <div class="col-10">
                        <h4 class="card-title">Nilai Ditolak</h4>
                    </div>
                    <div class="col-2 d-grid gap-2 d-md-flex justify-content-md-end">
                        @if (!empty($latest_per))
                        <h4>{{ count($scores->where('id_period', $latest_per->id_period)->where('status', 'Rejected')) ?? '-' }}</h4>
                        @else
                        <h4>-</h4>
                        @endif
                    </div>
                </div>
            </div>
            @if (!empty($latest_per))
            <div class="progress-stacked" style="border-radius: 0px; height: 5px">
                <div class="progress" role="progressbar" aria-label="Basic example" aria-valuenow="{{ count($scores->where('id_period', $latest_per->id_period)->where('status', 'Revised')) }}" aria-valuemin="0" aria-valuemax="{{ count($input_off) }}" style="width: {{ (count($scores->where('id_period', $latest_per->id_period)->where('status', 'Revised'))*100)/count($input_off) }}%">
                    @if (count($scores->where('id_period', $latest_per->id_period)->where('status', 'Revised')) >= 1 && count($scores->where('id_period', $latest_per->id_period)->where('status', 'Rejected')) >= 1)
                    <div class="progress-bar bg-primary progress-bar-striped progress-bar-animated"></div>
                    @elseif (count($scores->where('id_period', $latest_per->id_period)->where('status', 'Revised')) >= 1)
                    <div class="progress-bar bg-primary bg-opacity-50 progress-bar-striped progress-bar-animated"></div>
                    @endif
                </div>
                <div class="progress" role="progressbar" aria-label="Basic example" aria-valuenow="{{ count($scores->where('id_period', $latest_per->id_period)->where('status', 'Rejected')) }}" aria-valuemin="0" aria-valuemax="{{ count($input_off) }}" style="width: {{ (count($scores->where('id_period', $latest_per->id_period)->where('status', 'Rejected'))*100)/count($input_off) }}%">
                    <div class="progress-bar bg-danger progress-bar-striped progress-bar-animated"></div>
                </div>
            </div>
            @else
            <div class="progress" role="progressbar" aria-label="Basic example" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="border-radius: 0px; height: 5px">
                <div class="progress-bar progress-bar-striped progress-bar-animated" style="width: 0%"></div>
            </div>
            @endif
            @if (!empty($latest_per))
                @if ($latest_per->progress_status == 'Verifying')
                    @if (count($scores->where('id_period', $latest_per->id_period)->where('status', 'Revised')) >= 1 && count($scores->where('id_period', $latest_per->id_period)->where('status', 'Rejected')) >= 1)
                    <div class="card-footer text-body-secondary">
                    @elseif (count($scores->where('id_period', $latest_per->id_period)->where('status', 'Revised')) >= 1)
                    <div class="card-footer">
                    @elseif (count($scores->where('id_period', $latest_per->id_period)->where('status', 'Rejected')) >= 1)
                    <div class="card-footer text-body-secondary">
                    @else
                    <div class="card-footer text-body-secondary">
                    @endif
                @else
                <div class="card-footer text-body-secondary">
                @endif
            @else
            <div class="card-footer text-body-secondary">
            @endif
                <div class="row align-items-center">
                    <div class="col-9">
                        Periode: {{ $latest_per->month ?? 'Belum Aktif' }} {{ $latest_per->year ?? '' }}
                    </div>
                    <div class="col-3 d-grid gap-2 d-md-flex justify-content-md-end">
                        @if (!empty($latest_per))
                            @if ($latest_per->progress_status == 'Verifying')
                                @if (count($scores->where('id_period', $latest_per->id_period)->where('status', 'Revised')) >= 1 && count($scores->where('id_period', $latest_per->id_period)->where('status', 'Rejected')) >= 1)
                                <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#modal-inp-reject-{{ $latest_per->id_period }}">Cek</button>
                                @elseif (count($scores->where('id_period', $latest_per->id_period)->where('status', 'Revised')) >= 1)
                                <button type="button" class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#modal-inp-reject-{{ $latest_per->id_period }}">Cek</button>
                                @elseif (count($scores->where('id_period', $latest_per->id_period)->where('status', 'Rejected')) >= 1)
                                <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#modal-inp-reject-{{ $latest_per->id_period }}">Cek</button>
                                @else
                                <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#modal-inp-reject-{{ $latest_per->id_period }}">Cek</button>
                                @endif
                            @else
                            <button type="button" class="btn btn-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#modal-inp-reject-{{ $latest_per->id_period }}">Cek</button>
                            @endif
                        @else
                        <a type="button" class="btn btn-secondary btn-sm disabled">Cek</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<br/>
@endif
@if (Auth::user()->part == "Admin" || Auth::user()->part == "KBPS")
<div class="row align-items-md-stretch g-4">
    <div class="col-3">
    </div>
    <!--RESULT CARD-->
    <div class="col-6">
        <div id="carousel-results" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-indicators" style="bottom: -20px; filter: invert(100%)">
                <button type="button" data-bs-target="#carousel-results" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                <button type="button" data-bs-target="#carousel-results" data-bs-slide-to="1" aria-label="Slide 2"></button>
            </div>
            <div class="carousel-inner">
                <!--CHOOSEN CARD-->
                <div class="carousel-item active">
                    <div class="card">
                        <div class="row g-0">
                            <div class="col-auto">
                                @if (!empty($latest_best))
                                <img src="{{ url('Images/History/Portrait/'.$latest_best->officer_photo) }}" onerror="this.onerror=null; this.src='{{ asset('Images/Default/Portrait.png') }}'" class="img-fluid" style="height:140px;border-top-left-radius:7px;" alt="...">
                                @else
                                <img src="{{ url('Images/History/Portrait/'.$latest_best) }}" onerror="this.onerror=null; this.src='{{ asset('Images/Default/Portrait.png') }}'" class="img-fluid" style="height:140px;border-top-left-radius:7px;" alt="...">
                                @endif
                            </div>
                            <div class="col-auto">
                                <div class="card-body">
                                    <h4 class="card-title">Karyawan Terbaik Saat Ini</h4>
                                    <h5 class="card-text">{{ $latest_best->officer_name ?? 'Belum Ada' }}</h5>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-body-secondary">
                            <div class="row align-items-center">
                                <div class="col-9 px-4">
                                    Periode: {{ $latest_best->period_name ?? 'Belum Tersedia' }}
                                </div>
                                <div class="col-3 px-4 d-grid gap-2 d-md-flex justify-content-md-end">
                                    @if (count($voteresults) != 0)
                                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modal-best">Riwayat</button>
                                    @else
                                    <button type="button" class="btn btn-secondary btn-sm" disabled>Riwayat</button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--TOP 3 SCORES CARD-->
                <div class="carousel-item">
                    <div class="card">
                        <div class="row g-0">
                            <div class="col-auto">
                                <div class="card-body px-4" style="height:140px">
                                    <h4 class="card-title">Tiga Nilai Akhir Terbaik Saat Ini</h4>
                                    <ol class="card-text">
                                        @if (!empty($history_prd))
                                            @forelse ($latest_top3->where('id_period', $history_prd->id_period)->take(3) as $latest)
                                            <li>{{ $latest->officer_name}}</li>
                                            @empty
                                            <p>Belum Ada</p>
                                            @endforelse
                                        @else
                                        <p>Belum Ada</p>
                                        @endif
                                    </ol>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-body-secondary">
                            <div class="row align-items-center">
                                <div class="col-9 px-4">
                                    Periode: {{ $history_prd->period_name ?? 'Belum Aktif' }}
                                </div>
                                <div class="col-3 px-4 d-grid gap-2 d-md-flex justify-content-md-end">
                                    @if (!empty($history_prd))
                                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modal-score-{{ $history_prd->id_period }}">Details</button>
                                    @else
                                    <button type="button" class="btn btn-secondary btn-sm" disabled>Details</button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <button class="carousel-control-hover carousel-control-prev" type="button" data-bs-target="#carousel-results" data-bs-slide="prev" style="filter: invert(100%); width: 5%; height:185px">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-hover carousel-control-next" type="button" data-bs-target="#carousel-results" data-bs-slide="next" style="filter: invert(100%); width: 5%; height:185px">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </div>
    <div class="col-3">
    </div>
</div>
@endif
@if (Auth::user()->part == "Dev")
<div class="row row-cols-1 row-cols-md-3 align-items-md-stretch g-4">
    <!--OFFICERS COUNTER CARD-->
    <div class="col">
        <div class="card h-100">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-10">
                        <h4 class="card-title">Jumlah Pegawai</h4>
                    </div>
                    <div class="col-2 d-grid gap-2 d-md-flex justify-content-md-end">
                        <h4>{{ count($officers) }}</h4>
                    </div>
                </div>
            </div>
            <div class="card-footer text-body-secondary">
                <div class="row align-items-center">
                    <div class="col-9">

                    </div>
                    <div class="col-3 d-grid gap-2 d-md-flex justify-content-md-end">
                        <button type="button" class="btn btn-secondary btn-sm" disabled>Cek</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--USERS COUNTER CARD-->
    <div class="col">
        <div class="card h-100">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-10">
                        <h4 class="card-title">Jumlah Pengguna</h4>
                    </div>
                    <div class="col-2 d-grid gap-2 d-md-flex justify-content-md-end">
                        <h4>{{ count($users) }}</h4>
                    </div>
                </div>
            </div>
            <div class="card-footer text-body-secondary">
                <div class="row align-items-center">
                    <div class="col-9">

                    </div>
                    <div class="col-3 d-grid gap-2 d-md-flex justify-content-md-end">
                        <button type="button" class="btn btn-secondary btn-sm" disabled>Cek</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--MESSGES COUNTER CARD-->
    <div class="col">
        <div class="card h-100">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-10">
                        <h4 class="card-title">Jumlah Pesan</h4>
                    </div>
                    <div class="col-2 d-grid gap-2 d-md-flex justify-content-md-end">
                        <h4>{{ count($messages) }}</h4>
                    </div>
                </div>
            </div>
            <div class="card-footer text-body-secondary">
                <div class="row align-items-center">
                    <div class="col-9">

                    </div>
                    <div class="col-3 d-grid gap-2 d-md-flex justify-content-md-end">
                        <a type="button" class="btn btn-secondary btn-sm disabled">Cek</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

<!--OFFICER-->
@if (Auth::user()->part == "Pegawai")
<div>
    <form action="" method="GET">
        <div class="input-group input-group-sm mb-3">
            <select class="form-select" id="year" name="year" aria-label="Small select example">
                <option selected disabled>Pilih Tahun Periode Nilai Akhir</option>
                @foreach ($hscore_year as $year)
                <option value="{{ $year->period_year }}" {{ request('year') ==  $year->period_year ? 'selected' : '' }}>{{ $year->period_year }}</option>
                @endforeach
            </select>
            <button id="editsaveBtn" class="btn btn-primary" type="submit">Pilih</button>
        </div>
    </form>
    <div style="width: 90%; margin: auto;">
        <canvas id="myChart" height="100px"></canvas>
    </div>
</div>
<br/>
<div class="row row-cols-1 row-cols-md-2 g-4">
    <!--SCORES-->
    <div class="col">
        <div class="card h-100">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col-9">
                        <ul class="nav nav-tabs card-header-tabs" id="myTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                @if (!empty($latest_per))
                                <button class="nav-link active" id="latest-tab" data-bs-toggle="tab" data-bs-target="#latest-tab-pane" type="button" role="tab" aria-controls="latest-tab-pane" aria-selected="true">Sekarang</button>
                                @endif
                            </li>
                            <li class="nav-item" role="presentation">
                                @if (!empty($latest_per))
                                <button class="nav-link" id="previous-tab" data-bs-toggle="tab" data-bs-target="#previous-tab-pane" type="button" role="tab" aria-controls="previous-tab-pane" aria-selected="false">Sebelumnya</button>
                                @else
                                <button class="nav-link active" id="previous-tab" data-bs-toggle="tab" data-bs-target="#previous-tab-pane" type="button" role="tab" aria-controls="previous-tab-pane" aria-selected="true">Sebelumnya</button>
                                @endif
                            </li>
                        </ul>
                    </div>
                    <div class="col-3 d-grid gap-2 d-md-flex justify-content-md-end">
                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modal-dsh-history">Riwayat</button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="tab-content" id="myTabContent">
                    @if (!empty($latest_per))
                    <div class="tab-pane fade show active" id="latest-tab-pane" role="tabpanel" aria-labelledby="latest-tab" tabindex="0">
                    @else
                    <div class="tab-pane fade" id="latest-tab-pane" role="tabpanel" aria-labelledby="latest-tab" tabindex="0">
                    @endif
                        <h5 class="card-title">{{ $latest_per->name ?? '' }}</h5>
                        <p class="card-text">
                            @if (!empty($input))
                            <table class="table">
                                @foreach ($criterias->where('id_period', $latest_per->id_period) as $criteria)
                                    @forelse ($inputs->where('id_criteria', $criteria->id_criteria)->where('id_officer', Auth::user()->nip)->where('id_period', $latest_per->id_period) as $input)
                                    <tr>
                                        <th scope="row">{{ $criteria->name }}</th>
                                        <td>{{ $input->input_raw }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <th scope="row">{{ $criteria->name }}</th>
                                        <td>0</td>
                                    </tr>
                                    @endforelse
                                @endforeach
                            </table>
                            @else
                            <div class="alert alert-warning" role="alert">
                                <i class="bi bi-exclamation-triangle-fill"></i> <strong>PERHATIAN</strong></br>
                                Tidak ada data nilai yang terdaftar pada periode ini.
                            </div>
                            @endif
                        </p>
                    </div>
                    @if (!empty($latest_per))
                    <div class="tab-pane fade" id="previous-tab-pane" role="tabpanel" aria-labelledby="previous-tab" tabindex="0">
                    @else
                    <div class="tab-pane fade show active" id="previous-tab-pane" role="tabpanel" aria-labelledby="previous-tab" tabindex="0">
                    @endif
                        <h5 class="card-title">{{ $hper_latest->period_name ?? '' }}</h5>
                        <p class="card-text">
                            @if (!empty($hper_latest))
                            <table class="table">
                                @foreach ($hcriterias->where('id_period', $hper_latest->id_period) as $criteria)
                                    @foreach ($histories->where('id_criteria', $criteria->id_criteria)->where('id_officer', Auth::user()->nip)->where('id_period', $hper_latest->id_period) as $input)
                                    <tr>
                                        <th scope="row">{{ $criteria->criteria_name }}</th>
                                        <td>{{ $input->input_raw }} {{ $criteria->unit }}
                                        </td>
                                    </tr>
                                    @endforeach
                                @endforeach
                            </table>
                            @else
                            <div class="alert alert-warning" role="alert">
                                <i class="bi bi-exclamation-triangle-fill"></i> <strong>PERHATIAN</strong></br>
                                Tidak ada data nilai dari periode yang telah dijalankan.
                            </div>
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--RESULTS-->
    <div class="col">
        <div class="card h-100">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col-9">

                    </div>
                    <div class="col-3 d-grid gap-2 d-md-flex justify-content-md-end">
                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modal-dsh-result">Riwayat</button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                @if (!empty($hper_year))
                <h5 class="card-title">Riwayat Nilai Akhir ({{ $hper_year->period_year }})</h5>
                <p class="card-text">
                    <table class="table">
                        <thead>
                            <tr>
                                <th class="col-1" scope="col">#</th>
                                <th>Periode</th>
                                <th>Nilai Akhir</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($hscores->where('id_officer', Auth::user()->nip)->where('period_year', $hper_year->period_year) as $score)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $score->period_name }}</td>
                                <td>{{ $score->final_score }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </p>
                @else
                <div class="alert alert-warning" role="alert">
                    <i class="bi bi-exclamation-triangle-fill"></i> <strong>PERHATIAN</strong></br>
                    Tidak ada nilai akhir yang anda dapatkan.
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endif
<br/>
