@extends('layouts.backend-layout')
@section('title')
Add Subject
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
                    <h3>Add Subject</h3>
                    <ul>
                        <li>
                            <a href="{{ route('home') }}">Home</a>
                        </li>
                        <li>Add Subject</li>
                    </ul>
                </div>
                <!-- Breadcubs Area End Here -->
                <!-- Add Subject Area Start Here -->
                <div class="card height-auto">
                    <div class="card-body">
                        <div class="heading-layout1">
                        </div>
                        <form class="new-added-form" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-xl-3 col-lg-6 col-12 form-group">
                                    <label>Select Classes <span class="required">*</span></label>
                                    <select class="select2" name="class[]" multiple required>
                                        @if(old('class'))
                                            @if(!$classes->isEmpty())
                                                @foreach($classes as $class)
                                                    @if(old('class') == $class->id)
                                                        <option value="{{ $class->id }}" selected>{{ $class->class_name }}</option>
                                                    @else
                                                        <option value="{{ $class->id }}">{{ $class->class_name }}</option>
                                                    @endif   
                                                @endforeach
                                            @endif
                                        @else
                                            @if(!$classes->isEmpty())
                                                @foreach($classes as $class)
                                                    <option value="{{ $class->id }}">{{ $class->class_name }}</option>
                                                @endforeach
                                            @else
                                                <option value="">No class Found</option>
                                            @endif
                                        @endif
                                    </select>
                                    @error('class')
                                      <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                      </span>
                                    @enderror
                                </div>
                                <div class="col-xl-3 col-lg-6 col-12 form-group">
                                    <label>subject <span class="required">*</span></label>
                                    <input type="text" name="subject" value="{{ old('subject') }}" class="form-control" maxlength="30" placeholder="Enter subject" required autocomplete autofocus>
                                    @error('subject')
                                      <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                      </span>
                                    @enderror
                                  <label for="status" style="margin-top:10px;"><input type="checkbox" id="status" class="pt-2" name="status" checked> Active</label>
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