<!--TEMPLATE-->
@extends('Templates.Home.template')

<!--TITLE-->
@section('title')
<title>Feedback Message | Tugas Akhir</title>
@endsection

<!--CONTENTS-->
@section('contents')
@include('Pages.Includes.Contents.message')
@endsection

<!--MODALS-->
@section('modals')
@include('Pages.Includes.Components.Modals.message')
@endsection

<!--TOASTS-->
@section('toasts')
@endsection

<!--OFFCANVAS-->
@section('offcanvas')
@include('Pages.Includes.Components.Offcanvas.message')
@endsection

<!--SCRIPTS-->
@push('scripts')
@endpush
