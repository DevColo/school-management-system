@extends('layouts.backend-layout')
@section('title')
Student List
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
                    <h3>Student</h3>
                    <ul>
                        <li>
                            <a href="{{ route('home') }}">Home</a>
                        </li>
                        <li>Student List</li>
                    </ul>
                </div>
                <div class="breadcrumbs-area">
                        <a href="{{ route('add-student') }}">
                            <button type="button" class="btn-fill-md radius-30 text-light shadow-dark-pastel-blue bg-dark-pastel-blue">
                              Add Student
                            </button>
                        </a>
                  </div>
                </div>
                <!-- Breadcubs Area End Here -->
                <!-- Student Table Area Start Here -->
                <div class="card height-auto">
                    <div class="card-body">
                        <div class="heading-layout1">
                            <div style="display: flex;" class="">
                              <a class="dropdown-item" href="#"  title="Excel"><i
                                class="fa fa-table text-orange-red" aria-hidden="true" ></i></a>
                                
                                <a class="dropdown-item" href="#" title="Print"><i
                                  class="fa fa-print text-green" aria-hidden="true" ></i></a>

                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table display data-table text-nowrap" id="student_table">
                                <thead>
                                    <tr>
                                        <th><label class="">Student ID</label></th>
                                        <th>First Name</th>
                                        <th>Other Name</th>
                                        <th>Last Name</th>
                                        <th>Gender</th>
                                        <th>Class</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- Student Table Area End Here -->
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
          let table =  new DataTable('#student_table',{
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
                searchPlaceholder: "Find by student ID or name"
              },
              ajax:{
               url: "{{ URL::to('/student-list') }}",
              },
              columns:[
               {
                data: 'student_id',
                name: 'student_id'
               },
               {
                data: 'first_name',
                name: 'first_name'
               },
               {
                data: 'other_name',
                name: 'other_name'
               },
               {
                data: 'last_name',
                name: 'last_name'
               },
               {
                data: 'gender',
                name: 'gender'
               },
               {
                data: 'class',
                name: 'class'
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
        function activateUser(user_id) {
          var token = $('meta[name="csrf-token"]').attr('content');
          swal(
              {
                title: "Activate?",
                text: "Confirm you want to activate this user",
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
                      url:  "/activate-user",
                      data: {_token:token,user_id: user_id},
                      success: function(data) {
                        var parsedJson = jQuery.parseJSON(data);
                        if (typeof parsedJson.msg != 'undefined' && parsedJson.msg == 'unauthorized') {
                          swal("Cancelled", "You're unauthorized to activate this user", "error");
                        }else if(typeof parsedJson.msg != 'undefined' && parsedJson.msg == 'error'){
                          swal("Cancelled", "The user was not activated, Contact the System Admin", "error");
                        }else if(typeof parsedJson.msg != 'undefined' && parsedJson.msg == 'success'){
                          swal(
                            {
                              title: "Activated",
                              text: "The user has been activated successfully.",
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
                  swal("Cancelled", "The user was not activated, Contact the System Admin", "error");
                }
              }
          );
      }

      // deactivate user
      function deactivateUser(user_id) {
        var token = $('meta[name="csrf-token"]').attr('content');
          swal(
              {
                title: "Deactivate?",
                text: "Confirm you want to deactivate this user",
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
                      url:  "/deactivate-user",
                      data: {_token:token,user_id: user_id},
                      success: function(data) {
                        var parsedJson = jQuery.parseJSON(data);
                        if (typeof parsedJson.msg != 'undefined' && parsedJson.msg == 'unauthorized') {
                          swal("Cancelled", "You're unauthorized to deactivate this user", "error");
                        }else if(typeof parsedJson.msg != 'undefined' && parsedJson.msg == 'error'){
                          swal("Cancelled", "The user was not deactivated, Contact the System Admin", "error");
                        }else if(typeof parsedJson.msg != 'undefined' && parsedJson.msg == 'success'){
                          swal(
                            {
                              title: "Deactivated",
                              text: "The user has been deactivated successfully.",
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
                  swal("Cancelled", "The user was not deactivated, Contact the System Admin", "error");
                }
              }
          );
      }
    </script>
@endsection('js')