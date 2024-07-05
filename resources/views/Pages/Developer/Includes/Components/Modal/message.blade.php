@foreach ($messages as $message)
<!--REPLY MESSAGE-->
<div class="modal modal-lg fade" id="modal-msg-reply-{{ $message->id }}" data-bs-backdrop="static" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('developer.messages.update', $message->id) }}" method="POST" enctype="multipart/form-data" id="form-msg-reply-{{ $message->id }}">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Balas Pesan ({{ $message->officer_name }})</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="close-msg-reply-{{ $message->id }}"></button>
                </div>
                <div class="modal-body">
                    @csrf @method('PUT')
                    <div class="row justify-content-center g-4">
                        <div class="col-md-7">
                            <div class="row mb-3">
                                <div class="col">
                                    <label for="officer_nip" class="form-label">NIP Pegawai</label>
                                    <input type="text" class="form-control" id="officer_nip" name="officer_nip" value="{{ $message->officer_nip }}" readonly>
                                </div>
                                <div class="col">
                                    <label for="officer_name" class="form-label">Nama Pegawai</label>
                                    <input type="text" class="form-control" id="officer_name" name="officer_name" value="{{ $message->officer_name }}" readonly>
                                </div>
                            </div>
                            <hr/>
                            <div class="mb-3">
                                <label for="status" class="form-label">Status</label>
                                <div class="btn-group" role="group" aria-label="Basic radio toggle button group">
                                    <input type="radio" class="btn-check" name="status" id="solved" autocomplete="off" required>
                                    <label class="btn btn-outline-success" for="solved">Sukses</label>
                                    <input type="radio" class="btn-check" name="status" id="notsolved" autocomplete="off" required>
                                    <label class="btn btn-outline-danger" for="notsolved">Gagal</label>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="message_out" class="form-label">Pesan Balasan</label>
                                <textarea class="form-control" name="message_out" id="message_out" rows="5"></textarea>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="position-sticky" style="top: 2rem;">
                                <div class="alert alert-info" role="alert">
                                    <i class="bi bi-info-circle-fill"></i> <strong>CARA PENGISIAN</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">
                        <i class="bi bi-x-lg"></i>
                        Batal
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-send"></i>
                        Kirim
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<!--DELETE MESSAGE-->
<div class="modal fade" id="modal-msg-delete-{{ $message->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('developer.messages.destroy', $message->id) }}" method="POST" enctype="multipart/form-data">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Hapus Pesan ({{ $message->id}})</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning" role="alert">
                        <i class="bi bi-exclamation-triangle-fill"></i> <b>PERHATIAN</b>
                        <br/>
                        Apakah anda ingin menghapus Pesan tersebut?
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-lg"></i>
                        Tidak
                    </button>
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-check-lg"></i>
                        Ya
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach
