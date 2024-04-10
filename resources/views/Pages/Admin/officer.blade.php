<!--TEMPLATE-->
@extends('Templates.Admin.template')

<!--TITLE-->
@section('title')
<title>Pegawai | Tugas Akhir</title>
@endsection

<!--CONTENTS-->
@section('contents')
@include('Pages.Admin.Includes.Contents.officer')
@endsection

<!--MODALS-->
@section('modals')
@include('Pages.Admin.Includes.Components.modal')
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
