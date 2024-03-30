@if (Request::is('officer/officers*'))
    @foreach ($officers as $officer)
    <div class="modal fade" id="modal-off-view-{{ $officer->id_officer }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Detail Pegawai ({{ $officer->id_officer }})</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table class="table">
                        <tr>
                            <th scope="row">Nama Pegawai</th>
                            <td>{{ $officer->name }}</td>
                        </tr>
                        <tr>
                            <th scope="row">Jabatan</th>
                            <td>{{ $officer->department->name }}</td>
                        </tr>
                        <tr>
                            <th scope="row">Bagian</th>
                            <td>{{ $officer->part->name }}</td>
                        </tr>
                        <tr>
                            <th scope="row">Tempat Tanggal Lahir</th>
                            <td>{{ $officer->place_birth }}, {{ date('d F Y', strtotime($officer->date_birth)) }}</td>
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
@endif

@if (Request::is('officer/votes*'))
<div class="modal fade" id="modal-vte-periods" tabindex="-1" aria-labelledby="modalsaw" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="modalsaw">Pilih Periode</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3 align-items-center">
                    <div class="col-auto">
                        <label for="tahun_saw_dl" class="col-form-label">Pilih Tahun</label>
                    </div>
                    <div class="col-auto dropend">
                        <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-calendar3-event-fill"></i>
                        </button>
                        <ul class="dropdown-menu" style="max-height: 180px; overflow-y: auto;">
                            @forelse ( $periods as $period )
                            <li><a class="dropdown-item" href="/officer/votes/{{ $period->id_period }}">{{ $period->name }}</a></li>
                            @empty
                            <li><a class="dropdown-item disabled" href="#" aria-disabled="true">Tidak ada data</a></li>
                            @endforelse
                        </ul>
                    </div>
                    <div class="col-auto">
                        <span id="tahun_help_saw_dl" class="form-text">
                            Antara 2010 sampai sekarang
                        </span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endif

@if (Request::is('officer/votes/*'))
    @foreach ($criterias as $criteria)
        @foreach ($votes->where('id_period', $prd_select->id_period)->where('id_vote_criteria', $criteria->id_vote_criteria) as $vote)
        <div class="modal fade" id="modal-vte-select-{{ $prd_select->id_period }}-{{ $vote->id_officer }}-{{ $criteria->id_vote_criteria }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{ route('officer.votes.select', ['period'=>$prd_select->id_period, 'officer'=>$vote->id_officer, 'criteria'=>$criteria->id_vote_criteria]) }}" method="POST" enctype="multipart/form-data">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="exampleModalLabel">Pilih Pegawai ({{ $vote->id_officer }}) ({{ $criteria->id_vote_criteria }})</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            @csrf
                            <div class="mb-3">
                                <div class="col">
                                    <input type="text" class="form-control" id="id" name="id" value="{{ $vote->id_officer }}" hidden>
                                </div>
                            </div>
                            <div class="alert alert-info" role="alert">
                                <i class="bi bi-info-circle-fill"></i> <b>INFO</b>
                                <br/>
                                Pegawai yang dipilih: {{$vote->officer->name}}
                            </div>
                            <div class="alert alert-warning" role="alert">
                                <i class="bi bi-exclamation-triangle-fill"></i> <b>PERHATIAN</b>
                                <br/>
                                Apakah anda yakin untuk memilih pegawai tersebut? Harap diperhatikan bahwa setelah melakukan pemilihan, anda tidak dapat mengubah atau membatalkan pilihan anda.
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
@endif
