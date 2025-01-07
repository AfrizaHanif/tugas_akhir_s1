<!--TEMPLATE-->
@extends('Templates.Employee.template')

<!--TITLE-->
@section('title')
<title>Logs | Tugas Akhir</title>
@endsection

<!--CONTENTS-->
@section('contents')
@include('Pages.Includes.Contents.log')
@endsection

<!--MODALS-->
@section('modals')
@include('Pages.Includes.Components.Modal.log')
@endsection

<!--TOASTS-->
@section('toasts')
@endsection

<!--OFFCANVAS-->
@section('offcanvas')
@endsection

<!--SCRIPTS-->
@push('scripts')
@endpush
