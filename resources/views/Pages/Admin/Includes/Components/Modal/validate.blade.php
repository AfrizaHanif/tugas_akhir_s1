@foreach ($periods as $period)
<!--VIEW SCORES-->
<div class="modal modal-lg fade" id="modal-stt-view-{{ $period->id_period }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
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
                            @forelse ($employees as $employee)
                            <tr>
                                <th scope="row">{{ $loop->iteration }}</th>
                                <td>{{ $employee->name }}</td>
                                <td>{{ $employee->position->name }}</td>
                                <td>
                                    @forelse ($status->where('id_employee', $employee->id_employee)->where('id_period', $period->id_period) as $s)
                                        @if ($s->status == 'Pending')
                                        <span class="badge text-bg-primary">Belum Diperiksa</span>
                                        @elseif ($s->status == 'In Review')
                                        <span class="badge text-bg-warning">Dalam Pemeriksaan</span>
                                        @elseif ($s->status == 'Final')
                                        <span class="badge text-bg-success">Nilai Akhir</span>
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
                                <td colspan="10">Tidak ada Karyawan yang terdaftar</td>
                            </tr>
                            @endforelse
                        </tbody>
                        <tfoot class="table-group-divider table-secondary">
                            <tr>
                                <td colspan="20">Total Data: <b>{{ $employees->count() }}</b> Karyawan</td>
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
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Penutupan Proses ({{ $period->name }})</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="form-scr-finish-{{ $period->id_period }}" action="{{ route('admin.inputs.validate.finish', $period->id_period) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3" hidden>
                            <div class="col" hidden>
                                <input type="text" class="form-control" id="id_period" name="id_period" value="{{ $period->id_period }}" hidden>
                            </div>
                        </div>
                        <div class="alert alert-warning" role="alert">
                            <i class="bi bi-exclamation-triangle-fill"></i> <b>PERHATIAN</b>
                            <br/>
                            Apakah anda telah selesai melakukan verifikasi?
                            <ul>
                                <li>Proses ini juga akan <strong>mengakhiri proses penentuan karyawan terbaik</strong>.</li>
                                <li>Data Nilai, Data Nilai Akhir, dan karyawan terpilih sebagai karyawan terbaik akan dipindahkan ke <strong>riwayat</strong> yang tidak dapat diubah atau dihapus kembali <strong>(Permanen)</strong>.</li>
                                <li>Kepegawaian dapat melakukan penambahan, perubahan, dan penghapusan data <strong>master</strong>.</li>
                                <li><strong>Laporan</strong> akan tersedia secara langsung oleh sistem setelah proses ini selesai.</li>
                            </ul>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-lg"></i>
                        Tidak
                    </button>
                    <button type="submit" form="form-scr-finish-{{ $period->id_period }}" class="btn btn-primary">
                        <i class="bi bi-check-lg"></i>
                        Ya
                    </button>
                </div>
        </div>
    </div>
