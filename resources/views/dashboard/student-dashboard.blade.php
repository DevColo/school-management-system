@extends('layouts.backend-layout')
@section('title')
Dashboard
@endsection('title')
@section('content')

          <!-- Breadcubs Area Start Here -->
          <div class="breadcrumbs-area">
            <h3>Student Dashboard</h3>
            <ul>
              <li>
                <a href="{{ route('home') }}">Home</a>
              </li>
              <li>Dashboard</li>
            </ul>
          </div>
          <!-- Breadcubs Area End Here -->
         
          <!-- Dashboard summery Start Here -->
          <div class="row gutters-20">
            <div class="col-xl-4 col-sm-6 col-12">
              <div class="dashboard-summery-one mg-b-20">
                <div class="row align-items-center">
                  <div class="col-6">
                    <div class="item-icon bg-light-blue">
                      <i
                        class="flaticon-checklist text-blue"
                      ></i>
                    </div>
                  </div>
                  <div class="col-6">
                    <div class="item-content">
                      <div class="item-title">Class</div>
                      <div class="item-number">
                        <span >{{ $class ?? '' }}</span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-xl-4 col-sm-6 col-12">
              <div class="dashboard-summery-one mg-b-20">
                <div class="row align-items-center">
                  <div class="col-6">
                    <div class="item-icon bg-light-green">
                      <i class="flaticon-open-book text-green"></i>
                    </div>
                  </div>
                  <div class="col-6">
                    <div class="item-content">
                      <div class="item-title">Total Subjects</div>
                      <div class="item-number">
                        <span class="counter" data-num="{{$subject_count ?? ''}}">{{ $subject_count ?? '' }}</span>
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
                      <i class="flaticon-money text-red text-center"></i>
                    </div>
                  </div>
                  <div class="col-6">
                    <div class="item-content">
                      <div class="item-title">Tuition Balance</div>
                      <div class="item-number">
                        <span class="counter" data-num="{{$classes_count ?? ''}}">{{$classes_count ?? ''}}</span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- Dashboard summery End Here -->
          <!-- Dashboard Content Start Here -->
          <div class="row gutters-20">
            <div class="col-12 col-xl-6 col-4-xxxl">
              <div class="card dashboard-card-four pd-b-20">
                <div class="card-body">
                  <div class="heading-layout1">
                    <div class="item-title">
                      <h3>Event Calender</h3>
                    </div>
               
                  </div>
                  <div class="calender-wrap">
                    <div id="fc-calender" class="fc-calender"></div>
                  </div>
                </div>
              </div>
            </div>
        </div>
    <!-- Dashboard Content End Here -->
@endsection
