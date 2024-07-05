<!--TEMPLATE-->
@extends('Templates.Admin.template')

<!--TITLE-->
@section('title')
<title>Kriteria | Tugas Akhir</title>
@endsection

<!--CONTENTS-->
@section('contents')
@include('Pages.Admin.Includes.Contents.criteria')
@endsection

<!--MODALS-->
@section('modals')
@include('Pages.Admin.Includes.Components.Modals.criteria')
@endsection

<!--TOASTS-->
@section('toasts')
@endsection

<!--OFFCANVAS-->
@section('offcanvas')
@include('Pages.Admin.Includes.Components.Offcanvas.criteria')
@endsection

<!--SCRIPTS-->
@push('scripts')
<script>
    $(document).ready(function () {
        $('#v-pills-tab button[data-bs-target="#{{ old('tab_redirect') }}"]').trigger("click")
    });
</script>
<!--
<script>
    document.getElementById("close-crt-create").addEventListener("click", function()
    {
        document.getElementById("form-crt-create").reset();
    });
</script>
-->
@endpush
