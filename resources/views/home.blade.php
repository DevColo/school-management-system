@extends('layouts.backend-layout')
@section('title')
Dashboard
@endsection('title')
@section('content')

    @if (Auth()->user()->hasAnyRole(['superadmin', 'admin']))
          <!-- Breadcubs Area Start Here -->
          <div class="breadcrumbs-area">
            <h3>Admin Dashboard</h3>
            <ul>
              <li>
                <a href="index.html">Home</a>
              </li>
              <li>Admin</li>
            </ul>
          </div>
          <!-- Breadcubs Area End Here -->
         
          <!-- Dashboard summery Start Here -->
          <div class="row gutters-20">
            <div class="col-xl-4 col-sm-6 col-12">
              <div class="dashboard-summery-one mg-b-20">
                <div class="row align-items-center">
                  <div class="col-6">
                    <div class="item-icon bg-light-green">
                      <i class="flaticon-classmates text-green"></i>
                    </div>
                  </div>
                  <div class="col-6">
                    <div class="item-content">
                      <div class="item-title">Students</div>
                      <div class="item-number">
                        <span class="counter" data-num="{{$student_count ?? ''}}">{{$student_count ?? ''}}</span>
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
                    <div class="item-icon bg-light-blue">
                      <i
                        class="flaticon-multiple-users-silhouette text-blue"
                      ></i>
                    </div>
                  </div>
                  <div class="col-6">
                    <div class="item-content">
                      <div class="item-title">Librarians</div>
                      <div class="item-number">
                        <span class="counter" data-num="{{$librarian_count ?? ''}}">{{$librarian_count ?? ''}}</span>
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
                    <div class="item-icon bg-light-yellow">
                      <i class="flaticon-couple text-orange"></i>
                    </div>
                  </div>
                  <div class="col-6">
                    <div class="item-content">
                      <div class="item-title">Admins</div>
                      <div class="item-number">
                        <span class="counter" data-num="{{$total_admins ?? ''}}">{{$total_admins ?? ''}}</span>
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
           
       
            <div class="col-12 col-xl-6 col-3-xxxl">
              <div class="card dashboard-card-three pd-b-20">
                <div class="card-body">
                  <div class="heading-layout1">
                    <div class="item-title">
                      <h3> Totoal Students</h3>
                      <div class="item-number">100,000</div>
                    </div>
                   
                  </div>
                  <div class="doughnut-chart-wrap">
                    <canvas
                      id="student-doughnut-chart"
                      width="100"
                      height="300"
                    ></canvas>
                  </div>
                  <div class="student-report">
                    <div class="student-count pseudo-bg-blue">
                      <h4 class="item-title">Female Students</h4>
                      <div class="item-number">45,000</div>
                    </div>
                    <div class="student-count pseudo-bg-yellow">
                      <h4 class="item-title">Male Students</h4>
                      <div class="item-number">55,000</div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
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
         
         

            <!-- Yearly promotion statistics -->

            <div class="col-12 col-xl-12 col-6-xxxl">
                <div class="card dashboard-card-one pd-b-20">
                  <div class="card-body">
                    <div class="heading-layout1">
                      <div class="item-title">
                        <h3>Yearly Statistics</h3>
                      </div>
              
                    </div>
                    <div class="earning-report">
                      <div class="item-content">
                        <div class="single-item pseudo-bg-Aquamarine">
                          <h4>Promoted</h4>
                          <span>75,000</span>
                        </div>
                        <div class="single-item pseudo-bg-yellow">
                          <h4>Vacation School</h4>
                          <span>15,000</span>
                        </div>
                        <div class="single-item pseudo-bg-red">
                          <h4>Failed</h4>
                          <span>15,000</span>
                        </div>
                      </div>
                      <div class="dropdown">
                        <a
                          class="date-dropdown-toggle"
                          href="#"
                          role="button"
                          data-toggle="dropdown"
                          aria-expanded="false"
                          >2023/2024</a
                        >
                        <div class="dropdown-menu dropdown-menu-right">
                          <a class="dropdown-item" href="#">2021/2022</a>
                          <a class="dropdown-item" href="#">2022/2023</a>
                          <a class="dropdown-item" href="#">2023/2024</a>
                        </div>
                      </div>
                    </div>
                    <div class="earning-chart-wrap">
                      <canvas
                        id="earning-line-chart"
                        width="660"
                        height="320"
                      ></canvas>
                    </div>
                  </div>
                </div>
              </div>
          </div>
    @endif
    <!-- Dashboard Content End Here -->
@endsection