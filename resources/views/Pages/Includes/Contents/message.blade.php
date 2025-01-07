<h1 class="text-center mb-4">Pesan Feedback</h1>
@if (Session::get('code_alert') == 1)
@include('Templates.Includes.Components.alert')
@endif
<!--MENU-->
<p>
    @if (Auth::user()->part != "Dev")
    <!--SEND MESSAGE-->
    <a class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-msg-send">
        <i class="bi bi-envelope-plus"></i>
        Kirim Pesan
    </a>
    <!--HELP-->
    <a class="btn btn-secondary" data-bs-toggle="offcanvas" href="#offcanvas-help" role="button" aria-controls="offcanvas-help">
        <i class="bi bi-question-lg"></i>
        Bantuan
    </a>
    @endif
</p>
<!--TABLE-->
<table class="table table-hover table-bordered">
    <thead>
        <tr class="table-primary">
            <th rowspan="2" class="col-1" scope="col">#</th>
            @if (Auth::user()->part == "Dev")
            <th rowspan="2" class="col-1" scope="col">NIP</th>
            <th rowspan="2" class="col-2" scope="col">Nama Karyawan</th>
            @endif
            <th rowspan="2" class="col-2" scope="col">Jenis</th>
            <th colspan="2" scope="col">Pesan</th>
            <th rowspan="2" class="col-1" scope="col">Action</th>
        </tr>
        <tr class="table-secondary">
            <th scope="col">Feedback</th>
            <th scope="col">Balasan</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($messages as $message)
        <tr>
            <th scope="row">{{ $loop->iteration }}</th>
            @if (Auth::user()->part == "Dev")
            <td>{{ $message->employee->id_employee }}</td>
            <td>{{ $message->employee->name }}</td>
            @endif
            <td>
                @if ($message->type == 'Apresiasi')
                <span class="badge text-bg-success">Apresiasi</span>
                @elseif ($message->type == 'Sugesti')
                <span class="badge text-bg-warning">Sugesti</span>
                @elseif ($message->type == 'Perbaikan')
                <span class="badge text-bg-danger">Perbaikan</span>
                @elseif ($message->type == 'Saran')
                <span class="badge text-bg-primary">Perbaikan</span>
                @else
                <span class="badge text-bg-secondary">Blank</span>
                @endif
            </td>
            <td>{{ $message->message_in }}</td>
            <td>{{ $message->message_out ?? 'Tidak / Belum ada balasan.' }}</td>
            <td>
                <div class="dropdown">
                    <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-menu-button-fill"></i>
                    </button>
                    <ul class="dropdown-menu mx-0 shadow w-table-menu">
                        <li>
                            <a class="dropdown-item d-flex gap-2 align-items-center" href="#" data-bs-toggle="modal" data-bs-target="#modal-msg-view-{{ $message->id }}"><svg class="bi" width="16" height="16" style="vertical-align: -.125em;"><use xlink:href="#read_msg"/></svg>
                                Baca Pesan
                            </a>
                        </li>
                        @if (Auth::user()->part == "Dev")
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            @if (empty($message->message_out))
                            <a class="dropdown-item d-flex gap-2 align-items-center" href="#" data-bs-toggle="modal" data-bs-target="#modal-msg-reply-{{ $message->id }}">
                            @else
                            <a class="dropdown-item d-flex gap-2 align-items-center disabled" href="#">
                            @endif
                                <svg class="bi" width="16" height="16" style="vertical-align: -.125em;"><use xlink:href="#reply"/></svg>
                                Balas
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item d-flex gap-2 align-items-center" href="#" data-bs-toggle="modal" data-bs-target="#modal-msg-delete-{{ $message->id }}"><svg class="bi" width="16" height="16" style="vertical-align: -.125em;"><use xlink:href="#delete"/></svg>
                                Delete
                            </a>
                        </li>
                        @endif
                    </ul>
                </div>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="10">Tidak ada Pesan Feedback yang terkirim</td>
        </tr>
        @endforelse
    </tbody>
    <tfoot class="table-group-divider table-secondary">
        <tr>
            <td colspan="10">Total Data: <b>{{ $messages->count() }}</b> Pesan Feedback</td>
        </tr>
    </tfoot>
</table>
