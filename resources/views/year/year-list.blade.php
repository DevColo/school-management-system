@extends('layouts.backend-layout')
@section('title')
Year List
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
                    <h3>Year</h3>
                    <ul>
                        <li>
                            <a href="{{ route('home') }}">Home</a>
                        </li>
                        <li>Year List</li>
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
                            <table class="table display data-table text-nowrap" id="year_table">
                                <thead>
                                    <tr>
                                        <th><label class="">ID</label></th>
                                        <th>Year</th>
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
          let table =  new DataTable('#year_table',{
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
                searchPlaceholder: "Find by year"
              },
              ajax:{
               url: "{{ URL::to('/year-list') }}",
              },
              columns:[
               {
                data: 'id',
                name: 'id'
               },
               {
                data: 'year',
                name: 'year'
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
        });
        function activateYear(year_id) {
          var token = $('meta[name="csrf-token"]').attr('content');
          swal(
              {
                title: "Activate?",
                text: "Confirm you want to activate this academic year",
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
                      url:  "/activate-year",
                      data: {_token:token,year_id: year_id},
                      success: function(data) {
                        var parsedJson = jQuery.parseJSON(data);
                        if (typeof parsedJson.msg != 'undefined' && parsedJson.msg == 'unauthorized') {
                          swal("Cancelled", "You're unauthorized to activate this academic year", "error");
                        }else if(typeof parsedJson.msg != 'undefined' && parsedJson.msg == 'error'){
                          swal("Cancelled", "The academic year was not activated, Contact the System Admin", "error");
                        }else if(typeof parsedJson.msg != 'undefined' && parsedJson.msg == 'success'){
                          swal(
                            {
                              title: "Activated",
                              text: "The academic year has been activated successfully.",
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
                  swal("Cancelled", "The academic year was not activated, Contact the System Admin", "error");
                }
              }
          );
      }

      // deactivate year
      function deactivateYear(year_id) {
        var token = $('meta[name="csrf-token"]').attr('content');
          swal(
              {
                title: "Deactivate?",
                text: "Confirm you want to deactivate this academic year",
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
                      url:  "/deactivate-year",
                      data: {_token:token,year_id: year_id},
                      success: function(data) {
                        var parsedJson = jQuery.parseJSON(data);
                        if (typeof parsedJson.msg != 'undefined' && parsedJson.msg == 'unauthorized') {
                          swal("Cancelled", "You're unauthorized to deactivate this academic year", "error");
                        }else if(typeof parsedJson.msg != 'undefined' && parsedJson.msg == 'error'){
                          swal("Cancelled", "The academic year was not deactivated, Contact the System Admin", "error");
                        }else if(typeof parsedJson.msg != 'undefined' && parsedJson.msg == 'success'){
                          swal(
                            {
                              title: "Deactivated",
                              text: "The academic year has been deactivated successfully.",
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
                  swal("Cancelled", "The academic year was not deactivated, Contact the System Admin", "error");
                }
              }
          );
      }
    </script>
@endsection('js')