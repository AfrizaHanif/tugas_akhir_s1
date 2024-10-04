@if (Request::is('admin/analysis*'))
<!--PERIOD PICKER-->
<div class="modal fade" id="modal-saw-periods" tabindex="-1" aria-labelledby="modalsaw" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="modalsaw">Pilih Periode Sebelumnya</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="accordion" id="accordion-analysis">
                    @forelse ($h_years as $h_year)
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-analysis-{{ $h_year->period_year }}" aria-expanded="true" aria-controls="collapse-analysis-{{ $h_year->period_year }}">
                                {{ $h_year->period_year }}
                            </button>
                        </h2>
                        <div id="collapse-analysis-{{ $h_year->period_year }}" class="accordion-collapse collapse" data-bs-parent="#accordion-analysis">
                            <div class="accordion-body">
                                @forelse ($h_months->where('period_year', $h_year->period_year) as $h_month)
                                <div class="row align-items-center pt-1">
                                    <div class="col">
                                        {{ $h_month->period_name }}
                                    </div>
                                    <div class="col">
                                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                            <a href="/admin/analysis/{{ $h_month->id_period }}" type="button" class="btn btn-primary">
                                                <i class="bi bi-box-arrow-in-right"></i>
                                                Visit
                                            </a>
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
                    </div>
                    @empty
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="alert alert-danger" role="alert">
                                <i class="bi bi-x-octagon-fill"></i> <strong>ERROR</strong>
                                <br/>
                                Tidak Ada Periode yang tersedia.
                                <ul>
                                    <li>Jika periode saat ini sedang berjalan, klik <strong>Sekarang</strong> di menu <strong>Pilih Periode</strong></li>
                                    <li>Silahkan coba beberapa saat lagi setelah proses penentuan <strong>Karyawan Terbaik</strong> selesai</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    @endforelse
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endif
