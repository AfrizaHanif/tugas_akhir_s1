<!--SAVE SETTINGS-->
<div class="modal fade" id="modal-stg-save" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            @if (Auth::user()->part == "Dev")
            <form action="{{ route('developer.settings.update') }}" method="POST" enctype="multipart/form-data">
            @elseif (Auth::user()->part != "Karyawan")
            <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
            @else
            <form action="{{ route('employee.settings.update') }}" method="POST" enctype="multipart/form-data">
            @endif
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Simpan Pengaturan</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="col">
                        <input type="text" class="form-control" id="presence_counter" name="presence_counter" value="" readonly hidden>
                    </div>
                    <div class="col">
                        <input type="text" class="form-control" id="second_sort" name="second_sort" value="" readonly hidden>
                    </div>
                    <div class="col">
                        <input type="text" class="form-control" id="username" name="username" value="" readonly hidden>
                    </div>
                    <div class="col">
                        <input type="password" class="form-control" id="password" name="password" value="" readonly hidden>
                    </div>
                    <div class="alert alert-warning" role="alert">
                        <i class="bi bi-exclamation-triangle-fill"></i> <b>PERHATIAN</b>
                        <br/>
                        Apakah anda ingin menyimpan peraturan tersebut?
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-lg"></i>
                        Tidak
                    </button>
                    @csrf
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-lg"></i>
                        Ya
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
