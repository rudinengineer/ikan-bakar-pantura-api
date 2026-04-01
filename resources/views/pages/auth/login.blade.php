<!DOCTYPE html>
<html lang="en" dir="ltr" data-bs-theme="light" data-color-theme="Blue_Theme" data-layout="vertical">

<!-- Required meta tags -->
<meta charset="UTF-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta name="csrf-token" content="{{ csrf_token() }}">

<!-- Favicon icon-->
<link rel="shortcut icon" type="image/png" href="{{ url('assets/images/logos/favicon.png') }}" />

<!-- Core Css -->
<link rel="stylesheet" href="{{ url('assets/css/styles.css') }}" />

{{-- Alert --}}
<link rel="stylesheet" href="{{ url('assets/css/iziToast.min.css') }}">

<title>Login</title>

</head>

<body>
  <!-- Preloader -->
  <div class="preloader">
    <img src="{{ url('assets/images/logos/favicon.png') }}" alt="loader" class="lds-ripple img-fluid" />
  </div>
  <div id="main-wrapper" class="auth-customizer-none">
    <div class="position-relative overflow-hidden radial-gradient min-vh-100 w-100 d-flex align-items-center justify-content-center">
      <div class="d-flex align-items-center justify-content-center w-100">
        <div class="row justify-content-center w-100">
          <div class="col-md-8 col-lg-6 col-xxl-3 auth-card">
            <div class="card mb-0">
              <div class="card-body">
                <h3 style="font-family: 'Plus Jakarta Sans', sans-serif;" class="mb-4 text-center">Dashboard Login</h3>
                {{-- <a href="{{ route('login') }}" class="text-nowrap logo-img text-center d-block mb-5 w-100">
                  <img src="{{ url('assets/images/logos/dark-logo.svg') }}" class="dark-logo" alt="Logo-Dark" />
                  <img src="{{ url('assets/images/logos/light-logo.svg') }}" class="light-logo" alt="Logo-light" />
                </a> --}}
                <form id="form" method="POST">
                  <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" name="username" class="form-control" id="username" aria-describedby="emailHelp" autocomplete="off">
                    <small id="username-error" class="text-danger"></small>
                  </div>
                  <div class="mb-4">
                    <label for="password" class="form-label">Password</label>
                    <input name="password" type="password" class="form-control" id="password" autocomplete="off">
                    <small id="password-error" class="text-danger"></small>
                  </div>
                  <div class="d-flex align-items-center justify-content-between mb-4">
                    <div class="form-check">
                      <input name="remember_me" class="form-check-input primary" type="checkbox" value="" id="flexCheckChecked" checked>
                      <label class="form-check-label text-dark" for="flexCheckChecked">
                        Remeber this Device
                      </label>
                    </div>
                  </div>
                  <button id="btn-submit" class="btn btn-primary w-100 py-8 mb-4 rounded-2">
                    @include('components.loading.button')
                    <span>Sign In</span>
                  </button>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="dark-transparent sidebartoggler"></div>
  <!-- Import Js Files -->
  <script src="{{ url('assets/js/vendor.min.js') }}"></script>
  <script src="{{ url('assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ url('assets/libs/simplebar/dist/simplebar.min.js') }}"></script>
  <script src="{{ url('assets/js/theme/app.init.js') }}"></script>
  <script src="{{ url('assets/js/theme/theme.js') }}"></script>
  <script src="{{ url('assets/js/theme/app.min.js') }}"></script>

  <!-- solar icons -->
  <script src="https://cdn.jsdelivr.net/npm/iconify-icon@1.0.8/dist/iconify-icon.min.js"></script>

  {{-- JQuery --}}
    <script src="{{ url('assets/js/jquery.min.js') }}"></script>

  {{-- Alert --}}
    <script src="{{ url('assets/js/iziToast.min.js') }}"></script>

  @include('pages.auth.login-js')
</body>

</html>