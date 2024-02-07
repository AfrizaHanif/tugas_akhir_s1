<!--TEMPLATE-->
@extends('Templates.Home.template')

<!--TITLE-->
@section('title')
<title>Karyawan Terbaik | Tugas Akhir</title>
@endsection

<!--CONTENTS-->
@section('contents')
@include('Pages.Home.Includes.Contents.result')
@endsection

<!--MODALS-->
@section('modals')
@include('Pages.Home.Includes.Components.modal')
@endsection

<!--SCRIPTS-->
@push('scripts')
@include('Pages.Home.Includes.Scripts.js')
@endpush
