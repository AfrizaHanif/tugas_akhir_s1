<!--TEMPLATE-->
@extends('Templates.Admin.template')

<!--TITLE-->
@section('title')
<title>Kriteria untuk Pemilihan | Tugas Akhir</title>
@endsection

<!--CONTENTS-->
@section('contents')
@include('Pages.Admin.Includes.Contents.vote-crit')
@endsection

<!--MODALS-->
@section('modals')
@include('Pages.Admin.Includes.Components.modal')
@endsection

<!--SCRIPTS-->
@push('scripts')
<!--
<script>
    document.getElementById("close-vcr-create").addEventListener("click", function()
    {
        document.getElementById("form-vcr-create").reset();
    });
</script>
-->
@endpush
