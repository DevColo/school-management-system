@extends('layouts.backend-layout')
@section('title')
My Lecturer Courses
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
                        <li>My Lecturer Courses</li>
                    </ul>
                </div>
                </div>
                <!-- Breadcubs Area End Here -->
                <!-- Teacher Table Area Start Here -->
                <div class="card height-auto">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table display data-table text-nowrap" id="course_table">
                                <thead>
                                    <tr>
                                        <th><label class="">ID</label></th>
                                        <th>Course</th>
                                        <th>College</th>
                                        <th>Academic Year</th>
                                        <th>Semester</th>
                                        <th>Enrolled Students</th>
                                        <th>Status</th>
                                        <th>Action</th>
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
                searchPlaceholder: "Find Course"
              },
              ajax:{
               url: "{{ URL::to('/my-lecturer-courses') }}",
              },
              columns:[
               {
                data: 'id',
                name: 'id'
               },
               {
                data: 'course',
                name: 'course'
               },
               {
                data: 'college',
                name: 'college'
               },
               {
                data: 'year',
                name: 'year'
               },
               {
                data: 'semester',
                name: 'semester'
               },
               {
                data: 'enrolled_students',
                name: 'enrolled_students'
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

            // Get the current URL
            var currentURL = window.location.href;
            var segments = currentURL.split('/');
            segments = segments.filter(function(segment) {
              return segment !== '';
            });

            if(typeof segments[2] !== 'undefined'){
                var urlParams = new URLSearchParams(window.location.search);
                var desiredParam = urlParams.get("search");
                if (desiredParam !== null) {
                    table.search(desiredParam).draw();
                }
            }
        });
        function activateSubject(subject_id) {
          var token = $('meta[name="csrf-token"]').attr('content');
          swal(
              {
                title: "Activate?",
                text: "Confirm you want to activate this subject",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: "btn-success",
                confirmButtonText: "Yes, activate!",
                cancelButtonText: "No, cancel please!",
                closeOnConfirm: false,
                closeOnCancel: false,
                showLoaderOnConfirm: true
              },
              function(isConfirm) {
                if (isConfirm) {
                  $.ajax({
                      type: 'POST',
                      url:  "/activate-subject",
                      data: {_token:token,subject_id: subject_id},
                      success: function(data) {
                        var parsedJson = jQuery.parseJSON(data);
                        if (typeof parsedJson.msg != 'undefined' && parsedJson.msg == 'unauthorized') {
                          swal("Cancelled", "You're unauthorized to activate this subject", "error");
                        }else if(typeof parsedJson.msg != 'undefined' && parsedJson.msg == 'error'){
                          swal("Cancelled", "The subject was not activated, Contact the System Admin", "error");
                        }else if(typeof parsedJson.msg != 'undefined' && parsedJson.msg == 'success'){
                          swal(
                            {
                              title: "Activated",
                              text: "The subject has been activated successfully.",
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
                  swal("Cancelled", "The subject was not activated, Contact the System Admin", "error");
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