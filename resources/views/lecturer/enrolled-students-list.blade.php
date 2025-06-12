@extends('layouts.backend-layout')
@section('title')
Enrolled Student List
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
                            <a href="{{ route('my-lecturer-courses') }}">My Lecturer Courses</a>
                        </li>
                        <li>Enrolled Student List</li>
                    </ul>
                </div>
                </div>
                <!-- Breadcubs Area End Here -->
                <!-- Teacher Table Area Start Here -->
                <div class="card height-auto">
                    <div class="card-body">
                        <div class="heading-layout1">
                            <div style="display: flex;" class="">
                                <a class="btn btn-lg btn-primary" href="{{ URL::to('print-enrolled-students/'.$enrollmentId) }}" target="_blank" title="Print"><i
                                  class="fa fa-print" aria-hidden="true" ></i> Print List</a>
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
                                        <th>College</th>
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
               url: "{{ URL::to('/enrolled-students-list/'.$enrollmentId) }}",
              },
              columns:[
               {
                data: 'student_id',
                name: 'student_id'
               },
               {
                data: 'college',
                name: 'college'
               },
               ]
             });
        });
    </script>
@endsection('js')