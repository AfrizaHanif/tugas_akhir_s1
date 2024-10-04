<!--CREATE PERIOD-->
<div class="modal fade" id="modal-per-create" data-bs-backdrop="static" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.masters.periods.store') }}" method="POST" enctype="multipart/form-data" id="form-per-create">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Tambah Periode</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="close-per-create"></button>
                </div>
                <div class="modal-body">
                    @if (Session::get('modal_redirect') == 'modal-per-create')
                    @include('Templates.Includes.Components.alert')
                    @endif
                    @csrf
                    <div class="row mb-3">
                        <div class="col">
                            <label for="month" class="form-label">Bulan</label>
                            <select class="form-select" id="month" name="month" required>
                                <option selected disabled value="">---Pilih Bulan---</option>
                                <option value="01" {{ old('month') == '01' ? 'selected' : null }}>Januari</option>
                                <option value="02" {{ old('month') == '02' ? 'selected' : null }}>Februari</option>
                                <option value="03" {{ old('month') == '03' ? 'selected' : null }}>Maret</option>
                                <option value="04" {{ old('month') == '04' ? 'selected' : null }}>April</option>
                                <option value="05" {{ old('month') == '05' ? 'selected' : null }}>Mei</option>
                                <option value="06" {{ old('month') == '06' ? 'selected' : null }}>Juni</option>
                                <option value="07" {{ old('month') == '07' ? 'selected' : null }}>Juli</option>
                                <option value="08" {{ old('month') == '08' ? 'selected' : null }}>Agustus</option>
                                <option value="09" {{ old('month') == '09' ? 'selected' : null }}>September</option>
                                <option value="10" {{ old('month') == '10' ? 'selected' : null }}>Oktober</option>
                                <option value="11" {{ old('month') == '11' ? 'selected' : null }}>November</option>
                                <option value="12" {{ old('month') == '12' ? 'selected' : null }}>Desember</option>
                            </select>
                        </div>
                        <div class="col">
                            <label for="year" class="form-label">Tahun</label>
                            <input type="number" class="form-control" id="year" name="year" min="2010" max="2099" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="active_days" class="form-label">Hari Aktif (Setelah dikurangi hari libur)</label>
                        <input type="number" class="form-control" id="active_days" name="active_days" min="1" max="31" required>
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
@foreach ($periods as $period)
<!--UPDATE PERIOD-->
<div class="modal fade" id="modal-per-update-{{ $period->id_period }}" data-bs-backdrop="static" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.masters.periods.update', $period->id_period) }}" method="POST" enctype="multipart/form-data" id="form-per-create">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Ubah Periode ({{ $period->name }})</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="close-per-create"></button>
                </div>
                <div class="modal-body">
                    @if (Session::get('modal_redirect') == 'modal-per-create')
                    @include('Templates.Includes.Components.alert')
                    @endif
                    @csrf @method('PUT')
                    <div class="mb-3">
                        <label for="active_days" class="form-label">Hari Aktif (Setelah dikurangi hari libur)</label>
                        <input type="number" class="form-control" id="active_days" name="active_days" min="1" max="31" value="{{ $period->active_days }}" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">
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
<!--START PROGRESS-->
<div class="modal fade" id="modal-per-start-{{ $period->id_period }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.masters.periods.start', $period->id_period) }}" method="POST" enctype="multipart/form-data">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Mulai Proses Pemilihan Karyawan Terbaik ({{ $period->name }})</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @csrf
                    <div class="alert alert-warning" role="alert">
                        <i class="bi bi-exclamation-triangle-fill"></i> <b>PERHATIAN</b>
                        <br/>
                        Apakah anda ingin memulai proses pemilihan karyawan terbaik? Anda tidak dapat melewatkan periode ini setelah proses tersebut dimulai.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#modal-dep-view">
                        <i class="bi bi-backspace"></i>
                        Tidak
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-lg"></i>
                        Ya
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<!--SKIP PERIOD-->
<div class="modal fade" id="modal-per-skip-{{ $period->id_period }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.masters.periods.skip', $period->id_period) }}" method="POST" enctype="multipart/form-data">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Lewati Periode ({{ $period->name }})</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @csrf
                    <div class="alert alert-warning" role="alert">
                        <i class="bi bi-exclamation-triangle-fill"></i> <b>PERHATIAN</b>
                        <br/>
                        Apakah anda ingin melewati periode tersebut? Harap diperhatikan bahwa setelah melakukan proses tersebut anda tidak dapat membatalkan proses pelewatan periode tersebut.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#modal-dep-view">
                        <i class="bi bi-backspace"></i>
                        Tidak
                    </button>
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-check-lg"></i>
                        Ya
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<!--DELETE PERIOD-->
<div class="modal fade" id="modal-per-delete-{{ $period->id_period }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.masters.periods.destroy', $period->id_period) }}" method="POST" enctype="multipart/form-data">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Hapus Periode ({{ $period->name }})</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning" role="alert">
                        <i class="bi bi-exclamation-triangle-fill"></i> <b>PERHATIAN</b>
                        <br/>
                        Apakah anda ingin menghapus periode tersebut?
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#modal-dep-view">
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
