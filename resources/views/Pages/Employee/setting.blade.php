<!--TEMPLATE-->
@extends('Templates.Employee.template')

<!--TITLE-->
@section('title')
<title>Pengaturan | Tugas Akhir</title>
@endsection

<!--CONTENTS-->
@section('contents')
@include('Pages.Includes.Contents.setting')
@endsection

<!--MODALS-->
@section('modals')
@include('Pages.Includes.Components.Modal.setting')
@endsection

<!--SCRIPTS-->
@push('scripts')
<script>
    window.onload = function() {
        var src = document.getElementById("s_password"),
            dst = document.getElementById("password");
        src.addEventListener('input', function() {
            dst.value = src.value;
        });
    };
</script>
<script>
    window.onload = function() {
        var src = document.getElementById("s_username"),
            dst = document.getElementById("username");
        src.addEventListener('input', function() {
            dst.value = src.value;
        });
    };
</script>
@endpush
