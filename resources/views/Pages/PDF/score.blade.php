<!--TEMPLATE-->
@extends('Templates.PDF.template')

<!--TITLE-->
@section('title')
<title>Laporan Nilai (Auth::user()->name) | Tugas Akhir</title>
@endsection

<!--CSS-->
@section('css')
<link rel="stylesheet" href="{{public_path('Sources/CSS/Admin/pdf.css')}}">
@endsection

<!--CONTENTS-->
@section('contents')
@include('Pages.PDF.Includes.Contents.score')
@endsection
