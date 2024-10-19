<!--TEMPLATE-->
@extends('Templates.Officer.template')

<!--TITLE-->
@section('title')
<title>Karyawan Pegawai | Tugas Akhir</title>
@endsection

<!--CONTENTS-->
@section('contents')
@include('Pages.Includes.Contents.score')
@endsection

<!--MODALS-->
@section('modals')
@include('Pages.Officer.Includes.Components.modal')
@endsection

<!--SCRIPTS-->
@push('scripts')
@endpush
