@extends('layouts.backend-layout')
@section('title')
Add New Class
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
                    <h3>Add New Class</h3>
                    <ul>
                        <li>
                            <a href="{{ route('home') }}">Home</a>
                        </li>
                        <li>Add New Class</li>
                    </ul>
                </div>
                <!-- Breadcubs Area End Here -->
                <!-- Add New Class Area Start Here -->
                <div class="card height-auto">
                    <div class="card-body">
                        <div class="heading-layout1">
                        </div>
                        <form class="new-added-form" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-xl-4 col-lg-4 col-4 form-group">
                                    <input type="hidden" name="class_id" value="{{ $class->id ?? '' }}">
                                    <label>Class Name <span class="required">*</span></label>
                                    <input type="text" name="class_name" value="{{ $class->class_name ?? '' }}" class="form-control" maxlength="30" placeholder="Enter Class Name" required autocomplete autofocus>
                                    @error('class_name')
                                      <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                      </span>
                                    @enderror
                                   <label for="status" style="margin-top:10px;">
                                    
                                    <input type="checkbox" id="status" class="pt-2" name="status" 
                                    @if($class->status == 1) {{'checked'}} @endif
                                    > Active
                                   </label>
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