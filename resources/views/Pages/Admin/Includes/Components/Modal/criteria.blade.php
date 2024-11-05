<!--CREATE CATEGORY-->
<div class="modal fade" id="modal-cat-create" data-bs-backdrop="static" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.masters.categories.store') }}" method="POST" enctype="multipart/form-data" id="form-crt-create">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Tambah Kategori</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="close-crt-create"></button>
                </div>
                <div class="modal-body">
                    @if (Session::get('modal_redirect') == 'modal-cat-create')
                    @include('Templates.Includes.Components.alert')
                    @endif
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Kategori</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <!--
                    <div-- class="mb-3">
                        <label for="type" class="form-label">Jenis Kategori</label>
                        <select class="form-select" id="type" name="type" required>
                            <option selected disabled value="">---Pilih Jenis Kategori---</option>
                            <option value="Kehadiran" {{ old('type') == 'Kehadiran' ? 'selected' : null }}>Kehadiran</option>
                            <option value="Prestasi Kerja" {{ old('type') == 'Prestasi Kerja' ? 'selected' : null }}>Prestasi Kerja</option>
                        </select>
                    </div-->
                    <div class="mb-3">
                        <label for="source" class="form-label">Sumber Data</label>
                        <select class="form-select" id="source" name="source" required>
                            <option selected disabled value="">---Pilih Sumber Data---</option>
                            <option value="Presensi" {{ old('source') == 'Presensi' ? 'selected' : null }}>Presensi</option>
                            <option value="SKP" {{ old('source') == 'SKP' ? 'selected' : null }}>Sasaran Kinerja Pegawai (SKP)</option>
                            <option value="CKP" {{ old('source') == 'CKP' ? 'selected' : null }}>Capaian Kinerja Pegawai (CKP)</option>
                        </select>
                    </div>
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
@foreach ($categories as $category)
<!--UPDATE CATEGORY-->
<div class="modal fade" id="modal-cat-update-{{ $category->id_category }}" data-bs-backdrop="static" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.masters.categories.update', $category->id_category) }}" method="POST" enctype="multipart/form-data">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Ubah Kategori</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @if (Session::get('modal_redirect') == 'modal-cat-update')
                    @include('Templates.Includes.Components.alert')
                    @endif
                    @csrf @method('PUT')
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Kategori</label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ $category->name }}" required>
                    </div>
                    <!--
                    <div class="mb-3">
                        <label for="type" class="form-label">Jenis Kategori</label>
                        <select class="form-select" id="type" name="type" required>
                            <option selected disabled value="">---Pilih Jenis Kategori---</option>
                            <option value="Kehadiran" {{ $category->type == 'Kehadiran' ? 'selected' : null }}>Kehadiran</option>
                            <option value="Prestasi Kerja" {{ $category->type == 'Prestasi Kerja' ? 'selected' : null }}>Prestasi Kerja</option>
                        </select>
                    </div>
                    -->
                    <div class="mb-3">
                        <label for="source" class="form-label">Sumber Data</label>
                        <select class="form-select" id="source" name="source" required>
                            <option selected disabled value="">---Pilih Sumber Data---</option>
                            <option value="Presensi" {{ $category->source == 'Presensi' ? 'selected' : null }}>Presensi</option>
                            <option value="SKP" {{ $category->source == 'SKP' ? 'selected' : null }}>Sasaran Kinerja Pegawai (SKP)</option>
                            <option value="CKP" {{ $category->source == 'CKP' ? 'selected' : null }}>Capaian Kinerja Pegawai (CKP)</option>
                        </select>
                    </div>
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
<!--DELETE CATEGORY-->
<div class="modal fade" id="modal-cat-delete-{{ $category->id_category }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Hapus Kategori</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="form-cat-delete-{{ $category->id_category }}" action="{{ route('admin.masters.categories.destroy', $category->id_category) }}" method="POST" enctype="multipart/form-data">
                    @csrf @method('DELETE')
                    <div class="alert alert-warning" role="alert">
                        <i class="bi bi-exclamation-triangle-fill"></i> <b>PERHATIAN</b>
                        <br/>
                        Apakah anda ingin menghapus Kategori <b>{{ $category->name }}</b>?
                        <ul>
                            <li>Seluruh <strong>Kriteria (Termasuk Data Crips)</strong> dari Kategori ini akan dihapus bersamaan.</li>
                            <li>Proses ini akan menghapus data nilai yang berkaitan dengan <strong>Kriteria</strong> pada Kategori ini.</li>
                            <li>Segera lakukan perubahan angka bobot pada seluruh Kriteria agar bisa mencapai <strong>100%</strong>.</li>
                            <li>Segera lakukan <strong>Import Ulang / Konversi Ulang</strong> setelah melakukan penghapusan Kategori ini.</li>
                        </ul>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-lg"></i>
                    Tidak
                </button>
                <button type="submit" form="form-cat-delete-{{ $category->id_category }}" class="btn btn-danger">
                    <i class="bi bi-check-lg"></i>
                    Ya
                </button>
            </div>
        </div>
    </div>
</div>
<!--CREATE CRITERIA-->
<div class="modal modal-lg fade" id="modal-crt-create-{{ $category->id_category }}" data-bs-backdrop="static" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Tambah Kriteria dari Kategori {{ $category->name }}</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="close-sub-create-{{ $category->id_category }}"></button>
                </div>
                <div class="modal-body">
                    <form id="form-crt-create-{{ $category->id_category }}" action="{{ route('admin.masters.criterias.store') }}" method="POST" enctype="multipart/form-data" id="form-sub-create-{{ $category->id_category }}">
                        @csrf
                        <div class="row justify-content-center g-4">
                            <div class="col-md-7">
                                <div class="position-sticky" style="top: 0rem;">
                                    @if (Session::get('modal_redirect') == 'modal-crt-create')
                                    @include('Templates.Includes.Components.alert')
                                    @endif
                                    <div class="mb-3" hidden>
                                        <label for="id_category" class="form-label" hidden>Kode Kategori</label>
                                        <input type="text" class="form-control" id="id_category" name="id_category" value="{{ $category->id_category }}" readonly hidden>
                                    </div>
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Nama Kriteria</label>
                                        <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col">
                                            <label for="weight" class="form-label">Bobot</label>
                                            <div class="input-group">
                                                <input type="number" class="form-control" id="weight" name="weight" min="0" max="100" value="{{ old('weight') }}" aria-describedby="percent_weight" required>
                                                <span class="input-group-text" id="percent_weight">%</span>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <label for="attribute" class="form-label">Atribut</label>
                                            <select class="form-select" id="attribute" name="attribute" required>
                                                <option selected disabled value="">---Pilih Atribut---</option>
                                                <option value="Benefit" {{ old('attribute') == 'Benefit' ? 'selected' : null }}>Benefit</option>
                                                <option value="Cost" {{ old('attribute') == 'Cost' ? 'selected' : null }}>Cost</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col">
                                            <label for="max" class="form-label">Maksimum Nilai Asli</label>
                                            <input type="number" class="form-control" id="max" name="max" min="0" value="{{ old('max') }}" required>
                                        </div>
                                        <div class="col">
                                            <label for="unit" class="form-label">Satuan Nilai</label>
                                            <input type="text" class="form-control" id="unit" name="unit" value="{{ old('unit') }}" required>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="source" class="form-label">Sumber Kolom (Untuk Import)</label>
                                        <input type="text" class="form-control" id="source" name="source" value="{{ old('source') }}" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="position-sticky" style="top: 0rem;">
                                    <div class="alert alert-info" role="alert">
                                        <i class="bi bi-info-circle-fill"></i> <strong>CARA PENGISIAN</strong>
                                        <ol>
                                            <li>Isi data sesuai dengan form yang tersedia</li>
                                            <li>Bobot dan Tingkat Kepentingan:</li>
                                            <ol>
                                                <li>Khusus <b>Cost</b>, disarankan tidak menggunakan bobot yang berat / tingkat yang tinggi</li>
                                                <li>Pastikan total bobot dari semua kriteria mencapai <b>100% (Tidak kurang dan lebih)</b> (Khusus bobot)</li>
                                            </ol>
                                            <li>Isi maksimum nilai dari data asli untuk kebutuhan konversi</li>
                                            <li>Isi sumber kolom sesuai dengan Excel yang akan di import ke aplikasi</li>
                                        </ol>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">
                        <i class="bi bi-x-lg"></i>
                        Batal
                    </button>
                    <button type="submit" form="form-crt-create-{{ $category->id_category }}" class="btn btn-primary">
                        <i class="bi bi-plus-lg"></i>
                        Tambah
                    </button>
                </div>
        </div>
    </div>
