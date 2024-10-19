<!--TEMPLATE-->
@extends('Templates.Admin.template')

<!--TITLE-->
@section('title')
<title>Data Input | Tugas Akhir</title>
@endsection

<!--STYLE-->
@section('style')
@endsection

<!--CONTENTS-->
@section('contents')
@include('Pages.Admin.Includes.Contents.input')
@endsection

<!--MODALS-->
@section('modals')
@include('Pages.Admin.Includes.Components.Modal.inputall')
@include('Pages.Admin.Includes.Components.Modal.input')
@endsection

<!--TOASTS-->
@section('toasts')
    @if ((!empty($latest_per)))
    <div class="toast-container position-fixed bottom-0 end-0 p-3">
        <div id="exportToast-{{ $latest_per->id_period }}" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header">
                <strong class="me-auto">Export File</strong>
                <small>{{ $latest_per->month }} {{ $latest_per->year }}</small>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">
                Export sedang diproses dan akan muncul di Download pada browser anda.
            </div>
        </div>
    </div>
    @endif

    <div class="toast-container position-fixed bottom-0 end-0 p-3">
        @foreach ($history_per as $period)
        <div id="exportToast-{{ $period->id_period }}" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header">
                <strong class="me-auto">Export File</strong>
                <small>{{ $period->period_name }}</small>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">
                Export sedang diproses dan akan muncul di Download pada browser anda.
            </div>
        </div>
        @endforeach
    </div>
@endsection

<!--OFFCANVAS-->
@section('offcanvas')
@include('Pages.Admin.Includes.Components.Offcanvas.input')
@endsection

<!--SCRIPTS-->
@push('scripts')
@if ((!empty($latest_per)))
<script>
    $(document).ready(function () {
        $('#v-pills-tab button[data-bs-target="#{{ old('tab_redirect') }}"]').trigger("click")
    });
</script>
<script>
    const toastTrigger = document.getElementById('exportToastBtn-{{ $latest_per->id_period }}')
    const toastLiveExample = document.getElementById('exportToast-{{ $latest_per->id_period }}')

    if (toastTrigger) {
        const toastBootstrap = bootstrap.Toast.getOrCreateInstance(toastLiveExample)
        toastTrigger.addEventListener('click', () => {
            toastBootstrap.show()
        })
    }
</script>
@endif
@foreach ($history_per as $period)
<script>
    const toastTriggerOld{{ str_replace('-', '', $period->id_period) }} = document.getElementById('exportToastBtn-{{ $period->id_period }}')
    const toastLiveExampleOld{{ str_replace('-', '', $period->id_period) }} = document.getElementById('exportToast-{{ $period->id_period }}')

    if (toastTriggerOld{{ str_replace('-', '', $period->id_period) }}) {
        const toastBootstrapOld{{ str_replace('-', '', $period->id_period) }} = bootstrap.Toast.getOrCreateInstance(toastLiveExampleOld{{ str_replace('-', '', $period->id_period) }})
        toastTriggerOld{{ str_replace('-', '', $period->id_period) }}.addEventListener('click', () => {
            toastBootstrapOld{{ str_replace('-', '', $period->id_period) }}.show()
        })
    }
</script>
@endforeach
@endpush
