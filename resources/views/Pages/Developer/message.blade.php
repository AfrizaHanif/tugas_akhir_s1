<!--TEMPLATE-->
@extends('Templates.Developer.template')

<!--TITLE-->
@section('title')
<title>Pesan Feedback | Tugas Akhir</title>
@endsection

<!--CONTENTS-->
@section('contents')
@include('Pages.Includes.Contents.message')
@endsection

<!--MODALS-->
@section('modals')
@include('Pages.Includes.Components.Modal.message')
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
