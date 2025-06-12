@extends('layouts.backend-layout')
@section('title')
Edit Role
@endsection('title')
@section('css')
    <!-- Select 2 CSS -->
    <link rel="stylesheet" href="{{ asset('css/select2.min.css') }}">
    <!-- Date Picker CSS -->
    <link rel="stylesheet" href="{{ asset('css/datepicker.min.css') }}">
@endsection('css')
@section('content')
                <div style="display: flex; justify-content: space-between">
                    <div class="breadcrumbs-area">
                        <h3>Edit Role</h3>
                        <ul>
                            <li>
                              <a href="{{ route('home') }}">Home</a>
                            </li>
                            <li>
                              <a href="{{ route('manage-roles') }}">Manage Roles</a>
                            </li>
                            <li>Edit Role</li>
                        </ul>
                    </div>
                </div>
                <!-- Breadcubs Area End Here -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <form class="new-added-form" action="{{ route('update-role') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="user_id" value="{{Auth::user()->id}}">
                                    <input type="hidden" name="role_id" value="{{ $role->id ?? '' }}">
                                    <div class="row">
                                        <div class="col-xl-3 col-lg-6 col-12 form-group">
                                            <label>Role <span class="required">*</span></label>
                                            <input type="text" name="name" value="{{ $role->name ?? '' }}" class="form-control" maxlength="30" required autocomplete autofocus>
                                            @error('name')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        <div class="form-group mg-t-8">
                                            <button type="submit" class="btn-fill-lg btn-gradient-yellow btn-hover-bluedark">Save</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
    </div>
@endsection('content')