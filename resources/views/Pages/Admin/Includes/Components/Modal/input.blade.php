@if ((!empty($latest_per)))
<!--IMPORT DATA-->
<div class="modal modal-lg fade" id="modal-inp-import-{{ $latest_per->id_period }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.inputs.data.import', $latest_per->id_period) }}" method="post" enctype="multipart/form-data">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Import Data ({{ $latest_per->name }})</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @csrf
                    <div class="row justify-content-center g-4">
                        <div class="col-md-7">
                            <div class="position-sticky" style="top: 2rem;">
                                <ul class="nav nav-tabs" id="myTab" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link active" id="upload-{{ $latest_per->id_period }}-tab" data-bs-toggle="tab" data-bs-target="#upload-{{ $latest_per->id_period }}-tab-pane" type="button" role="tab" aria-controls="upload-{{ $latest_per->id_period }}-tab-pane" aria-selected="true">Upload File</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="column-{{ $latest_per->id_period }}-tab" data-bs-toggle="tab" data-bs-target="#column-{{ $latest_per->id_period }}-tab-pane" type="button" role="tab" aria-controls="column-{{ $latest_per->id_period }}-tab-pane" aria-selected="false">Daftar Sumber Kolom</button>
                                    </li>
                                </ul>
                                <div class="tab-content pt-2" id="myTabContent">
                                    <div class="tab-pane fade show active" id="upload-{{ $latest_per->id_period }}-tab-pane" role="tabpanel" aria-labelledby="upload-{{ $latest_per->id_period }}-tab" tabindex="0">
                                        <div class="alert alert-warning" role="alert">
                                            <i class="bi bi-exclamation-triangle-fill"></i> <strong>WARNING</strong>
                                            <br/>
                                            1. Baca <b>Cara Import</b> sebelum melakukan import<br/>
                                            2. Data yang telah terinput akan <b>dihapus</b> saat proses import berlangsung<br/>
                                            3. <b>Tutup file yang akan di import.</b> Jika dibiarkan dibuka, akan terjadi error dari browser saat import
                                        </div>
                                        <div class="mb-3">
                                            <label for="file" class="form-label">File Upload</label>
                                            <input class="form-control" type="file" id="file" name="file[]" multiple required>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="column-{{ $latest_per->id_period }}-tab-pane" role="tabpanel" aria-labelledby="column-{{ $latest_per->id_period }}-tab" tabindex="0">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th class="col-1" scope="col">#</th>
                                                    <th scope="col">Sumber</th>
                                                    <th scope="col">Nama Kriteria</th>
                                                    <th scope="col">Kolom di Excel</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse ($criterias as $criteria)
                                                <tr>
                                                    <th scope="row">{{ $loop->iteration }}</th>
                                                    <td>{{ $criteria->category->source }}</td>
                                                    <td>{{ $criteria->name }}</td>
                                                    <td>{{ $criteria->source }}</td>
                                                </tr>
                                                @empty
                                                <tr>
                                                    <td colspan="10">Tidak ada Kriteria yang terdaftar</td>
                                                </tr>
                                                @endforelse
                                            </tbody>
                                            <tfoot class="table-group-divider">
                                                <tr>
                                                    <td colspan="7">Total Data: <b>{{ $criterias->count() }}</b> Kriteria</td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="position-sticky" style="top: 2rem;">
                                <div class="alert alert-info" role="alert">
                                    <i class="bi bi-info-circle-fill"></i> <strong>CARA IMPORT</strong>
                                    <ol>
                                        <li>Sebelum melakukan import, buatlah file Excel baru, dan pastikan file tersebut memenuhi ketentuan berikut:</li>
                                        <ol>
                                            <li>Wajib ada <b>NIK</b> agar dapat memasukkan data sesuai dengan data pegawai. Anda juga dapat menambahkan kolom <b>Nama Pegawai</b> sebagai pelengkap.</li>
                                            <li>Samakan dengan ketentuan yang diberikan di tab <b>Datar Sumber Kolom</b> (Contoh: Jika ingin import <b>presensi</b>, maka kolom yang harus ada adalah kriteria dari sumber <b>Presensi</b>).</li>
                                        </ol>
                                        <li>Pilih file yang akan di import ke dalam sistem. Pastikan file tersebut mengandung nama seperti berikut:</li>
                                        <ol>
                                            <li>Bulan dan Tahun <b>(Lengkap tanpa tanggal)</b></li>
                                            <li>Untuk Presensi: <b>Presensi</b></li>
                                            <li>Untuk SKP: <b>SKP</b></li>
                                            <li>Untuk CKP: <b>CKP</b></li>
                                            <li>Ekstensi Excel 2007: <b>(.xlsx)</b></li>
                                            <li>Contoh nama File: CKP Januari 2024.xlsx</li>
                                        </ol>
                                        <li>Perlu diingat bahwa angka yang ada di file akan <b>dikonversi</b> sesuai dengan <b>data crips</b> di setiap kriteria yang ada. Cek <b>Kelola Crips</b> di setiap kriteria pada halaman <b>Kriteria</b> untuk mengetahui range-range yang ada pada setiap kriteria.</li>
                                        <li>Setelah melakukan import, pastikan anda memeriksa data apakah sudah masuk atau belum.</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-lg"></i>
                        Tutup
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-upload"></i>
                        Import
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<!--DELETE ALL INPUT-->
<div class="modal fade" id="modal-inp-delete-{{ $latest_per->id_period }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.inputs.data.destroyall', $latest_per->id_period) }}" method="POST" enctype="multipart/form-data">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Hapus Data Semua Input ({{ $latest_per->id_period}})</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning" role="alert">
                        <i class="bi bi-exclamation-triangle-fill"></i> <b>PERHATIAN</b>
                        <br/>
                        Apakah anda ingin menghapus seluruh data tersebut? Ini akan berpengaruh dengan total penilaian.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-backspace"></i>
                        Tidak
                    </button>
                    @csrf
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-check-lg"></i>
                        Ya
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<!--EXPORT DATA-->
<div class="modal modal-sheet p-4 py-md-5 fade" tabindex="-1" role="dialog" id="modal-inp-export-{{ $latest_per->id_period }}">
    <div class="modal-dialog" role="document">
        <div class="modal-content rounded-4 shadow">
            <form action="{{ route('admin.inputs.data.export.latest') }}" method="post" enctype="multipart/form-data">
                <div class="modal-header border-bottom-0">
                    <h1 class="modal-title fs-5">Export Input Data ({{ $latest_per->id_period }})</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body py-0">
                    <p>Proses ini akan mengunduh Data Input ke komputer anda. Mohon perhatian bahwa file hasil export tidak dapat dilakukan Import kembali.</p>
                </div>
                <div class="modal-footer flex-column align-items-stretch w-100 gap-2 pb-3 border-top-0">
                    @csrf
                    <button type="submit" class="btn btn-lg btn-primary" id="exportToastBtn-{{ $latest_per->id_period }}">Export Data</button>
                    <button type="button" class="btn btn-lg btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </form>
        </div>
    </div>
