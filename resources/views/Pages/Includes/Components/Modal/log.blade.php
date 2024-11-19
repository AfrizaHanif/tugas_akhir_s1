<!--EXPORT LOG-->
<div class="modal modal-sheet p-4 py-md-5 fade" tabindex="-1" role="dialog" id="modal-log-export">
    <div class="modal-dialog" role="document">
        <div class="modal-content rounded-4 shadow">
            @if (Auth::user()->part == 'Dev')
            <form action="{{ route('developer.logs.export') }}" method="post" enctype="multipart/form-data">
            @elseif (Auth::user()->part == 'Admin' || Auth::user()->part == 'KBPS')
            <form action="{{ route('admin.logs.export') }}" method="post" enctype="multipart/form-data">
            @elseif (Auth::user()->part == 'Pegawai')
            <form action="{{ route('officer.logs.export') }}" method="post" enctype="multipart/form-data">
            @endif
                <div class="modal-header border-bottom-0">
                    <h1 class="modal-title fs-5">Export Logs</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body py-0">
                    <p>Proses ini akan mengunduh Data Logs ke komputer anda. Gunakan file ini sebagai bukti agar Developer dapat melakukan perbaikan pada aplikasi ini.</p>
                </div>
                <div class="modal-footer flex-column align-items-stretch w-100 gap-2 pb-3 border-top-0">
                    @csrf
                    <button type="submit" class="btn btn-lg btn-primary" id="exportToastBtn">Export Logs</button>
                    <button type="button" class="btn btn-lg btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </form>
        </div>
    </div>
</div>
