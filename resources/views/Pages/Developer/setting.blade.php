<!--TEMPLATE-->
@extends('Templates.Developer.template')

<!--TITLE-->
@section('title')
<title>Pengaturan | Tugas Akhir</title>
@endsection

<!--CONTENTS-->
@section('contents')
@include('Pages.Developer.Includes.Contents.setting')
@endsection

<!--MODALS-->
@section('modals')
@include('Pages.Developer.Includes.Components.Modal.setting')
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
@endpush
