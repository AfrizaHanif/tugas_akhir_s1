<!--TEMPLATE-->
@extends('Templates.Auth.template')

<!--TITLE-->
@section('title')
<title>Masuk | Tugas Akhir</title>
@endsection

<!--CONTENTS-->
@section('contents')
@include('Pages.Auth.Includes.Contents.login')
@endsection

<!--MODALS-->
@section('modals')
@include('Pages.Auth.Includes.Components.modal')
@endsection

<!--SCRIPTS-->
@push('scripts')
@include('Pages.Auth.Includes.Scripts.js')
@endpush
