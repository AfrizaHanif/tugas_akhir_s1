@if (Request::is('analysis/saw'))
<h1 class="text-center mb-4">Analisis SAW</h1>
@elseif (Request::is('analysis/wp'))
<h1 class="text-center mb-4">Analisis WP</h1>
@endif
@include('Pages.Admin.Includes.Components.alert')
<p>
    @if (Request::is('analysis/saw'))
    <div class="btn-group" role="group" aria-label="Basic example">
        <a class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-saw-view">
            <i class="bi bi-folder-plus"></i>
            Pilih Periode
        </a>
    </div>
    @elseif (Request::is('analysis/wp'))
    <div class="btn-group" role="group" aria-label="Basic example">
        <a class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-wp-view">
            <i class="bi bi-folder-plus"></i>
            Pilih Periode
        </a>
    </div>
    @endif
</p>
<div class="alert alert-info" role="alert">
    Untuk melihat hasil analisis di setiap periode, klik pilih periode untuk melihat hasil analisis.
</div>
<div class="alert alert-warning" role="alert">
    <strong>PERHATIAN:</strong> Pastikan seluruh data input di setiap pegawai telah terisi. Cek status di halaman input apakah pegawai tersebut telah terinput atau belum.
</div>
