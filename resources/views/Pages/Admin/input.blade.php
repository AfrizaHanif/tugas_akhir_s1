<!--TEMPLATE-->
@extends('Templates.Admin.template')

<!--TITLE-->
@section('title')
    @if (Request::is('admin/inputs/presences'))
    <title>Data Kehadiran | Tugas Akhir</title>
    @elseif (Request::is('admin/inputs/kbu/performances') || Request::is('admin/inputs/ktt/performances') || Request::is('admin/inputs/kbps/performances'))
    <title>Data Prestasi Kerja | Tugas Akhir</title>
    @endif
@endsection

<!--CONTENTS-->
@section('contents')
@include('Pages.Admin.Includes.Contents.input')
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
@endpush
