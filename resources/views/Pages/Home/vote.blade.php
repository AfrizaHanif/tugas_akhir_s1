<!--TEMPLATE-->
@extends('Templates.Home.template')

<!--TITLE-->
@section('title')
<title>Voting | Tugas Akhir</title>
@endsection

<!--CONTENTS-->
@section('contents')
@include('Pages.Includes.Contents.vote')
@endsection

<!--MODALS-->
@section('modals')
@endsection

<!--SCRIPTS-->
@push('scripts')
@include('Pages.Home.Includes.Scripts.js')
@endpush
