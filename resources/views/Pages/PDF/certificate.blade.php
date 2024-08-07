<!--TEMPLATE-->
@extends('Templates.PDF.template')

<!--TITLE-->
@section('title')
<title>Sertifikat (Beta) | Tugas Akhir</title>
@endsection

<!--CSS-->
@section('css')
<style>
    * {
    margin:0;
    padding:0
}
body {
    margin: 0;
    height: 8.27in;
    width: 11.69in;
    background-image: url("/public/Images/Default/Sertifikat.jpg");
    background-repeat: no-repeat;
}
</style>
@endsection

<!--CONTENTS-->
@section('contents')
@include('Pages.PDF.Includes.Contents.certificate')
@endsection
