@if (Request::is('admin/masters/officers') || Request::is('developer/masters/officers'))
<!--IMPORT PEGAWAI-->
<div class="modal modal-lg fade" id="modal-off-import" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Import Data Pegawai</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                @if (Auth::user()->part == 'Admin')
                <form id="form-off-import" action="{{ route('admin.masters.officers.import') }}" method="post" enctype="multipart/form-data">
                @elseif (Auth::user()->part == 'Dev')
                <form id="form-off-import" action="{{ route('developer.masters.officers.import') }}" method="post" enctype="multipart/form-data">
                @endif
                    @csrf
                    <div class="row justify-content-center g-4">
                        <div class="col-md-7">
                            <div class="position-sticky" style="top: 0rem;">
                                @if (Session::get('modal_redirect') == 'modal-off-import')
                                @include('Templates.Includes.Components.alert')
                                @endif
                                <ul class="nav nav-tabs" id="myTab" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link active" id="upload-tab" data-bs-toggle="tab" data-bs-target="#upload-tab-pane" type="button" role="tab" aria-controls="upload-tab-pane" aria-selected="true">Upload File</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="columns-tab" data-bs-toggle="tab" data-bs-target="#columns-tab-pane" type="button" role="tab" aria-controls="columns-tab-pane" aria-selected="false">Sumber Kolom</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="datas-tab" data-bs-toggle="tab" data-bs-target="#datas-tab-pane" type="button" role="tab" aria-controls="datas-tab-pane" aria-selected="false">Sumber Data</button>
                                    </li>
                                </ul>
                                <div class="tab-content pt-2" id="myTabContent">
                                    <div class="tab-pane fade show active" id="upload-tab-pane" role="tabpanel" aria-labelledby="upload-tab" tabindex="0">
                                        <div class="alert alert-warning" role="alert">
                                            <i class="bi bi-exclamation-triangle-fill"></i> <strong>PERHATIAN</strong>
                                            <ol>
                                                <li>Baca <b>Cara Import</b> sebelum melakukan import</li>
                                                <li><b>Tutup file yang akan di import.</b> Jika dibiarkan dibuka, akan terjadi error dari browser saat import</li>
                                            </ol>
                                        </div>
                                        <div class="mb-3">
                                            <label for="file" class="form-label">File Upload</label>
                                            <input class="form-control" type="file" id="file" name="file" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="import_method" class="form-label">Metode Import</label>
                                            <div class="list-group">
                                                <label class="list-group-item d-flex gap-2">
                                                    <input class="form-check-input flex-shrink-0" type="radio" name="import_method" id="import_method_upcreate" value="upcreate" checked>
                                                    <span>
                                                        Penambahan dan Pembaharuan Data
                                                        <small class="d-block text-body-secondary">
                                                            Metode ini akan melakukan penambahan (Jika tidak terdaftar di sistem ini.) dan perubahan data pegawai dan pengguna (Jika terdaftar di sistem ini).
                                                        </small>
                                                    </span>
                                                </label>
                                                <label class="list-group-item d-flex gap-2">
                                                    <input class="form-check-input flex-shrink-0" type="radio" name="import_method" id="import_method_reset" value="reset">
                                                    <span>
                                                        Reset Data Pegawai
                                                        <small class="d-block text-body-secondary">
                                                            <strong>PERHATIAN: </strong>Pilihan ini akan menghapus seluruh data Pegawai, Pengguna, dan Input sebelum melakukan import. Baca perhatian di <b>CARA IMPORT</b>.
                                                        </small>
                                                    </span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="columns-tab-pane" role="tabpanel" aria-labelledby="columns-tab" tabindex="0">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th class="col-1" scope="col">#</th>
                                                    <th scope="col">Nama</th>
                                                    <th scope="col">Kolom di Excel</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <th scope="row">0</th>
                                                    <td>NIP</td>
                                                    <td>nip</td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">1</th>
                                                    <td>Nama Pegawai</td>
                                                    <td>nama</td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">2</th>
                                                    <td>Jabatan</td>
                                                    <td>jabatan</td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">3</th>
                                                    <td>Tim</td>
                                                    <td>tim</td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">4</th>
                                                    <td>Tim Utama</td>
                                                    <td>subtim1</td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">5</th>
                                                    <td>Tim Cadangan</td>
                                                    <td>subtim2</td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">6</th>
                                                    <td>Tempat Lahir</td>
                                                    <td>tmplahir</td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">7</th>
                                                    <td>Tanggal Lahir</td>
                                                    <td>tgllahir</td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">8</th>
                                                    <td>Jenis Kelamin</td>
                                                    <td>jk</td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">9</th>
                                                    <td>Agama</td>
                                                    <td>agama</td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">10</th>
                                                    <td>Foto</td>
                                                    <td>foto</td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">11</th>
                                                    <td>Kepegawaian</td>
                                                    <td>is_hr</td>
                                                </tr>
                                            </tbody>
                                            <tfoot class="table-group-divider">
                                                <tr>
                                                    <td colspan="7">Sesuaikan nama kolom di Excel dengan yang diatas agar proses Import dapat berjalan dengan lancar.</td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                    <div class="tab-pane fade" id="datas-tab-pane" role="tabpanel" aria-labelledby="datas-tab" tabindex="0">
                                        <ul class="nav nav-pills" id="myTab" role="tablist">
                                            <li class="nav-item" role="presentation">
                                                <button class="nav-link active" id="positions-tab" data-bs-toggle="tab" data-bs-target="#positions-tab-pane" type="button" role="tab" aria-controls="positions-tab-pane" aria-selected="true">Jabatan</button>
                                            </li>
                                            <li class="nav-item" role="presentation">
                                                <button class="nav-link" id="teams-tab" data-bs-toggle="tab" data-bs-target="#teams-tab-pane" type="button" role="tab" aria-controls="teams-tab-pane" aria-selected="false">Tim</button>
                                            </li>
                                        </ul>
                                        <div class="tab-content pt-2" id="myTabContent">
                                            <div class="tab-pane fade show active" id="positions-tab-pane" role="tabpanel" aria-labelledby="positions-tab" tabindex="0">
                                                <table class="table">
                                                    <thead>
                                                        <tr>
                                                            <th class="col-1" scope="col">#</th>
                                                            <th scope="col">Nama</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @forelse ($positions as $position)
                                                        <tr>
                                                            <th scope="row">{{ $loop->iteration }}</th>
                                                            <td>{{ $position->name }}</td>
                                                        </tr>
                                                        @empty
                                                        <tr>
                                                            <td colspan="10">Tidak ada Jabatan yang terdaftar</td>
                                                        </tr>
                                                        @endforelse
                                                    </tbody>
                                                    <tfoot class="table-group-divider">
                                                        <tr>
                                                            <td colspan="7">Total Data: <b>{{ $positions->count() }}</b> Jabatan</td>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                            <div class="tab-pane fade" id="teams-tab-pane" role="tabpanel" aria-labelledby="teams-tab" tabindex="0">
                                                <div class="table-responsive">
                                                    <table class="table">
                                                        <thead>
                                                            <tr>
                                                                <th class="col-1" scope="col">#</th>
                                                                <th scope="col">Nama Bagian</th>
                                                                <th scope="col">Nama Tim</th>
                                                                <th scope="col">Nama Sub Tim</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @forelse ($subteams as $subteam)
                                                            <tr>
                                                                <th scope="row">{{ $loop->iteration }}</th>
                                                                <td>{{ $subteam->team->part->name }}</td>
                                                                <td>{{ $subteam->team->name }}</td>
                                                                <td>{{ $subteam->name }}</td>
                                                            </tr>
                                                            @empty
                                                            <tr>
                                                                <td colspan="10">Tidak ada Tim yang terdaftar</td>
                                                            </tr>
                                                            @endforelse
                                                        </tbody>
                                                        <tfoot class="table-group-divider">
                                                            <tr>
                                                                <td colspan="7">Total Data: <b>{{ $subteams->count() }}</b> Tim</td>
                                                            </tr>
                                                        </tfoot>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
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
                                        <ul>
                                            <li>Wajib ada <b>NIK</b> agar dapat memasukkan data sesuai dengan data pegawai.</li>
                                            <li>Samakan nama kolom dengan ketentuan yang diberikan di tab <b>Sumber Kolom</b> agar proses Import Pegawai berjalan dengan lancar.</li>
                                            <li>Jika ada penamaan jabatan dan tim (Utama dan cadangan) terdapat kata yang berbeda dengan sumber data, maka sesuaikan dengan sumber data yang ada.</li>
                                            <li>Untuk pegawai yang tidak memiliki tim cadangan dan foto, bisa dikosongkan.</li>
                                            <li>Untuk <b>is_hr</b>, isi kata "Ya" bagi pegawai yang tergabung dalam Kepegawaian. Jika tidak, isi kata "Tidak"</li>
                                        </ul>
                                        <li>Pilih file yang akan di import ke dalam sistem.</li>
                                        <li>Pilih metode Import yang anda inginkan. Baca deskripsi metode sebelum melakukan Import.</li>
                                        <li><b>KHUSUS METODE RESET</b></li>
                                        <ul>
                                            <li>Metode Reset akan menghapus semua data (Pegawai, Pengguna, dan Data Nilai).</li>
                                            <li>Anda akan <b>otomatis keluar</b> dari sistem ini dan anda diperlukan masuk lagi sesuai dengan NIP anda (Password: bps3500)</li>
                                            <li>Jika sebelumnya terdapat data nilai yang telah diisi, silahkan lakukan import ulang kembali.</li>
                                        </ul>
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
                <button type="submit" form="form-off-import" class="btn btn-primary">
                    <i class="bi bi-upload"></i>
                    Import
                </button>
            </div>
        </div>
    </div>
