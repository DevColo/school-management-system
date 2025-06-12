@extends('layouts.backend-layout')
@section('title')
Add Course
@endsection('title')
@section('css')
    <!-- Select 2 CSS -->
    <link rel="stylesheet" href="{{ asset('css/select2.min.css') }}">
    <!-- Date Picker CSS -->
    <link rel="stylesheet" href="{{ asset('css/datepicker.min.css') }}">
@endsection('css')
@section('content')
                <!-- Breadcubs Area Start Here -->
                <div class="breadcrumbs-area">
                    <h3>Add Course</h3>
                    <ul>
                        <li>
                            <a href="{{ route('home') }}">Home</a>
                        </li>
                        <li>Add Course</li>
                    </ul>
                </div>
                <!-- Breadcubs Area End Here -->
                <!-- Add Course Area Start Here -->
                <div class="card height-auto">
                    <div class="card-body">
                        <div class="heading-layout1">
                        </div>
                        <form class="new-added-form" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-xl-3 col-lg-6 col-12 form-group">
                                    <label>Select College <span class="required">*</span></label>
                                    <select class="select2" name="college[]" multiple required>
                                        @if(old('college'))
                                            @if(!$colleges->isEmpty())
                                                @foreach($colleges as $college)
                                                    @if(old('college') == $college->id)
                                                        <option value="{{ $college->id }}" selected>{{ $college->college_name }}</option>
                                                    @else
                                                        <option value="{{ $college->id }}">{{ $college->college_name }}</option>
                                                    @endif   
                                                @endforeach
                                            @endif
                                        @else
                                            @if(!$colleges->isEmpty())
                                                <option value="">Select College</option>
                                                @foreach($colleges as $college)
                                                    <option value="{{ $college->id }}">{{ $college->college_name }}</option>
                                                @endforeach
                                            @else
                                                <option value="">No college Found</option>
                                            @endif
                                        @endif
                                    </select>
                                    @error('college')
                                      <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                      </span>
                                    @enderror
                                    <label for="status" style="margin-top:10px;"><input type="checkbox" id="status" class="pt-2" name="status" checked> Active</label>
                                </div>
                                <div class="col-xl-3 col-lg-6 col-12 form-group">
                                    <label>Course Name <span class="required">*</span></label>
                                    <input type="text" name="course_name" value="{{ old('course_name') }}" class="form-control" maxlength="30" placeholder="Enter Course Name" required autocomplete autofocus>
                                    @error('course_name')
                                      <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                      </span>
                                    @enderror
                                </div>

                                <div class="col-xl-3 col-lg-6 col-12 form-group">
                                    <label>Course Code <span class="required"></span></label>
                                    <input type="text" name="course_code" value="{{ old('course_code') }}" class="form-control" maxlength="30" placeholder="Optional" autocomplete autofocus>
                                    @error('course_code')
                                      <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                      </span>
                                    @enderror
                                </div>

                                <div class="col-xl-3 col-lg-6 col-12 form-group">
                                    <label>Course Credit/Hour <span class="required">*</span></label>
                                    <input type="number" name="credit_hour" value="{{ old('credit_hour') }}" class="form-control" maxlength="30" placeholder="Enter Course Credit/Hour" required autocomplete autofocus>
                                    @error('credit_hour')
                                      <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                      </span>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6 form-group"></div>
                                <div class="col-12 form-group mg-t-8">
                                    <button type="submit" class="btn-fill-lg btn-gradient-yellow btn-hover-bluedark">Save</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
@endsection('content')
@section('js')
<script src="{{ asset('js/select2.min.js') }}"></script>
@endsection('js')