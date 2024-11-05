<h1 class="text-center mb-4">Logs</h1>
@if (Session::get('code_alert') == 1)
@include('Templates.Includes.Components.alert')
@endif
<!--TABLE-->
<table class="table table-hover table-bordered">
    <thead>
        <tr class="table-primary">
            <th class="col-1" scope="col">#</th>
            <th class="col-2" scope="col">Halaman</th>
            <th class="col-2" scope="col">Proses</th>
            <th class="col-2" scope="col">Hasil</th>
            <th scope="col">Deskripsi</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($logs as $log)
        <tr>
            <th scope="row">{{ $loop->iteration }}</th>
            <td>{{ $log->page }}</td>
            <td>
                @if ($log->progress == 'Create')
                <span class="badge text-bg-primary">Create</span>
                @elseif ($log->progress == 'Update')
                <span class="badge text-bg-warning">Update</span>
                @elseif ($log->progress == 'Delete')
                <span class="badge text-bg-danger">Delete</span>
                @elseif ($log->progress == 'View')
                <span class="badge text-bg-info">View</span>
                @elseif ($log->progress == 'Import')
                <span class="badge text-bg-primary">Import</span>
                @else
                <span class="badge text-bg-secondary">Other</span>
                @endif
            </td>
            <td>
                @if ($log->result == 'Success')
                <span class="badge text-bg-success">Sukses</span>
                @elseif ($log->result == 'Warning')
                <span class="badge text-bg-warning">Perhatian</span>
                @elseif ($log->result == 'Error')
                <span class="badge text-bg-danger">Error</span>
                @else
                <span class="badge text-bg-secondary">Lainnya</span>
                @endif
            </td>
            <td>{{ $log->descriptions }}</td>
        </tr>
        @empty
        <tr>
            <td colspan="7">Tidak ada Log yang terdaftar</td>
        </tr>
        @endforelse
    </tbody>
    <tfoot class="table-group-divider table-secondary">
        <tr>
            <td colspan="7">Total Data: <b>{{ $logs->count() }}</b> Log</td>
        </tr>
    </tfoot>
</table>
