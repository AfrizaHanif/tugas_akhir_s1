<!--TEMPLATE-->
@extends('Templates.Employee.template')

<!--TITLE-->
@section('title')
<title>Daftar Karyawan | Tugas Akhir</title>
@endsection

<!--CONTENTS-->
@section('contents')
@include('Pages.Includes.Contents.employee')
@endsection

<!--MODALS-->
@section('modals')
@include('Templates.Includes.Components.Modal.employee')
@include('Pages.Includes.Components.Modal.employee')
@endsection

<!--SCRIPTS-->
@push('scripts')
@endpush
