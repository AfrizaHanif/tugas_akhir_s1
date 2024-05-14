@if (Request::is('admin/inputs/votes') || Request::is('officer/votes'))
<h1 class="text-center mb-4">Pemilihan Karyawan Terbaik</h1>
@elseif (Request::is('admin/inputs/votes/*') || Request::is('officer/votes/*'))
<h1 class="text-center mb-4">Pemilihan Karyawan Terbaik ({{ $prd_select->month }} {{ $prd_select->year }})</h1>
@endif
@include('Templates.Includes.Components.alert')
<p>
    <div class="row g-3 align-items-center">
        <div class="col-auto">
            <div class="dropdown">
                <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-folder-plus"></i>
                    Pilih Periode
                </button>
                <ul class="dropdown-menu">
                    <li>
                        @if (!empty($latest_per->id_period))
                            @if (Auth::check() && Auth::user()->part != 'Pegawai')
                            <a class="dropdown-item" href="{{ route('admin.inputs.votes.vote', $latest_per->id_period) }}">
                                Sekarang
                            </a>
                            @else
                            <a class="dropdown-item" href="{{ route('officer.votes.vote', $latest_per->id_period) }}">
                                Sekarang
                            </a>
                            @endif
                        @else
                        <button class="dropdown-item" disabled>
                            Sekarang
                        </button>
                        @endif
                    </li>
                    <li>
                        <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#modal-vte-periods">
                            Sebelumnya
                        </a>
                    </li>
                </ul>
                <a class="btn btn-secondary" data-bs-toggle="offcanvas" href="#offcanvas-help" role="button" aria-controls="offcanvas-help">
                    <i class="bi bi-question-lg"></i>
                    Bantuan
                </a>
            </div>
        </div>
        @if (Request::is('admin/inputs/votes/*') || Request::is('officer/votes/*'))
            @if (Auth::check() && Auth::user()->part != 'Pegawai')
            <div class="col-auto">
                <label for="tahun_saw_dl" class="col-form-label">Cek Pegawai:</label>
            </div>
            <div class="col-auto">
                <div class="btn-group" role="group" aria-label="Basic example">
                    <a class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#modal-chk-view-{{ $prd_select->id_period }}">
                        <i class="bi bi-database"></i>
                        Hanya Jabatan Ini
                    </a>
                    <a class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#modal-chk-all-{{ $prd_select->id_period }}">
                        <i class="bi bi-database"></i>
                        Semua Jabatan
                    </a>
                </div>
            </div>
            @endif
        @endif
    </div>
</p>
@if (Request::is('admin/inputs/votes') || Request::is('officer/votes'))
<div class="alert alert-info" role="alert">
    Untuk melihat atau memilih pegawai yang akan dijadikan sebagai karyawan terbaik di setiap periode, klik pilih periode untuk memilih periode yang tersedia.
</div>
@endif
@if (Request::is('admin/inputs/votes/*') || Request::is('officer/votes/*'))
<div class="row g-2">
    <div class="col-md-3">
        <div class="position-sticky" style="top: 2rem;">
            <div class="nav flex-column nav-pills me-3" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                @forelse ($criterias as $criteria)
                <button class="nav-link {{ $loop->first ? 'active' : '' }} text-start" id="pills-{{ $criteria->id_vote_criteria }}-tab" data-bs-toggle="pill" data-bs-target="#pills-{{ $criteria->id_vote_criteria }}" type="button" role="tab" aria-controls="pills-{{ $criteria->id_vote_criteria }}" aria-selected="{{ $loop->first ? 'true' : 'false' }}">
                    @if ($checks->where('id_officer', Auth::user()->officer->id_officer)->where('id_period', $prd_select->id_period)->where('id_vote_criteria', $criteria->id_vote_criteria)->count() != 0)
                    <i class="bi bi-check-lg"></i> {{ $criteria->name }}
                    @else
                    <i class="bi bi-x-lg"></i> {{ $criteria->name }}
                    @endif
                </button>
                @empty
                <button class="nav-link active text-start" id="pills-empty-tab" data-bs-toggle="pill" data-bs-target="#pills-empty" type="button" role="tab" aria-controls="pills-empty" aria-selected="true">
                    Empty
                </button>
                @endforelse
            </div>
            <br/>
        </div>
    </div>
    <div class="col-md-9">
        @if ($prd_select->status == "Finished")
        <div class="alert alert-warning" role="alert">
            Pemilihan Karyawan Terbaik pada periode ini telah selesai dilaksanakan.
        </div>
        @endif
        <div class="tab-content" id="v-pills-tabContent">
            @forelse ($criterias as $criteria)
            <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" id="pills-{{ $criteria->id_vote_criteria }}" role="tabpanel" aria-labelledby="pills-{{ $criteria->id_vote_criteria }}-tab" tabindex="0">
                @if ($checks->where('id_officer', Auth::user()->officer->id_officer)->where('id_period', $prd_select->id_period)->where('id_vote_criteria', $criteria->id_vote_criteria)->count() != 0)
                <h2>{{ $criteria->name }} <span class="badge text-bg-success">Voted</span></h2>
                @else
                <h2>{{ $criteria->name }}</h2>
                @endif
                <table class="table table-hover table-bordered">
                    <thead>
                        <tr class="table-primary">
                            <th class="col-1" scope="col">#</th>
                            <th scope="col">Nama</th>
                            <th class="col-1" scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($votes->where('id_period', $prd_select->id_period)->where('id_vote_criteria', $criteria->id_vote_criteria) as $vote)
                        @if ($checks->where('id_officer', Auth::user()->officer->id_officer)->where('id_period', $prd_select->id_period)->where('id_vote_criteria', $criteria->id_vote_criteria)->where('officer_selected', $vote->id_officer)->count() != 0)
                        <tr class="table-success">
                        @else
                        <tr>
                        @endif
                            <th scope="row">{{ $loop->iteration }}</th>
                            <td>{{ $vote->officer->name }}</td>
                            <td>
                                @if ($prd_select->status == "Finished")
                                <span class="d-inline-block" tabindex="0" data-bs-toggle="tooltip" data-bs-title="Proses Karyawan Terbaik sudah selesai.">
                                    <button class="btn btn-secondary" href="#" role="button" disabled>
                                        <i class="bi bi-check-lg"></i>
                                    </a>
                                </span>
                                @else
                                    @if ($checks->where('id_period', $prd_select->id_period)->where('id_officer', Auth::user()->id_officer)->where('id_vote_criteria', $criteria->id_vote_criteria)->count() == 0)
                                    <a class="btn btn-primary" href="#" role="button" data-bs-toggle="modal" data-bs-target="#modal-vte-select-{{ $prd_select->id_period }}-{{ $vote->id_officer }}-{{ $criteria->id_vote_criteria }}">
                                        <i class="bi bi-check-lg"></i>
                                    </button>
                                    @else
                                    <a class="btn btn-secondary disabled" href="#" role="button">
                                        <i class="bi bi-check-lg"></i>
                                    </a>
                                    @endif
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tfoot class="table-group-divider table-secondary">
                            <tr>
                                <td colspan="10">Total Data: <b>{{ $votes->where('id_period', $prd_select->id_period)->count() }}</b> Pegawai</td>
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
@endif
