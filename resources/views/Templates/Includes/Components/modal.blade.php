<div class="modal fade modal-sheet p-4 py-md-5" tabindex="-1" role="dialog" id="modallogout">
    <div class="modal-dialog" role="document">
        <div class="modal-content rounded-3 shadow">
            <form action="{{ route('logout') }}" method="post">
                <div class="modal-body p-4 text-center">
                    <h5 class="mb-0">Keluar?</h5>
                    <p class="mb-0">Semua hasil proses akan terhapus setelah keluar dari sesi ini.</p>
                </div>
                <div class="modal-footer flex-nowrap p-0">
                    @csrf
                    <input type="submit" class="btn btn-lg btn-link fs-6 text-decoration-none col-6 py-3 m-0 rounded-0 border-end" value="Ya">
                    <button type="button" class="btn btn-lg btn-link fs-6 text-decoration-none col-6 py-3 m-0 rounded-0" data-bs-dismiss="modal">Tidak</button>
                </div>
            </form>
        </div>
    </div>
</div>
