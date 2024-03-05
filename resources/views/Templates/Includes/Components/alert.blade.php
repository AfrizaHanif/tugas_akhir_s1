@if ($message = Session::get('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-square-fill"></i> <strong>SUKSES</strong>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        <br/>
        {{ $message }}
    </div>
@endif

@if ($message = Session::get('fail'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-x-octagon-fill"></i> <strong>ERROR</strong>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        <br/>
        {{ $message }}
    </div>
@endif

@if ($message = Session::get('warning'))
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-triangle-fill"></i> <strong>PERHATIAN</strong>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        <br/>
        {{ $message }}
    </div>
@endif

@if ($message = Session::get('info'))
    <div class="alert alert-info alert-dismissible fade show" role="alert">
        <i class="bi bi-info-circle-fill"></i> <strong>INFO</strong>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        <br/>
        {{ $message }}
    </div>
@endif

@if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-x-octagon-fill"></i> <strong>ERROR</strong>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        <br/>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
