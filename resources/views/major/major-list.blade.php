@extends('layouts.backend-layout')
@section('title')
Major List
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
                <div class="breadcrumbs-area">
                    <h3>Major</h3>
                    <ul>
                        <li>
                            <a href="{{ route('home') }}">Home</a>
                        </li>
                        <li>Major List</li>
                    </ul>
                </div>
                <!-- Breadcubs Area End Here -->
                <!-- Teacher Table Area Start Here -->
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
                            <table class="table display data-table text-nowrap" id="major_table">
                                <thead>
                                    <tr>
                                        <th><label class="">ID</label></th>
                                        <th>Major</th>
                                        <th>College</th>
                                        <th>Create By</th>
                                        <th>Created On</th>
                                        <th>Updated On</th>
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
          let table =  new DataTable('#major_table',{
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
                searchPlaceholder: "Find Major"
              },
              ajax:{
               url: "{{ URL::to('/major-list') }}",
              },
              columns:[
               {
                data: 'id',
                name: 'id'
               },
               {
                data: 'major',
                name: 'major'
               },
               {
                data: 'college',
                name: 'college'
               },
               {
                data: 'username',
                name: 'username'
               },
               {
                data: 'created',
                name: 'created',
               },
               {
                data: 'updated',
                name: 'updated'
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
        function activateSemester(semester_id) {
          var token = $('meta[name="csrf-token"]').attr('content');
          swal(
              {
                title: "Activate?",
                text: "Confirm you want to activate this semester",
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
                      url:  "/activate-semester",
                      data: {_token:token,semester_id: semester_id},
                      success: function(data) {
                        var parsedJson = jQuery.parseJSON(data);
                        if (typeof parsedJson.msg != 'undefined' && parsedJson.msg == 'unauthorized') {
                          swal("Cancelled", "You're unauthorized to activate this semester", "error");
                        }else if(typeof parsedJson.msg != 'undefined' && parsedJson.msg == 'error'){
                          swal("Cancelled", "The semester was not activated, Contact the System Admin", "error");
                        }else if(typeof parsedJson.msg != 'undefined' && parsedJson.msg == 'success'){
                          swal(
                            {
                              title: "Activated",
                              text: "The semester has been activated successfully.",
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
                  swal("Cancelled", "The semester was not activated, Contact the System Admin", "error");
                }
              }
          );
      }

      // deactivate semester
      function deactivateSemester(semester_id) {
        var token = $('meta[name="csrf-token"]').attr('content');
          swal(
              {
                title: "Deactivate?",
                text: "Confirm you want to deactivate this semester",
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
                      url:  "/deactivate-semester",
                      data: {_token:token,semester_id: semester_id},
                      success: function(data) {
                        var parsedJson = jQuery.parseJSON(data);
                        if (typeof parsedJson.msg != 'undefined' && parsedJson.msg == 'unauthorized') {
                          swal("Cancelled", "You're unauthorized to deactivate this semester", "error");
                        }else if(typeof parsedJson.msg != 'undefined' && parsedJson.msg == 'error'){
                          swal("Cancelled", "The semester was not deactivated, Contact the System Admin", "error");
                        }else if(typeof parsedJson.msg != 'undefined' && parsedJson.msg == 'success'){
                          swal(
                            {
                              title: "Deactivated",
                              text: "The semester has been deactivated successfully.",
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
                  swal("Cancelled", "The semester was not deactivated, Contact the System Admin", "error");
                }
              }
          );
      }
    </script>
@endsection('js')