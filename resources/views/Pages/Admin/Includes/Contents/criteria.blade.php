<h1 class="text-center mb-4">Kriteria untuk Penilaian</h1>
@if (Session::get('code_alert') == 1)
@include('Templates.Includes.Components.alert')
@endif
<p>
    <a class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-crt-create">
        <i class="bi bi-folder-plus"></i>
        Tambah Kriteria
    </a>
    <a class="btn btn-secondary" data-bs-toggle="offcanvas" href="#offcanvas-help" role="button" aria-controls="offcanvas-help">
        <i class="bi bi-question-lg"></i>
        Bantuan
    </a>
</p>
<div class="row">
    <div class="col-md-3">
        <div class="position-sticky" style="top: 2rem;">
            <div class="nav flex-column nav-pills me-3" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                @forelse ($criterias as $criteria)
                <button class="nav-link {{ $loop->first ? 'active' : '' }}" id="pills-{{ $criteria->id_criteria }}-tab" data-bs-toggle="pill" data-bs-target="#pills-{{ $criteria->id_criteria }}" type="button" role="tab" aria-controls="pills-{{ $criteria->id_criteria }}" aria-selected="{{ $loop->first ? 'true' : 'false' }}">
                    {{ $criteria->name }}
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
            @forelse ($criterias as $criteria)
            <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" id="pills-{{ $criteria->id_criteria }}" role="tabpanel" aria-labelledby="pills-{{ $criteria->id_criteria }}-tab" tabindex="0">
                <h2>{{ $criteria->name }}</h2>
                <h4>{{ $criteria->type }}</h4>
                <p>
                    <div class="btn-group" role="group" aria-label="Basic example">
                        <a class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-sub-create-{{ $criteria->id_criteria }}">
                            <i class="bi bi-person-plus"></i>
                            Tambah Sub Kriteria
                        </a>
                    </div>
                    <div class="btn-group" role="group" aria-label="Basic example">
                        <a class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#modal-crt-update-{{ $criteria->id_criteria }}">
                            <i class="bi bi-pencil"></i>
                            Ubah Kriteria
                        </a>
                    </div>
                    <div class="btn-group" role="group" aria-label="Basic example">
                        <a class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#modal-crt-delete-{{ $criteria->id_criteria }}">
                            <i class="bi bi-folder-minus"></i>
                            Hapus Kriteria
                        </a>
                    </div>
                </p>
                <table class="table table-hover table-bordered">
                    <thead>
                        <tr class="table-primary">
                            <th class="col-1" scope="col">#</th>
                            <th scope="col">Nama</th>
                            <th class="col-1" scope="col">Bobot</th>
                            <th class="col-1" scope="col">Atribut</th>
                            <th class="col-1" scope="col">Tingkat</th>
                            <th class="col-1" scope="col">Butuh?</th>
                            <th class="col-1" scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($subcriterias->where('id_criteria', $criteria->id_criteria) as $subcriteria)
                        <tr>
                            <th scope="row">{{ $loop->iteration }}</th>
                            <td>{{ $subcriteria->name }}</td>
                            <td>{{ ($subcriteria->weight * 100 ) }}%</td>
                            <td>{{ $subcriteria->attribute }}</td>
                            <td>{{ $subcriteria->level }}</td>
                            <td>{{ $subcriteria->need }}</td>
                            <td>
                                <div class="dropdown">
                                    <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="bi bi-menu-button-fill"></i>
                                    </button>
                                    <ul class="dropdown-menu mx-0 shadow w-table-menu">
                                        <li>
                                            <a class="dropdown-item d-flex gap-2 align-items-center"  href="#" data-bs-toggle="modal" data-bs-target="#modal-sub-update-{{ $subcriteria->id_sub_criteria }}"><svg class="bi" width="16" height="16" style="vertical-align: -.125em;"><use xlink:href="#update"/></svg>
                                                Edit
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item d-flex gap-2 align-items-center" href="#" data-bs-toggle="modal" data-bs-target="#modal-sub-delete-{{ $subcriteria->id_sub_criteria }}"><svg class="bi" width="16" height="16" style="vertical-align: -.125em;"><use xlink:href="#delete"/></svg>
                                                Delete
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7">Tidak ada Pegawai yang terdaftar</td>
                        </tr>
                        @endforelse
                    </tbody>
                    <tfoot class="table-group-divider table-secondary">
                        <tr>
                            <td colspan="7">Total Data: <b>{{ $subcriterias->where('id_criteria', $criteria->id_criteria)->count() }}</b> Sub Kriteria</td>
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
