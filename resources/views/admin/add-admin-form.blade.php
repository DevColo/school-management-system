@extends('layouts.backend-layout')
@section('title')
Add New Admin
@endsection('title')
@section('css')
    <!-- Select 2 CSS -->
    <link rel="stylesheet" href="{{ asset('css/select2.min.css') }}">
    <!-- Date Picker CSS -->
    <link rel="stylesheet" href="{{ asset('css/datepicker.min.css') }}">
@endsection('css')
@section('content')
                <div style="display: flex; justify-content: space-between">
                    <div class="breadcrumbs-area">
                        <h3>Admin Profile</h3>
                        <ul>
                            <li>
                              <a href="{{ route('home') }}">Home</a>
                            </li>
                            <li>Add New Admin</li>
                        </ul>
                    </div>
                    <div class="breadcrumbs-area">
                        <a href="{{ route('admin-list') }}">
                            <button type="button" class="btn-fill-md radius-30 text-light shadow-dark-pastel-blue bg-dark-pastel-blue">
                              View Admins
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
                                <form class="new-added-form" action="{{ route('add-admin') }}" method="POST" id="add-user-form" enctype="multipart/form-data">
                                    @csrf
                                    <div class="row">
                                        <div class="col-xl-6 col-lg-6 col-12 form-group">
                                            <label>First Name <span class="required">*</span></label>
                                            <input type="text" name="first_name" value="{{ old('first_name') }}" class="form-control" maxlength="30" required autocomplete autofocus>
                                            @error('first_name')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="col-xl-6 col-lg-6 col-12 form-group">
                                            <label>Other Name</label>
                                            <input type="text" name="other_name" value="{{ old('other_name') }}" class="form-control" maxlength="30" autocomplete autofocus>
                                            @error('other_name')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="col-xl-6 col-lg-6 col-12 form-group">
                                            <label>Last Name <span class="required">*</span></label>
                                            <input type="text" name="last_name" value="{{ old('last_name') }}" class="form-control" maxlength="30" required autocomplete autofocus>
                                            @error('last_name')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="col-xl-6 col-lg-6 col-12 form-group">
                                            <label>Gender <span class="required">*</span></label>
                                            <select class="select2" name="gender" required>
                                                @if(old('gender') && old('gender') == 'm')
                                                    <option value="{{old('gender')}}" selected>Male</option>
                                                    <option value="f">Female</option>
                                                @elseif(old('gender') && old('gender') == 'f')
                                                    <option value="{{old('gender')}}" selected>Female</option>
                                                    <option value="m">Male</option>
                                                @else
                                                    <option value="">Select Gender</option>
                                                    <option value="m">Male</option>
                                                    <option value="f">Female</option>
                                                @endif
                                            </select>
                                            @error('gender')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="col-xl-6 col-lg-6 col-12 form-group">
                                            <label>Job Title</label>
                                            <input type="text" name="job_title" value="{{ old('job_title') }}" class="form-control" maxlength="30" autocomplete autofocus>
                                            @error('job_title')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="col-xl-6 col-lg-6 col-12 form-group">
                                            <label>Email</label>
                                            <input type="email" name="email" value="{{ old('email') }}" class="form-control" maxlength="30" autocomplete autofocus>
                                            @error('email')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="col-xl-6 col-lg-6 col-12 form-group">
                                            <label>Phone</label>
                                            <input type="text" name="phone" id="phone" value="{{ old('phone') }}" class="form-control" maxlength="14" placeholder="000-000-000-000" pattern="[0-9]{4}[0-9]{3}[0-9]{3}" autofocus>
                                            @error('phone')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="col-xl-6 col-lg-6 col-12 form-group">
                                            <label>Address</label>
                                            <input type="text" name="address" value="{{ old('address') }}" class="form-control" maxlength="60" autocomplete autofocus>
                                            @error('address')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="col-xl-6 col-lg-6 col-12 form-group">
                                            <label>Profile Photo</label>
                                            <input type="file" name="profile_image" class="form-control" accept="image/*" autofocus>
                                            @error('profile_image')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="col-xl-6 col-lg-6 col-12 form-group">
                                            <label>User Name <span class="required">*</span></label>
                                            <input type="text" name="user_name" value="{{ old('user_name') }}" class="form-control" maxlength="30" required autocomplete autofocus>
                                            @error('user_name')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="col-xl-6 col-lg-6 col-12 form-group">
                                            <label>Password <span class="required">*</span></label>
                                            <input type="password" id="password" name="password" class="form-control" maxlength="30" required autofocus>
                                            @error('password')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>   
                                        <div class="col-12 form-group mg-t-8">
                                            <button type="submit" class="btn-fill-lg btn-gradient-blue btn-hover-bluedark">Submit</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
    </div>
@endsection('content')
@section('js')
    <!-- Select 2 Js -->
    <script src="{{ asset('js/select2.min.js') }}"></script>
    <!-- Date Picker Js -->
    <script src="{{ asset('js/datepicker.min.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function(){
            $("#add-user-form").validate({
                errorPlacement: function(error, element){
                    if (element.hasClass('select2')) {
                        error.insertAfter(element.next('.select2-container'));
                    } else {
                        error.insertAfter(element);
                    }
                }
            });
        });
    </script>
@endsection('js')