</div>
<!--EXPORT PEGAWAI-->
<div class="modal modal-sheet p-4 py-md-5 fade" tabindex="-1" role="dialog" id="modal-off-export">
    <div class="modal-dialog" role="document">
        <div class="modal-content rounded-4 shadow">
            <form action="{{ route('admin.masters.officers.export') }}" method="post" enctype="multipart/form-data">
                <div class="modal-header border-bottom-0">
                    <h1 class="modal-title fs-5">Export Pegawai</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body py-0">
                    <p>Proses ini akan mengunduh Data Pegawai ke komputer anda. Anda dapat melakukan Import pegawai menggunakan file hasil Export Pegawai (Pastikan baca petunjuk Import terlebih dahulu).</p>
                </div>
                <div class="modal-footer flex-column align-items-stretch w-100 gap-2 pb-3 border-top-0">
                    @csrf
                    <button type="submit" class="btn btn-lg btn-primary" id="exportToastBtn">Export Pegawai</button>
                    <button type="button" class="btn btn-lg btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </form>
        </div>
    </div>
</div>
@foreach ($parts as $part)
<!--CHOICE METHODS (CREATE)-->
<div class="modal modal-sheet p-4 py-md-5 fade" tabindex="-1" role="dialog" id="modal-off-precre-{{ $part->id_part }}">
    <div class="modal-dialog" role="document">
        <div class="modal-content rounded-4 shadow">
            <div class="modal-header border-bottom-0">
                <h1 class="modal-title fs-5">Pilih Metode Create</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-0">
                <p>Untuk mempercepat pengisian data pegawai, disarankan menggunakan Import dari Excel untuk memudahkan anda saat mengisi data. Anda dapat melakukan pengisian secara manual jika membutuhkan.</p>
            </div>
            <div class="modal-footer flex-column align-items-stretch w-100 gap-2 pb-3 border-top-0">
                <button type="button" class="btn btn-lg btn-primary" data-bs-toggle="modal" data-bs-target="#modal-off-import">Import dari Excel (Disarankan)</button>
                <button type="button" class="btn btn-lg btn-primary" data-bs-toggle="modal" data-bs-target="#modal-off-create-{{ $part->id_part }}">Isi Manual</button>
                <button type="button" class="btn btn-lg btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!--CREATE OFFICER-->
