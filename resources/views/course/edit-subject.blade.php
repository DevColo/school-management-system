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
                                <input type="hidden" name="subject_id" value="{{ $subject->id ?? '' }}">
                                <div class="col-xl-3 col-lg-6 col-12 form-group">
                                    <label>Select Classes <span class="required">*</span></label>
                                    <select class="select2" name="class[]" id="subjectClasses" multiple required>
                                        @foreach($assignedClasses as $class)
                                            <option value="{{ $class->Classes->id }}" selected>{{ $class->Classes->class_name }}</option>  
                                        @endforeach

                                        @if(!$classList->isEmpty())
                                            @foreach($classList as $class)
                                                <option value="{{ $class->id }}" >{{ $class->class_name }}</option> 
                                            @endforeach
                                        @endif
                                    </select>
                                    @error('class')
                                      <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                      </span>
                                    @enderror
                                </div>
                                <div class="col-xl-3 col-lg-6 col-12 form-group">
                                    <label>Subject <span class="required">*</span></label>
                                    <input type="text" name="subject" value="{{ $subject->subject }}" class="form-control" maxlength="30" placeholder="Enter subject" required autocomplete autofocus>
                                    @error('subject')
                                      <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                      </span>
                                    @enderror
                                  <label for="status" style="margin-top:10px;">
                                    <input type="checkbox" id="status" class="pt-2" name="status" 
                                    @if($subject->status == 1) {{'checked'}} @endif
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
@section('js')
<script src="{{ asset('js/select2.min.js') }}"></script>
<script type="text/javascript">
$(document).ready(function () {
            var selectedOption = $('#subjectClasses').val(); // Get selected value
            var seenOptions = {};

            $('#subjectClasses option').each(function () {
                var value = $(this).val();

                // Keep the selected option even if it's a duplicate
                if (seenOptions[value] && value !== selectedOption) {
                    $(this).remove(); // Remove duplicates that are not selected
                } else {
                    seenOptions[value] = true; // Mark the value as seen
                }
            });
        });
</script>
@endsection('js')