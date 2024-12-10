<!--TEMPLATE-->
@extends('Templates.Admin.template')

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

<!--TOASTS-->
@section('toasts')
@endsection

<!--OFFCANVAS-->
@section('offcanvas')
@endsection

<!--SCRIPTS-->
@push('scripts')
<script>
    function copyValue1() {
    var dropboxvalue1 = document.getElementById('s_presence_counter').value;
    document.getElementById('presence_counter').value = dropboxvalue1;
    }
</script>
<script>
    function copyValue2() {
    var dropboxvalue2 = document.getElementById('s_second_sort').value;
    document.getElementById('second_sort').value = dropboxvalue2;
    }
</script>
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
