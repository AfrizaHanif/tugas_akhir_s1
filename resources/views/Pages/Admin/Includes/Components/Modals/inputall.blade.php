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
                                <td>{{ $officer->department->name }}</td>
                                @if ($countsub != 0)
                                    @foreach ($criterias as $criteria)
                                        @forelse ($inputs->where('id_criteria', $criteria->id_criteria)->where('id_officer', $officer->id_officer)->where('id_period', $period->id_period) as $input)
                                            @foreach ($input_raws->where('id_input_raw', $input->id_input) as $raw)
                                            <td>{{ $input->input }} ({{ $raw->input }})</td>
                                            @endforeach
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
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-lg"></i>
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>
@endforeach
