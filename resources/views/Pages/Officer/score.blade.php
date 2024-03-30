<!--TEMPLATE-->
@extends('Templates.Officer.template')

<!--TITLE-->
@section('title')
<title>Top 3 Pegawai | Tugas Akhir</title>
@endsection

<!--CONTENTS-->
@section('contents')
@include('Pages.Officer.Includes.Contents.score')
@endsection

<!--MODALS-->
@section('modals')
@include('Pages.Officer.Includes.Components.modal')
@endsection

<!--SCRIPTS-->
@push('scripts')
@endpush
