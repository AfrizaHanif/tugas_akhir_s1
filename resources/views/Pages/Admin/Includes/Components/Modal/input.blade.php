@if ((!empty($latest_per)))
<!--IMPORT DATA-->
<div class="modal modal-lg fade" id="modal-inp-import-{{ $latest_per->id_period }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Import Data ({{ $latest_per->name }})</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="form-inp-import-{{ $latest_per->id_period }}" action="{{ route('admin.inputs.data.import', $latest_per->id_period) }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="row justify-content-center g-4">
                        <div class="col-md-7">
                            <div class="position-sticky" style="top: 0rem;">
                                <ul class="nav nav-tabs" id="myTab" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link active" id="upload-{{ $latest_per->id_period }}-tab" data-bs-toggle="tab" data-bs-target="#upload-{{ $latest_per->id_period }}-tab-pane" type="button" role="tab" aria-controls="upload-{{ $latest_per->id_period }}-tab-pane" aria-selected="true">Upload File</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="column-{{ $latest_per->id_period }}-tab" data-bs-toggle="tab" data-bs-target="#column-{{ $latest_per->id_period }}-tab-pane" type="button" role="tab" aria-controls="column-{{ $latest_per->id_period }}-tab-pane" aria-selected="false">Sumber Kolom</button>
                                    </li>
                                </ul>
                                <div class="tab-content pt-2" id="myTabContent">
                                    <div class="tab-pane fade show active" id="upload-{{ $latest_per->id_period }}-tab-pane" role="tabpanel" aria-labelledby="upload-{{ $latest_per->id_period }}-tab" tabindex="0">
                                        <div class="alert alert-warning" role="alert">
                                            <i class="bi bi-exclamation-triangle-fill"></i> <strong>PERHATIAN</strong>
                                            <ol>
                                                <li>Baca <b>Cara Import</b> sebelum melakukan import</li>
                                                <li>Data yang telah terinput akan <b>dihapus</b> saat proses import berlangsung</li>
                                                <li><b>Tutup file yang akan di import.</b> Jika dibiarkan dibuka, akan terjadi error dari browser saat import</li>
                                            </ol>
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
                            <div class="position-sticky" style="top: 0rem;">
                                <div class="alert alert-info" role="alert">
                                    <i class="bi bi-info-circle-fill"></i> <strong>CARA IMPORT</strong>
                                    <ol>
                                        <li>Sebelum melakukan import, buatlah file Excel baru, dan pastikan file tersebut memenuhi ketentuan berikut:</li>
                                        <ol>
                                            <li>Wajib ada <b>NIK</b> agar dapat memasukkan data sesuai dengan data pegawai. Anda juga dapat menambahkan kolom <b>Nama Pegawai</b> sebagai pelengkap.</li>
                                            <li>Samakan nama kolom dengan ketentuan yang diberikan di tab <b>Sumber Kolom</b> (Contoh: Jika ingin import <b>Presensi</b>, maka kolom yang harus ada adalah kriteria dari sumber <b>Presensi</b>).</li>
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
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-lg"></i>
                    Tutup
                </button>
                <button type="submit" form="form-inp-import-{{ $latest_per->id_period }}" class="btn btn-primary">
                    <i class="bi bi-upload"></i>
                    Import
                </button>
            </div>
        </div>
    </div>
</div>
<!--DELETE ALL INPUT-->
<div class="modal fade" id="modal-inp-delete-{{ $latest_per->id_period }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Hapus Data Semua Input ({{ $latest_per->name}})</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="form-inp-delete-{{ $latest_per->id_period }}" action="{{ route('admin.inputs.data.destroyall', $latest_per->id_period) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="alert alert-warning" role="alert">
                        <i class="bi bi-exclamation-triangle-fill"></i> <b>PERHATIAN</b>
                        <br/>
                        Apakah anda ingin menghapus seluruh data nilai yang ada?
                        <ul>
                            <li>Data input yang telah terdaftar akan dihapus.</li>
                            <li>Segera lakukan lakukan <strong>Import Ulang</strong> setelah melakukan proses ini.</li>
                        </ul>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-lg"></i>
                    Tidak
                </button>
                <button type="submit" form="form-inp-delete-{{ $latest_per->id_period }}" class="btn btn-danger">
                    <i class="bi bi-check-lg"></i>
                    Ya
                </button>
            </div>
        </div>
    </div>
</div>
<!--EXPORT DATA-->
<div class="modal modal-sheet p-4 py-md-5 fade" tabindex="-1" role="dialog" id="modal-inp-export-{{ $latest_per->id_period }}">
    <div class="modal-dialog" role="document">
        <div class="modal-content rounded-4 shadow">
            <form action="{{ route('admin.inputs.data.export.latest') }}" method="post" enctype="multipart/form-data">
                <div class="modal-header border-bottom-0">
                    <h1 class="modal-title fs-5">Export Input Data ({{ $latest_per->name }})</h1>
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
<!--CONVERT DATA-->
<div class="modal fade" id="modal-inp-convert-{{ $latest_per->id_period }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Konversi Data ({{ $latest_per->name}})</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="form-inp-convert-{{ $latest_per->id_period }}" action="{{ route('admin.inputs.data.convert', $latest_per->id_period) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="alert alert-warning" role="alert">
                        <i class="bi bi-exclamation-triangle-fill"></i> <b>PERHATIAN</b>
                        <br/>
                        Apakah anda ingin melakukan konversi penilaian?
                        <ul>
                            <li>Pastikan data yang telah terdaftar sudah benar.</li>
                            <li>Jika tidak, periksa kembali data yang ada.</li>
                        </ul>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-lg"></i>
                    Tidak
                </button>
                <button type="submit" form="form-inp-convert-{{ $latest_per->id_period }}" class="btn btn-primary">
                    <i class="bi bi-check-lg"></i>
                    Ya
                </button>
            </div>
        </div>
    </div>
</div>
<!--CHOICE METHODS (REFRESH)-->
<div class="modal modal-sheet p-4 py-md-5 fade" tabindex="-1" role="dialog" id="modal-inp-refresh-{{ $latest_per->id_period }}">
    <div class="modal-dialog" role="document">
        <div class="modal-content rounded-4 shadow">
            <div class="modal-header border-bottom-0">
                <h1 class="modal-title fs-5">Pilih Metode Refresh</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-footer flex-column align-items-stretch w-100 gap-2 pb-3 border-top-0">
                <button type="button" class="btn btn-lg btn-warning" data-bs-toggle="modal" data-bs-target="#modal-inp-refresh-convert-{{ $latest_per->id_period }}">Convert Ulang (Refresh)</button>
                <button type="button" class="btn btn-lg btn-danger" data-bs-toggle="modal" data-bs-target="#modal-inp-refresh-reset-{{ $latest_per->id_period }}">Reset ke Nilai Asli</button>
                <button type="button" class="btn btn-lg btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
<!--REFRESH DATA-->
<div class="modal fade" id="modal-inp-refresh-convert-{{ $latest_per->id_period }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Refresh Data ({{ $latest_per->name}})</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="form-inp-refresh-convert-{{ $latest_per->id_period }}" action="{{ route('admin.inputs.data.refresh', $latest_per->id_period) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="alert alert-warning" role="alert">
                        <i class="bi bi-exclamation-triangle-fill"></i> <b>PERHATIAN</b>
                        <br/>
                        Apakah anda ingin melakukan refresh data nilai?
                        <ul>
                            <li>Gunakan fitur ini jika anda telah mengubah <strong>Data Crips</strong>.</li>
                            <li>Hasil konversi akan dihitung ulang setelah melakukan refresh nilai.</li>
                        </ul>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-lg"></i>
                    Tidak
                </button>
                <button type="submit" form="form-inp-refresh-convert-{{ $latest_per->id_period }}" class="btn btn-primary">
                    <i class="bi bi-check-lg"></i>
                    Ya
                </button>
            </div>
        </div>
    </div>
</div>
<!--RESET DATA-->
<div class="modal fade" id="modal-inp-refresh-reset-{{ $latest_per->id_period }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Reset Data ({{ $latest_per->name}})</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="form-inp-refresh-reset-{{ $latest_per->id_period }}" action="{{ route('admin.inputs.data.reset', $latest_per->id_period) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="alert alert-warning" role="alert">
                        <i class="bi bi-exclamation-triangle-fill"></i> <b>PERHATIAN</b>
                        <br/>
                        Apakah anda ingin melakukan reset data nilai?
                        <ul>
                            <li>Hasil konversi akan hilang setelah melakukan reset nilai.</li>
                            <li>Segera lakukan <strong>Konversi Ulang</strong> setelah melakukan proses ini.</li>
                        </ul>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-lg"></i>
                    Tidak
                </button>
                <button type="submit" form="form-inp-refresh-reset-{{ $latest_per->id_period }}" class="btn btn-danger">
                    <i class="bi bi-check-lg"></i>
                    Ya
                </button>
            </div>
        </div>
    </div>
</div>
@endif

@foreach ($periods as $period)
<!--CURRENT PERIOD-->
@endforeach

<!--EXPORT ALL OLD DATA-->
<div class="modal modal-sheet p-4 py-md-5 fade" tabindex="-1" role="dialog" id="modal-old-inp-export-all">
    <div class="modal-dialog" role="document">
        <div class="modal-content rounded-4 shadow">
            <form action="{{ route('admin.inputs.data.export.all') }}" method="post" enctype="multipart/form-data">
                <div class="modal-header border-bottom-0">
                    <h1 class="modal-title fs-5">Export Input Data (Semua Periode)</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body py-0">
                    <p>Proses ini akan mengunduh Data Input dari semua periode ke komputer anda.</p>
                </div>
                <div class="modal-footer flex-column align-items-stretch w-100 gap-2 pb-3 border-top-0">
                    @csrf
                    <button type="submit" class="btn btn-lg btn-primary" id="exportToastBtn-all">Export Data</button>
                    <button type="button" class="btn btn-lg btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </form>
        </div>
    </div>
</div>

@foreach ($history_per as $hperiod)
<!--EXPORT OLD DATA-->
<div class="modal modal-sheet p-4 py-md-5 fade" tabindex="-1" role="dialog" id="modal-old-inp-export-{{ $hperiod->id_period }}">
    <div class="modal-dialog" role="document">
        <div class="modal-content rounded-4 shadow">
            <form action="{{ route('admin.inputs.data.export.old', $hperiod->id_period) }}" method="post" enctype="multipart/form-data">
                <div class="modal-header border-bottom-0">
                    <h1 class="modal-title fs-5">Export Input Data ({{ $hperiod->period_name }})</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body py-0">
                    <p>Proses ini akan mengunduh Data Input ke komputer anda.</p>
                </div>
                <div class="modal-footer flex-column align-items-stretch w-100 gap-2 pb-3 border-top-0">
                    @csrf
                    <button type="submit" class="btn btn-lg btn-primary" id="exportToastBtn-{{ $hperiod->id_period }}">Export Data</button>
                    <button type="button" class="btn btn-lg btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach
