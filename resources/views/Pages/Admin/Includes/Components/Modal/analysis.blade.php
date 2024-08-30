@if (Request::is('admin/analysis*'))
<!--PERIOD PICKER-->
<div class="modal fade" id="modal-saw-periods" tabindex="-1" aria-labelledby="modalsaw" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="modalsaw">Pilih Periode (SAW)</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info" role="alert">
                    <i class="bi bi-info-circle-fill"></i> <strong>INFO</strong>
                    <br/>
                    Pilih tahun untuk melihat hasil analisis secara langsung. Fitur ini tidak memerlukan input.
                </div>
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
                            <li><a class="dropdown-item" href="/admin/analysis/{{ $period->id_period }}">{{ $period->period_name }}</a></li>
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