</div>
@foreach ($officers as $officer)
<!--CHOICE METHODS (INPUT)-->
<div class="modal modal-sheet p-4 py-md-5 fade" tabindex="-1" role="dialog" id="modal-inp-precre-{{ $latest_per->id_period }}-{{ $officer->id_officer }}">
    <div class="modal-dialog" role="document">
        <div class="modal-content rounded-4 shadow">
            <div class="modal-header border-bottom-0">
                <h1 class="modal-title fs-5">Pilih Metode Input</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-0">
                <p>Untuk mempercepat pengisian nilai, disarankan menggunakan Import dari Excel untuk memudahkan anda saat mengisi nilai.</p>
            </div>
            <div class="modal-footer flex-column align-items-stretch w-100 gap-2 pb-3 border-top-0">
                @if (!empty($latest_per))
                <button type="button" class="btn btn-lg btn-primary" data-bs-toggle="modal" data-bs-target="#modal-inp-import-{{ $latest_per->id_period }}">Import dari Excel (Disarankan)</button>
                <button type="button" class="btn btn-lg btn-primary" data-bs-toggle="modal" data-bs-target="#modal-inp-create-{{ $latest_per->id_period }}-{{ $officer->id_officer }}">Isi Manual</button>
                @endif
                <button type="button" class="btn btn-lg btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!--CHOICE METHODS (UPDATE)-->
<div class="modal modal-sheet p-4 py-md-5 fade" tabindex="-1" role="dialog" id="modal-inp-preupd-{{ $latest_per->id_period }}-{{ $officer->id_officer }}">
    <div class="modal-dialog" role="document">
        <div class="modal-content rounded-4 shadow">
            <div class="modal-header border-bottom-0">
                <h1 class="modal-title fs-5">Pilih Metode Update</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-0">
                <p>Untuk mempercepat update nilai, disarankan menggunakan Import dari Excel untuk memudahkan anda saat mengupdate nilai.</p>
            </div>
            <div class="modal-footer flex-column align-items-stretch w-100 gap-2 pb-3 border-top-0">
                @if (!empty($latest_per))
                <button type="button" class="btn btn-lg btn-primary" data-bs-toggle="modal" data-bs-target="#modal-inp-import-{{ $latest_per->id_period }}">Import dari Excel (Disarankan)</button>
                <button type="button" class="btn btn-lg btn-primary" data-bs-toggle="modal" data-bs-target="#modal-inp-update-{{ $latest_per->id_period }}-{{ $officer->id_officer }}">Update Manual</button>
                @endif
                <button type="button" class="btn btn-lg btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!--INSERT INPUT-->
