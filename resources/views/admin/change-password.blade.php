@extends('layouts.backend-layout')
@section('title')
Change Password
@endsection('title')
@section('css')
@endsection('css')
@section('content')
                <div style="display: flex; justify-content: space-between">
                    <div class="breadcrumbs-area">
                        <h3>Admin Profile</h3>
                        <ul>
                            <li>
                              <a href="{{ route('home') }}">Home</a>
                            </li>
                            <li>Change Password</li>
                        </ul>
                    </div>
                    <div class="breadcrumbs-area">
                        <a href="{{ route('user-profile',$user->id) }}">
                            <button type="button" class="btn-fill-md text-light bg-dark-pastel-green">
                              Account Setting
                            </button>
                        </a>
                    </div>
                </div>
                <!-- Breadcubs Area End Here -->
                <!-- Account Settings Area Start Here -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <form class="new-added-form" action="{{ route('change-user-password') }}" method="POST" id="change-password-form">
                                    @csrf
                                    <input type="hidden" name="user_id" value="{{$user->id}}">
                                    <div class="row">
                                        <div class="col-xl-3 col-lg-6 col-12 form-group">
                                            <label>Password <span class="required">*</span></label>
                                            <input type="password" id="password" name="password" class="form-control" maxlength="30" required autofocus>
                                            @error('password')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="col-xl-3 col-lg-6 col-12 form-group">
                                            <label>Confirm Password <span class="required">*</span></label>
                                            <input type="password" id="password_confirmation" name="password_confirmation"  class="form-control" maxlength="30" required autocomplete="new-password">
                                            @error('password_confirmation')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="col-12 form-group mg-t-8">
                                            <button type="submit" class="btn-fill-lg btn-gradient-yellow btn-hover-bluedark">Save</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
    </div>
@endsection('content')
@section('js')
    <script type="text/javascript">
        $(document).ready(function() {
          $("#change-password-form").validate({
            rules: {
              password: {
                required: true,
                minlength: 6 
              },
              password_confirmation: {
                required: true,
                equalTo: "#password"
              }
            },
            messages: {
              password: {
                required: "Please enter a password",
                minlength: "Password must be at least 6 characters long"
              },
              password_confirmation: {
                required: "Please confirm your password",
                equalTo: "Passwords do not match"
              }
            }
          });
        });
    </script>
@endsection('js')