@extends('layouts.backend-layout')
@section('title')
Assign Course
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
                    <h3>Assign Course</h3>
                    <ul>
                        <li>
                            <a href="{{ route('home') }}">Home</a>
                        </li>
                        <li>Assign Course</li>
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
                                    <select class="select2" name="college" id="college" required>
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
                                                <option value="">Select college</option>
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
                                </div>
                                <div class="col-xl-3 col-lg-6 col-12 form-group">
                                    <label>Select Course <span class="required">*</span></label>
                                    <select class="select2" name="course" id="course" required>
                                        <option value="">Select course</option>
                                    </select>
                                    @error('course')
                                      <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                      </span>
                                    @enderror
                                </div>
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
                                    <label>Lecturer <span class="required">*</span></label>
                                    <select class="select2" name="lecturer" id="lecturer" required>
                                        @if(old('lecturer'))
                                            @if(!$lecturers->isEmpty())
                                                @foreach($lecturers as $lecturer)
                                                    @if(old('lecturer') == $lecturer->id)
                                                        <option value="{{ $lecturer->id }}" selected>{{ $lecturer->first_name }}</option>
                                                    @else
                                                        <option value="{{ $lecturer->id }}">{{ $lecturer->first_name }} {{ $lecturer->other_name }} {{ $lecturer->last_name }} ({{ $lecturer->email }})</option>
                                                    @endif   
                                                @endforeach
                                            @endif
                                        @else
                                            @if(!$lecturers->isEmpty())
                                                <option value="">Select Academic lecturer</option>
                                                @foreach($lecturers as $lecturer)
                                                    <option value="{{ $lecturer->id }}">{{ $lecturer->first_name }} {{ $lecturer->other_name }} {{ $lecturer->last_name }} ({{ $lecturer->email }})</option>
                                                @endforeach
                                            @else
                                                <option value="">No lecturer Found</option>
                                            @endif
                                        @endif
                                    </select>
                                    @error('lecturer')
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

        $("select#college").change(function () {
            var college_id = $(this).val();
            $('#course').find('option').not(':first').remove();
            $.ajax({
                url: "{{ URL::to('/get-courses') }}/"+college_id,
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

                            $("#course").append(option);
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