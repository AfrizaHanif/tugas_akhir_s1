<h1 class="text-center mb-4">Karyawan Terbaik</h1>
@include('Templates.Includes.Components.alert')
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
                @foreach ($results->where('id_period', $period->id_period) as $result)
                <div class="card">
                    <div class="card-body">
                        Selamat Kepada: <b>{{ $result->officer->name }}</b>
                    </div>
                </div>
                <br/>
                @endforeach
                @foreach ($votecriterias as $criteria)
                <div class="card">
                    <div class="card-body">
                        <h4>{{ $criteria->name }}</h4>
                        <table class="table">
                            @foreach ($votes->where('id_period', $period->id_period)->where('id_vote_criteria', $criteria->id_vote_criteria) as $vote)
                            <tr>
                                <th scope="row">{{ $vote->officer->name }}</th>
                                <td>{{ $vote->votes }}</td>
                            </tr>
                            @endforeach
                        </table>
                        @foreach ($voteresults->where('id_period', $period->id_period)->where('id_vote_criteria', $criteria->id_vote_criteria) as $result)
                        <p>Pegawai yang bernama <b>{{ $result->officer->name }}</b> merupakan pegawai terbaik di kriteria {{ $criteria->name }}</p>
                        @endforeach
                    </div>
                </div>
                <br/>
                @endforeach
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
