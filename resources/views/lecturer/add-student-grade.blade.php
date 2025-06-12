@extends('layouts.backend-layout')
@section('title')
Student Grade Form
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
                    <h3>Student Grade Form</h3>
                    <ul>
                        <li>
                            <a href="{{ route('home') }}">Home</a>
                        </li>
                        <li>Student Grade Form</li>
                    </ul>
                </div>
                <!-- Breadcubs Area End Here -->
                <!-- Add Semester Area Start Here -->
                <div class="card height-auto">
                    <div class="card-body">
                        <div class="heading-layout1">
                            <div>
                              <p style="margin:0px 0 4px;">Course: <b>{{ $course->course_name ?? '' }} ({{ $course->course_code ?? '' }})</b></p>
                              <p style="margin:0px 0 4px;">Academic Year: <b>{{ $year->year ?? '' }}</b></p>
                              <p style="margin:0px 0 4px;">Semester: <b>{{ $semester->semester ?? '' }}</b></p>
                            </div>
                        </div>
                        <form id="gradeForm" method="POST">
                            @csrf

                            <input type="hidden" value="{{ $year->id ?? '' }}" name="year" required>
                            <input type="hidden" value="{{ $course->id ?? '' }}" name="course" required>
                            <input type="hidden" value="{{ $semester->id ?? '' }}" name="semester" required>
                            <input type="hidden" value="{{ $enrollmentId ?? '' }}" name="enrollmentId" required>

                            <div class="row">
                                <div class="col-xl-3 col-lg-6 col-12 form-group">
                                    <label>Select Student <span class="required">*</span></label>
                                    <select class="select2" name="student" required>
                                        @if(old('student'))
                                            @if(!is_null($records))
                                                @foreach($records as $record)
                                                    @if(old('student') == $record['id'])
                                                        <option value="{{ $record['id'] }}" selected>{{ $record['student_id'] }}</option>
                                                    @else
                                                        <option value="{{ $record['id'] }}">{{ $record['student_id'] }}</option>
                                                    @endif   
                                                @endforeach
                                            @endif
                                        @else
                                            @if(!is_null($records))
                                                <option value="">Select Student</option>
                                                @foreach($records as $record)
                                                    <option value="{{ $record['id'] }}">{{ $record['student_id'] }}</option>
                                                @endforeach
                                            @else
                                                <option value="">No Student Found</option>
                                            @endif
                                        @endif
                                    </select>
                                    @error('student')
                                      <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                      </span>
                                    @enderror
                                </div>
                                <div class="col-xl-3 col-lg-6 col-12 form-group">
                                    <label>Point <span class="required">*</span></label>
                                    <input type="text" class="form-control" name="point" id="numericInput" value="{{ old('point') }}" required>
                                    @error('point')
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
@endsection('content')
@section('js')
<script src="{{ asset('js/select2.min.js') }}"></script>
<script src="{{ asset('js/sweetalerts.js') }}"></script>
<script src="{{ asset('js/custom.js') }}"></script>
<script type="text/javascript">
    $(document).ready(function () {       
        $('#numericInput').on('input', function () {
          let value = $(this).val();

          // Allow only digits and a single dot
          if (!/^\d*\.?\d*$/.test(value)) {
            $(this).val(value.slice(0, -1));
            return;
          }

          const num = parseFloat(value);

          // If value is not a number, allow empty input
          if (value === '' || isNaN(num)) return;

          const parts = value.split('.');
          const whole = parseInt(parts[0], 10);
          const decimal = parts[1] || '';

          if (
            whole > 100 ||                                 
            (whole === 100 && decimal.length > 0) ||      
            decimal.length > 2                           
          ) {
            $(this).val(value.slice(0, -1));
          }
        });
    });
</script>
@endsection('js')