@if (Request::is('admin/reports*') || Auth::user()->part == 'Admin')
<!--PERIOD PICKER (NILAI AKHIR PER TIM)-->
<div class="modal modal-lg fade" id="modal-tim-rep-picker" tabindex="-1" aria-labelledby="modaltimrep" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="modaltimrep">Pilih Periode</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row justify-content-center g-2">
                    <div class="col-md-4">
                        <div class="position-sticky" style="top: 0rem;">
                            <div class="nav flex-column nav-pills me-3" id="teams-modal-tab" role="tablist" aria-orientation="vertical">
                                @foreach ($h_subteams as $h_subteam)
                                <button class="nav-link {{ $loop->first ? 'active' : '' }}" id="pills-{{ $h_subteam->id_sub_team }}-tab" data-bs-toggle="pill" data-bs-target="#pills-{{ $h_subteam->id_sub_team }}" type="button" role="tab" aria-controls="pills-{{ $h_subteam->id_sub_team }}" aria-selected="{{ $loop->first ? 'true' : 'false' }}">
                                    {{ $h_subteam->sub_team_1_name }}
                                </button>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="tab-content" id="teams-modal-tabContent">
                            @foreach ($h_subteams as $h_subteam)
                            <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" id="pills-{{ $h_subteam->id_sub_team }}" role="tabpanel" aria-labelledby="pills-{{ $h_subteam->id_sub_team }}-tab" tabindex="0">
                                <div class="accordion" id="accordion-team-result-{{ $h_subteam->id_sub_team }}">
                                    @forelse ($h_team_years->where('id_sub_team', $h_subteam->id_sub_team) as $h_year)
                                    <div class="accordion-item">
                                        <h2 class="accordion-header">
                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-result-{{ $h_subteam->id_sub_team }}-{{ $h_year->year }}" aria-expanded="true" aria-controls="collapse-result-{{ $h_subteam->id_sub_team }}-{{ $h_year->year }}">
                                                {{ $h_year->year }}
                                            </button>
                                        </h2>
                                        <div id="collapse-result-{{ $h_subteam->id_sub_team }}-{{ $h_year->year }}" class="accordion-collapse collapse" data-bs-parent="#accordion-team-result-{{ $h_subteam->id_sub_team }}">
                                            <div class="accordion-body">
                                                @forelse ($h_months->where('year', $h_year->year) as $h_month)
                                                    @foreach ($h_scores->where('id_sub_team', $h_subteam->id_sub_team)->where('year', $h_year->year)->where('month', $h_month->month) as $h_score)
                                                    <div class="row align-items-center pt-1">
                                                        <div class="col">
                                                            {{ $h_score->period->month }}
                                                        </div>
                                                        <div class="col">
                                                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                                                <a href="{{ route('admin.reports.teamresult', ['subteam'=>$h_score->id_sub_team,'month'=>$h_score->period->month,'year'=>$h_score->year]) }}" type="button" class="btn btn-danger" target="_blank" rel="noopener noreferrer">
                                                                    <i class="bi bi-filetype-pdf"></i>
                                                                    PDF
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @endforeach
                                                @empty
                                                <div class="row align-items-center">
                                                    <div class="col">
                                                        <div class="alert alert-danger" role="alert">
                                                            <i class="bi bi-x-octagon-fill"></i> <strong>ERROR</strong>
                                                            <br/>
                                                            Tidak Ada Laporan
                                                        </div>
                                                    </div>
                                                </div>
                                                @endforelse
                                            </div>
                                        </div>
                                    </div>
                                    @empty
                                    <div class="row align-items-center">
                                        <div class="col">
                                            <div class="alert alert-danger" role="alert">
                                                <i class="bi bi-x-octagon-fill"></i> <strong>ERROR</strong>
                                                <br/>
                                                Tidak Ada Laporan
                                            </div>
                                        </div>
                                    </div>
                                    @endforelse
                                </div>
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
@endif
