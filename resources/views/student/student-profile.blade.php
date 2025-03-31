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
                @if (auth()->user()->hasAnyRole(['superadmin', 'admin']))
                    <li>
                      <a href="{{ route('student-list') }}">Student List</a>
                    </li>
                @endif
                <li>Student Profile</li>
              </ul>
            </div>
            @if (auth()->user()->hasAnyRole(['superadmin', 'admin']))
                <div class="breadcrumbs-area">
                  <a href="">
                    <button
                      type="button"
                      class="btn-fill-md text-light bg-dark-pastel-green"
                    >
                      Account Settings
                    </button>
                  </a>
                </div>
            @endif
          </div>

          <!-- Breadcubs Area End Here -->
          <div class="row">
            <!-- Student Info Area Start Here -->
            <div class="col-12-xxxl col-xl-12">
                <div class="card account-settings-box">
                    <div class="card-body">
                        <div class="heading-layout1 mg-b-20">
                            <div class="item-title">
                                <h3>Student Profile</h3>
                            </div>
                            @if (auth()->user()->hasAnyRole(['superadmin', 'admin']))
                                <div>
                                    <i style="font-size: 12px;">Created: {{ $studentDetail->User->created_at ?? '' }} | Updated: {{ $studentDetail->User->updated_at ?? '' }}</i>
                                </div>
                            @endif
                        </div>
                        <div class="user-details-box">
                            <div class="item-img">
                                <img src="{{ asset('student_img')}}/{{ $studentDetail->User->profile_image }}" alt="user" style="border-radius: 0 !important;">
                                @if (auth()->user()->hasAnyRole(['superadmin', 'admin']))
                                    <div>
                                      <a href="#" class="" id="dropId" data-toggle="modal" data-target=".bs-example-modal-center"><i class="fa fa-camera"></i> Change Profile Photo</a> 
                                    </div>
                                @endif
                                @if($studentDetail->status)
                                    @if($studentDetail->status == '1')
                                        <button class="btn btn-md btn-success">Active</button>
                                    @else
                                        <button class="btn btn-md btn-danger">Disabled</button>
                                    @endif
                                @endif
                                @if($class != '')
                                <div>
                                    <button class="btn btn-md btn-primary">{{ $class }}</button>
                                </div>
                                @endif
                            </div>
                            <div class="item-content">
                                <div class="info-table table-responsive">
                                    <table class="table text-nowrap">
                                        <tbody>
                                            <tr>
                                                <td>Student ID:</td>
                                                <td class="font-medium text-dark-medium">{{ $studentDetail->User->user_name ?? '' }}</td>
                                            </tr>
                                            <tr>
                                                <td>First Name:</td>
                                                <td class="font-medium text-dark-medium">{{ $studentDetail->first_name ?? '' }}</td>
                                            </tr>
                                            <tr>
                                                <td>Last Name:</td>
                                                <td class="font-medium text-dark-medium">{{ $studentDetail->last_name ?? '' }}</td>
                                            </tr>
                                            <tr>
                                                <td>Gender:</td>
                                                <td class="font-medium text-dark-medium">
                                                  @if($studentDetail->gender)
                                                    @if($studentDetail->gender == 'm')
                                                      Male
                                                    @else
                                                      Female
                                                    @endif
                                                  @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Email:</td>
                                                <td class="font-medium text-dark-medium">{{ $studentDetail->User->email ?? '' }}</td>
                                            </tr>
                                            <tr>
                                                <td>Phone:</td>
                                                <td class="font-medium text-dark-medium">{{ $studentDetail->phone ?? '' }}</td>
                                            </tr>
                                            <tr>
                                                <td>Address:</td>
                                                <td class="font-medium text-dark-medium">{{ $studentDetail->address ?? '' }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="item-content">
                                <div class="info-table table-responsive">
                                    <table class="table text-nowrap">
                                        <tbody>
                                            <tr>
                                                <td>Date Of Birth:</td>
                                                <td class="font-medium text-dark-medium">{{ $studentDetail->dob ?? '' }}</td>
                                            </tr>
                                            <tr>
                                                <td>Place Of Birth:</td>
                                                <td class="font-medium text-dark-medium">{{ $studentDetail->pob ?? '' }}</td>
                                            </tr>
                                            <tr>
                                                <td>Nationality:</td>
                                                <td class="font-medium text-dark-medium">{{ $studentDetail->nationality ?? '' }}</td>
                                            </tr>
                                            <tr>
                                                <td>Mother Name:</td>
                                                <td class="font-medium text-dark-medium">{{ $studentDetail->mother_name ?? '' }}</td>
                                            </tr>
                                            <tr>
                                                <td>Mother Phone:</td>
                                                <td class="font-medium text-dark-medium">{{ $studentDetail->mother_phone ?? '' }}</td>
                                            </tr>
                                            <tr>
                                                <td>Mother Email:</td>
                                                <td class="font-medium text-dark-medium">{{ $studentDetail->  mother_email ?? '' }}</td>
                                            </tr>
                                            <tr>
                                                <td>Father Name:</td>
                                                <td class="font-medium text-dark-medium">{{ $studentDetail->father_name ?? '' }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="item-content">
                                <div class="info-table table-responsive">
                                    <table class="table text-nowrap">
                                        <tbody>
                                            <tr>
                                                <td>Father Phone:</td>
                                                <td class="font-medium text-dark-medium">{{ $studentDetail->father_phone ?? '' }}</td>
                                            </tr>
                                            <tr>
                                                <td>Father Email:</td>
                                                <td class="font-medium text-dark-medium">{{ $studentDetail->father_email ?? '' }}</td>
                                            </tr>
                                            <tr>
                                                <td>Emergency Contact Name:</td>
                                                <td class="font-medium text-dark-medium">{{ $studentDetail->emergency_contact_name ?? '' }}</td>
                                            </tr>
                                            <tr>
                                                <td>Emergency Contact Phone:</td>
                                                <td class="font-medium text-dark-medium">{{ $studentDetail->emergency_contact_phone ?? '' }}</td>
                                            </tr>
                                            <tr>
                                                <td>Emergency Contact Address:</td>
                                                <td class="font-medium text-dark-medium">{{ $studentDetail->  emergency_contact_address ?? '' }}</td>
                                            </tr>
                                            <tr>
                                                <td>Emergency Contact Email:</td>
                                                <td class="font-medium text-dark-medium">{{ $studentDetail->emergency_contact_email ?? '' }}</td>
                                            </tr>
                                            <tr>
                                                <td>Emergency Contact Relationship:</td>
                                                <td class="font-medium text-dark-medium">{{ $studentDetail->emergency_contact_relationship ?? '' }}</td>
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
                    <input type="hidden" value="{{ $studentDetail->User->profile_image }}" id="existing_image" name="existing_image">
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