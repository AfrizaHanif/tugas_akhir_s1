<!--TEMPLATE-->
@extends('Templates.Admin.template')

<!--TITLE-->
@section('title')
<title>User / Pengguna | Tugas Akhir</title>
@endsection

<!--CONTENTS-->
@section('contents')
@include('Pages.Admin.Includes.Contents.user')
@endsection

<!--MODALS-->
@section('modals')
@include('Pages.Admin.Includes.Components.modal')
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
