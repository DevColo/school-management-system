<!doctype html>
<html class="no-js" lang="">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Skoology | Login</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('img/skoology-black.png') }}">
    <!-- Normalize CSS -->
    <link rel="stylesheet" href="{{ asset('css/normalize.css') }}">
    <!-- Main CSS -->
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <!-- Fontawesome CSS -->
    <link rel="stylesheet" href="{{ asset('css/all.min.css') }}">
    <!-- Flaticon CSS -->
    <link rel="stylesheet" href="fonts/flaticon.css') }}">
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
                    <img width="250px" height="150px" src="{{ asset('img/skoology-black.png') }}" alt="logo">
                </div>
                <form method="POST" action="{{ route('login') }}" class="login-form">
                    @csrf

                    @error('user_name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    <div class="form-group">
                        <label>Username/ID</label>
                        <input type="text" placeholder="Enter usrename or ID" class="form-control @error('user_name') is-invalid @enderror" name="user_name" value="{{ old('user_name') }}" required autofocus>
                        <i class="far fa-envelope"></i>
                    </div>
                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" placeholder="Enter password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                        <i class="fas fa-lock"></i>
                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group d-flex align-items-center justify-content-between">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="remember-me" name="remember" {{ old('remember') ? 'checked' : '' }}>
                            <label for="remember-me" class="form-check-label">Remember Me</label>
                        </div>
                        <a href="#"  data-toggle="modal" data-target="#forget-password" class="forgot-btn ">Forgot Password?</a>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="login-btn">Login</button>
                    </div>
                </form>
               
          
            </div>   
            
         

         

          
        </div>
            
        
        <!-- Forget Password Popup -->
      
    </div>


</body>

<div   class="modal sign-up-modal fade" id="forget-password" tabindex="-1" role="dialog"
aria-hidden="true">
<div  class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
        <div class="modal-body">
            <div class="close-btn">
                <button type="button" class="close" data-dismiss="modal"
                    aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="item-logo">
                <img width="250px" height="150px" style="display: flex; margin: auto;" src="{{ asset('img/schooLogo.jpeg') }}" alt="logo">
            </div>
            <form  class="login-form">
                    
                    <div class="form-group col-12">
                        <input type="email" required placeholder="Enter E-mail"
                            class="form-control">
                    </div>
             
                <div class="form-group col-12">
                    <button type="submit" class="forget-password-btn">Send Email</button>
                </div>
            </form>
        </div>
    </div>
</div>
</div>

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