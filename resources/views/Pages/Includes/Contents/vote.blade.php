@if (Request::is('admin/inputs/votes') || Request::is('officer/votes'))
<h1 class="text-center mb-4">Pemilihan Karyawan Terbaik</h1>
@elseif (Request::is('admin/inputs/votes/*') || Request::is('officer/votes/*'))
<h1 class="text-center mb-4">Pemilihan Karyawan Terbaik ({{ $prd_select->month }} {{ $prd_select->year }})</h1>
@endif
@include('Templates.Includes.Components.alert')
<!--MENU-->
<p>
    <div class="row g-3 align-items-center">
        <!--PERIODE PICKER-->
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
        <!--OFFICER CHECKER-->
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
<!--NOTICE-->
@if (Request::is('admin/inputs/votes') || Request::is('officer/votes'))
<div class="alert alert-info" role="alert">
    Untuk melihat atau memilih pegawai yang akan dijadikan sebagai karyawan terbaik di setiap periode, klik pilih periode untuk memilih periode yang tersedia.
</div>
@endif
@if (Request::is('admin/inputs/votes/*') || Request::is('officer/votes/*'))
<div class="row g-2">
    <!--SIDEBAR-->
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
    <!--MAIN CONTENT-->
    <div class="col-md-9">
        <!--NOTICE-->
        @if ($prd_select->status == "Finished")
        <div class="alert alert-warning" role="alert">
            Pemilihan Karyawan Terbaik pada periode ini telah selesai dilaksanakan.
        </div>
        @endif
        <!--TAB CONTENT-->
        <div class="tab-content" id="v-pills-tabContent">
            @forelse ($criterias as $criteria)
            <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" id="pills-{{ $criteria->id_vote_criteria }}" role="tabpanel" aria-labelledby="pills-{{ $criteria->id_vote_criteria }}-tab" tabindex="0">
                @if ($checks->where('id_officer', Auth::user()->officer->id_officer)->where('id_period', $prd_select->id_period)->where('id_vote_criteria', $criteria->id_vote_criteria)->count() != 0)
                <h2>{{ $criteria->name }} <span class="badge text-bg-success">Voted</span></h2>
                @else
                <h2>{{ $criteria->name }}</h2>
                @endif
                <div class="row row-cols-1 row-cols-md-3 g-4">
                    @foreach ($votes->where('id_period', $prd_select->id_period)->where('id_vote_criteria', $criteria->id_vote_criteria) as $vote)
                    <div class="col">
                        @if ($checks->where('id_officer', Auth::user()->officer->id_officer)->where('id_period', $prd_select->id_period)->where('id_vote_criteria', $criteria->id_vote_criteria)->where('officer_selected', $vote->id_officer)->count() != 0)
                        <div class="card h-100 text-bg-success">
                        @else
                        <div class="card h-100">
                        @endif
                            <img src="{{ url('Images/Portrait/'.$vote->officer->photo) }}" onerror="this.onerror=null; this.src='{{ asset('Images/Default/Portrait.png') }}'" class="card-img-top object-fit-cover" style="display:block; height:200px; width:100%;" alt="...">
                            <div class="card-body">
                                <h4 class="card-title">{{ $vote->officer->name }}</h4>
                                <p class="card-text">Jabatan: {{ $vote->officer->department->name }}<br/>
                                Bagian: {{ $vote->officer->department->part->name }}</p>
                            </div>
                            <div class="card-footer">
                                <div class="row align-items-center">
                                    <div class="col-9">
                                    </div>
                                    <div class="col-3 d-grid gap-2 d-md-flex justify-content-md-end">
                                        @if ($prd_select->status == "Finished")
                                        <span class="d-inline-block" tabindex="0" data-bs-toggle="tooltip" data-bs-title="Proses Karyawan Terbaik sudah selesai.">
                                            <button class="btn btn-secondary btn-sm" href="#" role="button" disabled>
                                                Pilih
                                            </a>
                                        </span>
                                        @else
                                            @if ($checks->where('id_period', $prd_select->id_period)->where('id_officer', Auth::user()->id_officer)->where('id_vote_criteria', $criteria->id_vote_criteria)->count() == 0)
                                            <a class="btn btn-primary btn-sm stretched-link" href="#" role="button" data-bs-toggle="modal" data-bs-target="#modal-vte-select-{{ $prd_select->id_period }}-{{ $vote->id_officer }}-{{ $criteria->id_vote_criteria }}">
                                                Pilih
                                            </a>
                                            @elseif ($checks->where('id_period', $prd_select->id_period)->where('id_officer', Auth::user()->id_officer)->where('id_vote_criteria', $criteria->id_vote_criteria)->where('officer_selected', $vote->id_officer)->count() != 0)
                                            <a class="btn btn-outline-light stretched-link btn-sm disabled" href="#" role="button">
                                                Pilih
                                            </a>
                                            @else
                                            <a class="btn btn-secondary stretched-link btn-sm disabled" href="#" role="button">
                                                Pilih
                                            </a>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @empty
            <div class="tab-pane fade show active" id="pills-empty" role="tabpanel" aria-labelledby="pills-empty-tab" tabindex="0">
                <div class="alert alert-danger" role="alert">
                    <p>Tidak ada data yang terdaftar.</p>
                </div>
            </div>
            @endforelse
            <br/>
        </div>
    </div>
</div>
@endif
