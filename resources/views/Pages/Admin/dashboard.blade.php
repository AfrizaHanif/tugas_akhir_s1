<!--TEMPLATE-->
@extends('Templates.Admin.template')

<!--TITLE-->
@section('title')
@if (Auth::user()->part == "Admin")
<title>Kepegawaian Dashboard | Tugas Akhir</title>
@elseif (Auth::user()->part == "KBU")
<title>Ketua Badan Umum Dashboard | Tugas Akhir</title>
@elseif (Auth::user()->part == "KTT")
<title>Koordinasi Tim Teknis Dashboard | Tugas Akhir</title>
@elseif (Auth::user()->part == "KBPS")
<title>Kepala BPS Jawa Timur Dashboard | Tugas Akhir</title>
@endif
@endsection

<!--CONTENTS-->
@section('contents')
<h1 class="text-center mb-4">Selamat Datang, {{ Auth::user()->officer->name }}</h1>
@endsection

<!--MODALS-->
@section('modals')
@endsection

<!--SCRIPTS-->
@push('scripts')
@endpush
