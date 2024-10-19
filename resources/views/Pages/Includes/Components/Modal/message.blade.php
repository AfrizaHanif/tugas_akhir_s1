<!--SEND MESSAGE-->
<div class="modal fade" id="modal-msg-send" data-bs-backdrop="static" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.messages.in') }}" method="POST" enctype="multipart/form-data" id="form-msg-send">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Kirim Pesan Feedback</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="close-msg-send"></button>
                </div>
                <div class="modal-body">
                    @if (Session::get('modal_redirect') == 'modal-msg-send')
                    @include('Templates.Includes.Components.alert')
                    @endif
                    @csrf
                    <div class="alert alert-warning" role="alert">
                        <i class="bi bi-exclamation-triangle-fill"></i> <strong>PERHATIAN</strong>
                        <br/>
                        Mohon periksa kembali pesan anda karena pesan yang telah dikirim tidak dapat diubah kembali.
                    </div>
                    <div class="row">
                        <div class="col">
                            <label for="id_officer" class="form-label" hidden>ID</label>
                            <input type="text" class="form-control" id="id_officer" name="id_officer" value="{{ Auth::user()->id_user }}" hidden>
                        </div>
                        <div class="col">
                            <label for="officer_name" class="form-label" hidden>Nama Pegawai</label>
                            <input type="text" class="form-control" id="officer_name" name="officer_name" value="{{ Auth::user()->name }}" hidden>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="type" class="form-label">Tipe Feedback</label>
                        <select class="form-select" id="type" name="type" required>
                            <option selected disabled value="">---Pilih Tipe---</option>
                            <option value="Apresiasi" {{ old('type') == 'Apresiasi' ? 'selected' : null }}>Apresiasi</option>
                            <option value="Perbaikan" {{ old('type') == 'Perbaikan' ? 'selected' : null }}>Perbaikan</option>
                            <option value="Sugesti" {{ old('type') == 'Sugesti' ? 'selected' : null }}>Sugesti</option>
                            <option value="Saran" {{ old('type') == 'Saran' ? 'selected' : null }}>Saran</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="description">Pesan</label>
                        <textarea class="form-control" name="message_in" id="message_in" rows="3"></textarea>
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

@foreach ($messages as $message)
<!--VIEW MESSAGE-->
<div class="modal modal-lg fade" id="modal-msg-view-{{ $message->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Detail Pesan (Jenis: {{ $message->type }})</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row justify-content-center g-3">
                    <div class="col-md-6">
                        <h6>Masukkan</h6>
                        <p>{{ $message->message_in }}</p>
                    </div>
                    <div class="col-md-6">
                        <h6>Balasan</h6>
                        <p>{{ $message->message_out }}</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-lg"></i>
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>
<!--REPLY MESSAGE-->
<div class="modal modal-lg fade" id="modal-msg-reply-{{ $message->id }}" data-bs-backdrop="static" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('developer.messages.out', $message->id) }}" method="POST" enctype="multipart/form-data" id="form-msg-reply">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Balas Pesan Feedback ({{ $message->id}})</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="close-msg-reply"></button>
                </div>
                <div class="modal-body">
                    @if (Session::get('modal_redirect') == 'modal-msg-reply')
                    @include('Templates.Includes.Components.alert')
                    @endif
                    @csrf
                    <div class="alert alert-warning" role="alert">
                        <i class="bi bi-exclamation-triangle-fill"></i> <strong>PERHATIAN</strong>
                        <br/>
                        Mohon periksa kembali balasan anda karena pesan yang telah dikirim tidak dapat diubah kembali.
                    </div>
                    <div class="row justify-content-center g-4">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <h6>Pesan</h6>
                                <p>{{ $message->message_in }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="message_out">Balas</label>
                                <textarea class="form-control" name="message_out" id="message_out" rows="5"></textarea>
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
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Hapus Pesan ({{ $message->id}})</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="form-msg-delete-{{ $message->id }}" action="{{ route('developer.messages.destroy', $message->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf @method('DELETE')
                        <div class="mb-3">
                            <div class="col">
                                <input type="text" class="form-control" id="id" name="id" value="{{ $message->id }}" hidden>
                            </div>
                        </div>
                        <div class="alert alert-warning" role="alert">
                            <i class="bi bi-exclamation-triangle-fill"></i> <b>PERHATIAN</b>
                            <br/>
                            Apakah anda ingin menghapus pesan anda?
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-lg"></i>
                        Tidak
                    </button>
                    <button type="submit" form="form-msg-delete-{{ $message->id }}" class="btn btn-primary">
                        <i class="bi bi-check-lg"></i>
                        Ya
                    </button>
                </div>
        </div>
    </div>
</div>
@endforeach
