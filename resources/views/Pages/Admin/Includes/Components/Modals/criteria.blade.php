<!--CREATE CATEGORY-->
<div class="modal fade" id="modal-cat-create" data-bs-backdrop="static" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.masters.categories.store') }}" method="POST" enctype="multipart/form-data" id="form-crt-create">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Tambah Kriteria</h1>
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
                    <div class="mb-3">
                        <label for="type" class="form-label">Jenis Kategori (Untuk Dimasukkan ke Tabel)</label>
                        <select class="form-select" id="type" name="type" required>
                            <option selected disabled value="">---Pilih Jenis Kategori---</option>
                            <option value="Kehadiran" {{ old('type') == 'Kehadiran' ? 'selected' : null }}>Kehadiran</option>
                            <option value="Prestasi Kerja" {{ old('type') == 'Prestasi Kerja' ? 'selected' : null }}>Prestasi Kerja</option>
                        </select>
                    </div>
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
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Ubah Kategori ({{ $category->id_category }})</h1>
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
                    <div class="mb-3">
                        <label for="type" class="form-label">Jenis Kategori (Untuk Dimasukkan ke Tabel)</label>
                        <select class="form-select" id="type" name="type" required>
                            <option selected disabled value="">---Pilih Jenis Kategori---</option>
                            <option value="Kehadiran" {{ $category->type == 'Kehadiran' ? 'selected' : null }}>Kehadiran</option>
                            <option value="Prestasi Kerja" {{ $category->type == 'Prestasi Kerja' ? 'selected' : null }}>Prestasi Kerja</option>
                        </select>
                    </div>
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
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.masters.categories.destroy', $category->id_category) }}" method="POST" enctype="multipart/form-data">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Hapus Kriteria ({{ $category->id_category}})</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning" role="alert">
                        <i class="bi bi-exclamation-triangle-fill"></i> <b>PERHATIAN</b>
                        <br/>
                        Apakah anda ingin menghapus Kriteria?
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-lg"></i>
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
<!--CREATE CRITERIA-->
<div class="modal modal-lg fade" id="modal-crt-create-{{ $category->id_category }}" data-bs-backdrop="static" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.masters.criterias.store') }}" method="POST" enctype="multipart/form-data" id="form-sub-create-{{ $category->id_category }}">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Tambah Kriteria</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="close-sub-create-{{ $category->id_category }}"></button>
                </div>
                <div class="modal-body">
                    @csrf
                    <div class="row justify-content-center g-4">
                        <div class="col-md-7">
                            <div class="position-sticky" style="top: 2rem;">
                                @if (Session::get('modal_redirect') == 'modal-crt-create')
                                @include('Templates.Includes.Components.alert')
                                @endif
                                <div class="mb-3">
                                    <label for="id_category" class="form-label">Kode Kategori</label>
                                    <input type="text" class="form-control" id="id_category" name="id_category" value="{{ $category->id_category }}" readonly>
                                </div>
                                <div class="mb-3">
                                    <label for="name" class="form-label">Nama Kriteria</label>
                                    <input type="text" class="form-control" id="name" name="name" required>
                                </div>
                                <div class="mb-3">
                                    <label for="weight" class="form-label">Bobot</label>
                                    <input type="text" class="form-control" id="weight" name="weight" required>
                                </div>
                                <div class="mb-3">
                                    <label for="attribute" class="form-label">Atribut</label>
                                    <select class="form-select" id="attribute" name="attribute" required>
                                        <option selected disabled value="">---Pilih Atribut---</option>
                                        <option value="Benefit" {{ old('attribute') == 'Benefit' ? 'selected' : null }}>Benefit</option>
                                        <option value="Cost" {{ old('attribute') == 'Cost' ? 'selected' : null }}>Cost</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="level" class="form-label">Tingkat Kepentingan</label>
                                    <select class="form-select" id="level" name="level" required>
                                        <option selected disabled value="">---Pilih Atribut---</option>
                                        <option value="1" {{ old('level') == '1' ? 'selected' : null }}>1. Sama Penting</option>
                                        <option value="3" {{ old('level') == '3' ? 'selected' : null }}>3. Cukup Penting</option>
                                        <option value="5" {{ old('level') == '5' ? 'selected' : null }}>5. Lebih Penting</option>
                                        <option value="7" {{ old('level') == '7' ? 'selected' : null }}>7. Sangat Lebih Penting</option>
                                        <option value="9" {{ old('level') == '9' ? 'selected' : null }}>9. Mutlak Lebih Penting</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="max" class="form-label">Maksimum Nilai Asli</label>
                                    <input type="number" class="form-control" id="max" name="max" required>
                                </div>
                                <div class="mb-3">
                                    <label for="need" class="form-label">Apakah Dibutuhkan untuk Proses Karyawan Terbaik?</label>
                                    <select class="form-select" id="need" name="need" required>
                                        <option selected disabled value="">---Pilih---</option>
                                        <option value="Ya" {{ old('attribute') == 'Ya' ? 'selected' : null }}>Ya</option>
                                        <option value="Tidak" {{ old('attribute') == 'Tidak' ? 'selected' : null }}>Tidak</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="source" class="form-label">Sumber Kolom (Untuk Import)</label>
                                    <input type="text" class="form-control" id="source" name="source" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="position-sticky" style="top: 2rem;">
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
    @foreach ($criterias->where('id_category', $category->id_category) as $criteria)
    <!--UPDATE CRITERIA-->
    <div class="modal modal-lg fade" id="modal-crt-update-{{ $criteria->id_criteria }}" data-bs-backdrop="static" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('admin.masters.criterias.update', $criteria->id_criteria) }}" method="POST" enctype="multipart/form-data">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Ubah Kriteria ({{ $criteria->id_criteria }})</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        @csrf @method('PUT')
                        <div class="row justify-content-center g-4">
                            <div class="col-md-7">
                                <div class="position-sticky" style="top: 2rem;">
                                    @if (Session::get('modal_redirect') == 'modal-crt-update')
                                    @include('Templates.Includes.Components.alert')
                                    @endif
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Nama Kriteria</label>
                                        <input type="text" class="form-control" id="name" name="name" value="{{ $criteria->name }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="weight" class="form-label">Bobot</label>
                                        <input type="text" class="form-control" id="weight" name="weight" value="{{ $criteria->weight*100 }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="attribute" class="form-label">Atribut</label>
                                        <select class="form-select" id="attribute" name="attribute" required>
                                            <option selected disabled value="">---Pilih Atribut---</option>
                                            <option value="Benefit" {{ $criteria->attribute == 'Benefit' ? 'selected' : null }}>Benefit</option>
                                            <option value="Cost" {{ $criteria->attribute == 'Cost' ? 'selected' : null }}>Cost</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="level" class="form-label">Tingkat Kepentingan</label>
                                        <select class="form-select" id="level" name="level" required>
                                            <option selected disabled value="">---Pilih Atribut---</option>
                                            <option value="1" {{ $criteria->level == '1' ? 'selected' : null }}>1. Sama Penting</option>
                                            <option value="3" {{ $criteria->level == '3' ? 'selected' : null }}>3. Cukup Penting</option>
                                            <option value="5" {{ $criteria->level == '5' ? 'selected' : null }}>5. Lebih Penting</option>
                                            <option value="7" {{ $criteria->level == '7' ? 'selected' : null }}>7. Sangat Lebih Penting</option>
                                            <option value="9" {{ $criteria->level == '9' ? 'selected' : null }}>9. Mutlak Lebih Penting</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="max" class="form-label">Maksimum Nilai Asli</label>
                                        <input type="number" class="form-control" id="max" name="max" value="{{ $criteria->max }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="need" class="form-label">Apakah Dibutuhkan untuk Proses Karyawan Terbaik?</label>
                                        <select class="form-select" id="need" name="need" required>
                                            <option selected disabled value="">---Pilih---</option>
                                            <option value="Ya" {{ $criteria->need == 'Ya' ? 'selected' : null }}>Ya</option>
                                            <option value="Tidak" {{ $criteria->need == 'Tidak' ? 'selected' : null }}>Tidak</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="source" class="form-label">Sumber Kolom (Untuk Import)</label>
                                        <input type="text" class="form-control" id="source" name="source" value="{{ $criteria->source }}" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="position-sticky" style="top: 2rem;">
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
    <!--DELETE CRITERIA-->
    <div class="modal fade" id="modal-crt-delete-{{ $criteria->id_criteria }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('admin.masters.criterias.destroy', $criteria->id_criteria) }}" method="POST" enctype="multipart/form-data">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Hapus Kriteria ({{ $criteria->id_criteria}})</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-warning" role="alert">
                            <i class="bi bi-exclamation-triangle-fill"></i> <b>PERHATIAN</b>
                            <br/>
                            Apakah anda ingin menghapus Kriteria?
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="bi bi-x-lg"></i>
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
    <!--VIEW CRIPS-->
    <div class="modal fade" id="modal-crp-view-{{ $criteria->id_criteria }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Daftar Data Crips ({{ $criteria->id_criteria }})</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
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
    <div class="modal fade" id="modal-crp-create-{{ $criteria->id_criteria }}" data-bs-backdrop="static" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('admin.masters.crips.store') }}" method="POST" enctype="multipart/form-data" id="form-crt-create">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Tambah Data Crips ({{ $criteria->id_criteria }})</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="close-crt-create"></button>
                    </div>
                    <div class="modal-body">
                        @if (Session::get('modal_redirect') == 'modal-crp-create')
                        @include('Templates.Includes.Components.alert')
                        @endif
                        @csrf
                        <div class="mb-3">
                            <label for="id_criteria" class="form-label">Kode Kriteria</label>
                            <input type="text" class="form-control" id="id_criteria" name="id_criteria" value="{{ $criteria->id_criteria }}" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="name" class="form-label">Nama Pilihan</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-5">
                                <label for="value_type" class="form-label">Tipe Range</label>
                                <select class="form-select" id="value_type" name="value_type" required>
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
                                    <input type="number" id="value_to" name="value_to" aria-label="Akhir Angka" class="form-control" min="0">
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="score" class="form-label">Nilai Konversi</label>
                            <input type="number" class="form-control" id="score" name="score" min="1" max="5" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#modal-crp-view-{{ $criteria->id_criteria }}">
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
        @foreach ($crips->where('id_criteria', $criteria->id_criteria) as $crip)
        <!--UPDATE CRIPS-->
        <div class="modal fade" id="modal-crp-update-{{ $crip->id_crips }}" data-bs-backdrop="static" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{ route('admin.masters.crips.update', $crip->id_crips) }}" method="POST" enctype="multipart/form-data">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="exampleModalLabel">Ubah Data Crips ({{ $crip->id_crips }})</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            @if (Session::get('modal_redirect') == 'modal-crp-update')
                            @include('Templates.Includes.Components.alert')
                            @endif
                            @csrf @method('PUT')
                            <div class="mb-3">
                                <label for="name" class="form-label">Nama Pilihan</label>
                                <input type="text" class="form-control" id="name" name="name" value="{{ $crip->name }}" required>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-5">
                                    <label for="value_type" class="form-label">Tipe Range</label>
                                    <select class="form-select" id="value_type" name="value_type" required>
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
                                        <input type="number" id="value_to" name="value_to" aria-label="Akhir Angka" class="form-control" value="{{ $crip->value_to }}" min="0">
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="score" class="form-label">Nilai Konversi</label>
                                <input type="number" class="form-control" id="score" name="score" min="1" max="5" value="{{ $crip->score }}" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#modal-crp-view-{{ $criteria->id_criteria }}">
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
        <!--DELETE CRIPS-->
        <div class="modal fade" id="modal-crp-delete-{{ $crip->id_crips }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{ route('admin.masters.crips.destroy', $crip->id_crips) }}" method="POST" enctype="multipart/form-data">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="exampleModalLabel">Hapus Data Crips ({{ $crip->id_crips}})</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="alert alert-warning" role="alert">
                                <i class="bi bi-exclamation-triangle-fill"></i> <b>PERHATIAN</b>
                                <br/>
                                Apakah anda ingin menghapus Data Crips?
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#modal-crp-view-{{ $criteria->id_criteria }}">
                                <i class="bi bi-x-lg"></i>
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
@endforeach
