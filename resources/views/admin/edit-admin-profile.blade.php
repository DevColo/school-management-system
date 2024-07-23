@extends('layouts.backend-layout')
@section('title')
Edit Profile
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
                            <li>Account Setting</li>
                        </ul>
                    </div>
                    <div class="breadcrumbs-area">
                        <a href="{{ route('user-profile',$user->id) }}">
                            <button type="button" class="btn-fill-md text-light bg-dark-pastel-green">
                              Account Profile
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
                                <form class="new-added-form" action="{{ route('update-user-profile') }}" method="POST" id="edit-user-form">
                                    @csrf
                                    <input type="hidden" name="user_id" value="{{$user->id}}">
                                    <div class="row">
                                        <div class="col-xl-3 col-lg-6 col-12 form-group">
                                            <label>User Name <span class="required">*</span></label>
                                            <input type="text" name="user_name" value="{{ $user->user_name ?? '' }}" class="form-control" maxlength="30" required autocomplete autofocus>
                                            @error('user_name')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="col-xl-3 col-lg-6 col-12 form-group">
                                            <label>First Name <span class="required">*</span></label>
                                            <input type="text" name="first_name" value="{{ $user_detail[0]->first_name ?? '' }}" class="form-control" maxlength="30" required autocomplete autofocus>
                                            @error('first_name')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="col-xl-3 col-lg-6 col-12 form-group">
                                            <label>Other Name</label>
                                            <input type="text" name="other_name" value="{{ $user_detail[0]->other_name ?? '' }}" class="form-control" maxlength="30" autocomplete autofocus>
                                            @error('other_name')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="col-xl-3 col-lg-6 col-12 form-group">
                                            <label>Last Name <span class="required">*</span></label>
                                            <input type="text" name="last_name" value="{{ $user_detail[0]->last_name ?? '' }}" class="form-control" maxlength="30" required autocomplete autofocus>
                                            @error('last_name')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="col-xl-3 col-lg-6 col-12 form-group">
                                            <label>Gender <span class="required">*</span></label>
                                            <select class="select2" name="gender" required>
                                                @if($user_detail[0]->gender == 'm')
                                                    <option value="{{$user_detail[0]->gender}}" selected>Male</option>
                                                    <option value="f">Female</option>
                                                @elseif($user_detail[0]->gender == 'f')
                                                    <option value="{{$user_detail[0]->gender}}" selected>Female</option>
                                                    <option value="m">Male</option>
                                                @else
                                                    <option value="" selected>Select Gender</option>
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
                                        <div class="col-xl-3 col-lg-6 col-12 form-group">
                                            <label>Job Title</label>
                                            <input type="text" name="job_title" value="{{ $user_detail[0]->job_title ?? '' }}" class="form-control" maxlength="30" autocomplete autofocus>
                                            @error('job_title')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="col-xl-3 col-lg-6 col-12 form-group">
                                            <label>Email</label>
                                            <input type="email" name="email" value="{{ $user->email ?? '' }}" class="form-control" maxlength="30" autocomplete autofocus>
                                            @error('email')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="col-xl-3 col-lg-6 col-12 form-group">
                                            <label>Phone</label>
                                            <input type="text" name="phone" value="{{ $user_detail[0]->phone ?? '' }}" class="form-control" maxlength="14" autocomplete autofocus>
                                            @error('phone')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="col-xl-3 col-lg-6 col-12 form-group">
                                            <label>Address</label>
                                            <input type="text" name="address" value="{{ $user_detail[0]->address ?? '' }}" class="form-control" maxlength="60" autocomplete autofocus>
                                            @error('address')
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
    <!-- Select 2 Js -->
    <script src="{{ asset('js/select2.min.js') }}"></script>
    <!-- Date Picker Js -->
    <script src="{{ asset('js/datepicker.min.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function(){
            $("#edit-user-form").validate({
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