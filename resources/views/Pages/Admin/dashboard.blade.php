<!--TEMPLATE-->
@extends('Templates.Admin.template')

<!--TITLE-->
@section('title')
@if (Auth::user()->part == "Admin")
<title>Kepegawaian Dashboard | Tugas Akhir</title>
@elseif (Auth::user()->part == "KBU")
<title>Ketua Bagian Umum Dashboard | Tugas Akhir</title>
@elseif (Auth::user()->part == "KTT")
<title>Koordinasi Tim Teknis Dashboard | Tugas Akhir</title>
@elseif (Auth::user()->part == "KBPS")
<title>Kepala BPS Jawa Timur Dashboard | Tugas Akhir</title>
@endif
@endsection

<!--CONTENTS-->
@section('contents')
@include('Pages.Admin.Includes.Contents.dashboard')
@endsection

<!--MODALS-->
@section('modals')
@include('Pages.Admin.Includes.Components.modal')
@endsection

<!--SCRIPTS-->
@push('scripts')
@endpush