<div class="modal modal-lg fade" id="modal-inp-create-{{ $latest_per->id_period }}-{{ $officer->id_officer }}" data-bs-backdrop="static" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.inputs.data.store') }}" method="POST" enctype="multipart/form-data" id="form-inp-create-{{ $latest_per->id_period }}-{{ $officer->id_officer }}">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Tambah Data Input ({{ $officer->name }})</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="close-inp-create-{{ $latest_per->id_period }}-{{ $officer->id_officer }}"></button>
                </div>
                <div class="modal-body">
                    @csrf
                    <div class="row justify-content-center g-4">
                        <div class="col-md-7">
                            <div class="position-sticky" style="top: 2rem;">
                                <div class="row mb-3">
                                    <div class="col">
                                        <label for="id_officer" class="form-label">Kode Pegawai</label>
                                        <input type="text" class="form-control" id="id_officer" name="id_officer" value="{{ $officer->id_officer }}" readonly>
                                    </div>
                                    <div class="col">
                                        <label for="id_period" class="form-label">Kode Periode</label>
                                        <input type="text" class="form-control" id="id_period" name="id_period" value="{{ $latest_per->id_period }}" readonly>
                                    </div>
                                </div>
                                <hr/>
                                <ul class="nav nav-pills nav-fill" id="myTab" role="tablist">
                                    @foreach ($categories as $category)
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link {{ $loop->first ? 'active' : '' }}" id="input-create-{{ $officer->id_officer }}-{{ $category->id_category }}-tab" data-bs-toggle="tab" data-bs-target="#input-create-{{ $officer->id_officer }}-{{ $category->id_category }}-tab-pane" type="button" role="tab" aria-controls="input-create-{{ $officer->id_officer }}-{{ $category->id_category }}-tab-pane" aria-selected="{{ $loop->first ? 'true' : 'false' }}">{{ Str::limit($category->name, 15) }}</button>
                                    </li>
                                    @endforeach
                                </ul>
                                <div class="tab-content" id="myTabContent">
                                    @foreach ($categories as $category)
                                    <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }} pt-2" id="input-create-{{ $officer->id_officer }}-{{ $category->id_category }}-tab-pane" role="tabpanel" aria-labelledby="input-create-{{ $officer->id_officer }}-{{ $category->id_category }}-tab" tabindex="0">
                                        @forelse ($criterias->where('id_category', $category->id_category) as $criteria)
                                        <div class="mb-3">
                                            <label for="{{ $criteria->id_criteria }}" class="form-label">{{ $criteria->name }}</label>
                                            <select class="form-select" id="{{ $criteria->id_criteria }}" name="{{ $criteria->id_criteria }}" required>
                                                <option selected disabled value="">---Pilih Pilihan---</option>
                                                @foreach ($crips->where('id_criteria', $criteria->id_criteria) as $crip)
                                                <option value="{{ $crip->score }}" {{ old($criteria->id_criteria) ==  $crip->id_criteria ? 'selected' : null }}>
                                                    {{ $crip->score }}. {{ $crip->name }}
                                                    @if($crip->value_type == 'Less')
                                                    (Range: 0 - {{ $crip->value_from }})
                                                    @elseif($crip->value_type == 'More')
                                                    (Range: {{ $crip->value_from }} - {{ $criteria->max }})
                                                    @else
                                                    (Range: {{ $crip->value_from }} - {{ $crip->value_to}})
                                                    @endif
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @empty
                                        <div class="alert alert-danger" role="alert">
                                            Tidak ada data sub kriteria
                                        </div>
                                        @endforelse
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="position-sticky" style="top: 2rem;">
                                <div class="alert alert-info" role="alert">
                                    <i class="bi bi-info-circle-fill"></i> <strong>CARA PENGISIAN</strong>
                                    <ol>
                                        <li>Pengisian secara manual hanya dilakukan jika proses import tidak dapat berjalan</li>
                                        <li>Isi sesuai dengan data yang ada di aplikasi</li>
                                        <ol>
                                            <li>BackOffice: Presensi dan CKP</li>
                                            <li>KipApp: BerAkhlak</li>
                                        </ol>
                                        <li>Periksa kembali hasil input anda sebelum dimasukkan ke dalam sistem</li>
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
<!--UPDATE INPUT-->
<div class="modal modal-lg fade" id="modal-inp-update-{{ $latest_per->id_period }}-{{ $officer->id_officer }}" data-bs-backdrop="static" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.inputs.data.update', $officer->id_officer) }}" method="POST" enctype="multipart/form-data">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Ubah Data Input ({{ $officer->id_officer }})</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @csrf @method('PUT')
                    <div class="row justify-content-center g-4">
                        <div class="col-md-7">
                            <div class="position-sticky" style="top: 2rem;">
                                <div class="row mb-3">
                                    <div class="col">
                                        <label for="id_officer" class="form-label">Kode Pegawai</label>
                                        <input type="text" class="form-control" id="id_officer" name="id_officer" value="{{ $officer->id_officer }}" readonly>
                                    </div>
                                    <div class="col">
                                        <label for="id_period" class="form-label">Kode Periode</label>
                                        <input type="text" class="form-control" id="id_period" name="id_period" value="{{ $latest_per->id_period }}" readonly>
                                    </div>
                                </div>
                                <hr/>
                                <ul class="nav nav-pills nav-fill" id="myTab" role="tablist">
                                    @foreach ($categories as $category)
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link {{ $loop->first ? 'active' : '' }}" id="input-update-{{ $officer->id_officer }}-{{ $category->id_category }}-tab" data-bs-toggle="tab" data-bs-target="#input-update-{{ $officer->id_officer }}-{{ $category->id_category }}-tab-pane" type="button" role="tab" aria-controls="input-update-{{ $officer->id_officer }}-{{ $category->id_category }}-tab-pane" aria-selected="{{ $loop->first ? 'true' : 'false' }}">{{ Str::limit($category->name, 15) }}</button>
                                    </li>
                                    @endforeach
                                </ul>
                                <div class="tab-content" id="myTabContent">
                                    @foreach ($categories as $category)
                                    <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }} pt-2" id="input-update-{{ $officer->id_officer }}-{{ $category->id_category }}-tab-pane" role="tabpanel" aria-labelledby="input-update-{{ $officer->id_officer }}-{{ $category->id_category }}-tab" tabindex="0">
                                        @forelse ($criterias->where('id_category', $category->id_category) as $criteria)
                                            @forelse ($inputs->where('id_criteria', $criteria->id_criteria)->where('id_officer', $officer->id_officer)->where('id_period', $latest_per->id_period) as $input)
                                            <div class="mb-3">
                                                <label for="{{ $criteria->id_criteria }}" class="form-label">{{ $criteria->name }}</label>
                                                <select class="form-select" id="{{ $criteria->id_criteria }}" name="{{ $criteria->id_criteria }}" required>
                                                    <option selected disabled value="">---Pilih Pilihan---</option>
                                                    @foreach ($crips->where('id_criteria', $criteria->id_criteria) as $crip)
                                                    <option value="{{ $crip->score }}" {{ $input->input ==  $crip->score ? 'selected' : null }}>
                                                        {{ $crip->score }}. {{ $crip->name }}
                                                        @if($crip->value_type == 'Less')
                                                        (Range: 0 - {{ $crip->value_from }})
                                                        @elseif($crip->value_type == 'More')
                                                        (Range: {{ $crip->value_from }} - {{ $criteria->max }})
                                                        @else
                                                        (Range: {{ $crip->value_from }} - {{ $crip->value_to}})
                                                        @endif
                                                    </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            @empty
                                            <div class="mb-3">
                                                <label for="{{ $criteria->id_criteria }}" class="form-label">{{ $criteria->name }}</label>
                                                <select class="form-select" id="{{ $criteria->id_criteria }}" name="{{ $criteria->id_criteria }}" required>
                                                    <option selected disabled value="">---Pilih Pilihan---</option>
                                                    @foreach ($crips->where('id_criteria', $criteria->id_criteria) as $crip)
                                                    <option value="{{ $crip->score }}" {{ old($criteria->id_criteria) ==  $crip->id_criteria ? 'selected' : null }}>
                                                        {{ $crip->score }}. {{ $crip->name }}
                                                        @if($crip->value_type == 'Less')
                                                        (Range: 0 - {{ $crip->value_from }})
                                                        @elseif($crip->value_type == 'More')
                                                        (Range: {{ $crip->value_from }} - {{ $criteria->max }})
                                                        @else
                                                        (Range: {{ $crip->value_from }} - {{ $crip->value_to}})
                                                        @endif
                                                    </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            @endforelse
                                        @empty
                                        <div class="alert alert-danger" role="alert">
                                            Tidak ada data sub kriteria
                                        </div>
                                        @endforelse
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="position-sticky" style="top: 2rem;">
                                <div class="alert alert-info" role="alert">
                                    <i class="bi bi-info-circle-fill"></i> <strong>CARA PENGISIAN</strong>
                                    <ol>
                                        <li>Pengisian secara manual hanya dilakukan jika proses import tidak dapat berjalan</li>
                                        <li>Isi sesuai dengan data yang ada di aplikasi</li>
                                        <ol>
                                            <li>BackOffice: Presensi dan CKP</li>
                                            <li>KipApp: BerAkhlak</li>
                                        </ol>
                                        <li>Periksa kembali hasil input anda sebelum dimasukkan ke dalam sistem</li>
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
<!--DELETE INPUT-->
<div class="modal fade" id="modal-inp-delete-{{ $latest_per->id_period }}-{{ $officer->id_officer }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.inputs.data.destroy', $officer->id_officer) }}" method="POST" enctype="multipart/form-data">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Hapus Data Input ({{ $officer->id_officer}})</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col">
                            <label for="id_officer" class="form-label">Kode Pegawai</label>
                            <input type="text" class="form-control" id="id_officer" name="id_officer" value="{{ $officer->id_officer }}" readonly>
                        </div>
                        <div class="col">
                            <label for="id_period" class="form-label">Kode Periode</label>
                            <input type="text" class="form-control" id="id_period" name="id_period" value="{{ $latest_per->id_period }}" readonly>
                        </div>
                    </div>
                    <hr/>
                    <div class="alert alert-warning" role="alert">
                        <i class="bi bi-exclamation-triangle-fill"></i> <b>PERHATIAN</b>
                        <br/>
                        Apakah anda ingin menghapus data tersebut? Ini akan berpengaruh dengan total penilaian.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-backspace"></i>
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
<!--CONVERT DATA-->
<div class="modal fade" id="modal-inp-convert-{{ $latest_per->id_period }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.inputs.data.convert', $latest_per->id_period) }}" method="POST" enctype="multipart/form-data">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Konversi Data ({{ $latest_per->id_period}})</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning" role="alert">
                        <i class="bi bi-exclamation-triangle-fill"></i> <b>PERHATIAN</b>
                        <br/>
                        Apakah anda ingin melakukan konversi penilaian? Pastikan data yang telah diimport sudah benar. Jika tidak, periksa kembali data yang ada.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-backspace"></i>
                        Tidak
                    </button>
                    @csrf
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-lg"></i>
                        Ya
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<!--REFRESH DATA-->
<div class="modal fade" id="modal-inp-refresh-{{ $latest_per->id_period }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.inputs.data.refresh', $latest_per->id_period) }}" method="POST" enctype="multipart/form-data">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Refresh Data ({{ $latest_per->id_period}})</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning" role="alert">
                        <i class="bi bi-exclamation-triangle-fill"></i> <b>PERHATIAN</b>
                        <br/>
                        Apakah anda ingin melakukan refresh penilaian? Gunakan fitur ini setelah melakukan perubahan kriteria.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-backspace"></i>
                        Tidak
                    </button>
                    @csrf
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-lg"></i>
                        Ya
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

@foreach ($periods as $period)
<!--CURRENT PERIOD-->
@endforeach

@foreach ($history_per as $hperiod)
<!--EXPORT OLD DATA-->
<div class="modal modal-sheet p-4 py-md-5 fade" tabindex="-1" role="dialog" id="modal-old-inp-export-{{ $hperiod->id_period }}">
    <div class="modal-dialog" role="document">
        <div class="modal-content rounded-4 shadow">
            <form action="{{ route('admin.inputs.data.export.old', $period->id_period) }}" method="post" enctype="multipart/form-data">
                <div class="modal-header border-bottom-0">
                    <h1 class="modal-title fs-5">Export Input Data ({{ $period->id_period }})</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body py-0">
                    <p>Proses ini akan mengunduh Data Input ke komputer anda.</p>
                </div>
                <div class="modal-footer flex-column align-items-stretch w-100 gap-2 pb-3 border-top-0">
                    @csrf
                    <button type="submit" class="btn btn-lg btn-primary" id="exportToastBtn-{{ $period->id_period }}">Export Data</button>
                    <button type="button" class="btn btn-lg btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach
