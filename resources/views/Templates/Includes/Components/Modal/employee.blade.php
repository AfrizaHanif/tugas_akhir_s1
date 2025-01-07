@foreach ($employees as $employee)
    <!--VIEW DETAILS PER EMPLOYEE-->
    <div class="modal modal-lg fade" id="modal-emp-view-{{ $employee->id_employee }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Detail Karyawan ({{ $employee->id_employee }})</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row justify-content-center g-3">
                        <div class="col-md-4">
                            <div class="position-sticky" style="top: 0rem;">
                                <img src="{{ url('Images/Portrait/'.$employee->photo) }}" onerror="this.onerror=null; this.src='{{ asset('Images/Default/Portrait.png') }}'" class="img-thumbnail rounded">
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="position-sticky" style="top: 0rem;">
                                <table class="table">
                                    <tr>
                                        <th scope="row">NIP (ID)</th>
                                        <td>{{ $employee->id_employee }}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Nama Karyawan</th>
                                        <td>{{ $employee->name }}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Jabatan</th>
                                        <td>{{ $employee->position->name }}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Tim Utama</th>
                                        <td>{{ $employee->subteam_1->team->name }} ({{ $employee->subteam_1->name }})</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Tim Cadangan</th>
                                        @if (empty($employee->subteam_2->team->name))
                                        <td>Tidak Ada</td>
                                        @else
                                        <td>{{ $employee->subteam_2->team->name ?? 'Tidak Ada' }} ({{ $employee->subteam_2->name ?? 'Tidak Ada' }})</td>
                                        @endif
                                    </tr>
                                    <tr>
                                        <th scope="row">E-Mail</th>
                                        <td>{{ $employee->email }}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Nomor Telepon</th>
                                        <td>{{ $employee->phone }}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Tempat Tanggal Lahir</th>
                                        <td>{{ $employee->place_birth }}, {{ date('d F Y', strtotime($employee->date_birth)) }}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Umur</th>
                                        <td>{{ \Carbon\Carbon::parse($employee->date_birth)->diff(\Carbon\Carbon::now())->format('%y Tahun, %m Bulan, dan %d Hari'); }}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Jenis Kelamin</th>
                                        <td>{{ $employee->gender }}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Agama</th>
                                        <td>{{ $employee->religion }}</td>
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
