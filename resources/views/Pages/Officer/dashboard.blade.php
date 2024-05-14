<!--TEMPLATE-->
@extends('Templates.Officer.template')

<!--TITLE-->
@section('title')
<title>Pegawai | Tugas Akhir</title>
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
