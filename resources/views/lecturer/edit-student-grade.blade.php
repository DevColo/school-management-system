@extends('layouts.backend-layout')
@section('title')
Edit Student Grade
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
                    <h3>Edit Student Grade</h3>
                    <ul>
                        <li>
                            <a href="{{ route('home') }}">Home</a>
                        </li>
                        <li>
                            <a href="{{ URL::to('enrolled-student-grades/'.$enrollmentId) }}">Enrolled Student Grades</a>
                        </li>
                        <li>Edit Student Grade</li>
                    </ul>
                </div>
                <!-- Breadcubs Area End Here -->
                <!-- Add Semester Area Start Here -->
                <div class="card height-auto">
                    <div class="card-body">
                        <div class="heading-layout1">
                            <div>
                              <p style="margin:0px 0 4px;">Course: <b>{{ $grade->Course->course_name ?? '' }} ({{ $grade->Course->course_code ?? '' }})</b></p>
                              <p style="margin:0px 0 4px;">Academic Year: <b>{{ $grade->AcademicYear->year ?? '' }}</b></p>
                              <p style="margin:0px 0 4px;">Semester: <b>{{ $grade->Semester->semester ?? '' }}</b></p>
                            </div>
                        </div>
                        <form id="gradeForm" method="POST">
                            @csrf

                            <input type="hidden" value="{{ $grade->AcademicYear->id ?? '' }}" name="year" required>
                            <input type="hidden" value="{{ $grade->Course->id ?? '' }}" name="course" required>
                            <input type="hidden" value="{{ $grade->Semester->id ?? '' }}" name="semester" required>
                            <input type="hidden" value="{{ $grade->id ?? '' }}" name="gradeId" required>

                            <div class="row">
                                <div class="col-xl-3 col-lg-6 col-12 form-group">
                                    <label>Select Student <span class="required">*</span></label>
                                    <select class="form-control" name="student" required>
                                        <option class="{{ $grade->StudentDetail->id ?? '' }}" selected>{{ $grade->StudentDetail->student_id ?? '' }}</option>
                                    </select>
                                    @error('student')
                                      <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                      </span>
                                    @enderror
                                </div>
                                <div class="col-xl-3 col-lg-6 col-12 form-group">
                                    <label>Point <span class="required">*</span></label>
                                    <input type="text" class="form-control" name="point" id="numericInput" value="{{ $grade->point ?? '' }}" required>
                                    @error('point')
                                      <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                      </span>
                                    @enderror
                                </div>
                                <div class="col-12 form-group mg-t-8">
                                    <button type="submit" class="btn-fill-lg btn-gradient-yellow btn-hover-bluedark">Update</button>
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