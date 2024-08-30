<main class="form-signin w-100 m-auto">
    <div class="row g-1 align-items-center"> <!-- G- For Padding -->
        <div class="col-md-7 justify-content-center">
            <img src="{{ asset('Images/Vector/Login.png') }}" class="img-fluid">
        </div>
        <div class="col-md-5">
            <form action="{{ route('login.auth') }}" method="post">
                @csrf
                <img class="mb-4" src="https://upload.wikimedia.org/wikipedia/commons/2/28/Lambang_Badan_Pusat_Statistik_%28BPS%29_Indonesia.svg" alt="" width="72" height="57">
                <h1 class="h3 mb-3 fw-normal">Masuk / Login</h1>
                @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-x-circle-fill"></i> <strong>ERROR </strong>
                    Terdapat kesalahan saat melakukan input data:
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
                    <input type="text" class="form-control" id="username" name="username" placeholder="name@example.com" required>
                    <label for="username">User Name</label>
                </div>
                <div class="form-floating">
                    <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                    <label for="password">Password</label>
                </div>
                <div class="form-check text-start my-3">
                    <input class="form-check-input" type="checkbox" value="remember_me" id="remember_me" name="remember_me">
                    <label class="form-check-label" for="remember_me">
                        Ingat Saya (Tetap Login)
                    </label>
                </div>
                <button class="btn btn-primary w-100 py-2" type="submit">Sign in atau Tekan Enter <i class="bi bi-arrow-return-left"></i></button>
                <p class="mt-5 mb-3 text-body-secondary">&copy; Badan Pusat Statistik Jawa Timur</p>
            </form>
        </div>
    </div>
</main>
