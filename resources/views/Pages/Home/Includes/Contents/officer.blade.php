<h1 class="text-center mb-4">Data Pegawai</h1>
<p>
    <form action="{{ route('officers.search') }}" method="GET">
        <div class="input-group mb-3">
            <span class="input-group-text" id="officer-search"><i class="bi bi-search"></i></span>
            @if (Request::is('officers/search*'))
            <input type="search" id="search" name="search" class="typeahead form-control" placeholder="Ketik untuk mencari pegawai, lalu tekan enter atau klik cari" aria-label="Search" aria-describedby="officer-search" value="{{ $search }}">
            @else
            <input type="search" id="search" name="search" class="typeahead form-control" placeholder="Ketik untuk mencari pegawai, lalu tekan enter atau klik cari" aria-label="Search" aria-describedby="officer-search">
            @endif
            <button class="btn btn-outline-primary" type="submit" id="officer-search">Cari</button>
        </div>
    </form>
</p>
@if (Request::is('officers/search*'))
<table class="table table-hover table-bordered">
    <thead>
        <tr class="table-primary">
            <th class="col-1" scope="col">#</th>
            <th scope="col">Nama</th>
            <th scope="col">Jabatan</th>
            <th scope="col">Jenis Kelamin</th>
            <th class="col-1" scope="col">Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($officers as $officer)
        <tr>
            <th scope="row">{{ $loop->iteration }}</th>
            <td>{{ $officer->name }}</td>
            <td>{{ $officer->department->name }}</td>
            <td>{{ $officer->gender }}</td>
            <td>
                <div class="dropdown">
                    <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-menu-button-fill"></i>
                    </button>
                    <ul class="dropdown-menu mx-0 shadow w-table-menu">
                        <li>
                            <a class="dropdown-item d-flex gap-2 align-items-center"  href="#" data-bs-toggle="modal" data-bs-target="#modal-off-view-{{ $officer->id_officer }}"><svg class="bi" width="16" height="16" style="vertical-align: -.125em;"><use xlink:href="#view"/></svg>
                                Detail
                            </a>
                        </li>
                    </ul>
                </div>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="7">Tidak ada Pegawai yang terdaftar</td>
        </tr>
        @endforelse
    </tbody>
    <tfoot class="table-group-divider table-secondary">
        <tr>
            <td colspan="7">Total Data: <b>{{ $officers->count() }}</b> Pegawai</td>
        </tr>
    </tfoot>
</table>
{{$officers->withQueryString()->links()}}
@else
<nav>
    <div class="nav nav-tabs" id="nav-tab" role="tablist">
        @forelse ($parts as $part)
        <button class="nav-link {{ $loop->first ? 'active' : '' }}" id="nav-{{ $part->id_part }}-tab" data-bs-toggle="tab" data-bs-target="#nav-{{ $part->id_part }}" type="button" role="tab" aria-controls="nav-{{ $part->id_part }}" aria-selected="{{ $loop->first ? 'true' : 'false' }}">{{ $part->name }}</button>
        @empty
        <button class="nav-link active" id="nav-empty-tab" data-bs-toggle="tab" data-bs-target="#nav-empty" type="button" role="tab" aria-controls="nav-empty" aria-selected="true">Empty</button>
        @endforelse
    </div>
</nav>

<div class="tab-content" id="nav-tabContent">
    @forelse ($parts as $part)
    <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" id="nav-{{ $part->id_part }}" role="tabpanel" aria-labelledby="nav-{{ $part->id_part }}-tab" tabindex="0">
        <table class="table table-hover table-bordered">
            <thead>
                <tr class="table-primary">
                    <th class="col-1" scope="col">#</th>
                    <th scope="col">Nama</th>
                    <th scope="col">Jabatan</th>
                    <th scope="col">Jenis Kelamin</th>
                    <th class="col-1" scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($officers->where('id_part', $part->id_part) as $officer)
                <tr>
                    <th scope="row">{{ $loop->iteration }}</th>
                    <td>{{ $officer->name }}</td>
                    <td>{{ $officer->department->name }}</td>
                    <td>{{ $officer->gender }}</td>
                    <td>
                        <div class="dropdown">
                            <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-menu-button-fill"></i>
                            </button>
                            <ul class="dropdown-menu mx-0 shadow w-table-menu">
                                <li>
                                    <a class="dropdown-item d-flex gap-2 align-items-center"  href="#" data-bs-toggle="modal" data-bs-target="#modal-off-view-{{ $officer->id_officer }}"><svg class="bi" width="16" height="16" style="vertical-align: -.125em;"><use xlink:href="#view"/></svg>
                                        Detail
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7">Tidak ada Pegawai yang terdaftar</td>
                </tr>
                @endforelse
            </tbody>
            <tfoot class="table-group-divider table-secondary">
                <tr>
                    <td colspan="7">Total Data: <b>{{ $officers->where('id_part', $part->id_part)->count() }}</b> Pegawai</td>
                </tr>
            </tfoot>
        </table>
    </div>
    @empty
    <div class="tab-pane fade show active" id="nav-empty" role="tabpanel" aria-labelledby="nav-empty-tab" tabindex="0">
        <div class="alert alert-danger" role="alert">
            <p>Tidak ada data yang terdaftar.</p>
        </div>
    </div>
    @endforelse
</div>
@endif