</div>
<!--GET SCORE (ANALYSIS SAW)-->
<div class="modal fade" id="modal-scr-get-{{ $period->id_period }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Ambil Data ({{ $period->name}})</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="form-scr-get-{{ $period->id_period }}" action="{{ route('admin.inputs.validate.get', $period->id_period) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3" hidden>
                        <div class="col" hidden>
                            <input type="text" class="form-control" id="id_period" name="id_period" value="{{ $period->id_period }}" hidden>
                        </div>
                    </div>
                    <div class="alert alert-warning" role="alert">
                        <i class="bi bi-exclamation-triangle-fill"></i> <b>PERHATIAN</b>
                        <br/>
                        Apakah anda ingin mengambil data nilai akhir pada periode ini?
                        <ul>
                            <li>Data nilai akhir yang lama akan <strong>digantikan</strong> dengan nilai akhir yang baru.</li>
                            <li>Jika terdapat Revisi, seluruh status dari nilai akhir yang telah disetujui akan <strong>terhapus</strong> dan diperlukan <strong>verifikasi ulang</strong> yang dikarenakan terdapat <strong>perubahan</strong> data nilai akhir dari seluruh karyawan.</li>
                            <li>Kepegawaian <strong>tidak dapat</strong> melakukan penambahan, perubahan, dan penghapusan <strong>data master</strong> selama proses verifikasi berlangsung.</li>
                            <li>Tidak dapat mengambil <strong>ambil data</strong> kembali selama proses verifikasi berlangsung (Kecuali jika ada <strong>Revisi</strong>).</li>
                        </ul>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-lg"></i>
                    Tidak
                </button>
                <button type="submit" form="form-scr-get-{{ $period->id_period }}" class="btn btn-primary">
                    <i class="bi bi-check-lg"></i>
                    Ya
                </button>
            </div>
        </div>
    </div>
</div>
<!--ACCEPT ALL-->
<div class="modal fade" id="modal-scr-yesall-{{ $period->id_period }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Persetujuan Nilai Akhir ({{ $period->name}})</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="form-scr-yesall-{{ $period->id_period }}" action="{{ route('admin.inputs.validate.yesall', $period->id_period) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3" hidden>
                        <div class="col" hidden>
                            <input type="text" class="form-control" id="id" name="id" value="{{ $period->id_period }}" hidden>
                        </div>
                    </div>
                    <div class="alert alert-warning" role="alert">
                        <i class="bi bi-exclamation-triangle-fill"></i> <b>PERHATIAN</b>
                        <br/>
                        Apakah anda ingin menyetujui seluruh hasil penilaian ini?
                        <ul>
                            <li>Nilai akhir yang telah disetujui dapat berubah sewaktu-waktu ketika terdapat revisi yang ada dari karyawan lain.</li>
                        </ul>
                    </div>
                </form>
                <form id="form-scr-yesall-remain-{{ $period->id_period }}" action="{{ route('admin.inputs.validate.yesall.remain', $period->id_period) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                </form>
            </div>
            <div class="modal-footer">
                @if (!empty($latest_per))
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-lg"></i>
                        Tidak
                    </button>
                    @if ($scores->where('id_period', $latest_per->id_period)->whereIn('status', ['Accepted'])->count() == count($employees))
                    <span class="d-inline-block" tabindex="0" data-bs-toggle="tooltip" data-bs-title="Semua nilai telah dilakukan persetujuan.">
                        <button type="submit" class="btn btn-secondary" disabled>
                            <i class="bi bi-check-lg"></i>
                            Ya (Sebagian)
                        </button>
                    </span>
                    @elseif ($scores->where('id_period', $latest_per->id_period)->whereIn('status', ['Rejected'])->count() == count($employees))
                    <span class="d-inline-block" tabindex="0" data-bs-toggle="tooltip" data-bs-title="Semua nilai telah dilakukan penolakan.">
                        <button type="submit" class="btn btn-secondary" disabled>
                            <i class="bi bi-check-lg"></i>
                            Ya (Sebagian)
                        </button>
                    </span>
                    @elseif ($scores->where('id_period', $latest_per->id_period)->whereIn('status', [ 'Revised'])->count() == count($employees))
                    <span class="d-inline-block" tabindex="0" data-bs-toggle="tooltip" data-bs-title="Semua nilai telah dilakukan revisi.">
                        <button type="submit" class="btn btn-secondary" disabled>
                            <i class="bi bi-check-lg"></i>
                            Ya (Sebagian)
                        </button>
                    </span>
                    @elseif ($scores->where('id_period', $latest_per->id_period)->whereIn('status', ['Accepted'])->count() >= 1 && $scores->where('id_period', $latest_per->id_period)->whereIn('status', ['Rejected', 'Revised'])->count() >= 1 && $scores->where('id_period', $latest_per->id_period)->whereIn('status', ['Pending'])->count() == 0)
                    <span class="d-inline-block" tabindex="0" data-bs-toggle="tooltip" data-bs-title="Semua nilai telah dilakukan pemeriksaan.">
                        <button type="submit" class="btn btn-secondary" disabled>
                            <i class="bi bi-check-lg"></i>
                            Ya (Sebagian)
                        </button>
                    </span>
                    @else
                    <button type="submit" form="form-scr-yesall-remain-{{ $period->id_period }}" class="btn btn-warning">
                        <i class="bi bi-check-lg"></i>
                        Ya (Sebagian)
                    </button>
                    @endif
                    @if ($scores->where('id_period', $latest_per->id_period)->whereIn('status', ['Accepted'])->count() == count($employees))
                    <span class="d-inline-block" tabindex="0" data-bs-toggle="tooltip" data-bs-title="Semua nilai telah dilakukan persetujuan.">
                        <button type="submit" class="btn btn-secondary" disabled>
                            <i class="bi bi-check-lg"></i>
                            Ya (Semua)
                        </button>
                    </span>
                    @elseif ($scores->where('id_period', $latest_per->id_period)->whereIn('status', ['Rejected'])->count() >= 1)
                    <span class="d-inline-block" tabindex="0" data-bs-toggle="tooltip" data-bs-title="Beberapa nilai telah dilakukan penolakan.">
                        <button type="submit" class="btn btn-secondary" disabled>
                            <i class="bi bi-check-lg"></i>
                            Ya (Semua)
                        </button>
                    </span>
                    @elseif ($scores->where('id_period', $latest_per->id_period)->whereIn('status', ['Revised'])->count() >= 1)
                    <span class="d-inline-block" tabindex="0" data-bs-toggle="tooltip" data-bs-title="Beberapa nilai telah dilakukan revisi.">
                        <button type="submit" class="btn btn-secondary" disabled>
                            <i class="bi bi-check-lg"></i>
                            Ya (Semua)
                        </button>
                    </span>
                    @else
                    <button type="submit" form="form-scr-yesall-{{ $period->id_period }}" class="btn btn-primary">
                        <i class="bi bi-check-lg"></i>
                        Ya (Semua)
                    </button>
                    @endif
                @endif
            </div>
        </div>
    </div>
</div>
<!--REJECT ALL-->
<div class="modal fade" id="modal-scr-noall-{{ $period->id_period }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Penolakan Nilai Akhir ({{ $period->name}})</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                    <form id="form-scr-noall-{{ $period->id_period }}" action="{{ route('admin.inputs.validate.noall', $period->id_period) }}" method="POST" enctype="multipart/form-data">
                    <div class="mb-3" hidden>
                        @csrf
                        <div class="col" hidden>
                            <input type="text" class="form-control" id="id" name="id" value="{{ $period->id_period }}" hidden>
                        </div>
                    </div>
                    <div class="alert alert-warning" role="alert">
                        <i class="bi bi-exclamation-triangle-fill"></i> <b>PERHATIAN</b>
                        <br/>
                        Apakah anda ingin tidak menyetujui seluruh hasil penilaian ini?
                        <ul>
                            <li>Segera hubungi bagian <strong>Kepegawaian</strong> untuk dilakukannya pemeriksaan dan Import ulang pada seluruh data nilai yang ditolak.</li>
                            <li>Status nilai akhir yang ditolak akan berubah menjadi telah direvisi apabila Kepegawaian telah melakukan Import ulang.</li>
                            <li>Segera lakukan pengambilan data <strong>nilai akhir</strong> setelah seluruh nilai akhir yang ditolak telah direvisi.</li>
                        </ul>
                    </div>
                </form>
                <form id="form-scr-noall-remain-{{ $period->id_period }}" action="{{ route('admin.inputs.validate.noall.remain', $period->id_period) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                </form>
            </div>
            <div class="modal-footer">
                @if (!empty($latest_per))
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-lg"></i>
                        Tidak
                    </button>
                    @if ($scores->where('id_period', $latest_per->id_period)->whereIn('status', ['Accepted'])->count() == count($employees))
                    <span class="d-inline-block" tabindex="0" data-bs-toggle="tooltip" data-bs-title="Semua nilai telah dilakukan persetujuan.">
                        <button type="submit" class="btn btn-secondary" disabled>
                            <i class="bi bi-check-lg"></i>
                            Ya (Sebagian)
                        </button>
                    </span>
                    @elseif ($scores->where('id_period', $latest_per->id_period)->whereIn('status', ['Rejected'])->count() == count($employees))
                    <span class="d-inline-block" tabindex="0" data-bs-toggle="tooltip" data-bs-title="Semua nilai telah dilakukan penolakan.">
                        <button type="submit" class="btn btn-secondary" disabled>
                            <i class="bi bi-check-lg"></i>
                            Ya (Sebagian)
                        </button>
                    </span>
                    @elseif ($scores->where('id_period', $latest_per->id_period)->whereIn('status', ['Revised'])->count() == count($employees))
                    <span class="d-inline-block" tabindex="0" data-bs-toggle="tooltip" data-bs-title="Semua nilai telah dilakukan revisi.">
                        <button type="submit" class="btn btn-secondary" disabled>
                            <i class="bi bi-check-lg"></i>
                            Ya (Sebagian)
                        </button>
                    </span>
                    @elseif ($scores->where('id_period', $latest_per->id_period)->whereIn('status', ['Accepted'])->count() >= 1 && $scores->where('id_period', $latest_per->id_period)->whereIn('status', ['Rejected', 'Revised'])->count() >= 1 && $scores->where('id_period', $latest_per->id_period)->whereIn('status', ['Pending'])->count() == 0)
                    <span class="d-inline-block" tabindex="0" data-bs-toggle="tooltip" data-bs-title="Semua nilai telah dilakukan pemeriksaan.">
                        <button type="submit" class="btn btn-secondary" disabled>
                            <i class="bi bi-check-lg"></i>
                            Ya (Sebagian)
                        </button>
                    </span>
                    @else
                    <button type="submit" form="form-scr-noall-remain-{{ $period->id_period }}" class="btn btn-warning">
                        <i class="bi bi-check-lg"></i>
                        Ya (Sebagian)
                    </button>
                    @endif
                    @if ($scores->where('id_period', $latest_per->id_period)->whereIn('status', ['Rejected'])->count() == count($employees))
                    <span class="d-inline-block" tabindex="0" data-bs-toggle="tooltip" data-bs-title="Semua nilai telah dilakukan penolakan.">
                        <button type="submit" class="btn btn-secondary" disabled>
                            <i class="bi bi-check-lg"></i>
                            Ya (Semua)
                        </button>
                    </span>
                    @elseif ($scores->where('id_period', $latest_per->id_period)->whereIn('status', ['Revised'])->count() >= 1)
                    <span class="d-inline-block" tabindex="0" data-bs-toggle="tooltip" data-bs-title="Beberapa nilai telah dilakukan revisi.">
                        <button type="submit" class="btn btn-secondary" disabled>
                            <i class="bi bi-check-lg"></i>
                            Ya (Semua)
                        </button>
                    </span>
                    @else
                    <button type="submit" form="form-scr-noall-{{ $period->id_period }}" class="btn btn-danger">
                        <i class="bi bi-check-lg"></i>
                        Ya (Semua)
                    </button>
                    @endif
                @endif
            </div>
        </div>
    </div>
</div>
    @foreach ($scores as $score)
    <!--ACCEPT-->
    <div class="modal fade" id="modal-scr-yes-{{ $period->id_period }}-{{ $score->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Persetujuan Nilai Akhir ({{ $score->employee->name}})</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="form-scr-yes-{{ $period->id_period }}-{{ $score->id }}" action="{{ route('admin.inputs.validate.yes', $score->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3" hidden>
                            <div class="col" hidden>
                                <input type="text" class="form-control" id="id" name="id" value="{{ $score->id }}" hidden>
                            </div>
                        </div>
                        <div class="alert alert-warning" role="alert">
                            <i class="bi bi-exclamation-triangle-fill"></i> <b>PERHATIAN</b>
                            <br/>
                            Apakah anda ingin menyetujui hasil penilaian ini?
                            <ul>
                                <li>Nilai akhir yang telah disetujui dapat berubah sewaktu-waktu ketika terdapat revisi yang ada dari karyawan lain.</li>
                            </ul>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-lg"></i>
                        Tidak
                    </button>
                    <button type="submit" form="form-scr-yes-{{ $period->id_period }}-{{ $score->id }}" class="btn btn-primary">
                        <i class="bi bi-check-lg"></i>
                        Ya
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!--REJECT-->
    <div class="modal fade" id="modal-scr-no-{{ $period->id_period }}-{{ $score->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Penolakan Nilai Akhir ({{ $score->employee->name}})</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="form-scr-no-{{ $period->id_period }}-{{ $score->id }}" action="{{ route('admin.inputs.validate.no', $score->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3" hidden>
                            <div class="col" hidden>
                                <input type="text" class="form-control" id="id" name="id" value="{{ $score->id }}" hidden>
                            </div>
                        </div>
                        <div class="alert alert-warning" role="alert">
                            <i class="bi bi-exclamation-triangle-fill"></i> <b>PERHATIAN</b>
                            <br/>
                            Apakah anda ingin tidak menyetujui hasil penilaian ini? Jika ya, data tersebut akan dikembalikan oleh penilai.
                            <ul>
                                <li>Segera hubungi bagian <strong>Kepegawaian</strong> untuk dilakukannya pemeriksaan dan Import ulang pada data nilai yang ditolak.</li>
                                <li>Status nilai akhir yang ditolak akan berubah menjadi telah direvisi apabila Kepegawaian telah melakukan Import ulang.</li>
                                <li>Segera lakukan pengambilan data <strong>nilai akhir</strong> setelah seluruh nilai akhir yang ditolak telah direvisi.</li>
                            </ul>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-lg"></i>
                        Tidak
                    </button>
                    <button type="submit" form="form-scr-no-{{ $period->id_period }}-{{ $score->id }}" class="btn btn-danger">
                        <i class="bi bi-check-lg"></i>
                        Ya
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endforeach
@endforeach
