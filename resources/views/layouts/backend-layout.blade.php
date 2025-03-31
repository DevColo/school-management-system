<!DOCTYPE html>
<html class="no-js" lang="">
  <head>
    <meta charset="utf-8" />
    <meta http-equiv="x-ua-compatible" content="ie=edge" />
    <title>@yield('title')</title>
    <meta name="description" content="" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <!-- Normalize CSS -->
    <link rel="stylesheet" href="{{ asset('css/normalize.css') }}" />
    <!-- School Logo -->
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('img/skoology-black.png') }}">
    <!-- Main CSS -->
    <link rel="stylesheet" href="{{ asset('css/main.css') }}" />
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}" />
    <!-- Fontawesome CSS -->
    <link rel="stylesheet" href="{{ asset('css/all.min.css') }}" />
    <!-- Flaticon CSS -->
    <link rel="stylesheet" href="{{ asset('fonts/flaticon.css') }}" />
    <!-- Full Calender CSS -->
    <link rel="stylesheet" href="{{ asset('css/fullcalendar.min.css') }}" />
    <!-- Animate CSS -->
    <link rel="stylesheet" href="{{ asset('css/animate.min.css') }}" />
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}" />
    @yield('css')
    <!-- Modernize js -->
    <script src="{{ asset('js/modernizr-3.6.0.min.js') }}"></script>
  </head>

  <body>
    <!-- Preloader Start Here -->
    <div id="preloader"></div>
    <!-- Preloader End Here -->
    <div id="wrapper" class="wrapper bg-ash">
      <!-- Header Menu Area Start Here -->
      <div class="navbar navbar-expand-md header-menu-one bg-light">
        <div class="nav-bar-header-one">
          <div class="header-logo">
            <a href="{{ route('home') }}">
              <img
                width="160px"
                height="100px"
                src="{{ asset('img/skoology-white.png') }}"
                alt="logo"
              />
            </a>
          </div>
          <div class="toggle-button sidebar-toggle">
            <button type="button" class="item-link">
              <span class="btn-icon-wrap">
                <span></span>
                <span></span>
                <span></span>
              </span>
            </button>
          </div>
        </div>
        <div class="d-md-none mobile-nav-bar">
          <button
            class="navbar-toggler pulse-animation"
            type="button"
            data-toggle="collapse"
            data-target="#mobile-navbar"
            aria-expanded="false"
          >
            <i class="far fa-arrow-alt-circle-down"></i>
          </button>
          <button type="button" class="navbar-toggler sidebar-toggle-mobile">
            <i class="fas fa-bars"></i>
          </button>
        </div>
        <div
          class="header-main-menu collapse navbar-collapse"
          id="mobile-navbar"
        >
          <ul class="navbar-nav"></ul>
          <ul class="navbar-nav">
            <li class="navbar-item dropdown header-admin">
              <a
                class="navbar-nav-link dropdown-toggle"
                href="#"
                role="button"
                data-toggle="dropdown"
                aria-expanded="false"
              >
                <div class="admin-title">
                  @if (auth()->user()->hasAnyRole(['superadmin', 'admin']))
                    <h5 class="item-title">{{ Auth::user()->user_name ?? '' }}</h5>
                    @hasrole('superadmin')
                      <span>Super-Admin</span>
                    @endrole
                    @hasrole('admin')
                      <span>Admin</span>
                    @endrole
                  @endif
                  @hasrole('librarian')
                    <h5 class="item-title">{{ Auth::user()->user_name ?? '' }}</h5>
                    <span>Librarian</span>
                  @endrole
                  @hasrole('student')
                    <h5 class="item-title">{{ Auth::user()->user_name ?? '' }}</h5>
                    <span>Student</span>
                  @endrole
                </div>
                <div class="admin-img">
                  @if (auth()->user()->hasAnyRole(['superadmin', 'admin']))
                    <img src="{{ asset('admin_img')}}/{{ Auth::user()->profile_image ?? 'avatar.jpg' }}" width="200" height="150" alt="Admin" />
                  @elseif (auth()->user()->hasAnyRole(['student']))
                     <img src="{{ asset('student_img')}}/{{ Auth::user()->profile_image ?? 'avatar.jpg' }}" width="200" height="150" alt="Admin" />
                  @endif
                  <!-- <img src="{{ asset('img/figure/admin.jpg') }}" alt="Admin" /> -->
                </div>
              </a>
              <div class="dropdown-menu dropdown-menu-right">
                <div class="item-header">
                  <h6 class="item-title">{{ Auth::user()->user_name }}</h6>
                </div>
                <div class="item-content">
                  <ul class="settings-list">
                    @if (auth()->user()->hasAnyRole(['superadmin', 'admin']))
                      <li>
                        <a href="{{ route('user-profile',Auth::user()->id) }}"><i class="flaticon-user"></i>My Profile</a>
                      </li>
                      <li>
                        <a href="{{ route('account-setting',Auth::user()->id) }}"
                          ><i class="flaticon-gear-loading"></i>Account
                          Settings</a
                        >
                      </li>
                     @elseif (auth()->user()->hasAnyRole(['student']))
                       <li>
                        <a href="{{ route('student-profile',Auth::user()->id) }}"><i class="flaticon-user"></i>My Profile</a>
                      </li>
                    @endif
                    <li>
                      <a href="{{ route('change-password', Auth::user()->id) }}"
                        ><i class="fa fa-key"></i>Change
                        Password</a
                      >
                    </li>
                    <li>
                      <a href="{{ route('logout') }}"
                        onclick="event.preventDefault();
                        document.getElementById('logout-form').submit();">
                        <i class="flaticon-turn-off"></i>Log Out</a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </li>
                  </ul>
                </div>
              </div>
            </li>
          </ul>
        </div>
      </div>
      <!-- Header Menu Area End Here -->
      <!-- Page Area Start Here -->
      <div class="dashboard-page-one">



        <!-- Sidebar Area Start Here -->
        <div
          class="sidebar-main sidebar-menu-one sidebar-expand-md sidebar-color"
        >
          <div class="mobile-sidebar-header d-md-none">
            <div class="header-logo">
            <a href="{{ route('home') }}">
              <img
                width="160px"
                height="100px"
                src="{{ asset('img/skoology-white.png') }}"
                alt="logo"
              />
            </a>
          </div>
          </div>
          <div class="sidebar-menu-content">
            <ul class="nav nav-sidebar-menu sidebar-toggle-view">
              <li class="nav-item">
                <a href="{{ route('home') }}" class="nav-link"
                  ><i class="flaticon-dashboard"></i><span>Dashboard</span></a
                >
              </li>
              <!-- Superadmin & Admin -->
              @if (Auth()->user()->hasAnyRole(['superadmin', 'admin']))
                <li class="nav-item sidebar-nav-item">
                  <a href="#" class="nav-link"
                    ><i class="flaticon-couple"></i><span>Administrators</span></a
                  >
                  <ul class="nav sub-group-menu">
                    <li class="nav-item">
                      <a href="{{ route('add-admin-form') }}" class="nav-link"
                        >Add Administrator</a
                      >
                    </li>
                    <li class="nav-item">
                      <a href="{{ route('admin-list') }}" class="nav-link"
                        >Administrators List</a
                      >
                    </li>
                  </ul>
                </li>
                <li class="nav-item sidebar-nav-item">
                  <a href="#" class="nav-link"
                    ><i class="flaticon-classmates"></i><span>Students</span></a
                  >
                  <ul class="nav sub-group-menu">
                    <li class="nav-item">
                      <a href="{{ route('add-student') }}" class="nav-link"
                        >Add Student</a
                      >
                    </li>
                    <li class="nav-item">
                      <a href="{{ route('student-list') }}" class="nav-link"
                        >Student List</a
                      >
                    </li>
                  </ul>
                </li>
                <!-- <li class="nav-item sidebar-nav-item">
                  <a href="#" class="nav-link"
                    ><i class="flaticon-multiple-users-silhouette"></i
                    ><span>Librarian</span></a
                  >
                  <ul class="nav sub-group-menu">
                    <li class="nav-item">
                      <a href="all-librarian.html" class="nav-link"
                        >All Librarian</a
                      >
                    </li>
                    <li class="nav-item">
                      <a href="add-librarian.html" class="nav-link"
                        >Add Librarian</a
                      >
                    </li>
                  </ul>
                </li> -->

                <!-- <li class="nav-item sidebar-nav-item">
                  <a href="#" class="nav-link"
                    ><i class="flaticon-books"></i><span>Library</span></a
                  >
                  <ul class="nav sub-group-menu">
                    <li class="nav-item">
                      <a href="all-book.html" class="nav-link"
                        ><i class="fas fa-angle-right"></i>All Books</a
                      >
                    </li>
                    <li class="nav-item">
                      <a href="add-book.html" class="nav-link"
                        ><i class="fas fa-angle-right"></i>Add New Book</a
                      >
                    </li>
                  </ul>
                </li> -->
                <!-- Academic Year -->

                <li class="nav-item sidebar-nav-item">
                  <a href="#" class="nav-link"
                    ><i class="fa fa-globe"></i><span>Academic Years</span></a
                  >
                  <ul class="nav sub-group-menu">
                     <li class="nav-item">
                      <a href="{{ route('add-year') }}" class="nav-link"
                        >Add Academic Year</a
                      >
                    </li>
                    <li class="nav-item">
                      <a href="{{ route('year-list') }}" class="nav-link"
                        >View Academic Years</a
                      >
                    </li>
                  </ul>
                </li>

                <!-- Semester -->
                <li class="nav-item sidebar-nav-item">
                  <a href="#" class="nav-link"
                    ><i class="fa fa-star-half"></i><span>Semesters</span></a
                  >
                  <ul class="nav sub-group-menu">
                    <li class="nav-item">
                      <a href="{{ route('add-semester') }}" class="nav-link"
                        >Add New Semester</a
                      >
                    </li>
                    <li class="nav-item">
                      <a href="{{ route('semester-list') }}" class="nav-link"
                        >View Semesters</a
                      >
                    </li>
                  </ul>
                </li>

                <!-- Period -->
                <li class="nav-item sidebar-nav-item">
                  <a href="#" class="nav-link"
                    ><i class="flaticon-open-book"></i><span>Periods</span></a
                  >
                  <ul class="nav sub-group-menu">
                    <li class="nav-item">
                      <a href="{{ route('add-period') }}" class="nav-link"
                        >Add New Period</a
                      >
                    </li>
                    <li class="nav-item">
                      <a href="{{ route('period-list') }}" class="nav-link"
                        >View Periods</a
                      >
                    </li>
                  </ul>
                </li>

                <!-- Class -->
                <li class="nav-item sidebar-nav-item">
                  <a href="#" class="nav-link"
                    ><i class="fa fa-users"></i><span>Classes</span></a
                  >
                  <ul class="nav sub-group-menu">
                    <li class="nav-item">
                      <a href="{{ route('add-class') }}" class="nav-link"
                        >Add New Class</a
                      >
                    </li>
                    <li class="nav-item">
                      <a href="{{ route('class-list') }}" class="nav-link"
                        >View Classes</a
                      >
                    </li>
                  </ul>
                </li>

                <!-- Subjects -->
                <li class="nav-item sidebar-nav-item">
                  <a href="#" class="nav-link"
                    ><i class="fa fa-book"></i><span>Subject</span></a
                  >
                  <ul class="nav sub-group-menu">
                    <li class="nav-item">
                      <a href="{{ route('add-subject') }}" class="nav-link"
                        >Add New Subject</a
                      >
                    </li>
                    <li class="nav-item">
                      <a href="{{ route('subject-list') }}" class="nav-link"
                        >View Subjects</a
                      >
                    </li>
                  </ul>
                </li>

                <!-- Grades -->
                <li class="nav-item sidebar-nav-item">
                  <a href="#" class="nav-link"
                    ><i class="fa fa-table"></i><span>Grades</span></a
                  >
                  <ul class="nav sub-group-menu">
                    <li class="nav-item">
                      <a href="add-subject.html" class="nav-link"
                        >Add New Grades</a
                      >
                    </li>
                    <li class="nav-item">
                      <a href="all-subject.html" class="nav-link"
                        >All Grades</a
                      >
                    </li>
                  </ul>
                </li>

                <!-- Activities Management -->

                <li class="nav-item sidebar-nav-item">
                  <a href="#" class="nav-link"
                    ><i class="fa fa-bookmark"></i><span>Activities Management</span></a
                  >
                  <ul class="nav sub-group-menu">
                    <li class="nav-item">
                      <a href="add-exam-schedule.html" class="nav-link"
                        >Add Exam Schedule</a
                      >
                    </li>
                    <li class="nav-item">
                      <a href="exam-schedule.html" class="nav-link"
                        >Exam Schedules</a
                      >
                    </li>
                    <li class="nav-item">
                      <a href="add-calendar.html" class="nav-link"
                        >Add Calendar of
                        Events</a
                      >
                    </li>
                    <li class="nav-item">
                      <a href="calendar.html" class="nav-link"
                        >Calendar of Events</a
                      >
                    </li>
                  </ul>
                </li>

                <!-- General Settings -->
                <li class="nav-item sidebar-nav-item">
                  <a href="#" class="nav-link"
                    ><i class="fa fa-cog"></i
                    ><span>Settings</span></a
                  >
                  <ul class="nav sub-group-menu">
                    <li class="nav-item">
                      <a href="{{ route('manage-roles') }}" class="nav-link"
                        >Roles</a
                      >
                    </li>
                    <li class="nav-item">
                      <a href="exam-schedule.html" class="nav-link"
                        >Permission</a
                      >
                    </li>
                    <li class="nav-item">
                      <a href="exam-schedule.html" class="nav-link"
                        >Add Exam Schedule</a
                      >
                    </li>
                    <li class="nav-item">
                      <a href="exam-grade.html" class="nav-link"
                        >Exam Schedules</a
                      >
                    </li>
                    <li class="nav-item">
                      <a href="exam-schedule.html" class="nav-link"
                        >Add Calendar of
                        Events</a
                      >
                    </li>
                    <li class="nav-item">
                      <a href="exam-grade.html" class="nav-link"
                        >Calendar of Events</a
                      >
                    </li>
                     </ul>
                  @endif
                  <!-- Superadmin & Admin -->
                  @if (Auth()->user()->hasAnyRole(['student']))

                <li class="nav-item sidebar-nav-item">
                  <a href="#" class="nav-link"
                    ><i class="flaticon-money"></i><span>Tuition</span></a
                  >
                  <ul class="nav sub-group-menu">
                    <li class="nav-item">
                      <a href="#" class="nav-link"
                        ><i class="fas fa-angle-right"></i>Installments</a
                      >
                    </li>
                    <li class="nav-item">
                      <a href="#" class="nav-link"
                        ><i class="fas fa-angle-right"></i>Payment History</a
                      >
                    </li>
                  </ul>
                </li>

                <!-- Grades -->
                <li class="nav-item">
                  <a href="" class="nav-link"
                    ><i class="fa fa-table"></i><span>Grade Sheet</span></a
                  >
                </li>
               @endif
          </div>
        </div>
        <!-- Sidebar Area End Here -->
        <div class="dashboard-content-one">
          <div class="ui-alart-box" style="margin-top: 18px;">
            <div class="dismiss-alart">
              @if(Session::has('msg'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                  {!!Session::get('msg')!!}
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">×</span>
                  </button>
                </div>
              @endif
              @if(Session::has('msgWrn'))
                  <div class="alert alert-warning alert-dismissible fade show" role="alert">
                  {!!Session::get('msgWrn')!!}
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">×</span>
                  </button>
                </div>
              @endif
              @if(Session::has('msgErr'))
                  <div class="alert alert-danger alert-dismissible fade show" role="alert">
                  {!!Session::get('msgErr')!!}
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">×</span>
                  </button>
                </div>
              @endif
            </div>
          </div>
           @yield('content')
          <!-- Dashboard Content End Here -->
        
         
          <footer class="footer-wrap-layout1">
          </footer>
          <!-- Footer Area End Here -->
        </div>
      </div>
      <!-- Page Area End Here -->
    </div>
    <!-- jquery-->
    <script src="{{ asset('js/jquery-3.3.1.min.js') }}"></script>
    <!-- Plugins js -->
    <script src="{{ asset('js/plugins.js') }}"></script>
    <!-- Popper js -->
    <script src="{{ asset('js/popper.min.js') }}"></script>
    <!-- Bootstrap js -->
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <!-- Counterup Js -->
    <script src="{{ asset('js/jquery.counterup.min.js') }}"></script>
    <!-- Moment Js -->
    <script src="{{ asset('js/moment.min.js') }}"></script>
    <!-- Waypoints Js -->
    <script src="{{ asset('js/jquery.waypoints.min.js') }}"></script>
    <!-- Scroll Up Js -->
    <script src="{{ asset('js/jquery.scrollUp.min.js') }}"></script>
    <!-- Full Calender Js -->
    <script src="{{ asset('js/fullcalendar.min.js') }}"></script>
    <!-- Chart Js -->
    <script src="{{ asset('js/Chart.min.js') }}"></script>
    <!-- Validate Js -->
    <script src="{{ asset('js/jquery.validate.min.js') }}"></script>
    <!-- Custom Js -->
    <script src="{{ asset('js/custom.js') }}"></script>
    @yield('js')
    <script src="{{ asset('js/main.js') }}"></script>
  </body>
</html>
