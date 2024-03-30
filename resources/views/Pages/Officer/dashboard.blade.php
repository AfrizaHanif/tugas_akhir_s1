<!--TEMPLATE-->
@extends('Templates.Officer.template')

<!--TITLE-->
@section('title')
<title>Pegawai | Tugas Akhir</title>
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
