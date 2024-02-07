<!--TEMPLATE-->
@extends('Templates.Admin.template')

<!--TITLE-->
@section('title')
    @if (Request::is('inputs/presences'))
    <title>Data Kehadiran | Tugas Akhir</title>
    @elseif (Request::is('inputs/performances'))
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
@include('Pages.Admin.Includes.Scripts.js')
@endpush
