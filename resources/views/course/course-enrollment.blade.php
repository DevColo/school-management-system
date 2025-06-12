@extends('layouts.backend-layout')
@section('title')
Course Enrollment Form
@endsection('title')
@section('css')
    <!-- Select 2 CSS -->
    <link rel="stylesheet" href="{{ asset('css/select2.min.css') }}">
    <!-- Date Picker CSS -->
    <link rel="stylesheet" href="{{ asset('css/datepicker.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/sweetalert.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection('css')
@section('content')
                <!-- Breadcubs Area Start Here -->
                <div class="breadcrumbs-area">
                    <h3>Course Enrollment Form</h3>
                    <ul>
                        <li>
                            <a href="{{ route('home') }}">Home</a>
                        </li>
                        <li>Course Enrollment Form</li>
                    </ul>
                </div>
                <!-- Breadcubs Area End Here -->
                <!-- Add Semester Area Start Here -->
                <div class="card height-auto">
                    <div class="card-body">
                        <div class="heading-layout1">
                        </div>
                        <form id="gradeForm" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-xl-3 col-lg-6 col-12 form-group">
                                    <label>Academic Year <span class="required">*</span></label>
                                    <select class="select2" name="year" id="year" required>
                                        @if(old('year'))
                                            @if(!$years->isEmpty())
                                                @foreach($years as $year)
                                                    @if(old('year') == $year->id)
                                                        <option value="{{ $year->id }}" selected>{{ $year->year }}</option>
                                                    @else
                                                        <option value="{{ $year->id }}">{{ $year->year }}</option>
                                                    @endif   
                                                @endforeach
                                            @endif
                                        @else
                                            @if(!$years->isEmpty())
                                                <option value="">Select Academic Year</option>
                                                @foreach($years as $year)
                                                    <option value="{{ $year->id }}">{{ $year->year }}</option>
                                                @endforeach
                                            @else
                                                <option value="">No Year Found</option>
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
                                    <label>Semester <span class="required">*</span></label>
                                    <select class="select2" name="semester" id="semester" required>
                                        <option value="{{old('semester')}}">@if(old('semester')){{old('semester')}}@else {{'Select Semester'}}@endif</option>
                                    </select>
                                    @error('semester')
                                      <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                      </span>
                                    @enderror
                                </div>
                                <div class="col-xl-3 col-lg-6 col-12 form-group">
                                    <label>Select Course <span class="required">*</span></label>
                                    <select class="select2" name="course" id="course" required>
                                        @if(old('course'))
                                            @if(!$courses->isEmpty())
                                                @foreach($courses as $course)
                                                    @if(old('course') == $course->id)
                                                        <option value="{{ $course->id }}" selected>{{ $course->course_name }}</option>
                                                    @else
                                                        <option value="{{ $course->id }}">{{ $course->course_name }} ({{ $course->course_code }})</option>
                                                    @endif   
                                                @endforeach
                                            @endif
                                        @else
                                            @if(!$courses->isEmpty())
                                                <option value="">Select course</option>
                                                @foreach($courses as $course)
                                                    <option value="{{ $course->id }}">{{ $course->course_name }} ({{ $course->course_code }})</option>
                                                @endforeach
                                            @else
                                                <option value="">No course Found</option>
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
                                    <label>Student <span class="required">*</span></label>
                                    <select class="select2" name="student" id="student" required>
                                        @if(old('student'))
                                            @if(!$students->isEmpty())
                                                @foreach($students as $student)
                                                    @if(old('student') == $student->User->id)
                                                        <option value="{{ $student->User->id }}">{{ $student->first_name }} {{ $student->other_name }} {{ $student->last_name }} ({{ $student->student_id }})</option>
                                                    @else
                                                        <option value="{{ $student->User->id }}">{{ $student->first_name }} {{ $student->other_name }} {{ $student->last_name }} ({{ $student->student_id }})</option>
                                                    @endif   
                                                @endforeach
                                            @endif
                                        @else
                                            @if(!$students->isEmpty())
                                                <option value="">Select student</option>
                                                @foreach($students as $student)
                                                    <option value="{{ $student->User->id }}">{{ $student->first_name }} {{ $student->other_name }} {{ $student->last_name }} ({{ $student->student_id }})</option>
                                                @endforeach
                                            @else
                                                <option value="">No student Found</option>
                                            @endif
                                        @endif
                                    </select>
                                    @error('student')
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
<script src="{{ asset('js/sweetalerts.js') }}"></script>
<script src="{{ asset('js/custom.js') }}"></script>
<script type="text/javascript">
    $(document).ready(function () {       
        $("select#year").change(function () {
            var year_id = $(this).val();
            $('#semester').find('option').not(':first').remove();
             $.ajax({
                url: "{{ URL::to('/get-semesters') }}/"+year_id,
                type: 'GET',
                dataType: 'json',
                success:function (response) {
                    var len = 0;
                    if (response.data != null) {
                        len = response.data.length;
                    }

                    if (len>0) {
                        for (var i = 0; i<len; i++) {
                            var id = response.data[i].value;
                            var name = response.data[i].name;

                            var option = "<option value='"+id+"'>"+name+"</option>"; 

                            $("#semester").append(option);
                        }
                    }
                }
            });
        });
    });
</script>
</script>
@endsection('js')