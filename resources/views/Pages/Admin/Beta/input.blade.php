<!--TEMPLATE-->
@extends('Templates.Admin.template')

<!--TITLE-->
@section('title')
    @if (Request::is('inputs/beta/presences'))
    <title>Data Kehadiran (Beta) | Tugas Akhir</title>
    @elseif (Request::is('inputs/beta/performances'))
    <title>Data Prestasi Kerja (Beta) | Tugas Akhir</title>
    @endif
@endsection

<!--CONTENTS-->
@section('contents')
@include('Pages.Admin.Includes.Contents.Beta.input')
@endsection

<!--MODALS-->
@section('modals')
@include('Pages.Admin.Includes.Components.Beta.modal')
@endsection

<!--SCRIPTS-->
@push('scripts')
@endpush
