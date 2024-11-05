<!--TEMPLATE-->
@extends('Templates.PDF.template')

<!--TITLE-->
@section('title')
<title>Laporan Karyawan Tim {{ $subteams->sub_team_1_name }} Terbaik ({{ $periods->period_name }}) | Tugas Akhir</title>
@endsection

<!--CSS-->
@section('css')
<link rel="stylesheet" href="{{public_path('Sources/CSS/Admin/pdf.css')}}">
@endsection

<!--CONTENTS-->
@section('contents')
@include('Pages.PDF.Includes.Contents.teamresult')
@endsection
