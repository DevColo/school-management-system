@extends('layouts.backend-layout')
@section('title')
Add Period
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
                    <h3>Add Period</h3>
                    <ul>
                        <li>
                            <a href="{{ route('home') }}">Home</a>
                        </li>
                        <li>Add Period</li>
                    </ul>
                </div>
                <!-- Breadcubs Area End Here -->
                <!-- Add Period Area Start Here -->
                <div class="card height-auto">
                    <div class="card-body">
                        <div class="heading-layout1">
                        </div>
                        <form class="new-added-form" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-xl-3 col-lg-6 col-12 form-group">
                                    <label>Semester <span class="required">*</span></label>
                                    <select class="select2" name="semester" required>
                                        @if(old('semester'))
                                            @if(!$semesters->isEmpty())
                                                @foreach($semesters as $semester)
                                                    @if(old('semester') == $semester->id)
                                                        <option value="{{ $semester->id }}" selected>{{ $semester->AcademicYear->year }} {{ $semester->semester }}</option>
                                                    @else
                                                        <option value="{{ $semester->id }}">{{ $semester->AcademicYear->year }} {{ $semester->semester }}</option>
                                                    @endif   
                                                @endforeach
                                            @endif
                                        @else
                                            @if(!$semesters->isEmpty())
                                                <option value="">Select Academic semester</option>
                                                @foreach($semesters as $semester)
                                                    <option value="{{ $semester->id }}">{{ $semester->AcademicYear->year }} Sem.{{ $semester->semester }}</option>
                                                @endforeach
                                            @else
                                                <option value="">No Semester Found</option>
                                            @endif
                                        @endif
                                    </select>
                                    @error('year')
                                      <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                      </span>
                                    @enderror
                                </div>
                                <div class="col-xl-3 col-lg-6 col-12 form-group">
                                    <label>Period <span class="required">*</span></label>
                                    <input type="text" name="period" value="{{ old('period') }}" class="form-control" maxlength="30" placeholder="Enter Period" required autocomplete autofocus>
                                    @error('period')
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