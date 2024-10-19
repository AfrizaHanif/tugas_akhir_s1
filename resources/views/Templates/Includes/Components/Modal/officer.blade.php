@foreach ($officers as $officer)
    <!--VIEW DETAILS PER OFFICER-->
    <div class="modal modal-lg fade" id="modal-off-view-{{ $officer->id_officer }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Detail Pegawai ({{ $officer->id_officer }})</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row justify-content-center g-3">
                        <div class="col-md-4">
                            <div class="position-sticky" style="top: 0rem;">
                                <img src="{{ url('Images/Portrait/'.$officer->photo) }}" onerror="this.onerror=null; this.src='{{ asset('Images/Default/Portrait.png') }}'" class="img-thumbnail rounded">
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="position-sticky" style="top: 0rem;">
                                <table class="table">
                                    <tr>
                                        <th scope="row">NIP (ID)</th>
                                        <td>{{ $officer->id_officer }}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Nama Pegawai</th>
                                        <td>{{ $officer->name }}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Jabatan</th>
                                        <td>{{ $officer->position->name }}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Tim Utama</th>
                                        <td>{{ $officer->subteam_1->team->name }} ({{ $officer->subteam_1->name }})</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Tim Cadangan</th>
                                        <td>{{ $officer->subteam_2->team->name ?? 'Tidak Ada' }} ({{ $officer->subteam_2->name ?? 'Tidak Ada' }})</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">E-Mail</th>
                                        <td>{{ $officer->email }}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Nomor Telepon</th>
                                        <td>{{ $officer->phone }}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Tempat Tanggal Lahir</th>
                                        <td>{{ $officer->place_birth }}, {{ date('d F Y', strtotime($officer->date_birth)) }}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Umur</th>
                                        <td>{{ \Carbon\Carbon::parse($officer->date_birth)->diff(\Carbon\Carbon::now())->format('%y Tahun, %m Bulan, dan %d Hari'); }}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Jenis Kelamin</th>
                                        <td>{{ $officer->gender }}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Agama</th>
                                        <td>{{ $officer->religion }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
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
@endforeach
