<!--TEMPLATE-->
@extends('Templates.Auth.template')

<!--TITLE-->
@section('title')
<title>Masuk | Tugas Akhir</title>
@endsection

<!--CONTENTS-->
@section('contents')
<section class="vh-100">
    <div class="container py-5 h-100">
        <div class="row d-flex align-items-center justify-content-center h-100">
            <div class="col-md-9 col-lg-6 col-xl-5">
                <img src="https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-login-form/draw2.svg"
                class="img-fluid" alt="Phone image">
            </div>
            <div class="col-md-8 col-lg-6 col-xl-4 offset-xl-1">
                <main class="form-signin w-100 m-auto">
                    <form action="{{ route('login.auth') }}" method="post">
                        @csrf
                        <h1 class="h3 mb-3 fw-normal">Selamat Datang</h1>

                        @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="bi bi-x-circle-fill"></i> <strong>ERROR: </strong>Terdapat kesalahan saat melakukan input data:
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            Silahkan periksa kembali inputan anda.
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        @endif

                        <div class="form-floating">
                            <input type="email" class="form-control" id="email" name="email" placeholder="name@example.com">
                            <label for="email">Alamat E-Mail</label>
                        </div>
                        <div class="form-floating">
                            <input type="password" class="form-control" id="password" name="password"  placeholder="Password">
                            <label for="floatingPassword">Password</label>
                        </div>
                        <div class="form-check text-start my-3">
                            <input class="form-check-input" type="checkbox" value="remember-me" id="remember_me" name="remember_me">
                            <label class="form-check-label" for="flexCheckDefault">
                                Ingat Saya (Tetap Login)
                            </label>
                        </div>
                        <button class="btn btn-primary w-100 py-2" type="submit">Masuk</button>
                        <p class="mt-5 mb-3 text-body-secondary">&copy; Halaman dan Proses Login sedang dalam konstruksi</p>
                    </form>
                </main>
            </div>
        </div>
    </div>
</section>
@endsection

<!--MODALS-->
@section('modals')
@include('Pages.Auth.Includes.Components.modal')
@endsection

<!--SCRIPTS-->
@push('scripts')
@include('Pages.Auth.Includes.Scripts.js')
@endpush
