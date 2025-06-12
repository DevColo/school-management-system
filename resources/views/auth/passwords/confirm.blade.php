<!doctype html>
<html class="no-js" lang="">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Projal University College | Login</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('img/logo.png') }}">
    <!-- Normalize CSS -->
    <link rel="stylesheet" href="{{ asset('css/normalize.css') }}">
    <!-- Main CSS -->
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <!-- Fontawesome CSS -->
    <link rel="stylesheet" href="{{ asset('css/all.min.css') }}">
    <!-- Flaticon CSS -->
    <link rel="stylesheet" href="{{ asset('fonts/flaticon.css') }}">
    <!-- Animate CSS -->
    <link rel="stylesheet" href="{{ asset('css/animate.min.css') }}">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    <!-- Modernize js -->
    <script src="{{ asset('js/modernizr-3.6.0.min.js') }}"></script>
</head>

<body>
    <!-- Preloader Start Here -->
    <div id="preloader"></div>
    <!-- Preloader End Here -->
    <!-- Login Page Start Here -->
    <div class="login-page-wrap">
        <div class="login-page-content">
            <div class="login-box">
                <div class="item-logo">
                    <img width="100px" height="100px" src="{{ asset('img/logo.png') }}" alt="logo">
                </div>
                <form method="POST" action="{{ route('password.confirm') }}">
                    @csrf
                    {{ __('Please confirm your password before continuing.') }}
                    <div class="form-group">
                        <label>{{ __('Password') }}</label>
                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                        <i class="fas fa-lock"></i>
                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group d-flex align-items-center justify-content-between">
                        <div class="form-check">
                        </div>
                        @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="forgot-btn ">Forgot Password?</a>
                        @endif
                    </div>
                    <div class="form-group">
                        <button type="submit" class="login-btn">{{ __('Confirm Password') }}</button>
                    </div>
                </form>
            </div> 
            <div class="item-logo">
                <h6 style="text-align: center;margin-bottom: 0;color: #feff65;margin-top: 12px;font-size: 12px;">Powered by</h6>
                <a href="https://sms.druptech.com" target="_blank" title="Druptech SMS"><img width="135px" height="122px" style="display: flex; margin: auto;" src="{{ asset('img/druptech-sms-white.png') }}" alt="Druptech SMS"></a>
            </div>  
        </div>
    </div>
</body>
</html>

    <!-- Login Page End Here -->
    <!-- jquery-->
    <script src="{{ asset('js/jquery-3.3.1.min.js') }}"></script>
    <!-- Plugins js -->
    <script src="{{ asset('js/plugins.js') }}"></script>
    <!-- Popper js -->
    <script src="{{ asset('js/popper.min.js') }}"></script>
    <!-- Bootstrap js -->
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <!-- Scroll Up Js -->
    <script src="{{ asset('js/jquery.scrollUp.min.js') }}"></script>
    <!-- Custom Js -->
    <script src="{{ asset('js/main.js') }}"></script>