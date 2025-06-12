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
                                    <label>Period <span class="required">*</span></label>
                                    <select class="select2" name="period" id="period" required>
                                        <option value="{{old('period')}}">@if(old('period')){{old('period')}}@else {{'Select Period'}}@endif</option>
                                    </select>
                                    @error('period')
                                      <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                      </span>
                                    @enderror
                                </div>
                                <div class="col-xl-3 col-lg-6 col-12 form-group">
                                    <label>Select Subject <span class="required">*</span></label>
                                    <select class="select2" name="subject" id="subject" required>
                                        @if(old('subject'))
                                            @if(!$subjects->isEmpty())
                                                @foreach($subjects as $subject)
                                                    @if(old('subject') == $subject->id)
                                                        <option value="{{ $subject->id }}" selected>{{ $subject->subject }}</option>
                                                    @else
                                                        <option value="{{ $subject->id }}">{{ $subject->subject }}</option>
                                                    @endif   
                                                @endforeach
                                            @endif
                                        @else
                                            @if(!$subjects->isEmpty())
                                                <option value="">Select Subject</option>
                                                @foreach($subjects as $subject)
                                                    <option value="{{ $subject->id }}">{{ $subject->subject }}</option>
                                                @endforeach
                                            @else
                                                <option value="">No subject Found</option>
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
                                    <label>Class <span class="required">*</span></label>
                                    <select class="select2" name="class" id="class" required>
                                        <option value="{{old('class')}}">@if(old('class')){{old('class')}}@else {{'Select class'}}@endif</option>
                                    </select>
                                    @error('class')
                                      <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                      </span>
                                    @enderror
                                    <label for="status" style="margin-top:10px;"><input type="checkbox" id="status" class="pt-2" name="status" checked> Active</label>
                                </div>
                                <div class="col-xl-3 col-lg-6 col-12 form-group">
                                    <label>Student <span class="required">*</span></label>
                                    <select class="select2" name="student" id="student" required>
                                        <option value="{{old('student')}}">@if(old('student')){{old('student')}}@else {{'Select Student'}}@endif</option>
                                    </select>
                                    @error('student')
                                      <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                      </span>
                                    @enderror
                                </div>
                                <div class="col-xl-3 col-lg-6 col-12 form-group">
                                    <label>Grade <span class="required">*</span></label>
                                    <input type="text" name="grade" value="{{ old('grade') }}" class="form-control" maxlength="30" placeholder="Enter grade" id="grade" required autocomplete autofocus>
                                    @error('grade')
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

        $("select#semester").change(function () {
            var semester_id = $(this).val();
            $('#period').find('option').not(':first').remove();
            $.ajax({
                url: "{{ URL::to('/get-periods') }}/"+semester_id,
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

                            $("#period").append(option);
                        }
                    }
                }
            });
        });

        $("select#subject").change(function () {
            var subject_id = $(this).val();
            $('#class').find('option').not(':first').remove();
            $.ajax({
                url: "{{ URL::to('/get-classes') }}/"+subject_id,
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

                            $("#class").append(option);
                        }
                    }
                }
            });
        });

        $("select#class").change(function () {
            var class_id = $(this).val();
            var year_id = $("select#year").val();
            if (year_id == '') {
                alert("Kindly select the Academic Year");
                $(this).val('');
                return;
            }
            $('#student').find('option').not(':first').remove();
            $.ajax({
                url: "{{ URL::to('/get-students') }}/"+class_id+"/"+year_id,
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

                            $("#student").append(option);
                        }
                    }
                }
            });
        });

        // Submit the Grade form
        $("form#gradeForm").submit(function (e) {
            e.preventDefault();

            var year_id = $("select#year").val();
            var semester_id = $("select#semester").val();
            var period_id = $("select#period").val();
            var subject_id = $("select#subject").val();
            var class_id = $("select#class").val();
            var student_id = $("select#student").val();
            var grade = $("#grade").val();
            var status = $("#status").val();

            if (year_id == '') {
                alert("Kindly select the Academic Year");
                $(this).val('');
                return;
            }
            if (semester_id == '') {
                alert("Kindly select the Semester");
                $(this).val('');
                return;
            }
            if (period_id == '') {
                alert("Kindly select the Period");
                $(this).val('');
                return;
            }
            if (subject_id == '') {
                alert("Kindly select the Subject");
                $(this).val('');
                return;
            }
            if (class_id == '') {
                alert("Kindly select the Class");
                $(this).val('');
                return;
            }
            if (student_id == '') {
                alert("Kindly select the Student");
                $(this).val('');
                return;
            }
            if (grade == '') {
                alert("Kindly enter the Student's grade");
                $(this).val('');
                return;
            }
            var token = $('meta[name="csrf-token"]').attr('content');
            swal(
              {
                title: "Confirm?",
                text: "Are you to want to submit this grade ?",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: "btn-success",
                confirmButtonText: "Yes",
                cancelButtonText: "No",
                closeOnConfirm: false,
                closeOnCancel: false,
                showLoaderOnConfirm: true
              },
              function(isConfirm) {
                if (isConfirm) {
                  $.ajax({
                      type: 'POST',
                      url:  "/add-grade",
                      data: {_token:token, year: year_id, semester: semester_id, period: period_id, subject: subject_id, class: class_id, student: student_id, grade: grade, status: status},
                      success: function(data) {
                        var parsedJson = jQuery.parseJSON(data);
                        if (typeof parsedJson.msg != 'undefined' && parsedJson.msg == 'unauthorized') {
                          swal("Cancelled", "You're unauthorized to submit this grade", "error");
                        }else if(typeof parsedJson.msg != 'undefined' && parsedJson.msg == 'error'){
                          swal("Cancelled", parsedJson.errMsg, "error");
                        }else if(typeof parsedJson.msg != 'undefined' && parsedJson.msg == 'success'){
                          swal(
                            {
                              title: "Success",
                              text: "The grade has been submitted successfully.",
                              type: "success",
                            },
                            function(isOk) {
                              if (isOk) {
                                //document.location.reload();
                                $("select#student").val('');
                                $("#grade").val('');
                              }
                            }
                          );
                        }
                      }
                  });
                } else {
                  swal("Cancelled", "The grade was not submitted, Contact the System Admin", "error");
                }
              }
          );
        });
    });
</script>
@endsection('js')