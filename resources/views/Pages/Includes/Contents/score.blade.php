<h1 class="text-center mb-4">Karyawan Terbaik</h1>
<div class="row">
    <!--PERIOD PICKER-->
    <div class="col-md-3">
        <div class="position-sticky" style="top: 0rem;">
            <div class="nav flex-column nav-pills me-3" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                @forelse ($periods as $period)
                <button class="nav-link {{ $loop->first ? 'active' : '' }} text-start" id="pills-{{ $period->id_period }}-tab" data-bs-toggle="pill" data-bs-target="#pills-{{ $period->id_period }}" type="button" role="tab" aria-controls="pills-{{ $period->id_period }}" aria-selected="{{ $loop->first ? 'true' : 'false' }}">
                    {{ $period->period->name }}
                </button>
                @empty
                <button class="nav-link active text-start" id="pills-empty-tab" data-bs-toggle="pill" data-bs-target="#pills-empty" type="button" role="tab" aria-controls="pills-empty" aria-selected="true">
                    Empty
                </button>
                @endforelse
            </div>
            <br/>
        </div>
    </div>
    <!--MAIN CONTENT-->
    <div class="col-md-9">
        <div class="tab-content" id="v-pills-tabContent">
            @forelse ($periods as $period)
            <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" id="pills-{{ $period->id_period }}" role="tabpanel" aria-labelledby="pills-{{ $period->id_period }}-tab" tabindex="0">
                <h2>{{ $period->period->name }}</h2>
                <!--TABLE-->
                <table class="table table-hover table-bordered">
                    <thead>
                        <tr class="table-primary">
                            <th class="col-1" scope="col">Peringkat</th>
                            <th scope="col">Nama</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($scores->where('id_period', $period->id_period) as $score)
                        @if ($loop->first) <!--WINNER COLOR-->
                        <tr class="table-success">
                        @elseif ($loop->iteration == 2 || $loop->iteration == 3) <!--2ND AND 3RD PLACES COLOR-->
                        <tr class="table-warning">
                        @elseif ($loop->last) <!--LAST PLACES COLOR-->
                        <tr class="table-danger">
                        @else
                        <tr>
                        @endif
                            <th scope="row">{{ $loop->iteration }}</th>
                            <td>{{ $score->employee_name }}</td>
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
                                <td colspan="10">Total Data: <b>{{ $scores->where('id_period', $period->id_period)->count() }}</b> Karyawan</td>
                            </tr>
                        </tfoot>
                    </tfoot>
                </table>
            </div>
            @empty
            <div class="tab-pane fade show active" id="pills-empty" role="tabpanel" aria-labelledby="pills-empty-tab" tabindex="0">
                <div class="alert alert-danger" role="alert">
                    Tidak ada data yang terdaftar.
                </div>
            </div>
            @endforelse
        </div>
    </div>
</div>
