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
