<h1 class="text-center mb-4">Pesan Masukkan</h1>
@if (Session::get('code_alert') == 1)
@include('Templates.Includes.Components.alert')
@endif
<!--TABLE-->
<table class="table table-hover table-bordered">
    <thead>
        <tr class="table-primary">
            <th rowspan="2" class="col-1" scope="col">#</th>
            <th rowspan="2" scope="col">Pengirim</th>
            <th colspan="2" scope="col">Isi Pesan</th>
            <th rowspan="2" scope="col">Status</th>
            <th rowspan="2" class="col-1" scope="col">Action</th>
        </tr>
        <tr class="table-secondary">
            <th scope="col">Pegawai</th>
            <th scope="col">Developer</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($messages as $message)
        <tr>
            <th scope="row">{{ $loop->iteration }}</th>
            <td>{{ $message->officer_name }}</td>
            <td>{{ $message->message_in }}</td>
            <td>{{ $message->message_out }}</td>
            <td>
                @if ($message->status == 'Pending')
                <span class="badge text-bg-warning">Menunggu Balasan</span>
                @elseif ($message->status == 'Success')
                <span class="badge text-bg-success">Sukses</span>
                @elseif ($message->status == 'Fail')
                <span class="badge text-bg-danger">Gagal</span>
                @else
                <span class="badge text-bg-secondary">Blank</span>
                @endif
            </td>
            <td>
                <div class="dropdown">
                    <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-menu-button-fill"></i>
                    </button>
                    <ul class="dropdown-menu mx-0 shadow w-table-menu">
                        <li>
                            <a class="dropdown-item d-flex gap-2 align-items-center" href="#" data-bs-toggle="modal" data-bs-target="#modal-msg-reply-{{ $message->id }}"><svg class="bi" width="16" height="16" style="vertical-align: -.125em;"><use xlink:href="#update"/></svg>
                                Balas
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item d-flex gap-2 align-items-center" href="#" data-bs-toggle="modal" data-bs-target="#modal-msg-delete-{{ $message->id }}"><svg class="bi" width="16" height="16" style="vertical-align: -.125em;"><use xlink:href="#delete"/></svg>
                                Hapus
                            </a>
                        </li>
                    </ul>
                </div>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="7">Tidak ada Pesan</td>
        </tr>
        @endforelse
    </tbody>
    <tfoot class="table-group-divider table-secondary">
        <tr>
            <td colspan="7">Total Data: <b>{{ $messages->count() }}</b> Pesan</td>
        </tr>
    </tfoot>
</table>
