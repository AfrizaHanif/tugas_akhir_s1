<!--ALL PERIOD'S SCORES-->
<div class="modal modal-lg fade" id="modal-dsh-history" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Riwayat Nilai</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row g-2">
                    @if (count($history_per) != 0)
                    <div class="col-md-4">
                        <div class="position-sticky" style="top: 0rem;">
                            <div class="nav flex-column nav-pills me-3" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                                @foreach ($history_per as $hperiod)
                                <button class="nav-link {{ $loop->first ? 'active' : '' }} text-start" id="pills-{{ $hperiod->id_period }}-tab" data-bs-toggle="pill" data-bs-target="#pills-{{ $hperiod->id_period }}" type="button" role="tab" aria-controls="pills-{{ $hperiod->id_period }}" aria-selected="{{ $loop->first ? 'true' : 'false' }}">
                                    <i class="bi bi-check-lg"></i> {{ $hperiod->period->name }}
                                </button>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="tab-content" id="v-pills-tabContent">
                            @foreach ($history_per as $hperiod)
                            <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" id="pills-{{ $hperiod->id_period }}" role="tabpanel" aria-labelledby="pills-{{ $hperiod->id_period }}-tab" tabindex="0">
                                <h2>{{ $hperiod->period->name }}</h2>
                                <table class="table">
                                    @foreach ($hcriterias->where('id_period', $hperiod->id_period) as $hcriteria)
                                        @forelse ($histories->where('id_criteria', $hcriteria->id_criteria)->where('id_employee', Auth::user()->id_employee)->where('id_period', $hperiod->id_period) as $history)
                                        <tr>
                                            <th scope="row">{{ $hcriteria->criteria_name }}</th>
                                            <td>{{ $history->input_raw }} {{ $hcriteria->unit }}</td>
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
                    @else
                    <div class="alert alert-warning" role="alert">
                        <i class="bi bi-exclamation-triangle-fill"></i> <strong>PERHATIAN</strong></br>
                        Tidak ada data nilai dari periode yang telah dijalankan.
                    </div>
                    @endif
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!--ALL PERIOD'S RESULTS-->
<div class="modal modal-lg fade" id="modal-dsh-result" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Riwayat Nilai Akhir</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row g-2">
                    @if (count($hscore_year) != 0)
                    <div class="col-md-4">
                        <div class="position-sticky" style="top: 0rem;">
                            <div class="nav flex-column nav-pills me-3" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                                @foreach ($hscore_year as $hyear)
                                <button class="nav-link {{ $loop->first ? 'active' : '' }} text-start" id="pills-{{ $hyear->year }}-tab" data-bs-toggle="pill" data-bs-target="#pills-{{ $hyear->year }}" type="button" role="tab" aria-controls="pills-{{ $hyear->year }}" aria-selected="{{ $loop->first ? 'true' : 'false' }}">
                                    <i class="bi bi-check-lg"></i> {{ $hyear->year }}
                                </button>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="tab-content" id="v-pills-tabContent">
                            @foreach ($hscores as $hscore)
                            <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" id="pills-{{ $hyear->year }}" role="tabpanel" aria-labelledby="pills-{{ $hyear->year }}-tab" tabindex="0">
                                <h2>{{ $hyear->year }}</h2>
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th class="col-1" scope="col">#</th>
                                            <th>Periode</th>
                                            <th>Nilai Akhir</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($hscores->where('id_employee', Auth::user()->id_employee)->where('year', $hyear->year) as $score)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $score->period->name }}</td>
                                            <td>{{ $score->final_score }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @else
                    <div class="alert alert-warning" role="alert">
                        <i class="bi bi-exclamation-triangle-fill"></i> <strong>PERHATIAN</strong></br>
                        Tidak ada nilai akhir yang anda dapatkan.
                    </div>
                    @endif
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
