<!--TEMPLATE-->
@extends('Templates.Admin.template')

<!--TITLE-->
@section('title')
<title>Laporan | Tugas Akhir</title>
@endsection

<!--CONTENTS-->
@section('contents')
@include('Pages.Includes.Contents.report')
@endsection

<!--MODALS-->
@section('modals')
@include('Pages.Includes.Components.Modal.report')
@endsection

<!--TOASTS-->
@section('toasts')
@endsection

<!--OFFCANVAS-->
@section('offcanvas')
@include('Pages.Includes.Components.Offcanvas.report')
@endsection

<!--SCRIPTS-->
@push('scripts')
@endpush
