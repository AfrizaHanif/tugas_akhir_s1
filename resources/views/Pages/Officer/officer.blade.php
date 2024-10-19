<!--TEMPLATE-->
@extends('Templates.Officer.template')

<!--TITLE-->
@section('title')
<title>Daftar Pegawai | Tugas Akhir</title>
@endsection

<!--CONTENTS-->
@section('contents')
@include('Pages.Includes.Contents.officer')
@endsection

<!--MODALS-->
@section('modals')
@include('Templates.Includes.Components.Modal.officer')
@include('Pages.Includes.Components.Modal.officer')
@endsection

<!--SCRIPTS-->
@push('scripts')
@endpush