<div class="modal modal-lg fade" id="modal-off-create-{{ $part->id_part }}" data-bs-backdrop="static" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Tambah Data Pegawai Bagian {{ $part->name }}</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="close-off-create-{{ $part->id_part }}"></button>
            </div>
            <div class="modal-body">
                @if (Auth::user()->part == 'Admin')
                <form id="form-off-create-{{ $part->id_part }}" action="{{ route('admin.masters.officers.store') }}" method="POST" enctype="multipart/form-data" id="form-off-create-{{ $part->id_part }}">
                @else
                <form id="form-off-create-{{ $part->id_part }}" action="{{ route('developer.masters.officers.store') }}" method="POST" enctype="multipart/form-data" id="form-off-create-{{ $part->id_part }}">
                @endif
                    @csrf
                    <div class="row justify-content-center g-4">
                        <div class="col-md-7">
                            <div class="position-sticky" style="top: 0rem;">
                                @if (Session::get('modal_redirect') == 'modal-off-create')
                                @include('Templates.Includes.Components.alert')
                                @endif
                                <ul class="nav nav-tabs" id="myTab" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link active" id="diri-{{ $part->id_part }}-tab" data-bs-toggle="tab" data-bs-target="#diri-{{ $part->id_part }}-tab-pane" type="button" role="tab" aria-controls="didi-{{ $part->id_part }}-tab-pane" aria-selected="true">Data Diri</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="pegawai-{{ $part->id_part }}-tab" data-bs-toggle="tab" data-bs-target="#pegawai-{{ $part->id_part }}-tab-pane" type="button" role="tab" aria-controls="pegawai-{{ $part->id_part }}-tab-pane" aria-selected="false">Info Pegawai</button>
                                    </li>
                                </ul>
                                <div class="tab-content" id="myTabContent">
                                    <div class="tab-pane fade show active" id="diri-{{ $part->id_part }}-tab-pane" role="tabpanel" aria-labelledby="diri-{{ $part->id_part }}-tab" tabindex="0">
                                        <br/>
                                        <div class="mb-3">
                                            <label for="name" class="form-label">Nama Pegawai</label>
                                            <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="phone" class="form-label">Nomor Telepon</label>
                                            <input type="tel" class="form-control" id="phone" name="phone" value="{{ old('phone') }}" required>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col">
                                                <label for="place_birth" class="form-label">Tempat Lahir</label>
                                                <input type="text" class="form-control" id="place_birth" name="place_birth" value="{{ old('place_birth') }}" required>
                                            </div>
                                            <div class="col">
                                                <label for="date_birth" class="form-label">Tanggal Lahir</label>
                                                <input type="date" class="form-control" id="date_birth" name="date_birth" value="{{ old('date_birth') }}" required>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col">
                                                <label for="gender" class="form-label">Jenis Kelamin</label>
                                                <select class="form-select" id="gender" name="gender" required>
                                                    <option selected disabled value="">---Pilih Jenis Kelamin---</option>
                                                    <option value="Laki-Laki" {{ old('gender') == 'Laki-Laki' ? 'selected' : null }}>Laki-Laki</option>
                                                    <option value="Perempuan" {{ old('gender') == 'Perempuan' ? 'selected' : null }}>Perempuan</option>
                                                </select>
                                            </div>
                                            <div class="col">
                                                <label for="religion" class="form-label">Agama</label>
                                                <select class="form-select" id="religion" name="religion" required>
                                                    <option selected disabled value="">---Pilih Agama---</option>
                                                    <option value="Islam" {{ old('religion') == 'Islam' ? 'selected' : null }}>Islam</option>
                                                    <option value="Kristen" {{ old('religion') == 'Kristen' ? 'selected' : null }}>Kristen</option>
                                                    <option value="Budha" {{ old('religion') == 'Budha' ? 'selected' : null }}>Budha</option>
                                                    <option value="Hindu" {{ old('religion') == 'Hindu' ? 'selected' : null }}>Hindu</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label for="photo" class="form-label">Foto (Pas Foto)</label>
                                            <div class="input-group">
                                                <input type="file" class="form-control" name="photo" id="photo">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="pegawai-{{ $part->id_part }}-tab-pane" role="tabpanel" aria-labelledby="pegawai-{{ $part->id_part }}-tab" tabindex="0">
                                        <br/>
                                        <div class="mb-3">
                                            <label for="id_officer" class="form-label">NIP (ID)</label>
                                            <input type="number" class="form-control" id="id_officer" name="id_officer" min="10000000" max="999999999" value="{{ old('id_officer') }}">
                                        </div>
                                        <div class="mb-3">
                                            <label for="email" class="form-label">E-Mail Pegawai</label>
                                            <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required>
                                        </div>
                                        <div class="mb-3" hidden>
                                            <label for="id_part" class="form-label" hidden>Bagian</label>
                                            <input type="text" class="form-control" id="id_part" name="id_part" value="{{ $part->id_part }}" readonly hidden>
                                        </div>
                                        <div class="mb-3">
                                            <label for="id_position" class="form-label">Jabatan</label>
                                            <select class="form-select" id="id_position" name="id_position" required>
                                                <option selected disabled value="">---Pilih Jabatan---</option>
                                                @if ($part->id_part == 'PRT-001')
                                                @foreach ($kbps_positions as $position)
                                                <option value="{{ $position->id_position }}" {{ old('id_position') ==  $position->id_position ? 'selected' : null }}>{{ $position->name }}</option>
                                                @endforeach
                                                @elseif ($part->id_part == 'PRT-002')
                                                @foreach ($umum_positions as $position)
                                                <option value="{{ $position->id_position }}" {{ old('id_position') ==  $position->id_position ? 'selected' : null }}>{{ $position->name }}</option>
                                                @endforeach
                                                @else
                                                @foreach ($off_positions as $position)
                                                <option value="{{ $position->id_position }}" {{ old('id_position') ==  $position->id_position ? 'selected' : null }}>{{ $position->name }}</option>
                                                @endforeach
                                                @endif
                                            </select>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col">
                                                <label for="id_sub_team_1" class="form-label">Tim Fungsi Utama</label>
                                                @if ($part->id_part == 'PRT-001')
                                                <select class="form-select" id="id_sub_team_1" name="id_sub_team_1" disabled>
                                                    <option selected disabled value="">Tidak Ada</option>
                                                </select>
                                                @else
                                                <select class="form-select" id="id_sub_team_1" name="id_sub_team_1" required>
                                                    <option selected disabled value="">---Pilih Tim Fungsi---</option>
                                                    @foreach ($team_lists->where('id_part', $part->id_part) as $team)
                                                        <option disabled value="">---{{ $team->name }}---</option>
                                                        @foreach ($subteams->where('id_team', $team->id_team) as $subteam)
                                                        <option value="{{ $subteam->id_sub_team }}" {{ old('id_sub_team_1') ==  $subteam->id_sub_team ? 'selected' : null }}>{{ $subteam->name }}</option>
                                                        @endforeach
                                                    @endforeach
                                                </select>
                                                @endif
                                            </div>
                                            <div class="col">
                                                <label for="id_sub_team_2" class="form-label">Tim Fungsi Cadangan</label>
                                                @if ($part->id_part == 'PRT-001')
                                                <select class="form-select" id="id_sub_team_2" name="id_sub_team_2" disabled>
                                                    <option selected value="">Tidak Ada</option>
                                                </select>
                                                @else
                                                <select class="form-select" id="id_sub_team_2" name="id_sub_team_2">
                                                    <option selected value="">Tidak Ada</option>
                                                    @foreach ($team_lists as $team)
                                                        <option disabled value="">---{{ $team->name }}---</option>
                                                        @foreach ($subteams->where('id_team', $team->id_team) as $subteam)
                                                        <option value="{{ $subteam->id_sub_team }}" {{ old('id_sub_team_2') ==  $subteam->id_sub_team ? 'selected' : null }}>{{ $subteam->name }}</option>
                                                        @endforeach
                                                    @endforeach
                                                </select>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            @if ($part->name == 'Umum')
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="" id="is_hr" name="is_hr">
                                                <label class="form-check-label" for="is_hr">
                                                    Apakah Merupakan Kepegawaian?
                                                </label>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="position-sticky" style="top: 0rem;">
                                <div class="alert alert-info" role="alert">
                                    <i class="bi bi-info-circle-fill"></i> <strong>CARA PENGISIAN</strong>
                                    <ol>
                                        <li>Isi sesuai dengan data pegawai yang ada di BPS Jawa Timur</li>
                                        <li>Untuk jabatan dan tim fungsi, pastikan data tersebut telah terdaftar di aplikasi ini. Jika tidak, silahkan tambahkan data jabatan dan tim fungsi</li>
                                        @if ($part->name == 'Umum')
                                        <li>Jika pegawai tersebut bergabung ke Kepegawaian, centangkan di bawah pilihan tim utama.</li>
                                        @endif
                                        <li>Proses ini juga dapat menambahkan pengguna oleh sistem.</li>
                                        <li>Periksa hasil kembali data pegawai sebelum ditambahkan ke aplikasi</li>
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
                <button type="submit" form="form-off-create-{{ $part->id_part }}" class="btn btn-primary">
                    <i class="bi bi-plus-lg"></i>
                    Tambah
                </button>
            </div>
        </div>
    </div>
