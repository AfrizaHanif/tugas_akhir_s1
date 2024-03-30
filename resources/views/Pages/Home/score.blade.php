<!--TEMPLATE-->
@extends('Templates.Home.template')

<!--TITLE-->
@section('title')
<title>3 Pegawai Terbaik | Tugas Akhir</title>
@endsection

<!--CONTENTS-->
@section('contents')
@include('Pages.Home.Includes.Contents.score')
@endsection

<!--MODALS-->
@section('modals')
@include('Pages.Home.Includes.Components.modal')
@endsection

<!--SCRIPTS-->
@push('scripts')
@include('Pages.Home.Includes.Scripts.js')
@endpush
