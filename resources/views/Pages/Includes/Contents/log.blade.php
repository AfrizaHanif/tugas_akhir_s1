<h1 class="text-center mb-4">Logs</h1>
@if (Session::get('code_alert') == 1)
@include('Templates.Includes.Components.alert')
@endif
<!---->
<div class="row g-2">
    <div class="col-md-3">
        <div class="position-sticky" style="top: 0rem;">
            <!--MENU-->
            <p>
                <a class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-log-export">
                    <i class="bi bi-file-earmark-arrow-down"></i>
                    Export
                </a>
                <a class="btn btn-secondary" data-bs-toggle="offcanvas" href="#offcanvas-help" role="button" aria-controls="offcanvas-help">
                    <i class="bi bi-question-lg"></i>
                    Bantuan
                </a>
            </p>
            <div class="nav flex-column nav-pills me-3" id="log-tab" role="tablist" aria-orientation="vertical">
                <button class="nav-link active text-start" id="pills-all-tab" data-bs-toggle="pill" data-bs-target="#pills-all" type="button" role="tab" aria-controls="pills-all" aria-selected="true">
                    Semua
                </button>
                <button class="nav-link text-start" id="pills-success-tab" data-bs-toggle="pill" data-bs-target="#pills-success" type="button" role="tab" aria-controls="pills-success" aria-selected="false">
                    Sukses
                </button>
                <button class="nav-link text-start" id="pills-warning-tab" data-bs-toggle="pill" data-bs-target="#pills-warning" type="button" role="tab" aria-controls="pills-warning" aria-selected="false">
                    Peringatan
                </button>
                <button class="nav-link text-start" id="pills-error-tab" data-bs-toggle="pill" data-bs-target="#pills-error" type="button" role="tab" aria-controls="pills-error" aria-selected="false">
                    Gagal / Error
                </button>
            </div>
        </div>
    </div>
    <div class="col-md-9">
        <div class="tab-content" id="log-tabContent">
            <div class="tab-pane fade show active" id="pills-all" role="tabpanel" aria-labelledby="pills-all-tab" tabindex="0">
                <!--ALL TABLE-->
                <table class="table table-hover table-bordered">
                    <thead>
                        <tr class="table-primary">
                            <th class="col-1" scope="col">#</th>
                            <th class="col-3" scope="col">Aktivitas</th>
                            <th scope="col">Deskripsi</th>
                            <th class="col-2" scope="col">Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($logs as $log)
                        <tr>
                            <th scope="row">{{ $loop->iteration }}</th>
                            <td>
                                <div>{{ $log->activity }}</div>
                                @if ($log->progress == 'Create')
                                <span class="badge text-bg-success">Create</span>
                                @elseif ($log->progress == 'Update')
                                <span class="badge text-bg-warning">Update</span>
                                @elseif ($log->progress == 'Delete')
                                <span class="badge text-bg-danger">Delete</span>
                                @elseif ($log->progress == 'View')
                                <span class="badge text-bg-info">View</span>
                                @elseif ($log->progress == 'All')
                                <span class="badge text-bg-primary">Semua</span>
                                @else
                                <span class="badge text-bg-secondary">Other</span>
                                @endif
                                @if ($log->result == 'Success')
                                <span class="badge text-bg-success">Sukses</span>
                                @elseif ($log->result == 'Warning')
                                <span class="badge text-bg-warning">Perhatian</span>
                                @elseif ($log->result == 'Error')
                                <span class="badge text-bg-danger">Error</span>
                                @else
                                <span class="badge text-bg-secondary">Other</span>
                                @endif
                            </td>
                            <td>{{ $log->descriptions }}</td>
                            <td>
                                <div>
                                    {{ \Carbon\Carbon::parse($log->created_at)
                                    ->locale('id')
                                    ->settings(['formatFunction' => 'translatedFormat'])
                                    ->format('d/m/Y') }}
                                </div>
                                <div>
                                    {{ \Carbon\Carbon::parse($log->created_at)
                                    ->locale('id')
                                    ->settings(['formatFunction' => 'translatedFormat'])
                                    ->format('h:i:s') }}
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7">Tidak ada Log yang terdaftar</td>
                        </tr>
                        @endforelse
                    </tbody>
                    <tfoot class="table-group-divider table-secondary">
                        <tr>
                            <td colspan="7">Total Data: <b>{{ $logs->where('id_user', Auth::user()->id_user)->count() }}</b> Log</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <div class="tab-pane fade" id="pills-success" role="tabpanel" aria-labelledby="pills-success-tab" tabindex="0">
                <!--SUCCESS' TABLE-->
                <table class="table table-hover table-bordered">
                    <thead>
                        <tr class="table-primary">
                            <th class="col-1" scope="col">#</th>
                            <th class="col-3" scope="col">Aktivitas</th>
                            <th scope="col">Deskripsi</th>
                            <th class="col-2" scope="col">Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($logs->where('id_user', Auth::user()->id_user)->where('result', 'Success') as $log)
                        <tr>
                            <th scope="row">{{ $loop->iteration }}</th>
                            <td>
                                <div>{{ $log->activity }}</div>
                                @if ($log->progress == 'Create')
                                <span class="badge text-bg-success">Create</span>
                                @elseif ($log->progress == 'Update')
                                <span class="badge text-bg-warning">Update</span>
                                @elseif ($log->progress == 'Delete')
                                <span class="badge text-bg-danger">Delete</span>
                                @elseif ($log->progress == 'View')
                                <span class="badge text-bg-info">View</span>
                                @elseif ($log->progress == 'All')
                                <span class="badge text-bg-primary">Semua</span>
                                @else
                                <span class="badge text-bg-secondary">Other</span>
                                @endif
                            </td>
                            <td>{{ $log->descriptions }}</td>
                            <td>
                                <div>
                                    {{ \Carbon\Carbon::parse($log->created_at)
                                    ->locale('id')
                                    ->settings(['formatFunction' => 'translatedFormat'])
                                    ->format('d/m/Y') }}
                                </div>
                                <div>
                                    {{ \Carbon\Carbon::parse($log->created_at)
                                    ->locale('id')
                                    ->settings(['formatFunction' => 'translatedFormat'])
                                    ->format('h:i:s') }}
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7">Tidak ada Log yang terdaftar</td>
                        </tr>
                        @endforelse
                    </tbody>
                    <tfoot class="table-group-divider table-secondary">
                        <tr>
                            <td colspan="7">Total Data: <b>{{ $logs->where('id_user', Auth::user()->id_user)->where('result', 'Success')->count() }}</b> Log</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <div class="tab-pane fade" id="pills-warning" role="tabpanel" aria-labelledby="pills-warning-tab" tabindex="0">
                <!--WARNING'S TABLE-->
                <table class="table table-hover table-bordered">
                    <thead>
                        <tr class="table-primary">
                            <th class="col-1" scope="col">#</th>
                            <th class="col-3" scope="col">Aktivitas</th>
                            <th scope="col">Deskripsi</th>
                            <th class="col-2" scope="col">Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($logs->where('id_user', Auth::user()->id_user)->where('result', 'Warning') as $log)
                        <tr>
                            <th scope="row">{{ $loop->iteration }}</th>
                            <td>
                                <div>{{ $log->activity }}</div>
                                @if ($log->progress == 'Create')
                                <span class="badge text-bg-success">Create</span>
                                @elseif ($log->progress == 'Update')
                                <span class="badge text-bg-warning">Update</span>
                                @elseif ($log->progress == 'Delete')
                                <span class="badge text-bg-danger">Delete</span>
                                @elseif ($log->progress == 'View')
                                <span class="badge text-bg-info">View</span>
                                @elseif ($log->progress == 'All')
                                <span class="badge text-bg-primary">Semua</span>
                                @else
                                <span class="badge text-bg-secondary">Other</span>
                                @endif
                            </td>
                            <td>{{ $log->descriptions }}</td>
                            <td>
                                <div>
                                    {{ \Carbon\Carbon::parse($log->created_at)
                                    ->locale('id')
                                    ->settings(['formatFunction' => 'translatedFormat'])
                                    ->format('d/m/Y') }}
                                </div>
                                <div>
                                    {{ \Carbon\Carbon::parse($log->created_at)
                                    ->locale('id')
                                    ->settings(['formatFunction' => 'translatedFormat'])
                                    ->format('h:i:s') }}
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7">Tidak ada Log yang terdaftar</td>
                        </tr>
                        @endforelse
                    </tbody>
                    <tfoot class="table-group-divider table-secondary">
                        <tr>
                            <td colspan="7">Total Data: <b>{{ $logs->where('id_user', Auth::user()->id_user)->where('result', 'Warning')->count() }}</b> Log</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <div class="tab-pane fade" id="pills-error" role="tabpanel" aria-labelledby="pills-error-tab" tabindex="0">
                <!--ERROR'S TABLE-->
                <table class="table table-hover table-bordered">
                    <thead>
                        <tr class="table-primary">
                            <th class="col-1" scope="col">#</th>
                            <th class="col-3" scope="col">Aktivitas</th>
                            <th scope="col">Deskripsi</th>
                            <th class="col-2" scope="col">Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($logs->where('id_user', Auth::user()->id_user)->where('result', 'Error') as $log)
                        <tr>
                            <th scope="row">{{ $loop->iteration }}</th>
                            <td>
                                <div>{{ $log->activity }}</div>
                                @if ($log->progress == 'Create')
                                <span class="badge text-bg-success">Create</span>
                                @elseif ($log->progress == 'Update')
                                <span class="badge text-bg-warning">Update</span>
                                @elseif ($log->progress == 'Delete')
                                <span class="badge text-bg-danger">Delete</span>
                                @elseif ($log->progress == 'View')
                                <span class="badge text-bg-info">View</span>
                                @elseif ($log->progress == 'All')
                                <span class="badge text-bg-primary">Semua</span>
                                @else
                                <span class="badge text-bg-secondary">Other</span>
                                @endif
                            </td>
                            <td>{{ $log->descriptions }}</td>
                            <td>
                                <div>
                                    {{ \Carbon\Carbon::parse($log->created_at)
                                    ->locale('id')
                                    ->settings(['formatFunction' => 'translatedFormat'])
                                    ->format('d/m/Y') }}
                                </div>
                                <div>
                                    {{ \Carbon\Carbon::parse($log->created_at)
                                    ->locale('id')
                                    ->settings(['formatFunction' => 'translatedFormat'])
                                    ->format('h:i:s') }}
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7">Tidak ada Log yang terdaftar</td>
                        </tr>
                        @endforelse
                    </tbody>
                    <tfoot class="table-group-divider table-secondary">
                        <tr>
                            <td colspan="7">Total Data: <b>{{ $logs->where('id_user', Auth::user()->id_user)->where('result', 'Error')->count() }}</b> Log</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
