<!--TEMPLATE-->
@extends('Templates.Admin.template')

<!--TITLE-->
@section('title')
    @if (Request::is('inputs/presences'))
    <title>Data Kehadiran | Tugas Akhir</title>
    @elseif (Request::is('inputs/kbu/performances') || Request::is('inputs/ktt/performances'))
    <title>Data Prestasi Kerja | Tugas Akhir</title>
    @endif
@endsection

<!--CONTENTS-->
@section('contents')
@include('Pages.Admin.Includes.Contents.input')
@endsection

<!--MODALS-->
@section('modals')
@endsection

<!--SCRIPTS-->
@push('scripts')
<script>
    $(document).ready(function () {
        $('#v-pills-tab button[data-bs-target="#{{ old('tab_redirect') }}"]').trigger("click")
    });
</script>
@endpush
