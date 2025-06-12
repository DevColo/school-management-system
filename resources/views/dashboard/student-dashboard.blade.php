@extends('layouts.backend-layout')
@section('title')
Dashboard
@endsection('title')
@section('content')
<style type="text/css">
  .dashboard-summery-one .item-icon {
    height: 60px;
    width: 60px;
    line-height: revert;
  }
  .dashboard-summery-one .item-icon i:before {
    font-size: 36px;
  }
  .dashboard-summery-one .item-content .item-number {
    font-size: 17px;
  }
</style>

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
                  <div class="col-2">
                    <div class="item-icon bg-light-blue">
                      <i
                        class="flaticon-mortarboard text-blue"
                      ></i>
                    </div>
                  </div>
                  <div class="col-10">
                    <div class="item-content">
                      <div class="item-title">College</div>
                      <div class="item-number">
                        <span >{{ $studentEnrollment->College->college_name ?? '' }}</span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-xl-4 col-sm-6 col-12">
              <div class="dashboard-summery-one mg-b-20">
                <div class="row align-items-center">
                  <div class="col-2">
                    <div class="item-icon bg-light-magenta">
                      <i
                        class="flaticon-checklist text-magenta"
                      ></i>
                    </div>
                  </div>
                  <div class="col-10">
                    <div class="item-content">
                      <div class="item-title">Major</div>
                      <div class="item-number">
                        <span >{{ $studentEnrollment->Major->major ?? '' }}</span>
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
                    <div class="item-icon bg-light-green3">
                      <i class="flaticon-open-book text-green3"></i>
                    </div>
                  </div>
                  <div class="col-6">
                    <div class="item-content">
                      <div class="item-title">Total Enrolled Courses</div>
                      <div class="item-number">
                        <span>{{ $courseEnrollmentCount ?? '' }}</span>
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
                    @if($studentBalance == 0)
                      <div class="item-icon bg-light-green">
                        <i class="flaticon-money text-green text-center"></i>
                      </div>
                    @else
                      <div class="item-icon bg-light-red">
                        <i class="flaticon-money text-red text-center"></i>
                    </div>
                    @endif
                  </div>
                  <div class="col-6">
                    <div class="item-content">
                      <div class="item-title">Tuition Balance</div>
                      <div class="item-number">
                        <span>$ {{$studentBalance ?? ''}}</span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- Dashboard summery End Here -->
@endsection
