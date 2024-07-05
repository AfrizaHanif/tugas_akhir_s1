<!--TEMPLATE-->
@extends('Templates.Admin.template')

<!--TITLE-->
@section('title')
<title>Hasil Perhitungan | Tugas Akhir</title>
@endsection

<!--CONTENTS-->
@section('contents')
@include('Pages.Admin.Includes.Contents.score')
@endsection

<!--MODALS-->
@section('modals')
@include('Pages.Admin.Includes.Components.Modals.inputall')
@include('Pages.Admin.Includes.Components.Modals.validate')
@endsection

<!--TOASTS-->
@section('toasts')
@endsection

<!--OFFCANVAS-->
@section('offcanvas')
@include('Pages.Admin.Includes.Components.Offcanvas.validate')
@endsection

<!--SCRIPTS-->
@push('scripts')
@endpush
