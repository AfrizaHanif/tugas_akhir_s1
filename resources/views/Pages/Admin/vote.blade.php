<!--TEMPLATE-->
@extends('Templates.Admin.template')

<!--TITLE-->
@section('title')
<title>Voting | Tugas Akhir</title>
@endsection

<!--CONTENTS-->
@section('contents')
@include('Pages.Admin.Includes.Contents.vote')
@endsection

<!--MODALS-->
@section('modals')
@include('Pages.Admin.Includes.Components.modal')
@endsection

<!--SCRIPTS-->
@push('scripts')
@endpush