</div>
<!--VIEW TEAMS-->
<div class="modal modal-lg fade" id="modal-tim-view-{{ $part->id_part }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Daftar Tim Bagian {{ $part->name }}</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row justify-content-center g-2">
                    <div class="col-md-4">
                        <div class="position-sticky" style="top: 0rem;">
                            <div class="nav flex-column nav-pills me-3" id="teams-modal-tab" role="tablist" aria-orientation="vertical">
                                @forelse ($teams->where('id_part', $part->id_part) as $team)
                                <button class="nav-link {{ $loop->first ? 'active' : '' }}" id="pills-{{ $team->id_team }}-tab" data-bs-toggle="pill" data-bs-target="#pills-{{ $team->id_team }}" type="button" role="tab" aria-controls="pills-{{ $team->id_team }}" aria-selected="{{ $loop->first ? 'true' : 'false' }}">
                                    {{ $team->name }}
                                </button>
                                @empty
                                <button class="nav-link active" id="pills-empty-tab" data-bs-toggle="pill" data-bs-target="#pills-empty" type="button" role="tab" aria-controls="pills-empty" aria-selected="true">
                                    Empty
                                </button>
                                @endforelse
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8">
                        @if (Session::get('code_alert') == 2)
                        @include('Templates.Includes.Components.alert')
                        @endif
                        <div class="tab-content" id="teams-modal-tabContent">
                            @foreach ($teams->where('id_part', $part->id_part) as $team)
                            <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" id="pills-{{ $team->id_team }}" role="tabpanel" aria-labelledby="pills-{{ $team->id_team }}-tab" tabindex="0">
                                <p>
                                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-stm-create-{{ $team->id_team }}">
                                        <i class="bi bi-node-plus"></i>
                                        Tambah Sub Tim
                                    </button>
                                    <button class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#modal-tim-update-{{ $team->id_team }}">
                                        <i class="bi bi-pencil"></i>
                                        Ubah Tim
                                    </button>
                                    <button class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#modal-tim-delete-{{ $team->id_team }}">
                                        <i class="bi bi-trash3"></i>
                                        Hapus Tim
                                    </button>
                                </p>
                                <table class="table table-hover table-bordered">
                                    <thead>
                                        <tr class="table-primary">
                                            <th class="col-1" scope="col">#</th>
                                            <th scope="col">Nama Sub Tim</th>
                                            <th class="col-1" scope="col">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($subteams->where('id_team', $team->id_team) as $subteam)
                                        <tr>
                                            <th scope="row">{{ $loop->iteration }}</th>
                                            <td>{{ $subteam->name }}</td>
                                            <td>
                                                <div class="dropdown">
                                                    <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class="bi bi-menu-button-fill"></i>
                                                    </button>
                                                    <ul class="dropdown-menu mx-0 shadow w-table-menu">
                                                        <li>
                                                            <a class="dropdown-item d-flex gap-2 align-items-center" href="#" data-bs-toggle="modal" data-bs-target="#modal-stm-update-{{ $subteam->id_sub_team }}"><svg class="bi" width="16" height="16" style="vertical-align: -.125em;"><use xlink:href="#update"/></svg>
                                                                Edit
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item d-flex gap-2 align-items-center" href="#" data-bs-toggle="modal" data-bs-target="#modal-stm-delete-{{ $subteam->id_sub_team }}"><svg class="bi" width="16" height="16" style="vertical-align: -.125em;"><use xlink:href="#delete"/></svg>
                                                                Delete
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="7">Tidak ada Jabatan yang terdaftar</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                    <tfoot class="table-group-divider table-secondary">
                                        <tr>
                                            <td colspan="7">Total Data: <b>{{ $positions->count() }}</b> Jabatan</td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-lg"></i>
                    Tutup
                </button>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-tim-create-{{ $part->id_part }}">
                    <i class="bi bi-node-plus"></i>
                    Tambah Tim
                </button>
            </div>
        </div>
    </div>
