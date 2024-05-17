<!--TEMPLATE-->
@extends('Templates.Admin.template')

<!--TITLE-->
@section('title')
    @if (Request::is('admin/analysis/saw*'))
    <title>Analisis SAW | Tugas Akhir</title>
    @elseif (Request::is('admin/analysis/wp*'))
    <title>Analisis WP | Tugas Akhir</title>
    @endif
@endsection

<!--CONTENTS-->
@section('contents')
@include('Pages.Admin.Includes.Contents.analysis')
@endsection

<!--MODALS-->
@section('modals')
@include('Pages.Admin.Includes.Components.modal')
@endsection

<!--SCRIPTS-->
@push('scripts')
@endpush
