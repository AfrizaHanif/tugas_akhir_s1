<!--TEMPLATE-->
@extends('Templates.Developer.template')

<!--TITLE-->
@section('title')
<title>Developer | Tugas Akhir</title>
@endsection

<!--CONTENTS-->
@section('contents')
@include('Pages.Includes.Contents.dashboard')
@endsection

<!--MODALS-->
@section('modals')
@include('Pages.Includes.Components.modal')
@endsection

<!--SCRIPTS-->
@push('scripts')
@endpush
