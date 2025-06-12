@extends('layouts.backend-layout')
@section('title')
Admin Dashboard
@endsection('title')
@section('content')

    @if (Auth()->user()->hasAnyRole(['lecturer']))
          <!-- Breadcubs Area Start Here -->
          <div class="breadcrumbs-area">
            <h3>Admin Dashboard</h3>
            <ul>
              <li>
                <a href="{{ route('home') }}">Home</a>
              </li>
              <li>Lecturer Dashboard</li>
            </ul>
          </div>
          <!-- Breadcubs Area End Here -->
         
          <!-- Dashboard summery Start Here -->
          <div class="row gutters-20">
            <div class="col-xl-4 col-12 col-18">
              <div class="dashboard-summery-one mg-b-20">
                <div class="row align-items-center">
                  <div class="col-6">
                    <div class="item-icon bg-light-green">
                      <i class="flaticon-checklist text-green text-center"></i>
                    </div>
                  </div>
                  <div class="col-6">
                    <div class="item-content">
                      <div class="item-title">Total Courses</div>
                      <div class="item-number">
                        <span class="counter" data-num="{{$total_assigned_courses ?? ''}}">{{ $total_assigned_courses ?? ''}}</span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-xl-4 col-12 col-18">
              <div class="dashboard-summery-one mg-b-20">
                <div class="row align-items-center">
                  <div class="col-6">
                    <div class="item-icon bg-light-blue">
                      <i class="flaticon-checklist text-blue text-center"></i>
                    </div>
                  </div>
                  <div class="col-6">
                    <div class="item-content">
                      <div class="item-title">Active Courses</div>
                      <div class="item-number">
                        <span class="counter" data-num="{{$active_assigned_courses ?? ''}}">{{ $active_assigned_courses ?? ''}}</span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-xl-4 col-12 col-18">
              <div class="dashboard-summery-one mg-b-20">
                <div class="row align-items-center">
                  <div class="col-6">
                    <div class="item-icon bg-light-red">
                      <i class="flaticon-checklist text-red text-center"></i>
                    </div>
                  </div>
                  <div class="col-6">
                    <div class="item-content">
                      <div class="item-title">Completed Course</div>
                      <div class="item-number">
                        <span class="counter" data-num="{{$completed_assigned_courses ?? ''}}">{{ $completed_assigned_courses ?? ''}}</span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          
          </div>
          <!-- Dashboard summery End Here -->
    @endif
    <!-- Dashboard Content End Here -->
@endsection
