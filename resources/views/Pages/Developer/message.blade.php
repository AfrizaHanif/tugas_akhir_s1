<!--TEMPLATE-->
@extends('Templates.Developer.template')

<!--TITLE-->
@section('title')
<title>Pesan | Tugas Akhir</title>
@endsection

<!--CONTENTS-->
@section('contents')
@include('Pages.Developer.Includes.Contents.message')
@endsection

<!--MODALS-->
@section('modals')
@include('Pages.Developer.Includes.Components.Modal.message')
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
