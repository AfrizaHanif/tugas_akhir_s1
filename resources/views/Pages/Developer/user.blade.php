<!--TEMPLATE-->
@extends('Templates.Developer.template')

<!--TITLE-->
@section('title')
<title>User / Pengguna | Tugas Akhir</title>
@endsection

<!--CONTENTS-->
@section('contents')
@include('Pages.Includes.Contents.user')
@endsection

<!--MODALS-->
@section('modals')
@include('Pages.Includes.Components.Modal.user')
@endsection

<!--TOASTS-->
@section('toasts')
@endsection

<!--OFFCANVAS-->
@section('offcanvas')
@include('Pages.Includes.Components.Offcanvas.user')
@endsection

<!--SCRIPTS-->
@push('scripts')
<!--
<script>
    document.getElementById("close-usr-create").addEventListener("click", function()
    {
        document.getElementById("form-usr-create").reset();
    });
</script>
-->
@endpush
