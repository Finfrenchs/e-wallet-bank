<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>EasyPay | Log in</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{ asset('AdminLTE/plugins/fontawesome-free/css/all.min.css') }}">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="{{ asset('AdminLTE/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset('AdminLTE/dist/css/adminlte.min.css') }}">
  <style>
    .login-page {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    .login-box {
      width: 400px;
    }
    .login-card-body {
      border-radius: 10px;
      box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
      padding: 30px;
    }
    .login-logo {
      margin-bottom: 25px;
    }
    .login-logo a {
      color: #ffffff;
      font-size: 35px;
      font-weight: 700;
      text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
    }
    .login-logo .brand-icon {
      font-size: 45px;
      margin-bottom: 10px;
      display: block;
    }
    .login-box-msg {
      margin-bottom: 20px;
      font-size: 16px;
      color: #6c757d;
      font-weight: 500;
    }
    .btn-login {
      height: 45px;
      font-size: 16px;
      font-weight: 600;
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      border: none;
      transition: all 0.3s ease;
    }
    .btn-login:hover {
      transform: translateY(-2px);
      box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
    }
    .input-group-text {
      background-color: #f8f9fa;
      border-right: none;
    }
    .form-control {
      border-left: none;
      height: 45px;
    }
    .form-control:focus {
      border-color: #667eea;
      box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    }
    .alert {
      border-radius: 8px;
      margin-bottom: 20px;
    }
  </style>
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="login-logo">
    <i class="fas fa-wallet brand-icon"></i>
    <a href="#"><b>EasyPay</b> Admin</a>
  </div>
  <!-- /.login-logo -->
  <div class="card elevation-4">
    <div class="card-body login-card-body">
      <p class="login-box-msg">
        <i class="fas fa-user-shield mr-1"></i>
        Sign in to your admin account
      </p>

      {{-- alert here --}}
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <i class="icon fas fa-ban mr-1"></i>
                {{ session('error') }}
            </div>
        @endif

      <form action="{{ route('admin.auth.login') }}" method="post">
        @csrf
        <div class="input-group mb-3">
          <div class="input-group-prepend">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
          <input name="email" type="email" class="form-control" placeholder="Email Address" value="{{ old('email') }}" required autofocus>
        </div>
        <div class="input-group mb-4">
          <div class="input-group-prepend">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
          <input name="password" type="password" class="form-control" placeholder="Password" value="{{ old('password') }}" required>
        </div>
        <div class="row">
          <div class="col-12">
            <button type="submit" class="btn btn-primary btn-block btn-login">
              <i class="fas fa-sign-in-alt mr-2"></i>
              Sign In
            </button>
          </div>
        </div>
      </form>

      <div class="mt-4 text-center">
        <p class="mb-0 text-muted">
          <small>
            <i class="fas fa-shield-alt mr-1"></i>
            Secure Admin Access
          </small>
        </p>
      </div>
    </div>
    <!-- /.login-card-body -->
  </div>
</div>
<!-- /.login-box -->

<!-- jQuery -->
<script src="{{ asset('AdminLTE/plugins/jquery/jquery.min.js') }}"></script>
<!-- Bootstrap 4 -->
<script src="{{ asset('AdminLTE/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('AdminLTE/dist/js/adminlte.min.js') }}"></script>
</body>
</html>
