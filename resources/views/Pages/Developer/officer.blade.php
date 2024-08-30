<!--TEMPLATE-->
@extends('Templates.Developer.template')

<!--TITLE-->
@section('title')
<title>Pegawai | Tugas Akhir</title>
@endsection

<!--CONTENTS-->
@section('contents')
@include('Pages.Includes.Contents.officer')
@endsection

<!--MODALS-->
@section('modals')
@include('Templates.Includes.Components.Modal.officer')
@include('Pages.Includes.Components.modal')
@endsection

<!--TOASTS-->
@section('toasts')
<div class="toast-container position-fixed bottom-0 end-0 p-3">
    <div id="exportToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header">
            <strong class="me-auto">Export File</strong>
            <small>Sekarang</small>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body">
            Export sedang diproses dan akan muncul di Download pada browser anda.
        </div>
    </div>
</div>
@endsection

<!--OFFCANVAS-->
@section('offcanvas')
@include('Pages.Includes.Components.Offcanvas.officers')
@endsection

<!--SCRIPTS-->
@push('scripts')
<script>
    $(document).ready(function () {
        $('#parts-tab button[data-bs-target="#{{ old('tab_redirect') }}"]').trigger("click")
    });
</script>
<script>
    $(document).ready(function () {
        $('#teams-tab button[data-bs-target="#{{ old('sub_tab_redirect') }}"]').trigger("click")
    });
</script>
<script>
    $(document).ready(function () {
        $('#teams-modal-tab button[data-bs-target="#{{ old('modal_tab_redirect') }}"]').trigger("click")
    });
</script>
<script>
    const toastTrigger = document.getElementById('exportToastBtn')
    const toastLiveExample = document.getElementById('exportToast')

    if (toastTrigger) {
        const toastBootstrap = bootstrap.Toast.getOrCreateInstance(toastLiveExample)
        toastTrigger.addEventListener('click', () => {
            toastBootstrap.show()
        })
    }
</script>
<!--
<script>
    document.getElementById("close-prt-create").addEventListener("click", function()
    {
        document.getElementById("form-prt-create").reset();
    });
</script>
<script>
    document.getElementById("close-dep-create").addEventListener("click", function()
    {
        document.getElementById("form-dep-create").reset();
    });
</script>
-->
@endpush
