<h1 class="text-center mb-4">Data Karyawan Terbaik</h1>
<div class="row">
    <div class="col-md-3">
        <div class="position-sticky" style="top: 2rem;">
            <div class="nav flex-column nav-pills me-3" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                @forelse ($periods as $period)
                <button class="nav-link {{ $loop->first ? 'active' : '' }}" id="pills-{{ $period->id_period }}-tab" data-bs-toggle="pill" data-bs-target="#pills-{{ $period->id_period }}" type="button" role="tab" aria-controls="pills-{{ $period->id_period }}" aria-selected="{{ $loop->first ? 'true' : 'false' }}">
                    {{ $period->name }}
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
                <h2>{{ $period->name }}</h2>
                <table class="table table-hover table-bordered">
                    <thead>
                        <tr class="table-primary">
                            <th class="col-1" scope="col">#</th>
                            <th scope="col">Nama</th>
                            <th scope="col">Hasil Akhir</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($results->where('id_period', $period->id_period) as $result)
                        <tr>
                            <th scope="row">{{ $loop->iteration }}</th>
                            <td>{{ $result->officer->name }}</td>
                            <td>{{ $result->final_score }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5">Tidak Ada Data.</td>
                        </tr>
                        @endforelse
                    </tbody>
                    <tfoot>
                        <tfoot class="table-group-divider table-secondary">
                            <tr>
                                <td colspan="10">Total Data: <b>{{ $results->where('id_period', $period->id_period)->count() }}</b> Pegawai</td>
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
