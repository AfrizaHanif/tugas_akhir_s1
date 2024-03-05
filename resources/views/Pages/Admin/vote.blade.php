<!--TEMPLATE-->
@extends('Templates.Admin.template')

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
@endpush
