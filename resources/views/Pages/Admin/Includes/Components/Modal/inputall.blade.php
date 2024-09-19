@foreach ($periods as $period)
<!--VIEW INPUTS PER PERIOD-->
<div class="modal modal-xl fade" id="modal-inp-view-{{ $period->id_period }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Detail Data Nilai ({{ $period->name }})</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                @if (Auth::user()->part == 'Admin')
                    @if (!empty($latest_per))
                        @if ($status->where('id_period', $latest_per->id_period)->where('status', 'Not Converted')->count() >= 1)
                        <div class="alert alert-warning" role="alert">
                            <i class="bi bi-exclamation-triangle-fill"></i> <b>PERHATIAN</b>
                            <br/>
                            Blok kuning merupakan data yang belum dilakukan konversi. Silahkan lakukan konversi data dengan menekan tombol <strong>Convert</strong> apabila telah menyelesaikan pemeriksaan data.
                        </div>
                        @endif
                    @endif
                @endif
                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                        <thead>
                            <tr class="table-primary">
                                <th rowspan="2" class="col-1" scope="col">#</th>
                                <th rowspan="2" scope="col">Nama</th>
                                <th rowspan="2" scope="col">Jabatan</th>
                                @if ($countsub != 0)
                                <th colspan="{{ $countsub }}" scope="col">Kriteria</th>
                                @else
                                <th rowspan="2" scope="col">Kriteria</th>
                                @endif
                                <th rowspan="2" scope="col">Status</th>
                            </tr>
                            <tr class="table-secondary">
                                @foreach ($criterias as $criteria)
                                <th>{{ $criteria->name }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($officers as $officer)
                            @if ($officer->is_lead == 'Yes')
                            <tr class="table-secondary">
                            @else
                            <tr>
                            @endif
                                <th scope="row">{{ $loop->iteration }}</th>
                                <td>{{ $officer->name }}</td>
                                <td>{{ $officer->position->name }}</td>
                                @if ($countsub != 0)
                                    @foreach ($criterias as $criteria)
                                        @forelse ($inputs->where('id_criteria', $criteria->id_criteria)->where('id_officer', $officer->id_officer)->where('id_period', $period->id_period) as $input)
                                            @if ($input->status == 'Not Converted')
                                            <td class="table-warning">{{ $input->input }}</td>
                                            @else
                                            <td>{{ $input->input }} ({{ $input->input_raw }})</td>
                                            @endif
                                        @empty
                                            <td>0</td>
                                        @endforelse
                                    @endforeach
                                @else
                                <td colspan="3">
                                    <span class="badge text-bg-secondary">Kriteria Kosong</span>
                                </td>
                                @endif
                                @if ($countsub != 0)
                                <td>
                                    @if ($inputs->where('id_officer', $officer->id_officer)->where('id_period', $period->id_period)->count() == $countsub)
                                    <span class="badge text-bg-primary">Terisi Semua</span>
                                    @elseif ($inputs->where('id_officer', $officer->id_officer)->where('id_period', $period->id_period)->count() == 0)
                                    <span class="badge text-bg-danger">Tidak Terisi</span>
                                    @else
                                    <span class="badge text-bg-warning">Terisi Sebagian</span>
                                    @endif
                                </td>
                                @endif
                            </tr>
                            @empty
                            <tr>
                                <td colspan="99">Tidak ada Pegawai yang terdaftar</td>
                            </tr>
                            @endforelse
                        </tbody>
                        <tfoot class="table-group-divider table-secondary">
                            <tr>
                                <td colspan="99">Total Data: <b>{{ $officers->count() }}</b> Pegawai</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                @if (Auth::user()->part == 'Admin')
                    @if (!empty($latest_per))
                        @if ($status->where('id_period', $latest_per->id_period)->where('status', 'Not Converted')->count() >= 1)
                        <a class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-inp-convert-{{ $latest_per->id_period }}">
                            <i class="bi bi-arrow-clockwise"></i>
                            Convert
                        </a>
                        @endif
                    @endif
                @endif
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-lg"></i>
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>
    @foreach ($officers as $officer)
    @if (!empty($latest_per))
    <!--VIEW INPUT PER OFFICER-->
    <div class="modal fade" id="modal-inp-view-{{ $latest_per->id_period }}-{{ $officer->id_officer }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Detail Data Penilaian ({{ $officer->id_officer }})</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table class="table">
                    @foreach ($criterias as $criteria)
                        @forelse ($inputs->where('id_criteria', $criteria->id_criteria)->where('id_officer', $officer->id_officer)->where('id_period', $latest_per->id_period) as $input)
                        <tr>
                            <th scope="row">{{ $criteria->name }}</th>
                            <td>
                                @if ($criteria->need == 'Ya')
                                <b>{{ $input->input }} ({{ $input->input_raw }})</b>
                                @else
                                {{ $input->input }} ({{ $input->input_raw }})
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <th scope="row">{{ $criteria->name }}</th>
                            <td>
                                @if ($criteria->need == 'Ya')
                                <b>0</b>
                                @else
                                0
                                @endif
                            </td>
                        </tr>
                        @endforelse
                    @endforeach
                    </table>
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
    @endif
    @endforeach
@endforeach

@foreach ($history_per as $hperiod)
<!--VIEW OLD INPUTS-->
<div class="modal modal-xl fade" id="modal-old-all-view-{{ $hperiod->id_period }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Detail Seluruh Data ({{ $hperiod->period_name }})</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                        <thead>
                            <tr class="table-primary">
                                <th rowspan="2" class="col-1" scope="col">#</th>
                                <th rowspan="2" scope="col">Nama</th>
                                <th rowspan="2" scope="col">Jabatan</th>
                                @if ($countsub != 0)
                                <th colspan="{{ $countsub }}" scope="col">Kriteria</th>
                                @else
                                <th rowspan="2" scope="col">Kriteria</th>
                                @endif
                            </tr>
                            <tr class="table-secondary">
                                @foreach ($criterias as $criteria)
                                <th>{{ $criteria->name }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($hofficers->where('id_period', $hperiod->id_period) as $hofficer)
                            <tr>
                                <th scope="row">{{ $loop->iteration }}</th>
                                <td>{{ $hofficer->officer_name }}</td>
                                <td>{{ $hofficer->officer_position }}</td>
                                @foreach ($hcriterias as $criteria)
                                    @forelse ($histories->where('id_criteria', $criteria->id_criteria)->where('id_officer', $hofficer->id_officer)->where('id_period', $hperiod->id_period) as $history)
                                        <td>{{ $history->input }} ({{ $history->input_raw }})</td>
                                    @empty
                                        <td>0</td>
                                    @endforelse
                                @endforeach
                            </tr>
                            @empty
                            <tr>
                                <td colspan="20">Tidak ada Pegawai yang terdaftar</td>
                            </tr>
                            @endforelse
                        </tbody>
                        <tfoot class="table-group-divider table-secondary">
                            <tr>
                                <td colspan="20">Total Data: <b>{{ $hofficers->count() }}</b> Pegawai</td>
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
    <!--ARCHIVED PERIOD-->
    @foreach ($hofficers as $officer)
    <div class="modal fade" id="modal-old-inp-view-{{ $hperiod->id_period }}-{{ $officer->id_officer }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Detail Data Penilaian ({{ $officer->id_officer }})</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table class="table">
                        @foreach ($hcriterias as $criteria)
                        @forelse ($histories->where('id_criteria', $criteria->id_criteria)->where('id_officer', $officer->id_officer)->where('id_period', $hperiod->id_period) as $history)
                        <tr>
                            <th scope="row">{{ $criteria->criteria_name }}</th>
                            <td>{{ $history->input }} ({{ $history->input_raw }})</td>
                        </tr>
                        @empty
                        <tr>
                            <th scope="row">{{ $criteria->criteria_name }}</th>
                            <td>0</td>
                        </tr>
                        @endforelse
                    @endforeach
                    </table>
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
@endforeach