</div>
<!--CREATE TEAM-->
<div class="modal fade" id="modal-tim-create-{{ $part->id_part }}" data-bs-backdrop="static" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            @if (Auth::user()->part == 'Admin')
            <form action="{{ route('admin.masters.teams.store') }}" method="POST" enctype="multipart/form-data" id="form-tim-create">
            @else
            <form action="{{ route('developer.masters.teams.store') }}" method="POST" enctype="multipart/form-data" id="form-tim-create">
            @endif
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Tambah Tim Bagian {{ $part->name }}</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="close-tim-create"></button>
                </div>
                <div class="modal-body">
                    @if (Session::get('modal_redirect') == 'modal-tim-create')
                    @include('Templates.Includes.Components.alert')
                    @endif
                    @csrf
                    <div class="mb-3" hidden>
                        <label for="id_part" class="form-label" hidden>Kode Part</label>
                        <input type="text" class="form-control" id="id_part" name="id_part" value="{{ $part->id_part }}" readonly hidden>
                    </div>
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Tim</label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#modal-tim-view-{{ $part->id_part }}">
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
    @foreach ($teams->where('id_part', $part->id_part) as $team)
    <!--UPDATE TEAM-->
    <div class="modal fade" id="modal-tim-update-{{ $team->id_team }}" data-bs-backdrop="static" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                @if (Auth::user()->part == 'Admin')
                <form action="{{ route('admin.masters.teams.update', $team->id_team) }}" method="POST" enctype="multipart/form-data" id="form-tim-update">
                @else
                <form action="{{ route('developer.masters.teams.update', $team->id_team) }}" method="POST" enctype="multipart/form-data" id="form-tim-update">
                @endif
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Ubah Tim Bagian {{ $part->name }}</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="close-tim-update"></button>
                    </div>
                    <div class="modal-body">
                        @if (Session::get('modal_redirect') == 'modal-tim-update')
                        @include('Templates.Includes.Components.alert')
                        @endif
                        @csrf @method('PUT')
                        <div class="mb-3">
                            <label for="name" class="form-label">Nama Tim</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ $team->name }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="id_part" class="form-label">Bagian</label>
                            <select class="form-select" id="id_part" name="id_part" required>
                                <option selected disabled value="">---Pilih Bagian---</option>
                                @foreach ($parts as $part)
                                <option value="{{ $part->id_part }}" {{ $team->id_part ==  $part->id_part ? 'selected' : null }} {{ $loop->first ? 'disabled hidden' : '' }}>{{ $part->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#modal-tim-view-{{ $team->id_part }}">
                            <i class="bi bi-x-lg"></i>
                            Batal
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-pencil"></i>
                            Ubah
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!--DELETE TEAM-->
    <div class="modal fade" id="modal-tim-delete-{{ $team->id_team }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Hapus Tim</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @if (Auth::user()->part == 'Admin')
                    <form id="form-tim-delete-{{ $team->id_team }}" action="{{ route('admin.masters.teams.destroy', $team->id_team) }}" method="POST" enctype="multipart/form-data">
                    @else
                    <form id="form-tim-delete-{{ $team->id_team }}" action="{{ route('developer.masters.teams.destroy', $team->id_team) }}" method="POST" enctype="multipart/form-data">
                    @endif
                        @csrf @method('DELETE')
                        <div class="alert alert-warning" role="alert">
                            <i class="bi bi-exclamation-triangle-fill"></i> <b>PERHATIAN</b>
                            <br/>
                            Apakah anda ingin menghapus Tim <b>{{ $team->name }}</b> dari Bagian <b>{{ $team->part->name }}</b>?
                            <ul>
                                <li>Seluruh <strong>Data Nilai dari Pegawai</strong> yang tergabung dengan Tim ini akan terhapus.</li>
                                <li>Seluruh <strong>Pegawai</strong> yang tergabung dengan Tim ini akan terhapus.</li>
                                <li>Seluruh <strong>Sub Tim</strong> dari Tim ini akan terhapus.</li>
                            </ul>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#modal-tim-view-{{ $team->id_part }}">
                        <i class="bi bi-x-lg"></i>
                        Tidak
                    </button>
                    <button type="submit" form="form-tim-delete-{{ $team->id_team }}" class="btn btn-danger">
                        <i class="bi bi-check-lg"></i>
                        Ya
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!--CREATE SUB TEAM-->
    <div class="modal fade" id="modal-stm-create-{{ $team->id_team }}" data-bs-backdrop="static" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                @if (Auth::user()->part == 'Admin')
                <form action="{{ route('admin.masters.subteams.store') }}" method="POST" enctype="multipart/form-data" id="form-stm-create">
                @else
                <form action="{{ route('developer.masters.subteams.store') }}" method="POST" enctype="multipart/form-data" id="form-stm-create">
                @endif
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Tambah Sub Tim (Tim {{ $team->name }})</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="close-stm-create"></button>
                    </div>
                    <div class="modal-body">
                        @if (Session::get('modal_redirect') == 'modal-stm-create')
                        @include('Templates.Includes.Components.alert')
                        @endif
                        @csrf
                        <div class="mb-3" hidden>
                            <label for="id_part" class="form-label" hidden>Kode Bagian</label>
                            <input type="text" class="form-control" id="id_part" name="id_part" value="{{ $team->part->id_part }}" readonly hidden>
                        </div>
                        <div class="mb-3" hidden>
                            <label for="id_team" class="form-label" hidden>Kode Tim</label>
                            <input type="text" class="form-control" id="id_team" name="id_team" value="{{ $team->id_team }}" readonly hidden>
                        </div>
                        <div class="mb-3">
                            <label for="name" class="form-label">Nama Sub Tim</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#modal-tim-view-{{ $team->part->id_part }}">
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
        @foreach ($subteams->where('id_team', $team->id_team) as $subteam)
        <!--UPDATE SUB TEAM-->
        <div class="modal fade" id="modal-stm-update-{{ $subteam->id_sub_team }}" data-bs-backdrop="static" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    @if (Auth::user()->part == 'Admin')
                    <form action="{{ route('admin.masters.subteams.update', $subteam->id_sub_team) }}" method="POST" enctype="multipart/form-data" id="form-stm-update">
                    @else
                    <form action="{{ route('developer.masters.subteams.update', $subteam->id_sub_team) }}" method="POST" enctype="multipart/form-data" id="form-stm-update">
                    @endif
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="exampleModalLabel">Ubah Sub Tim (Tim {{ $team->name }})</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="close-stm-update"></button>
                        </div>
                        <div class="modal-body">
                            @if (Session::get('modal_redirect') == 'modal-stm-update')
                            @include('Templates.Includes.Components.alert')
                            @endif
                            @csrf @method('PUT')
                            <div class="mb-3" hidden>
                                <label for="id_part" class="form-label" hidden>Kode Bagian</label>
                                <input type="text" class="form-control" id="id_part" name="id_part" value="{{ $subteam->team->part->id_part }}" readonly hidden>
                            </div>
                            <div class="mb-3">
                                <label for="name" class="form-label">Nama Sub Tim</label>
                                <input type="text" class="form-control" id="name" name="name" value="{{ $subteam->name }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="id_team" class="form-label">Tim</label>
                                <select class="form-select" id="id_team" name="id_team" required>
                                    <option selected disabled value="">---Pilih Tim---</option>
                                    @foreach ($parts_2 as $part)
                                    <option disabled value="">---{{ $part->name }}---</option>
                                        @foreach ($teams->where('id_part', $part->id_part) as $team)
                                        <option value="{{ $team->id_team }}" {{ $subteam->id_team ==  $team->id_team ? 'selected' : null }}>{{ $team->name }}</option>
                                        @endforeach
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#modal-tim-view-{{ $subteam->team->part->id_part }}">
                                <i class="bi bi-x-lg"></i>
                                Batal
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-pencil"></i>
                                Ubah
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!--DELETE SUB TEAM-->
        <div class="modal fade" id="modal-stm-delete-{{ $subteam->id_sub_team }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Hapus Sub Tim</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        @if (Auth::user()->part == 'Admin')
                        <form id="form-stm-delete-{{ $subteam->id_sub_team }}" action="{{ route('admin.masters.subteams.destroy', $subteam->id_sub_team) }}" method="POST" enctype="multipart/form-data">
                        @else
                        <form id="form-stm-delete-{{ $subteam->id_sub_team }}" action="{{ route('developer.masters.subteams.destroy', $subteam->id_sub_team) }}" method="POST" enctype="multipart/form-data">
                        @endif
                            @csrf @method('DELETE')
                            <div class="alert alert-warning" role="alert">
                                <i class="bi bi-exclamation-triangle-fill"></i> <b>PERHATIAN</b>
                                <br/>
                                Apakah anda ingin menghapus Sub Tim <b>{{ $subteam->name }}</b> dari Tim <b>{{ $subteam->team->name }}</b>?
                                <ul>
                                    <li>Seluruh <strong>Data Nilai dari Pegawai</strong> yang tergabung dengan Tim ini akan terhapus.</li>
                                    <li>Seluruh <strong>Pegawai</strong> yang tergabung dengan Tim ini akan terhapus.</li>
                                </ul>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#modal-tim-view-{{ $subteam->team->id_part }}">
                            <i class="bi bi-x-lg"></i>
                            Tidak
                        </button>
                        <button type="submit" form="form-stm-delete-{{ $subteam->id_sub_team }}" class="btn btn-danger">
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
@foreach ($officers as $officer)
<!--CHOICE METHODS (UPDATE)-->
<div class="modal modal-sheet p-4 py-md-5 fade" tabindex="-1" role="dialog" id="modal-off-preupd-{{ $officer->id_officer }}">
    <div class="modal-dialog" role="document">
        <div class="modal-content rounded-4 shadow">
            <div class="modal-header border-bottom-0">
                <h1 class="modal-title fs-5">Pilih Metode Update</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-0">
                <p>Untuk mempercepat pengisian data pegawai, disarankan menggunakan Import dari Excel untuk memudahkan anda saat mengisi data. Anda dapat melakukan pengisian secara manual jika membutuhkan.</p>
            </div>
            <div class="modal-footer flex-column align-items-stretch w-100 gap-2 pb-3 border-top-0">
                <button type="button" class="btn btn-lg btn-primary" data-bs-toggle="modal" data-bs-target="#modal-off-import">Import dari Excel (Disarankan)</button>
                <button type="button" class="btn btn-lg btn-primary" data-bs-toggle="modal" data-bs-target="#modal-off-update-{{ $officer->id_officer }}">Isi Manual</button>
                <button type="button" class="btn btn-lg btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!--UPDATE OFFICER-->
<div class="modal modal-lg fade" id="modal-off-update-{{ $officer->id_officer }}" data-bs-backdrop="static" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Ubah Data Pegawai ({{ $officer->id_officer }}) ({{ $officer->subteam_1->team->part->id_part }})</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                @if (Auth::user()->part == 'Admin')
                <form id="form-off-update-{{ $officer->id_officer }}" action="{{ route('admin.masters.officers.update', $officer->id_officer) }}" method="POST" enctype="multipart/form-data">
                @else
                <form id="form-off-update-{{ $officer->id_officer }}" action="{{ route('developer.masters.officers.update', $officer->id_officer) }}" method="POST" enctype="multipart/form-data">
                @endif
                    @csrf @method('PUT')
                    <div class="row justify-content-center g-4">
                        <div class="col-md-7">
                            <div class="position-sticky" style="top: 0rem;">
                                @if (Session::get('modal_redirect') == 'modal-off-update')
                                @include('Templates.Includes.Components.alert')
                                @endif
                                <ul class="nav nav-tabs" id="myTab" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link active" id="diri-{{ $officer->id_officer }}-tab" data-bs-toggle="tab" data-bs-target="#diri-{{ $officer->id_officer }}-tab-pane" type="button" role="tab" aria-controls="didi-{{ $officer->id_officer }}-tab-pane" aria-selected="true">Data Diri</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="pegawai-{{ $officer->id_officer }}-tab" data-bs-toggle="tab" data-bs-target="#pegawai-{{ $officer->id_officer }}-tab-pane" type="button" role="tab" aria-controls="pegawai-{{ $officer->id_officer }}-tab-pane" aria-selected="false">Info Pegawai</button>
                                    </li>
                                </ul>
                                <div class="tab-content" id="myTabContent">
                                    <div class="tab-pane fade show active" id="diri-{{ $officer->id_officer }}-tab-pane" role="tabpanel" aria-labelledby="diri-{{ $officer->id_officer }}-tab" tabindex="0">
                                        <br/>
                                        <div class="mb-3">
                                            <label for="name" class="form-label">Nama Pegawai</label>
                                            <input type="text" class="form-control" id="name" name="name" value="{{ $officer->name }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="phone" class="form-label">Nomor Telepon</label>
                                            <input type="tel" class="form-control" id="phone" name="phone" value="{{ $officer->phone }}" required>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col">
                                                <label for="place_birth" class="form-label">Tempat Lahir</label>
                                                <input type="text" class="form-control" id="place_birth" name="place_birth" value="{{ $officer->place_birth }}" required>
                                            </div>
                                            <div class="col">
                                                <label for="date_birth" class="form-label">Tanggal Lahir</label>
                                                <input type="date" class="form-control" id="date_birth" name="date_birth" value="{{ $officer->date_birth }}" required>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col">
                                                <label for="gender" class="form-label">Jenis Kelamin</label>
                                                <select class="form-select" id="gender" name="gender" required>
                                                    <option selected disabled value="">---Pilih Jenis Kelamin---</option>
                                                    <option value="Laki-Laki" {{ $officer->gender == 'Laki-Laki' ? 'selected' : null }}>Laki-Laki</option>
                                                    <option value="Perempuan" {{ $officer->gender == 'Perempuan' ? 'selected' : null }}>Perempuan</option>
                                                </select>
                                            </div>
                                            <div class="col">
                                                <label for="religion" class="form-label">Agama</label>
                                                <select class="form-select" id="religion" name="religion" required>
                                                    <option selected disabled value="">---Pilih Agama---</option>
                                                    <option value="Islam" {{ $officer->religion == 'Islam' ? 'selected' : null }}>Islam</option>
                                                    <option value="Kristen" {{ $officer->religion == 'Kristen' ? 'selected' : null }}>Kristen</option>
                                                    <option value="Budha" {{ $officer->religion == 'Budha' ? 'selected' : null }}>Budha</option>
                                                    <option value="Hindu" {{ $officer->religion == 'Hindu' ? 'selected' : null }}>Hindu</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label for="photo" class="form-label">Foto (Pas Foto)</label>
                                            <div class="input-group">
                                                <input type="file" class="form-control" name="photo" id="photo" value="{{ $officer->photo }}">
                                            </div>
                                            <div class="form-check">
                                                @if ($officer->photo == '' || $officer->photo == null)
                                                <input class="form-check-input" type="checkbox" value="" id="photo_erase" name="photo_erase" disabled>
                                                <label class="form-check-label" for="photo_erase">
                                                    Hapus Gambar
                                                </label>
                                                @else
                                                <input class="form-check-input" type="checkbox" value="" id="photo_erase" name="photo_erase">
                                                <label class="form-check-label" for="photo_erase">
                                                    Hapus Gambar
                                                </label>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="pegawai-{{ $officer->id_officer }}-tab-pane" role="tabpanel" aria-labelledby="pegawai-{{ $officer->id_officer }}-tab" tabindex="0">
                                        <br/>
                                        <div class="mb-3">
                                            <label for="id_officer" class="form-label">NIP (ID)</label>
                                            <input type="number" class="form-control" id="id_officer" name="id_officer" min="10000000" max="999999999" value="{{ $officer->id_officer }}">
                                        </div>
                                        <div class="mb-3">
                                            <label for="email" class="form-label">E-Mail Pegawai</label>
                                            <input type="email" class="form-control" id="email" name="email" value="{{ $officer->email }}" required>
                                        </div>
                                        <div class="mb-3" hidden>
                                            <label for="id_part" class="form-label" hidden>Bagian</label>
                                            <input type="text" class="form-control" id="id_part" name="id_part" value="{{ $officer->subteam_1->team->part->id_part }}" readonly hidden>
                                        </div>
                                        <div class="mb-3">
                                            <label for="id_position" class="form-label">Jabatan</label>
                                            <select class="form-select" id="id_position_{{ $officer->id_officer }}" name="id_position" required>
                                                <option selected disabled value="">---Pilih Jabatan---</option>
                                                @foreach ($positions as $position)
                                                <option value="{{ $position->id_position }}" {{ $officer->id_position ==  $position->id_position ? 'selected' : null }}>{{ $position->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col">
                                                <label for="id_sub_team_1" class="form-label">Tim Fungsi Utama</label>
                                                <select class="form-select" id="id_sub_team_1_{{ $officer->id_officer }}" name="id_sub_team_1" required>
                                                    <option selected disabled value="">---Pilih Tim Fungsi---</option>
                                                    @foreach ($team_lists as $team)
                                                        <option disabled value="">---{{ $team->name }}---</option>
                                                        @foreach ($subteams->where('id_team', $team->id_team) as $subteam)
                                                        <option value="{{ $subteam->id_sub_team }}" {{ $officer->id_sub_team_1 ==  $subteam->id_sub_team ? 'selected' : null }}>{{ $subteam->name }}</option>
                                                        @endforeach
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col">
                                                <label for="id_sub_team_2" class="form-label">Tim Fungsi Cadangan</label>
                                                <select class="form-select" id="id_sub_team_2_{{ $officer->id_officer }}" name="id_sub_team_2">
                                                    <option selected value="">Tidak Ada</option>
                                                    @foreach ($team_lists as $team)
                                                        <option disabled value="">---{{ $team->name }}---</option>
                                                        @foreach ($subteams->where('id_team', $team->id_team) as $subteam)
                                                        <option value="{{ $subteam->id_sub_team }}" {{ $officer->id_sub_team_2 ==  $subteam->id_sub_team ? 'selected' : null }}>{{ $subteam->name }}</option>
                                                        @endforeach
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            @if ($officer->subteam_1->team->part->name == 'Umum')
                                            <div class="form-check">
                                                @if ($users->where('nip', $officer->id_officer)->first()->part == 'Admin')
                                                <input class="form-check-input" type="checkbox" value="" id="is_hr" name="is_hr" checked>
                                                <label class="form-check-label" for="is_hr">
                                                    Apakah Merupakan Kepegawaian?
                                                </label>
                                                @else
                                                <input class="form-check-input" type="checkbox" value="" id="is_hr" name="is_hr">
                                                <label class="form-check-label" for="is_hr">
                                                    Apakah Merupakan Kepegawaian?
                                                </label>
                                                @endif
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="position-sticky" style="top: 0rem;">
                                <div class="alert alert-info" role="alert">
                                    <i class="bi bi-info-circle-fill"></i> <strong>CARA PENGISIAN</strong>
                                    <ol>
                                        <li>Isi sesuai dengan data pegawai yang ada di BPS Jawa Timur</li>
                                        <li>Untuk jabatan dan tim fungsi, pastikan data tersebut telah terdaftar di aplikasi ini. Jika tidak, silahkan tambahkan data jabatan dan tim fungsi</li>
                                        <li>Periksa hasil kembali data pegawai sebelum ditambahkan ke aplikasi</li>
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
                <button type="submit" form="form-off-update-{{ $officer->id_officer }}" class="btn btn-primary">
                    <i class="bi bi-pencil"></i>
                    Ubah
                </button>
            </div>
        </div>
    </div>
</div>
<!--DELETE OFFICER-->
<div class="modal fade" id="modal-off-delete-{{ $officer->id_officer }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Hapus Data Pegawai</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                @if (Auth::user()->part == 'Admin')
                <form id="form-off-delete-{{ $officer->id_officer }}" action="{{ route('admin.masters.officers.destroy', $officer->id_officer) }}" method="POST" enctype="multipart/form-data">
                @else
                <form id="form-off-delete-{{ $officer->id_officer }}" action="{{ route('developer.masters.officers.destroy', $officer->id_officer) }}" method="POST" enctype="multipart/form-data">
                @endif
                    @csrf @method('DELETE')
                    <div class="mb-3" hidden>
                        <label for="id_part" class="form-label" hidden>Bagian</label>
                        <input type="text" class="form-control" id="id_part" name="id_part" value="{{ $officer->subteam_1->team->part->id_part }}" readonly hidden>
                    </div>
                    <div class="alert alert-warning" role="alert">
                        <i class="bi bi-exclamation-triangle-fill"></i> <b>PERHATIAN</b>
                        <br/>
                        Apakah anda ingin menghapus Pegawai dengan nama <b>{{ $officer->name }}</b>?
                        <ul>
                            <li>Data nilai yang dimiliki oleh Pegawai ini akan dihapus bersamaan.</li>
                        </ul>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-lg"></i>
                    Tidak
                </button>
                <button type="submit" form="form-off-delete-{{ $officer->id_officer }}" class="btn btn-danger">
                    <i class="bi bi-check-lg"></i>
                    Ya
                </button>
            </div>
        </div>
    </div>
</div>
@endforeach
<!--VIEW POSITIONS-->
<div class="modal fade" id="modal-dep-view" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Daftar Jabatan</h1>
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
                            <th scope="col">Nama Jabatan</th>
                            <th class="col-1" scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($positions as $position)
                        <tr>
                            <th scope="row">{{ $loop->iteration }}</th>
                            <td>{{ $position->name }}</td>
                            <td>
                                <div class="dropdown">
                                    @if (strpos($position,'Kepala'))
                                    <span class="d-inline-block" tabindex="0" data-bs-toggle="tooltip" data-bs-title="Kepala tidak dapat diubah.">
                                    <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false" disabled>
                                        <i class="bi bi-menu-button-fill"></i>
                                    </button>
                                    </span>
                                    @else
                                    <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="bi bi-menu-button-fill"></i>
                                    </button>
                                    @endif
                                    <ul class="dropdown-menu mx-0 shadow w-table-menu">
                                        <li>
                                            <a class="dropdown-item d-flex gap-2 align-items-center" href="#" data-bs-toggle="modal" data-bs-target="#modal-dep-update-{{ $position->id_position }}"><svg class="bi" width="16" height="16" style="vertical-align: -.125em;"><use xlink:href="#update"/></svg>
                                                Edit
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item d-flex gap-2 align-items-center" href="#" data-bs-toggle="modal" data-bs-target="#modal-dep-delete-{{ $position->id_position }}"><svg class="bi" width="16" height="16" style="vertical-align: -.125em;"><use xlink:href="#delete"/></svg>
                                                Delete
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7">Tidak ada Jabatan yang terdaftar</td>
                        </tr>
                        @endforelse
                    </tbody>
                    <tfoot class="table-group-divider table-secondary">
                        <tr>
                            <td colspan="7">Total Data: <b>{{ $positions->count() }}</b> Jabatan</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-lg"></i>
                    Tutup
                </button>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-dep-create">
                    <i class="bi bi-node-plus"></i>
                    Tambah
                </button>
            </div>
        </div>
    </div>
</div>
<!--CREATE POSITIONS-->
<div class="modal fade" id="modal-dep-create" data-bs-backdrop="static" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            @if (Auth::user()->part == 'Admin')
            <form action="{{ route('admin.masters.positions.store') }}" method="POST" enctype="multipart/form-data" id="form-dep-create">
            @else
            <form action="{{ route('developer.masters.positions.store') }}" method="POST" enctype="multipart/form-data" id="form-dep-create">
            @endif
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Tambah Jabatan</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="close-dep-create"></button>
                </div>
                <div class="modal-body">
                    @if (Session::get('modal_redirect') == 'modal-dep-create')
                    @include('Templates.Includes.Components.alert')
                    @endif
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Jabatan</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#modal-dep-view">
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
@foreach ($positions as $position)
<!--UPDATE POSITIONS-->
<div class="modal fade" id="modal-dep-update-{{ $position->id_position }}" data-bs-backdrop="static" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            @if (Auth::user()->part == 'Admin')
            <form action="{{ route('admin.masters.positions.update', $position->id_position) }}" method="POST" enctype="multipart/form-data">
            @else
            <form action="{{ route('developer.masters.positions.update', $position->id_position) }}" method="POST" enctype="multipart/form-data">
            @endif
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Ubah Jabatan</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @if (Session::get('modal_redirect') == 'modal-dep-update')
                    @include('Templates.Includes.Components.alert')
                    @endif
                    @csrf @method('PUT')
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Jabatan</label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ $position->name }}" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#modal-dep-view">
                        <i class="bi bi-x-lg"></i>
                        Batal
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-pencil"></i>
                        Ubah
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<!--DELETE POSITIONS-->
<div class="modal fade" id="modal-dep-delete-{{ $position->id_position }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Hapus Data Jabatan</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                @if (Auth::user()->part == 'Admin')
                <form id="form-dep-delete-{{ $position->id_position }}" action="{{ route('admin.masters.positions.destroy', $position->id_position) }}" method="POST" enctype="multipart/form-data">
                @else
                <form id="form-dep-delete-{{ $position->id_position }}" action="{{ route('developer.masters.positions.destroy', $position->id_position) }}" method="POST" enctype="multipart/form-data">
                @endif
                    @csrf @method('DELETE')
                    <div class="alert alert-warning" role="alert">
                        <i class="bi bi-exclamation-triangle-fill"></i> <b>PERHATIAN</b>
                        <br/>
                        Apakah anda ingin menghapus Jabatan <b>{{ $position->name }}</b>?
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#modal-dep-view">
                    <i class="bi bi-backspace"></i>
                    Tidak
                </button>
                <button type="submit" form="form-dep-delete-{{ $position->id_position }}" class="btn btn-danger">
                    <i class="bi bi-check-lg"></i>
                    Ya
                </button>
            </div>
        </div>
    </div>
</div>
@endforeach
@endif
