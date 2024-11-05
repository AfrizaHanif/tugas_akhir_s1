<h1 class="text-center mb-4">Kriteria</h1>
@if (Session::get('code_alert') == 1)
@include('Templates.Includes.Components.alert')
@endif
@if ($criterias->sum('weight')*100 > 100)
<div class="alert alert-warning" role="alert">
    <i class="bi bi-exclamation-triangle-fill"></i> <b>PERHATIAN</b>
    <br/>
    Total Bobot melebihi 100%. Cek kembali bobot di setiap kriteria
</div>
@elseif ($criterias->sum('weight')*100 <= 99)
<div class="alert alert-warning" role="alert">
    <i class="bi bi-exclamation-triangle-fill"></i> <b>PERHATIAN</b>
    <br/>
    Total Bobot belum mencapai 100%. Cek kembali bobot di setiap kriteria
</div>
@endif
<div class="row g-2">
    <!--SIDEBAR-->
    <div class="col-md-3">
        <div class="position-sticky" style="top: 0rem;">
            <!--MENU-->
            <p>
                <!--CATEGORY-->
                <a class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-cat-create">
                    <i class="bi bi-folder-plus"></i>
                    Tambah Kategori
                </a>
                <!--HELP-->
                <span class="d-inline-block" tabindex="0" data-bs-toggle="tooltip" data-bs-title="Bantuan">
                    <a class="btn btn-secondary" data-bs-toggle="offcanvas" href="#offcanvas-help" role="button" aria-controls="offcanvas-help">
                        <i class="bi bi-question-lg"></i>
                    </a>
                </span>
            </p>
            <!--LIST OF CRITERIA-->
            <div class="nav flex-column nav-pills me-3" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                @forelse ($categories as $category)
                <button class="nav-link {{ $loop->first ? 'active' : '' }}" id="pills-{{ $category->id_category }}-tab" data-bs-toggle="pill" data-bs-target="#pills-{{ $category->id_category }}" type="button" role="tab" aria-controls="pills-{{ $category->id_category }}" aria-selected="{{ $loop->first ? 'true' : 'false' }}">
                    {{ $category->name }}
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
    <!--LIST OF SUB CRITERIA-->
    <div class="col-md-9">
        <div class="tab-content" id="v-pills-tabContent">
            @forelse ($categories as $category)
            <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" id="pills-{{ $category->id_category }}" role="tabpanel" aria-labelledby="pills-{{ $category->id_category }}-tab" tabindex="0">
                <div class="row align-items-center">
                    <div class="col-6">
                        <h2>{{ $category->name }}</h2>
                    </div>
                    <div class="col-6 d-grid gap-2 d-md-flex justify-content-md-end">
                        <!--SUB MENU-->
                        <div class="row g-3 align-items-center">
                            <!--ADD SUB CRITERIA-->
                            <div class="col-auto pe-0">
                                <a type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-crt-create-{{ $category->id_category }}">
                                    <i class="bi bi-clipboard2-plus"></i>
                                    Tambah Kriteria
                                </a>
                            </div>
                            <!--MANAGE CRITERIA-->
                            <div class="col-auto">
                                <div class="dropdown">
                                    <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="bi bi-gear"></i>
                                        Kelola Kategori
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a class="dropdown-item" href="#"  data-bs-toggle="modal" data-bs-target="#modal-cat-update-{{ $category->id_category }}">
                                                <i class="bi bi-pencil"></i>
                                                Ubah Kategori
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="#"  data-bs-toggle="modal" data-bs-target="#modal-cat-delete-{{ $category->id_category }}">
                                                <i class="bi bi-folder-minus"></i>
                                                Hapus Kategori
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <h4 class="pb-2">Sumber: {{ $category->source }}</h4>
                <!--TABLE-->
                <table class="table table-hover table-bordered">
                    <thead>
                        <tr class="table-primary">
                            <th class="col-1" scope="col">#</th>
                            <th scope="col">Nama</th>
                            <th class="col-1" scope="col">Bobot</th>
                            <th class="col-1" scope="col">Atribut</th>
                            <th class="col-2" scope="col">Max</th>
                            <th class="col-1" scope="col">Crips</th>
                            <th class="col-1" scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($criterias->where('id_category', $category->id_category) as $criteria)
                        <tr>
                            <th scope="row">{{ $loop->iteration }}</th>
                            <td>{{ $criteria->name }}</td>
                            <td>{{ ($criteria->weight * 100 ) }}%</td>
                            <td>{{ $criteria->attribute }}</td>
                            <td>{{ $criteria->max }} {{ $criteria->unit }}</td>
                            <td>{{ count($crips->where('id_criteria', $criteria->id_criteria)) }}</td>
                            <td>
                                <div class="dropdown">
                                    <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="bi bi-menu-button-fill"></i>
                                    </button>
                                    <ul class="dropdown-menu mx-0 shadow w-table-menu">
                                        <li>
                                            <a class="dropdown-item d-flex gap-2 align-items-center" href="#"  data-bs-toggle="modal" data-bs-target="#modal-crp-view-{{ $criteria->id_criteria }}">
                                                <i class="bi bi-gear"></i>
                                                Kelola Crips
                                            </a>
                                        </li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <a class="dropdown-item d-flex gap-2 align-items-center"  href="#" data-bs-toggle="modal" data-bs-target="#modal-crt-update-{{ $criteria->id_criteria }}"><svg class="bi" width="16" height="16" style="vertical-align: -.125em;"><use xlink:href="#update"/></svg>
                                                Edit
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item d-flex gap-2 align-items-center" href="#" data-bs-toggle="modal" data-bs-target="#modal-crt-delete-{{ $criteria->id_criteria }}"><svg class="bi" width="16" height="16" style="vertical-align: -.125em;"><use xlink:href="#delete"/></svg>
                                                Delete
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10">Tidak ada Kriteria yang terdaftar</td>
                        </tr>
                        @endforelse
                    </tbody>
                    <tfoot class="table-group-divider table-secondary">
                        <tr>
                            <td colspan="10">Jumlah Data: <b>{{ $criterias->where('id_category', $criteria->id_category)->count() }}</b> Kriteria dengan total bobot <b>{{ $criterias->where('id_category', $criteria->id_category)->sum('weight')*100 }}%</b> (Total seluruh bobot: <b>{{ $criterias->sum('weight')*100 }}%</b>)</td>
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
