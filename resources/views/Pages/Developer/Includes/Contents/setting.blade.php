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
        <div class="col-md-6">
            <div class="h-100 p-5 text-bg-dark rounded-3">
                <h2>Perhitungan Kehadiran</h2>
                <p>Peraturan ini digunakan untuk menghitung jumlah hadir saat melakukan import.</p>
                <select class="form-select" id="s_presence_counter" name="s_presence_counter">
                    <option selected disabled value="">---Pilih Kriteria---</option>
                    @foreach ($criterias as $criteria)
                    <option value="{{ $criteria->id_criteria }}" {{ $settings->where('id_setting', 'STG-001')->first()->value ==  $criteria->id_criteria ? 'selected' : null }}>{{ $criteria->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-6">
            <div class="h-100 p-5 bg-body-tertiary border rounded-3">
                <h2>Sorting Terbaik Kedua</h2>
                <p>Apabila jumlah hasil analisis sama, maka dapat disortir lagi menggunakan peraturan ini.</p>
                <select class="form-select" id="s_second_sort" name="s_second_sort">
                    <option selected disabled value="">---Pilih Kriteria---</option>
                    @foreach ($criterias as $criteria)
                    <option value="{{ $criteria->id_criteria }}" {{ $settings->where('id_setting', 'STG-002')->first()->value ==  $criteria->id_criteria ? 'selected' : null }}>{{ $criteria->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
