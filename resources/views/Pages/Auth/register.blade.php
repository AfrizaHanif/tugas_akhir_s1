<!--TEMPLATE-->
@extends('Templates.Auth.template')

<!--TITLE-->
@section('title')
<title>Daftar Akun | Tugas Akhir</title>
@endsection

<!--CONTENTS-->
@section('contents')

@endsection

<!--MODALS-->
@section('modals')
@include('Pages.Auth.Includes.Components.modal')
@endsection

<!--SCRIPTS-->
@push('scripts')
@include('Pages.Auth.Includes.Scripts.js')
@endpush
