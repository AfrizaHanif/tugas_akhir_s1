<!--TEMPLATE-->
@extends('Templates.Employee.template')

<!--TITLE-->
@section('title')
<title>Karyawan Karyawan | Tugas Akhir</title>
@endsection

<!--CONTENTS-->
@section('contents')
@include('Pages.Includes.Contents.score')
@endsection

<!--MODALS-->
@section('modals')
@include('Pages.Employee.Includes.Components.modal')
@endsection

<!--SCRIPTS-->
@push('scripts')
@endpush
