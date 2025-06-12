@extends('layouts.backend-layout')
@section('title')
User Profile
@endsection('title')
@section('content')
          <!-- Breadcubs Area Start Here -->
          <div style="display: flex; justify-content: space-between">
            <div class="breadcrumbs-area">
              <h3>User Profile</h3>
              <ul>
                <li>
                  <a href="{{ route('home') }}">Home</a>
                </li>
                <li>User Profile</li>
              </ul>
            </div>
            <div class="breadcrumbs-area">
              <a href="{{ route('account-setting',$user->id) }}">
                <button
                  type="button"
                  class="btn-fill-md text-light bg-dark-pastel-green"
                >
                  Account Settings
                </button>
              </a>
            </div>
          </div>

          <!-- Breadcubs Area End Here -->
          <div class="row">
            <!-- Student Info Area Start Here -->
            <div class="col-8-xxxl col-xl-7">
                        <div class="card account-settings-box">
                            <div class="card-body">
                                <div class="heading-layout1 mg-b-20">
                                    <div class="item-title">
                                        <h3>User Details</h3>
                                    </div>
                                </div>
                                <div class="user-details-box">
                                    <div class="item-img">
                                        <img src="{{ asset('admin_img')}}/{{ $user->profile_image }}" alt="user">
                                        <div>
                                          <a href="#" class="" id="dropId" data-toggle="modal" data-target=".bs-example-modal-center"><i class="fa fa-camera"></i> Change Profile Photo</a> 
                                        </div>
                                    </div>
                                    <div class="item-content">
                                        <div class="info-table table-responsive">
                                            <table class="table text-nowrap">
                                                <tbody>
                                                    <tr>
                                                        <td>Username:</td>
                                                        <td class="font-medium text-dark-medium">{{ $user->user_name ?? '' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>User Type:</td>
                                                        <td class="font-medium text-dark-medium">
                                                          @hasrole('superadmin')
                                                            Super Admin
                                                          @endrole
                                                          @hasrole('admin')
                                                            Admin
                                                          @endrole
                                                          @hasrole('lecturer')
                                                            Lecturer
                                                          @endrole
                                                          @hasrole('registrar')
                                                            Registrar
                                                          @endrole
                                                          @hasrole('accountant')
                                                            Accountant
                                                          @endrole
                                                      </td>
                                                    </tr>
                                                    <tr>
                                                        <td>First Name:</td>
                                                        <td class="font-medium text-dark-medium">{{ $user_detail[0]->first_name ?? '' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Other Name:</td>
                                                        <td class="font-medium text-dark-medium">{{ $user_detail[0]->other_name ?? '' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Last Name:</td>
                                                        <td class="font-medium text-dark-medium">{{ $user_detail[0]->last_name ?? '' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Gender:</td>
                                                        <td class="font-medium text-dark-medium">
                                                          @if($user_detail[0]->gender)
                                                            @if($user_detail[0]->gender == 'm')
                                                              Male
                                                            @else
                                                              Female
                                                            @endif
                                                          @endif
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Email:</td>
                                                        <td class="font-medium text-dark-medium">{{ $user->email ?? '' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Phone:</td>
                                                        <td class="font-medium text-dark-medium">{{ $user_detail[0]->phone ?? '' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Address:</td>
                                                        <td class="font-medium text-dark-medium">{{ $user_detail[0]->address ?? '' }}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
            <!-- Student Info Area End Here -->
          </div>

<div class="modal fade bs-example-modal-center" tabindex="-1" role="dialog" aria-labelledby="myCenterModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myCenterModalLabel">Upload photo</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <form method="post" id="imgForm" action="{{ route('updateAdminImg') }}" enctype="multipart/form-data">
                    {{csrf_field()}}
                    <div class="form-group">
                    <input type="file" class="form-control" accept="image/*" name="profile_image"  required>
                    <input type="hidden" value="{{ $user->profile_image }}" id="existing_image" name="existing_image">
                    </div>
                    <div class="form-group">
                     <button type="submit" class="btn-fill-lg bg-blue-dark btn-hover-yellow">submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection