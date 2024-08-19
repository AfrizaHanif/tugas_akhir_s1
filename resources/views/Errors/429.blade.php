<!--TEMPLATE-->
@extends('Templates.Errors.template')

<!--TITLE-->
@section('title')
<title>Error 404 | Tugas Akhir</title>
@endsection

<!--CONTENTS-->
@section('contents')
@include('Errors.Includes.Contents.429')
@endsection

<!--OFFCANVAS-->
@section('offcanvas')
@include('Errors.Includes.Components.Offcanvas.429')
@endsection
