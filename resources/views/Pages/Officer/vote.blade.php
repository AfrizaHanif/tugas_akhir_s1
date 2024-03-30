<!--TEMPLATE-->
@extends('Templates.Officer.template')

<!--TITLE-->
@section('title')
<title>Pemilihan Pegawai | Tugas Akhir</title>
@endsection

<!--CONTENTS-->
@section('contents')
@include('Pages.Officer.Includes.Contents.vote')
@endsection

<!--MODALS-->
@section('modals')
@include('Pages.Officer.Includes.Components.modal')
@endsection

<!--SCRIPTS-->
@push('scripts')
@endpush
