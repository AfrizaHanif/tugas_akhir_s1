<h1 class="text-center mb-4">Pengaturan</h1>
@if (Session::get('code_alert') == 1)
@include('Templates.Includes.Components.alert')
@endif
<p>
    <a class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-stg-save" onclick="copyValue1();copyValue2();">
        <i class="bi bi-floppy"></i>
        Simpan
    </a>
</p>
<div class="row align-items-md-stretch">
    @if (Auth::user()->part != 'Pegawai')
    <div class="col-md-6 pb-4">
        <div class="h-100 p-5 text-bg-dark rounded-3">
            <h2>Perhitungan Kehadiran</h2>
            <p>Peraturan ini digunakan untuk menghitung jumlah hadir saat melakukan import. Peraturan ini dikhususkan untuk kriteria kehadiran yang memiliki kolom dari sumber yang berbeda (Misal: kriteria kehadiran, sumber kolom tanpa kabar).</p>
            <select class="form-select" id="s_presence_counter" name="s_presence_counter">
                <option selected disabled value="">---Pilih Kriteria---</option>
                <option value="None" {{ $settings->where('id_setting', 'STG-001')->first()->value ==  'None' ? 'selected' : null }}>Tidak Ada</option>
                @foreach ($criterias as $criteria)
                <option value="{{ $criteria->id_criteria }}" {{ $settings->where('id_setting', 'STG-001')->first()->value ==  $criteria->id_criteria ? 'selected' : null }}>{{ $criteria->name }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-md-6 pb-4">
        <div class="h-100 p-5 bg-body-tertiary border rounded-3">
            <h2>Sorting Kedua (Verifikasi)</h2>
            <p>Apabila jumlah nilai akhir dari hasil sama saat mengambil data untuk kebutuhan verifikasi, maka dapat disortir lagi menggunakan peraturan ini. Pengaturan ini wajib diatur untuk menghindari duplikat nilai akhir pada peringkat pertama.</p>
            <select class="form-select" id="s_second_sort" name="s_second_sort">
                <option selected disabled value="">---Pilih Kriteria---</option>
                @foreach ($criterias as $criteria)
                <option value="{{ $criteria->id_criteria }}" {{ $settings->where('id_setting', 'STG-002')->first()->value ==  $criteria->id_criteria ? 'selected' : null }}>{{ $criteria->name }}</option>
                @endforeach
            </select>
        </div>
    </div>
    @endif
    @if (Auth::user()->part != 'Dev') <!--DEVELOPER CANNOT EDIT USERNAME-->
    <div class="col-md-6 pb-4">
        <div class="h-100 p-5 bg-body-tertiary border rounded-3">
            <h2>Ganti Username</h2>
            <p>Anda dapat mengubah username anda untuk memudahkan anda saat melakukan login jika anda lupa NIP.</p>
            @if (Auth::user()->username == Auth::user()->nip) <!--IF USERNAME EQUALS NIP (FIRST TIME)-->
            <input type="username" class="form-control" id="s_username" name="s_username" required>
            @else
            <input type="username" class="form-control" id="s_username" name="s_username" value="{{ Auth::user()->username }}" required>
            @endif
        </div>
    </div>
    @endif
    <div class="col-md-6 pb-4">
        <div class="h-100 p-5 text-bg-dark rounded-3">
            <h2>Ganti Password</h2>
            <p>Anda perlu menggantikan password setelah Kepegawaian memberikan password kepada anda untuk pertama kali.</p>
            <input type="password" class="form-control" id="s_password" name="s_password" required>
        </div>
    </div>
</div>