</div>
    @foreach ($criterias->where('id_category', $category->id_category) as $criteria)
    <!--UPDATE CRITERIA-->
    <div class="modal modal-lg fade" id="modal-crt-update-{{ $criteria->id_criteria }}" data-bs-backdrop="static" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Ubah Kriteria dari Kategori {{ $category->name }}</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="form-crt-update-{{ $criteria->id_criteria }}" action="{{ route('admin.masters.criterias.update', $criteria->id_criteria) }}" method="POST" enctype="multipart/form-data">
                            @csrf @method('PUT')
                            <div class="row justify-content-center g-4">
                                <div class="col-md-7">
                                    <div class="position-sticky" style="top: 0rem;">
                                        @if (Session::get('modal_redirect') == 'modal-crt-update')
                                        @include('Templates.Includes.Components.alert')
                                        @endif
                                        <div class="mb-3">
                                            <label for="name" class="form-label">Nama Kriteria</label>
                                            <input type="text" class="form-control" id="name" name="name" value="{{ $criteria->name }}" required>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col">
                                                <label for="weight" class="form-label">Bobot</label>
                                                <div class="input-group">
                                                    <input type="number" class="form-control" id="weight" name="weight" min="0" max="100" value="{{ $criteria->weight*100 }}" aria-describedby="percent_weight" required>
                                                    <span class="input-group-text" id="percent_weight">%</span>
                                                </div>
                                            </div>
                                            <div class="col">
                                                <label for="attribute" class="form-label">Atribut</label>
                                                <select class="form-select" id="attribute" name="attribute" required>
                                                    <option selected disabled value="">---Pilih Atribut---</option>
                                                    <option value="Benefit" {{ $criteria->attribute == 'Benefit' ? 'selected' : null }}>Benefit</option>
                                                    <option value="Cost" {{ $criteria->attribute == 'Cost' ? 'selected' : null }}>Cost</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col">
                                                <label for="max" class="form-label">Maksimum Nilai Asli</label>
                                                <input type="number" class="form-control" id="max" name="max" min="0" value="{{ $criteria->max }}" required>
                                            </div>
                                            <div class="col">
                                                <label for="unit" class="form-label">Satuan Nilai</label>
                                                <input type="text" class="form-control" id="unit" name="unit" value="{{ $criteria->unit }}" required>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label for="source" class="form-label">Sumber Kolom (Untuk Import)</label>
                                            <input type="text" class="form-control" id="source" name="source" value="{{ $criteria->source }}" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="position-sticky" style="top: 0rem;">
                                        <div class="alert alert-info" role="alert">
                                            <i class="bi bi-info-circle-fill"></i> <strong>CARA PENGISIAN</strong>
                                            <ol>
                                                <li>Isi data sesuai dengan form yang tersedia</li>
                                                <li>Bobot dan Tingkat Kepentingan:</li>
                                                <ol>
                                                    <li>Khusus <b>Cost</b>, disarankan tidak menggunakan bobot yang berat / tingkat yang tinggi</li>
                                                    <li>Pastikan total bobot dari semua kriteria mencapai <b>100% (Tidak kurang dan lebih)</b> (Khusus bobot)</li>
                                                </ol>
                                                <li>Isi maksimum nilai dari data asli untuk kebutuhan konversi</li>
                                                <li>Isi sumber kolom sesuai dengan Excel yang akan di import ke aplikasi</li>
                                            </ol>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">
                            <i class="bi bi-x-lg"></i>
                            Batal
                        </button>
                        <button type="submit" form="form-crt-update-{{ $criteria->id_criteria }}" class="btn btn-primary">
                            <i class="bi bi-pencil"></i>
                            Ubah
                        </button>
                    </div>
            </div>
        </div>
    </div>
    <!--DELETE CRITERIA-->
    <div class="modal fade" id="modal-crt-delete-{{ $criteria->id_criteria }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Hapus Kriteria</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="form-crt-delete-{{ $criteria->id_criteria }}" action="{{ route('admin.masters.criterias.destroy', $criteria->id_criteria) }}" method="POST" enctype="multipart/form-data">
                        @csrf @method('DELETE')
                        <div class="alert alert-warning" role="alert">
                            <i class="bi bi-exclamation-triangle-fill"></i> <b>PERHATIAN</b>
                            <br/>
                            Apakah anda ingin menghapus Kriteria <b>{{ $criteria->name }}</b>?
                            <ul>
                                <li>Seluruh <strong>Data Crips</strong> dari Kriteria ini akan dihapus bersamaan.</li>
                                <li>Proses ini akan menghapus data nilai yang berkaitan dengan <strong>Kriteria</strong> dari Kategori ini.</li>
                                <li>Segera lakukan perubahan angka bobot pada seluruh Kriteria agar bisa mencapai <strong>100%</strong>.</li>
                                <li>Segera lakukan <strong>Import Ulang / Konversi Ulang</strong> setelah melakukan penghapusan Kriteria ini.</li>
                            </ul>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-lg"></i>
                        Tidak
                    </button>
                    <button type="submit" form="form-crt-delete-{{ $criteria->id_criteria }}" class="btn btn-danger">
                        <i class="bi bi-check-lg"></i>
                        Ya
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!--VIEW CRIPS-->
    <div class="modal fade" id="modal-crp-view-{{ $criteria->id_criteria }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Daftar Data Crips pada Kriteria {{ $criteria->name }}</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @if (Session::get('code_alert') == 2)
                    @include('Templates.Includes.Components.alert')
                    @endif
                    <table class="table table-hover table-bordered">
                        <thead>
                            <tr class="table-primary">
                                <th class="col-1" scope="col">#</th>
                                <th scope="col">Nama Pilihan</th>
                                <th class="col-3" scope="col">Range</th>
                                <th class="col-1" scope="col">Nilai</th>
                                <th class="col-1" scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($crips->where('id_criteria', $criteria->id_criteria) as $crip)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $crip->name }}</td>
                                <td>
                                    @if($crip->value_type == 'Less')
                                    0 - {{ $crip->value_from }}
                                    @elseif($crip->value_type == 'More')
                                    {{ $crip->value_from }} - {{ $criteria->max }}
                                    @else
                                    {{ $crip->value_from }} - {{ $crip->value_to}}
                                    @endif
                                </td>
                                <td>{{ $crip->score }}</td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="bi bi-menu-button-fill"></i>
                                        </button>
                                        <ul class="dropdown-menu mx-0 shadow w-table-menu">
                                            <li>
                                                <a class="dropdown-item d-flex gap-2 align-items-center" href="#" data-bs-toggle="modal" data-bs-target="#modal-crp-update-{{ $crip->id_crips }}"><svg class="bi" width="16" height="16" style="vertical-align: -.125em;"><use xlink:href="#update"/></svg>
                                                    Edit
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item d-flex gap-2 align-items-center" href="#" data-bs-toggle="modal" data-bs-target="#modal-crp-delete-{{ $crip->id_crips }}"><svg class="bi" width="16" height="16" style="vertical-align: -.125em;"><use xlink:href="#delete"/></svg>
                                                    Delete
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            @empty
                            <tr>
                                <td colspan="10">Tidak ada pilihan yang terdaftar</td>
                            </tr>
                            @endforelse
                        </tbody>
                        <tfoot class="table-group-divider table-secondary">
                            <tr>
                                <td colspan="7">Total Data: <b>{{ $crips->where('id_criteria', $criteria->id_criteria)->count() }}</b> Pilihan</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-lg"></i>
                        Tutup
                    </button>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-crp-create-{{ $criteria->id_criteria }}">
                        <i class="bi bi-node-plus"></i>
                        Tambah
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!--CREATE CRIPS-->
    <div class="modal modal-lg fade" id="modal-crp-create-{{ $criteria->id_criteria }}" data-bs-backdrop="static" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Tambah Data Crips pada Kriteria {{ $criteria->name }}</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="close-crt-create"></button>
                </div>
                <div class="modal-body">
                    <form id="form-crp-create-{{ $criteria->id_criteria }}" action="{{ route('admin.masters.crips.store') }}" method="POST" enctype="multipart/form-data" id="form-crt-create">
                        @if (Session::get('modal_redirect') == 'modal-crp-create')
                        @include('Templates.Includes.Components.alert')
                        @endif
                        @csrf
                        <div class="row justify-content-center g-4">
                            <div class="col-md-7">
                                <div class="position-sticky" style="top: 0rem;">
                                    <div class="mb-3" hidden>
                                        <label for="id_criteria" class="form-label" hidden>Kode Kriteria</label>
                                        <input type="text" class="form-control" id="id_criteria" name="id_criteria" value="{{ $criteria->id_criteria }}" readonly hidden>
                                    </div>
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Nama Pilihan</label>
                                        <input type="text" class="form-control" id="name" name="name" required>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-5">
                                            <label for="value_type" class="form-label">Tipe Range</label>
                                            <select class="form-select" id="value_type_{{ $criteria->id_criteria }}" name="value_type" required>
                                                <option selected disabled value="">---Pilih Tipe Range---</option>
                                                <option value="Less" {{ old('value_type') == 'Less' ? 'selected' : null }}>Kurang Dari</option>
                                                <option value="Between" {{ old('value_type') == 'Between' ? 'selected' : null }}>Antara</option>
                                                <option value="More" {{ old('value_type') == 'More' ? 'selected' : null }}>Lebih Dari</option>
                                            </select>
                                        </div>
                                        <div class="col-md-7">
                                            <label for="id_period" class="form-label">Range Nilai Asli</label>
                                            <div class="input-group">
                                                <input type="number" id="value_from" name="value_from" aria-label="Awal Angka" class="form-control" min="0" required>
                                                <span class="input-group-text">-</span>
                                                <input type="number" id="value_to_{{ $criteria->id_criteria }}" name="value_to" aria-label="Akhir Angka" class="form-control" min="0">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="score" class="form-label">Nilai Konversi</label>
                                        <input type="number" class="form-control" id="score" name="score" min="1" max="5" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="position-sticky" style="top: 0rem;">
                                    <div class="alert alert-info" role="alert">
                                        <i class="bi bi-info-circle-fill"></i> <strong>CARA PENGISIAN</strong>
                                        <ol>
                                            <li>Isi nama pilihan sesuai keinginan anda.</li>
                                            <li>Minimum nilai konversi adalah <b>1</b>.</li>
                                            <li>Maksimum nilai konversi adalah <b>5</b>.</li>
                                            <li>Peraturan Range</li>
                                            <ul>
                                                <li>Jika pilihan tersebut bernilai konversi 1 (Benefit) atau 5 (Cost), pilih <b>kurang dari</b>.</li>
                                                <li>Jika pilihan tersebut bernilai konversi 2 sampai 4, pilih <b>antara</b>.</li>
                                                <li>Jika pilihan tersebut bernilai konversi 5 (Benefit) atau 1 (Cost), pilih <b>lebih dari</b>.</li>
                                            </ul>
                                        </ol>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#modal-crp-view-{{ $criteria->id_criteria }}">
                        <i class="bi bi-x-lg"></i>
                        Batal
                    </button>
                    <button type="submit" form="form-crp-create-{{ $criteria->id_criteria }}" class="btn btn-primary">
                        <i class="bi bi-plus-lg"></i>
                        Tambah
                    </button>
                </div>
            </div>
        </div>
    </div>
        @foreach ($crips->where('id_criteria', $criteria->id_criteria) as $crip)
        <!--UPDATE CRIPS-->
        <div class="modal modal-lg fade" id="modal-crp-update-{{ $crip->id_crips }}" data-bs-backdrop="static" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Ubah Data Crips pada Kriteria {{ $criteria->name }}</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="form-crp-update-{{ $crip->id_crips }}" action="{{ route('admin.masters.crips.update', $crip->id_crips) }}" method="POST" enctype="multipart/form-data">
                            @if (Session::get('modal_redirect') == 'modal-crp-update')
                            @include('Templates.Includes.Components.alert')
                            @endif
                            @csrf @method('PUT')
                            <div class="row justify-content-center g-4">
                                <div class="col-md-7">
                                    <div class="position-sticky" style="top: 0rem;">
                                        <div class="mb-3">
                                            <label for="name" class="form-label">Nama Pilihan</label>
                                            <input type="text" class="form-control" id="name" name="name" value="{{ $crip->name }}" required>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-5">
                                                <label for="value_type" class="form-label">Tipe Range</label>
                                                <select class="form-select" id="value_type_{{ $crip->id_crips }}" name="value_type" required>
                                                    <option selected disabled value="">---Pilih Tipe Range---</option>
                                                    <option value="Less" {{ $crip->value_type == 'Less' ? 'selected' : null }}>Kurang Dari</option>
                                                    <option value="Between" {{ $crip->value_type == 'Between' ? 'selected' : null }}>Antara</option>
                                                    <option value="More" {{ $crip->value_type == 'More' ? 'selected' : null }}>Lebih Dari</option>
                                                </select>
                                            </div>
                                            <div class="col-md-7">
                                                <label for="id_period" class="form-label">Range Nilai Asli</label>
                                                <div class="input-group">
                                                    <input type="number" id="value_from" name="value_from" aria-label="Awal Angka" class="form-control" value="{{ $crip->value_from }}" min="0" required>
                                                    <span class="input-group-text">-</span>
                                                    <input type="number" id="value_to_{{ $crip->id_crips }}" name="value_to" aria-label="Akhir Angka" class="form-control" value="{{ $crip->value_to }}" min="0">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label for="score" class="form-label">Nilai Konversi</label>
                                            <input type="number" class="form-control" id="score" name="score" min="1" max="5" value="{{ $crip->score }}" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="position-sticky" style="top: 0rem;">
                                        <div class="alert alert-info" role="alert">
                                            <i class="bi bi-info-circle-fill"></i> <strong>CARA PENGISIAN</strong>
                                            <ol>
                                                <li>Isi nama pilihan sesuai keinginan anda.</li>
                                                <li>Minimum nilai konversi adalah <b>1</b>.</li>
                                                <li>Maksimum nilai konversi adalah <b>5</b>.</li>
                                                <li>Peraturan Range</li>
                                                <ul>
                                                    <li>Jika pilihan tersebut bernilai konversi 1 (Benefit) atau 5 (Cost), pilih <b>kurang dari</b>.</li>
                                                    <li>Jika pilihan tersebut bernilai konversi 2 sampai 4, pilih <b>antara</b>.</li>
                                                    <li>Jika pilihan tersebut bernilai konversi 5 (Benefit) atau 1 (Cost), pilih <b>lebih dari</b>.</li>
                                                </ul>
                                            </ol>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#modal-crp-view-{{ $criteria->id_criteria }}">
                            <i class="bi bi-x-lg"></i>
                            Batal
                        </button>
                        <button type="submit" form="form-crp-update-{{ $crip->id_crips }}" class="btn btn-primary">
                            <i class="bi bi-pencil"></i>
                            Ubah
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!--DELETE CRIPS-->
        <div class="modal fade" id="modal-crp-delete-{{ $crip->id_crips }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Hapus Data Crips ({{ $crip->id_crips}})</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="form-crp-delete-{{ $crip->id_crips }}" action="{{ route('admin.masters.crips.destroy', $crip->id_crips) }}" method="POST" enctype="multipart/form-data">
                            @csrf @method('DELETE')
                            <div class="alert alert-warning" role="alert">
                                <i class="bi bi-exclamation-triangle-fill"></i> <b>PERHATIAN</b>
                                <br/>
                                Apakah anda ingin menghapus Data Crips dengan Nama <b>{{ $crip->name }}</b>?
                                <ul>
                                    <li>Segera lakukan <strong>Import Ulang / Konversi Ulang</strong> setelah melakukan penghapusan Data Crips ini.</li>
                                </ul>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#modal-crp-view-{{ $criteria->id_criteria }}">
                            <i class="bi bi-x-lg"></i>
                            Tidak
                        </button>
                        <button type="submit" form="form-crp-delete-{{ $crip->id_crips }}" class="btn btn-danger">
                            <i class="bi bi-check-lg"></i>
                            Ya
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    @endforeach
@endforeach
