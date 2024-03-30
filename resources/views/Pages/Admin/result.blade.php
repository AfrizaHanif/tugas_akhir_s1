<!--TEMPLATE-->
@extends('Templates.Admin.template')

<!--TITLE-->
@section('title')
<title>Karyawan Terbaik | Tugas Akhir</title>
@endsection

<!--CONTENTS-->
@section('contents')
@include('Pages.Admin.Includes.Contents.result')
@endsection

<!--MODALS-->
@section('modals')
@include('Pages.Admin.Includes.Components.modal')
@endsection

<!--SCRIPTS-->
@push('scripts')
@endpush
