<!--TEMPLATE-->
@extends('Templates.Admin.template')

<!--TITLE-->
@section('title')
<title>Analisis SAW | Tugas Akhir</title>
@endsection

<!--CONTENTS-->
@section('contents')
@include('Pages.Admin.Includes.Contents.analysis')
@endsection

<!--MODALS-->
@section('modals')
@include('Pages.Admin.Includes.Components.Modal.analysis')
@endsection

<!--TOASTS-->
@section('toasts')
@endsection

<!--OFFCANVAS-->
@section('offcanvas')
@include('Pages.Admin.Includes.Components.Offcanvas.analysis')
@endsection

<!--SCRIPTS-->
@push('scripts')
@endpush
