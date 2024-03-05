<h1 class="text-center mb-4">Voting Karyawan Terbaik</h1>
@include('Templates.Includes.Components.alert')
<div class="row">
    <div class="col-md-3">
        <div class="position-sticky" style="top: 2rem;">
            <div class="nav flex-column nav-pills me-3" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                @forelse ($periods as $period)
                <button class="nav-link {{ $loop->first ? 'active' : '' }}" id="pills-{{ $period->id_period }}-tab" data-bs-toggle="pill" data-bs-target="#pills-{{ $period->id_period }}" type="button" role="tab" aria-controls="pills-{{ $period->id_period }}" aria-selected="{{ $loop->first ? 'true' : 'false' }}">
                    @if ($period->status == "Finished")
                    {{ $period->name }} <span class="badge bg-secondary">Selesai</span>
                    @else
                    {{ $period->name }}
                    @endif
                </button>
                @empty
                <button class="nav-link active" id="pills-empty-tab" data-bs-toggle="pill" data-bs-target="#pills-empty" type="button" role="tab" aria-controls="pills-empty" aria-selected="true">
                    Empty
                </button>
                @endforelse
            </div>
            <br/>
        </div>
    </div>
    <div class="col-md-9">
        <div class="tab-content" id="v-pills-tabContent">
            @forelse ($periods as $period)
            <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" id="pills-{{ $period->id_period }}" role="tabpanel" aria-labelledby="pills-{{ $period->id_period }}-tab" tabindex="0">
                @if ($period->status == "Finished")
                <h2>{{ $period->name }} <span class="badge bg-success">Selesai</span></h2>
                @else
                <h2>{{ $period->name }}</h2>
                @endif
                <table class="table table-hover table-bordered">
                    <thead>
                        <tr class="table-primary">
                            <th class="col-1" scope="col">#</th>
                            <th scope="col">Nama</th>
                            <th class="col-1" scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($votes->where('id_period', $period->id_period) as $vote)
                        <tr>
                            <th scope="row">{{ $loop->iteration }}</th>
                            <td>{{ $vote->officer->name }}</td>
                            <td>
                                <a class="btn btn-primary" href="#" role="button" data-bs-toggle="modal" data-bs-target="#modal-vte-select-{{ $period->id_period }}-{{ $vote->id }}">
                                    <i class="bi bi-check-lg"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tfoot class="table-group-divider table-secondary">
                            <tr>
                                <td colspan="10">Total Data: <b>{{ $votes->where('id_period', $period->id_period)->count() }}</b> Pegawai</td>
                            </tr>
                        </tfoot>
                    </tfoot>
                </table>
            </div>
            @empty
            <div class="tab-pane fade show active" id="pills-empty" role="tabpanel" aria-labelledby="pills-empty-tab" tabindex="0">
                <div class="alert alert-danger" role="alert">
                    <p>Tidak ada data yang terdaftar.</p>
                </div>
            </div>
            @endforelse
        </div>
    </div>
</div>
