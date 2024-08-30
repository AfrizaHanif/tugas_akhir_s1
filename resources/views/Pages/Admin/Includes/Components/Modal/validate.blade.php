@foreach ($periods as $period)
<!--VIEW SCORES-->
<div class="modal modal-lg fade" id="modal-stt-view-{{ $period->id_period }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Cek Status ({{ $period->name }})</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                        <thead>
                            <tr class="table-primary">
                                <th class="col-1" scope="col">#</th>
                                <th scope="col">Nama</th>
                                <th scope="col">Jabatan</th>
                                <th scope="col">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($officers as $officer)
                            <tr>
                                <th scope="row">{{ $loop->iteration }}</th>
                                <td>{{ $officer->name }}</td>
                                <td>{{ $officer->position->name }}</td>
                                <td>
                                    @forelse ($status->where('id_officer', $officer->id_officer)->where('id_period', $period->id_period) as $s)
                                        @if ($s->status == 'Pending')
                                        <span class="badge text-bg-primary">Belum Diperiksa</span>
                                        @elseif ($s->status == 'In Review')
                                        <span class="badge text-bg-warning">Dalam Pemeriksaan</span>
                                        @elseif ($s->status == 'Final')
                                        <span class="badge text-bg-success">Hasil Akhir</span>
                                        @elseif ($s->status == 'Need Fix')
                                        <span class="badge text-bg-danger">Perlu Perbaikan</span>
                                        @endif
                                    @empty
                                    <span class="badge text-bg-secondary">Blank</span>
                                    @endforelse
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="10">Tidak ada Pegawai yang terdaftar</td>
                            </tr>
                            @endforelse
                        </tbody>
                        <tfoot class="table-group-divider table-secondary">
                            <tr>
                                <td colspan="20">Total Data: <b>{{ $officers->count() }}</b> Pegawai</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-lg"></i>
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>
<!--CLOSE SESSION-->
<div class="modal fade" id="modal-scr-finish-{{ $period->id_period }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.inputs.validate.finish', $period->id_period) }}" method="POST" enctype="multipart/form-data">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Kunci Data ({{ $period->name }})</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @csrf
                    <div class="mb-3">
                        <div class="col">
                            <input type="text" class="form-control" id="id_period" name="id_period" value="{{ $period->id_period }}" hidden>
                        </div>
                    </div>
                    <div class="alert alert-warning" role="alert">
                        <i class="bi bi-exclamation-triangle-fill"></i> <b>PERHATIAN</b>
                        <br/>
                        Apakah anda telah selesai melakukan validasi dan mulai pelaksanaan voting? Proses ini akan mengunci perubahan yang ada di periode tersebut. Jika sudah dikunci, data tersebut tidak dapat diubah dan dihapus kembali untuk menghindari hal-hal yang tidak diinginkan.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-lg"></i>
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
<!--GET SCORE (ANALYSIS SAW)-->
<div class="modal fade" id="modal-scr-get-{{ $period->id_period }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.inputs.validate.get', $period->id_period) }}" method="POST" enctype="multipart/form-data">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Ambil Data ({{ $period->name}})</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @csrf
                    <div class="mb-3">
                        <div class="col">
                            <input type="text" class="form-control" id="id_period" name="id_period" value="{{ $period->id_period }}" hidden>
                        </div>
                    </div>
                    <div class="alert alert-warning" role="alert">
                        <i class="bi bi-exclamation-triangle-fill"></i> <b>PERHATIAN</b>
                        <br/>
                        Apakah anda ingin mengambil data hasil analisis pada periode ini? Jika ya, data tersebut akan menghapus data sebelumnya dan menggantikan dengan yang baru.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-lg"></i>
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
<!--ACCEPT ALL-->
<div class="modal fade" id="modal-scr-yesall-{{ $period->id_period }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.inputs.validate.yesall', $period->id_period) }}" method="POST" enctype="multipart/form-data">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Penyetujuan Hasil Akhir ({{ $period->id_period}})</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @csrf
                    <div class="mb-3">
                        <div class="col">
                            <input type="text" class="form-control" id="id" name="id" value="{{ $period->id_period }}" hidden>
                        </div>
                    </div>
                    <div class="alert alert-warning" role="alert">
                        <i class="bi bi-exclamation-triangle-fill"></i> <b>PERHATIAN</b>
                        <br/>
                        Apakah anda ingin menyetujui seluruh hasil penilaian ini? Jika ya, data tersebut akan disimpan sebagai hasil akhir.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-lg"></i>
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
<!--REJECT ALL-->
<div class="modal fade" id="modal-scr-noall-{{ $period->id_period }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.inputs.validate.noall', $period->id_period) }}" method="POST" enctype="multipart/form-data">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Penolakan Hasil Akhir ({{ $period->id_period}})</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @csrf
                    <div class="mb-3">
                        <div class="col">
                            <input type="text" class="form-control" id="id" name="id" value="{{ $period->id_period }}" hidden>
                        </div>
                    </div>
                    <div class="alert alert-warning" role="alert">
                        <i class="bi bi-exclamation-triangle-fill"></i> <b>PERHATIAN</b>
                        <br/>
                        Apakah anda ingin tidak menyetujui seluruh hasil penilaian ini? Jika ya, data tersebut akan dikembalikan oleh penilai.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-lg"></i>
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
    @foreach ($scores as $score)
    <!--ACCEPT-->
    <div class="modal fade" id="modal-scr-yes-{{ $period->id_period }}-{{ $score->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('admin.inputs.validate.yes', $score->id) }}" method="POST" enctype="multipart/form-data">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Penyetujuan Hasil Akhir ({{ $score->id}})</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        @csrf
                        <div class="mb-3">
                            <div class="col">
                                <input type="text" class="form-control" id="id" name="id" value="{{ $score->id }}" hidden>
                            </div>
                        </div>
                        <div class="alert alert-warning" role="alert">
                            <i class="bi bi-exclamation-triangle-fill"></i> <b>PERHATIAN</b>
                            <br/>
                            Apakah anda ingin menyetujui hasil penilaian ini? Jika ya, data tersebut akan disimpan sebagai hasil akhir.
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="bi bi-x-lg"></i>
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
    <!--REJECT-->
    <div class="modal fade" id="modal-scr-no-{{ $period->id_period }}-{{ $score->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('admin.inputs.validate.no', $score->id) }}" method="POST" enctype="multipart/form-data">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Penolakan Hasil Akhir ({{ $score->id}})</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        @csrf
                        <div class="mb-3">
                            <div class="col">
                                <input type="text" class="form-control" id="id" name="id" value="{{ $score->id }}" hidden>
                            </div>
                        </div>
                        <div class="alert alert-warning" role="alert">
                            <i class="bi bi-exclamation-triangle-fill"></i> <b>PERHATIAN</b>
                            <br/>
                            Apakah anda ingin tidak menyetujui hasil penilaian ini? Jika ya, data tersebut akan dikembalikan oleh penilai.
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="bi bi-x-lg"></i>
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
    @endforeach
@endforeach
