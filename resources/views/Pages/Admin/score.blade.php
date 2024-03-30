<!--TEMPLATE-->
@extends('Templates.Admin.template')

<!--TITLE-->
@section('title')
<title>Hasil Perhitungan | Tugas Akhir</title>
@endsection

<!--CONTENTS-->
@section('contents')
@include('Pages.Admin.Includes.Contents.score')
@endsection

<!--MODALS-->
@section('modals')
@include('Pages.Admin.Includes.Components.modal')
@endsection

<!--SCRIPTS-->
@push('scripts')
@endpush
