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
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('img/schooLogo.jpeg') }}">
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
                src="{{ asset('img/schooLogo.jpeg') }}"
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
                    <img src="{{ asset('admin_img')}}/{{ Auth::user()->profile_image }}" width="200" height="150" alt="Admin" />
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
                    <li>
                      <a href="{{ route('user-profile',Auth::user()->id) }}"><i class="flaticon-user"></i>My Profile</a>
                    </li>
                    <li>
                      <a href="{{ route('account-setting',Auth::user()->id) }}"
                        ><i class="flaticon-gear-loading"></i>Account
                        Settings</a
                      >
                    </li>
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
            <li class="navbar-item dropdown header-message">
              <a
                class="navbar-nav-link dropdown-toggle"
                href="#"
                role="button"
                data-toggle="dropdown"
                aria-expanded="false"
              >
                <i class="far fa-envelope"></i>
                <div class="item-title d-md-none text-16 mg-l-10">Message</div>
                <span>5</span>
              </a>

              <div class="dropdown-menu dropdown-menu-right">
                <div class="item-header">
                  <h6 class="item-title">05 Message</h6>
                </div>
                <div class="item-content">
                  <div class="media">
                    <div class="item-img bg-skyblue author-online">
                      <img src="{{ asset('img/figure/student11.png') }}" alt="img" />
                    </div>
                    <div class="media-body space-sm">
                      <div class="item-title">
                        <a href="#">
                          <span class="item-name">Maria Zaman</span>
                          <span class="item-time">18:30</span>
                        </a>
                      </div>
                      <p>
                        What is the reason of buy this item. Is it usefull for
                        me.....
                      </p>
                    </div>
                  </div>
                  <div class="media">
                    <div class="item-img bg-yellow author-online">
                      <img src="{{ asset('img/figure/student12.png') }}" alt="img" />
                    </div>
                    <div class="media-body space-sm">
                      <div class="item-title">
                        <a href="#">
                          <span class="item-name">Benny Roy</span>
                          <span class="item-time">10:35</span>
                        </a>
                      </div>
                      <p>
                        What is the reason of buy this item. Is it usefull for
                        me.....
                      </p>
                    </div>
                  </div>
                  <div class="media">
                    <div class="item-img bg-pink">
                      <img src="{{ asset('img/figure/student13.png') }}" alt="img" />
                    </div>
                    <div class="media-body space-sm">
                      <div class="item-title">
                        <a href="#">
                          <span class="item-name">Steven</span>
                          <span class="item-time">02:35</span>
                        </a>
                      </div>
                      <p>
                        What is the reason of buy this item. Is it usefull for
                        me.....
                      </p>
                    </div>
                  </div>
                  <div class="media">
                    <div class="item-img bg-violet-blue">
                      <img src="{{ asset('img/figure/student11.png') }}" alt="img" />
                    </div>
                    <div class="media-body space-sm">
                      <div class="item-title">
                        <a href="#">
                          <span class="item-name">Joshep Joe</span>
                          <span class="item-time">12:35</span>
                        </a>
                      </div>
                      <p>
                        What is the reason of buy this item. Is it usefull for
                        me.....
                      </p>
                    </div>
                  </div>
                </div>
              </div>
            </li>
            <li class="navbar-item dropdown header-notification">
              <a
                class="navbar-nav-link dropdown-toggle"
                href="#"
                role="button"
                data-toggle="dropdown"
                aria-expanded="false"
              >
                <i class="far fa-bell"></i>
                <div class="item-title d-md-none text-16 mg-l-10">
                  Notification
                </div>
                <span>8</span>
              </a>

              <div class="dropdown-menu dropdown-menu-right">
                <div class="item-header">
                  <h6 class="item-title">03 Notifiacations</h6>
                </div>
                <div class="item-content">
                  <div class="media">
                    <div class="item-icon bg-skyblue">
                      <i class="fas fa-check"></i>
                    </div>
                    <div class="media-body space-sm">
                      <div class="post-title">Complete Today Task</div>
                      <span>1 Mins ago</span>
                    </div>
                  </div>
                  <div class="media">
                    <div class="item-icon bg-orange">
                      <i class="fas fa-calendar-alt"></i>
                    </div>
                    <div class="media-body space-sm">
                      <div class="post-title">Director Metting</div>
                      <span>20 Mins ago</span>
                    </div>
                  </div>
                  <div class="media">
                    <div class="item-icon bg-violet-blue">
                      <i class="fas fa-cogs"></i>
                    </div>
                    <div class="media-body space-sm">
                      <div class="post-title">Update Password</div>
                      <span>45 Mins ago</span>
                    </div>
                  </div>
                </div>
              </div>
            </li>
            <li class="navbar-item dropdown header-language">
              <a
                class="navbar-nav-link dropdown-toggle"
                href="#"
                role="button"
                data-toggle="dropdown"
                aria-expanded="false"
                ><i class="fas fa-globe-americas"></i>EN</a
              >
              <div class="dropdown-menu dropdown-menu-right">
                <a class="dropdown-item" href="#">English</a>

                <a class="dropdown-item" href="#">French</a>
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
              <a href="index.html"><img src="{{ asset('img/logo1.png') }}" alt="logo" /></a>
            </div>
          </div>
          <div class="sidebar-menu-content">
            <ul class="nav nav-sidebar-menu sidebar-toggle-view">
              <li class="nav-item sidebar-nav-item">
                <a href="#" class="nav-link"
                  ><i class="flaticon-dashboard"></i><span>Dashboard</span></a
                >
                <ul class="nav sub-group-menu sub-group-active">
                  <li class="nav-item">
                    <a href="index.html" class="nav-link menu-active"
                      >Admin</a
                    >
                  </li>
                  <li class="nav-item">
                    <a href="student.html" class="nav-link"
                      >Students</a
                    >
                  </li>
                  <li class="nav-item">
                    <a href="librarian.html" class="nav-link"
                      >Librarian</a
                    >
                  </li>
                </ul>
              </li>
              <li class="nav-item sidebar-nav-item">
                <a href="#" class="nav-link"
                  ><i class="flaticon-couple"></i><span>Manage Admin</span></a
                >
                <ul class="nav sub-group-menu">
                  <li class="nav-item">
                    <a href="{{ route('add-admin-form') }}" class="nav-link"
                      >Add  Admin</a
                    >
                  </li>
                  <li class="nav-item">
                    <a href="{{ route('admin-list') }}" class="nav-link"
                      >Admin List</a
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
                    <a href="all-student.html" class="nav-link"
                      >All Students</a
                    >
                  </li>
           
                  <li class="nav-item">
                    <a href="admit-form.html" class="nav-link"
                      >Add Students</a
                    >
                  </li>
                  <li class="nav-item">
                    <a href="student-promotion.html" class="nav-link"
                      >Student Promotion</a
                    >
                  </li>
                </ul>
              </li>
              <li class="nav-item sidebar-nav-item">
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
                  <!-- <li class="nav-item">
                                    <a href="librarian-details.html" class="nav-link"><i
                                            class="fas fa-angle-right"></i>Librarian Details</a>
                                </li> -->
                  <li class="nav-item">
                    <a href="add-librarian.html" class="nav-link"
                      >Add Librarian</a
                    >
                  </li>
                </ul>
              </li>

              <li class="nav-item sidebar-nav-item">
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
              </li>
              <!-- Academic Year -->

              <li class="nav-item sidebar-nav-item">
                <a href="#" class="nav-link"
                  ><i class="fa fa-globe"></i><span>Academic Year</span></a
                >
                <ul class="nav sub-group-menu">
                  <li class="nav-item">
                    <a href="all-academic-year.html" class="nav-link"
                      >All Academic Year</a
                    >
                  </li>
                  <li class="nav-item">
                    <a href="add-academic-year" class="nav-link"
                      >Add Academic Year</a
                    >
                  </li>
                </ul>
              </li>
              <!-- Class -->
              <li class="nav-item sidebar-nav-item">
                <a href="#" class="nav-link"
                  ><i class="fa fa-users"></i><span>Class</span></a
                >
                <ul class="nav sub-group-menu">
                  <li class="nav-item">
                    <a href="all-subject.html" class="nav-link"
                      >All Classes</a
                    >
                  </li>
                  <li class="nav-item">
                    <a href="add-subject.html" class="nav-link"
                      >Add New Class</a
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
                    <a href="all-subject.html" class="nav-link"
                      >All Subjects</a
                    >
                  </li>
                  <li class="nav-item">
                    <a href="add-subject.html" class="nav-link"
                      >Add New Subject</a
                    >
                  </li>
                </ul>
              </li>

              <!-- Semester -->
              <li class="nav-item sidebar-nav-item">
                <a href="#" class="nav-link"
                  ><i class="fa fa-star-half"></i><span>Semester</span></a
                >
                <ul class="nav sub-group-menu">
                  <li class="nav-item">
                    <a href="all-semesters.html" class="nav-link"
                      >All Semester</a
                    >
                  </li>
                  <li class="nav-item">
                    <a href="add-semester.html" class="nav-link"
                      >Add New Semesters</a
                    >
                  </li>
                </ul>
              </li>

              <!-- Period -->
              <li class="nav-item sidebar-nav-item">
                <a href="#" class="nav-link"
                  ><i class="flaticon-open-book"></i><span>Period</span></a
                >
                <ul class="nav sub-group-menu">
                  <li class="nav-item">
                    <a href="all-periods.html" class="nav-link"
                      >All Periods</a
                    >
                  </li>
                  <li class="nav-item">
                    <a href="add-period.html" class="nav-link"
                      >Add New Period</a
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
                    <a href="all-subject.html" class="nav-link"
                      >All Grades</a
                    >
                  </li>
                  <li class="nav-item">
                    <a href="add-subject.html" class="nav-link"
                      >Add New Grade</a
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
              </li>
            </ul>
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
            <div class="copyright">
              © Copyrights <a href="#">CodeET School</a> 2023. All rights reserved.
              Designed by <a href="#">CodeET Developers</a>
            </div>
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
    @yield('js')
    <script src="{{ asset('js/main.js') }}"></script>
  </body>
</html>