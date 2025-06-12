@extends('layouts.backend-layout')
@section('title')
Enrolled Student Grades
@endsection('title')
@section('css')
    <!-- Select 2 CSS -->
    <link rel="stylesheet" href="{{ asset('css/select2.min.css') }}">
    <!-- Date Picker CSS -->
    <link rel="stylesheet" href="{{ asset('css/datepicker.min.css') }}">
    <!-- Data Table CSS -->
    <link rel="stylesheet" href="{{ asset('css/dataTables.css') }}">
<link rel="stylesheet" href="{{ asset('css/sweetalert.css') }}">
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection('css')
@section('content')
        <!-- Page Area Start Here -->
        <div class="dashboard-page-one">
            <!-- Sidebar Area End Here -->
            <div class="list-content">
                <!-- Breadcubs Area Start Here -->
                <div style="display: flex; justify-content: space-between">
                <div class="breadcrumbs-area">
                    <h3>Course</h3>
                    <ul>
                        <li>
                            <a href="{{ route('home') }}">Home</a>
                        </li>
                        <li>
                            <a href="{{ route('grade-courses') }}">Assigned Courses</a>
                        </li>
                        <li>Enrolled Student Grades</li>
                    </ul>
                </div>
                </div>
                <!-- Breadcubs Area End Here -->
                <!-- Teacher Table Area Start Here -->
                <div class="card height-auto">
                    <div class="card-body">
                        <div class="heading-layout1">
                            <div style="display: flex;" class="">
                                <a class="btn btn-lg btn-primary" href="{{ URL::to('add-student-grade/'.$enrollmentId) }}" target="_blank" title="Print"><i
                                  class="fa fa-plus" aria-hidden="true" ></i> Add Student Grade</a>
                            </div>
                            <div>
                              <p style="margin:0px 0 4px;">Course: <b>{{ $course ?? '' }}</b></p>
                              <p style="margin:0px 0 4px;">Academic Year: <b>{{ $year ?? '' }}</b></p>
                              <p style="margin:0px 0 4px;">Semester: <b>{{ $semester ?? '' }}</b></p>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table display data-table text-nowrap" id="course_table">
                                <thead>
                                    <tr>
                                        <th><label class="">Student ID</label></th>
                                        <th>Point</th>
                                        <th>Grade</th>
                                        <th>Observation</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- Teacher Table Area End Here -->
            </div>
        </div>
        <!-- Page Area End Here -->
@endsection('content')
@section('js')
  <script src="{{ asset('js/sweetalerts.js') }}"></script>
    <script src="{{ asset('js/custom.js') }}"></script>
    <!-- Select 2 Js -->
    <script src="{{ asset('js/select2.min.js') }}"></script>
    <!-- Data Table Js -->
    <script src="{{ asset('js/dataTables.js') }}"></script>

    <script type="text/javascript">
        $(document).ready(function(){
          let table =  new DataTable('#course_table',{
              paging: true,
              searching: true,
              info: true,
              lengthChange: true,
              lengthMenu: [10, 50, 75, 100],
              columnDefs: [{
                targets: [0, -1],
                orderable: true
              }],
              processing: true,
              serverSide: true,
              language: {
                searchPlaceholder: "Find Student by ID"
              },
              ajax:{
               url: "{{ URL::to('/student-grades/'.$enrollmentId) }}",
              },
              columns:[
               {
                data: 'student_id',
                name: 'student_id'
               },
               {
                data: 'point',
                name: 'point'
               },
               {
                data: 'grade',
                name: 'grade'
               },
               {
                data: 'observation',
                name: 'observation'
               },
               {
                data: 'status',
                name: 'status',
               },
               {
                data: 'action',
                name: 'action',
                orderable: false
               }
               ]
            });
        });

        function approveGrade(grade_id) {
          var token = $('meta[name="csrf-token"]').attr('content');
          swal(
              {
                title: "Approve?",
                text: "Confirm you want to approve this grade",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: "btn-success",
                confirmButtonText: "Yes, approve!",
                cancelButtonText: "No, cancel please!",
                closeOnConfirm: false,
                closeOnCancel: false,
                showLoaderOnConfirm: true
              },
              function(isConfirm) {
                if (isConfirm) {
                  $.ajax({
                      type: 'POST',
                      url:  "/approve-grade",
                      data: {_token:token,grade_id: grade_id},
                      success: function(data) {
                        var parsedJson = jQuery.parseJSON(data);
                        if (typeof parsedJson.msg != 'undefined' && parsedJson.msg == 'unauthorized') {
                          swal("Cancelled", "You're unauthorized to approve this grade", "error");
                        }else if(typeof parsedJson.msg != 'undefined' && parsedJson.msg == 'error'){
                          swal("Cancelled", "The subject was not approved, Contact the System Admin", "error");
                        }else if(typeof parsedJson.msg != 'undefined' && parsedJson.msg == 'success'){
                          swal(
                            {
                              title: "Approved",
                              text: "The Grade has been approved successfully.",
                              type: "success",
                            },
                            function(isOk) {
                              if (isOk) {
                                document.location.reload();
                              }
                            }
                          );
                        }
                      }
                  });
                } else {
                  swal("Cancelled", "The grade was not approved.", "error");
                }
              }
          );
      }

      // deactivate subject
      function deactivateSubject(subject_id) {
        var token = $('meta[name="csrf-token"]').attr('content');
          swal(
              {
                title: "Deactivate?",
                text: "Confirm you want to deactivate this subject",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: "btn-danger",
                confirmButtonText: "Yes, deactivate!",
                cancelButtonText: "No, cancel please!",
                closeOnConfirm: false,
                closeOnCancel: false,
                showLoaderOnConfirm: true
              },
              function(isConfirm) {
                if (isConfirm) {
                  $.ajax({
                      type: 'POST',
                      url:  "/deactivate-subject",
                      data: {_token:token,subject_id: subject_id},
                      success: function(data) {
                        var parsedJson = jQuery.parseJSON(data);
                        if (typeof parsedJson.msg != 'undefined' && parsedJson.msg == 'unauthorized') {
                          swal("Cancelled", "You're unauthorized to deactivate this subject", "error");
                        }else if(typeof parsedJson.msg != 'undefined' && parsedJson.msg == 'error'){
                          swal("Cancelled", "The subject was not deactivated, Contact the System Admin", "error");
                        }else if(typeof parsedJson.msg != 'undefined' && parsedJson.msg == 'success'){
                          swal(
                            {
                              title: "Deactivated",
                              text: "The subject has been deactivated successfully.",
                              type: "success",
                            },
                            function(isOk) {
                              if (isOk) {
                                document.location.reload();
                              }
                            }
                          );
                        }
                      }
                  });
                } else {
                  swal("Cancelled", "The subject was not deactivated, Contact the System Admin", "error");
                }
              }
          );
      }
    </script>
@endsection('js')