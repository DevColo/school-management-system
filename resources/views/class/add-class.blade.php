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
                <!-- Breadcubs Area Start Here -->
                <div class="breadcrumbs-area">
                    <h3>Classes</h3>
                    <ul>
                        <li>
                            <a href="index.html">Home</a>
                        </li>
                        <li>Add New Class</li>
                    </ul>
                </div>
                <!-- Breadcubs Area End Here -->
                <!-- Add Class Area Start Here -->
                <div class="card height-auto">
                    <div class="card-body">
                        <div class="heading-layout1">
                            <div class="item-title">
                                <h3>Add New Class</h3>
                            </div>
                          
                        </div>
                        <form class="new-added-form">
                            <div class="row">
                              
                               
                                <div class="col-xl-6 col-lg-12 col-12 form-group">
                                    <label>Class</label>
                                    <select class="select2">
                                        <option value="">Please Select</option>
                                        <option value="1">Grade 1</option>
                                        <option value="2">Grade 2</option>
                                        <option value="3">Grade 3</option>
                                        <option value="3">Grade 4</option>
                                        <option value="3">Grade 5</option>
                                        <option value="3">Grade 6</option>
                                        <option value="3">Grade 7</option>
                                    </select>
                                </div>
                                <div class="col-xl-6 col-lg-12 col-12 form-group">
                                    <label>Section *</label>
                                    <select class="select2">
                                        <option value="">Please Select *</option>
                                        <option value="1">A</option>
                                        <option value="2">B</option>
                                        <option value="3">C</option>
                                        <option value="3">D</option>
                                        <option value="3">E</option>
                                    </select>
                                </div>
                               
                                
                             
                              
                                <div class="col-md-6 form-group"></div>
                                <div class="col-12 form-group mg-t-8">
                                    <button type="submit" class="btn-fill-lg btn-gradient-yellow btn-hover-bluedark">Save</button>
                                    <button type="reset" class="btn-fill-lg bg-blue-dark btn-hover-yellow">Reset</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
@endsection('content')