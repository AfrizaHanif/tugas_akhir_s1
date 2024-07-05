<!--TEMPLATE-->
@extends('Templates.Admin.template')

<!--TITLE-->
@section('title')
<title>Periode | Tugas Akhir</title>
@endsection

<!--CONTENTS-->
@section('contents')
@include('Pages.Admin.Includes.Contents.period')
@endsection

<!--MODALS-->
@section('modals')
@include('Pages.Admin.Includes.Components.Modals.period')
@endsection

<!--TOASTS-->
@section('toasts')
@endsection

<!--OFFCANVAS-->
@section('offcanvas')
@include('Pages.Admin.Includes.Components.Offcanvas.period')
@endsection

<!--SCRIPTS-->
@push('scripts')
<!--
<script>
    document.getElementById("close-per-create").addEventListener("click", function()
    {
        document.getElementById("form-per-create").reset();
    });
</script>
-->
@endpush
