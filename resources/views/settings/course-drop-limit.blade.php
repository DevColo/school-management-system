@extends('layouts.backend-layout')
@section('title')
Course Drop Limit
@endsection('title')
@section('content')
                <!-- Breadcubs Area Start Here -->
                <div class="breadcrumbs-area">
                    <h3>Course Drop Limit</h3>
                    <ul>
                        <li>
                            <a href="{{ route('home') }}">Home</a>
                        </li>
                        <li>Course Drop Limit</li>
                    </ul>
                </div>
                <!-- Breadcubs Area End Here -->
                <div class="card height-auto">
                    <div class="card-body">
                        <div class="heading-layout1">
                        </div>
                        <form class="new-added-form" method="POST">
                            @csrf
                            <div class="row">
                                @if(!is_null($courseDropLimit))
                                <input type="hidden" name="courseDropLimitId" value="{{ $courseDropLimit->id }}">
                                @endif
                                <div class="col-xl-4 col-lg-4 col-4 form-group">
                                    <label>Days Limit </label>
                                    <input type="number" name="limit" value="{{ $courseDropLimit->limit ?? '' }}" class="form-control" maxlength="30" placeholder="Enter maximum days to drop courses">
                                    @error('limit')
                                      <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                      </span>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6 form-group"></div>
                                <div class="col-12 form-group mg-t-8">
                                    <button type="submit" class="btn-fill-lg btn-gradient-yellow btn-hover-bluedark">Save</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
@endsection('content')