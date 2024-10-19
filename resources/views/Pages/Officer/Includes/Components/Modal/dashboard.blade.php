<div class="modal modal-lg fade" id="modal-dsh-history" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Riwayat Nilai</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row g-2">
                    <div class="col-md-4">
                        <div class="position-sticky" style="top: 0rem;">
                            <div class="nav flex-column nav-pills me-3" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                                @foreach ($history_per as $hperiod)
                                <button class="nav-link {{ $loop->first ? 'active' : '' }} text-start" id="pills-{{ $hperiod->id_period }}-tab" data-bs-toggle="pill" data-bs-target="#pills-{{ $hperiod->id_period }}" type="button" role="tab" aria-controls="pills-{{ $hperiod->id_period }}" aria-selected="{{ $loop->first ? 'true' : 'false' }}">
                                    <i class="bi bi-check-lg"></i> {{ $hperiod->period_name }}
                                </button>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="tab-content" id="v-pills-tabContent">
                            @foreach ($history_per as $hperiod)
                            <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" id="pills-{{ $hperiod->id_period }}" role="tabpanel" aria-labelledby="pills-{{ $hperiod->id_period }}-tab" tabindex="0">
                                <h2>{{ $hperiod->period_name }}</h2>
                                <table class="table">
                                    @foreach ($hcriterias->where('id_period', $hperiod->id_period) as $hcriteria)
                                        @forelse ($histories->where('id_criteria', $hcriteria->id_criteria)->where('id_officer', Auth::user()->nip)->where('id_period', $hperiod->id_period) as $history)
                                        <tr>
                                            <th scope="row">{{ $hcriteria->criteria_name }}</th>
                                            <td>{{ $history->input }} ({{ $history->input_raw }})</td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <th scope="row">{{ $hcriteria->criteria_name }}</th>
                                            <td>0</td>
                                        </tr>
                                        @endforelse
                                    @endforeach
                                </table>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
