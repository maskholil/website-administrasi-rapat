@extends('layouts.app')

@section('title', __('Edit Profile'))

@section('content')
<div class="header pb-8 pt-5 pt-lg-8 d-flex align-items-center" style="background-image: url(../assets/img/theme/bg1-1100x525.jpg); background-size: cover; background-position: center top;">
    <!-- Mask -->
    <span class="mask bg-gradient-default opacity-8"></span>
    <!-- Header container -->
    <div class="container-fluid d-flex align-items-center">
        <div class="row">
            <div class="col-md-12 col-lg-7">
                <h1 class="display-2 text-white">{{ __('Hello') . ' '. auth()->user()->name }}</h1>
                <p class="text-white mt-0 mb-5">
                    {{ __('Ini adalah halaman profil Anda. Anda bisa mengubah identitas nama dan email password anda') }}
                </p>

            </div>
        </div>
    </div>
</div>
<div class="container-fluid mt--7">
    <div class="text-center">
        <h3>
            {{ auth()->user()->name }}
        </h3>
        <div class="h5 font-weight-300">
            <i class="ni location_pin mr-2"></i>{{ __('UNUGHA CILACAP') }}
        </div>
    </div>
    <div class="row">
        <div class="col-xl-4 order-xl-2 mb-5 mb-xl-0">
            <div class="card card-profile shadow">
                <div class="row justify-content-center">
                    <div class="col-lg-3 order-lg-2">
                        <div class="card-profile-image">
                            <style>
                                .custom-foto {
                                    width: 180px;
                                    height: 180px;
                                    border-radius: 50%;
                                    object-fit: cover;
                                    border: 2px solid #fff;
                                    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.25);
                                }
                            </style>
                            @php
                            $user = auth()->user();
                            $userPhoto = $user->foto ? 'storage/users/' . $user->foto : 'argon/img/theme/team-4-800x800.jpg';
                            @endphp
                            <a href="#">
                                <img alt="User Image" src="{{ asset($userPhoto) }}" class="rounded-circle custom-foto">
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-header text-center border-0 pt-8 pt-md-4 pb-0 pb-md-4">
                    <div class="d-flex justify-content-between">

                    </div>
                </div>

                <div class="card-body pt-0 pt-md-4">
                    <div class="row">
                        <div class="col">
                            <div class="card-profile-stats d-flex justify-content-center mt-md-5">
                                <div>
                                    <span class="description">{{ __('Jenis role') }}</span>
                                    <span class="heading text-sm">{{ auth()->user()->role->nama_role }}</span>
                                </div>
                                <div>
                                    <span class="description">{{ __('Nomor HP') }}</span>
                                    <span class="heading text-sm">{{ auth()->user()->no_hp }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="text-center">
                        <h3>
                            {{ auth()->user()->name }}
                        </h3>
                        <div class="h5 font-weight-300">
                            <i class="ni location_pin mr-2"></i>{{ __('UNUGHA CILACAP') }}
                        </div>
                        <hr class="my-4" />

                        <a href="javascript:void(0);" onclick="handleLogout();" class="btn btn-danger">
                            <i class="fas fa-sign-out-alt"></i> {{ __('Logout') }}
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>



                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-8 order-xl-1">
            <div class="card bg-secondary shadow">
                <div class="card-header bg-white border-0">
                    <div class="row align-items-center">
                        <h3 class="mb-0 ml-2">{{ __('Edit Profile') }}</h3>
                    </div>
                </div>
                <div class="card-body">
                    <form method="post" action="{{ route('profile.update') }}" autocomplete="off">
                        @csrf
                        @method('put')

                        <h6 class="heading-small text-muted mb-4">{{ __('User information') }}</h6>

                        @if (session('status'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('status') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        @endif


                        <div class="pl-lg-4">
                            <div class="form-group{{ $errors->has('name') ? ' has-danger' : '' }}">
                                <label class="form-control-label" for="input-name">{{ __('Name') }}</label>
                                <input type="text" name="name" id="input-name" class="form-control form-control-alternative{{ $errors->has('name') ? ' is-invalid' : '' }}" placeholder="{{ __('Name') }}" value="{{ old('name', auth()->user()->name) }}" required autofocus>

                                @if ($errors->has('name'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('name') }}</strong>
                                </span>
                                @endif
                            </div>
                            <div class="form-group{{ $errors->has('email') ? ' has-danger' : '' }}">
                                <label class="form-control-label" for="input-email">{{ __('Email') }}</label>
                                <input type="email" name="email" id="input-email" class="form-control form-control-alternative{{ $errors->has('email') ? ' is-invalid' : '' }}" placeholder="{{ __('Email') }}" value="{{ old('email', auth()->user()->email) }}" required>

                                @if ($errors->has('email'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                                @endif
                            </div>

                            <div class="text-center">
                                <button type="submit" class="btn btn-success mt-4">{{ __('Save') }}</button>
                            </div>
                        </div>
                    </form>
                    <hr class="my-4" />
                    <form method="post" action="{{ route('profile.password') }}" autocomplete="off">
                        @csrf
                        @method('put')

                        <h6 class="heading-small text-muted mb-4">{{ __('Password') }}</h6>

                        @if (session('password_status'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('password_status') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        @endif

                        <div class="pl-lg-4">
                            <div class="form-group{{ $errors->has('old_password') ? ' has-danger' : '' }}">
                                <label class="form-control-label" for="input-current-password">{{ __('Current Password') }}</label>
                                <input type="password" name="old_password" id="input-current-password" class="form-control form-control-alternative{{ $errors->has('old_password') ? ' is-invalid' : '' }}" placeholder="{{ __('Current Password') }}" value="" required>

                                @if ($errors->has('old_password'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('old_password') }}</strong>
                                </span>
                                @endif
                            </div>
                            <div class="form-group{{ $errors->has('password') ? ' has-danger' : '' }}">
                                <label class="form-control-label" for="input-password">{{ __('New Password') }}</label>
                                <input type="password" name="password" id="input-password" class="form-control form-control-alternative{{ $errors->has('password') ? ' is-invalid' : '' }}" placeholder="{{ __('New Password') }}" value="" required>

                                @if ($errors->has('password'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </span>
                                @endif
                            </div>
                            <div class="form-group">
                                <label class="form-control-label" for="input-password-confirmation">{{ __('Confirm New Password') }}</label>
                                <input type="password" name="password_confirmation" id="input-password-confirmation" class="form-control form-control-alternative" placeholder="{{ __('Confirm New Password') }}" value="" required>
                            </div>

                            <div class="text-center">
                                <button type="submit" class="btn btn-success mt-4">{{ __('Change password') }}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @include('layouts.footers.auth')
</div>
<script>
    function handleLogout() {
        Swal.fire({
            title: 'Apakah anda yakin?',
            text: "Anda akan keluar dari sesi saat ini!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, logout!',
            cancelButtonText: 'Batal',
            reverseButtons: true,
            buttonsStyling: false, // Menghilangkan styling default
            customClass: {
                confirmButton: 'btn btn-primary btn-md',
                cancelButton: 'btn btn-danger btn-md'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('logout-form').submit();
            }
        });
    }
</script>

@endsection
