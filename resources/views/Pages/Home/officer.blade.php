<!--TEMPLATE-->
@extends('Templates.Home.template')

<!--TITLE-->
@section('title')
<title>Pegawai | Tugas Akhir</title>
@endsection

<!--CONTENTS-->
@section('contents')
@include('Pages.Home.Includes.Contents.officer')
@endsection

<!--MODALS-->
@section('modals')
@include('Pages.Home.Includes.Components.modal')
@endsection

<!--SCRIPTS-->
@push('scripts')
@include('Pages.Home.Includes.Scripts.js')
@endpush
