@extends('layouts.app', ['class' => 'bg-default'])
@section('title', 'Login')

@section('content')
@include('layouts.headers.guest')

<div class="container mt--8 pb-5">
    <div class="row justify-content-center">
        <div class="col-lg-5 col-md-7">
            <div class="card bg-secondary shadow border-0">
                <!-- <div class="card-header bg-transparent pb-5">
                <div class="text-muted text-center mt-2 mb-3"><small>{{ __('Sign in with') }}</small></div>
                    <div class="btn-wrapper text-center">
                        <a href="#" class="btn btn-neutral btn-icon">
                            <span class="btn-inner--icon"><img src="{{ asset('argon') }}/img/icons/common/github.svg"></span>
                            <span class="btn-inner--text">{{ __('Github') }}</span>
                        </a>
                        <a href="#" class="btn btn-neutral btn-icon">
                            <span class="btn-inner--icon"><img src="{{ asset('argon') }}/img/icons/common/google.svg"></span>
                            <span class="btn-inner--text">{{ __('Google') }}</span>
                        </a>
                    </div>
                </div> -->

                <div class="card-body px-lg-5 py-lg-5">
                    <div class="text-center">
                        <h2 class="text-center">Selamat datang di Aplikasi SIAR</h2>
                        <p class="text-muted"> Silakan login atau buat akun baru untuk mengakses fitur lengkap.</p>
                    </div>
                    <!-- <div class="text-center text-muted mb-4">
                        <small>
                            Login: <strong>admin</strong> or <strong>admin@argon.com</strong> <br> Password: <strong>123456789</strong>
                        </small>
                    </div> -->
                    <form role="form" method="POST" action="{{ route('login') }}" id="loginForm">
                        @csrf
                        <div class="form-group{{ $errors->has('username') ? ' has-danger' : '' }} mb-3">
                            <div class="input-group input-group-alternative">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="ni ni-single-02"></i></span>
                                </div>
                                <input class="form-control{{ $errors->has('username') ? ' is-invalid' : '' }}" placeholder="{{ __('Username or Email') }}" type="text" name="username" value="{{ old('username') }}" required autofocus>
                            </div>
                            @if ($errors->has('username'))
                            <span class="invalid-feedback" style="display: block;" role="alert">
                                <strong>{{ $errors->first('username') }}</strong>
                            </span>
                            @endif
                        </div>
                        <div class="form-group{{ $errors->has('password') ? ' has-danger' : '' }}">
                            <div class="input-group input-group-alternative">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="ni ni-lock-circle-open"></i></span>
                                </div>
                                <input class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" placeholder="{{ __('Password') }}" type="password"  required>
                            </div>
                            @if ($errors->has('password'))
                            <span class="invalid-feedback" style="display: block;" role="alert">
                                <strong>{{ $errors->first('password') }}</strong>
                            </span>
                            @endif
                        </div>

                        <div class="text-center">
                            <button type="submit" class="btn btn-primary my-4" id="submitBtn">{{ __('Sign in') }}</button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
    var isSubmitting = false;

    document.getElementById('loginForm').addEventListener('submit', function(event) {
        if (isSubmitting) {
            event.preventDefault();
            return;
        }

        isSubmitting = true;
        var submitBtn = document.getElementById('submitBtn');
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';
        submitBtn.disabled = true;
    });
</script>
@endsection
