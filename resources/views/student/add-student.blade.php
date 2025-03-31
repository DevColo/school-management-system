@extends('layouts.backend-layout')
@section('title')
Add Student
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
                    <h3>Add Student</h3>
                    <ul>
                        <li>
                            <a href="{{ route('home') }}">Home</a>
                        </li>
                        <li>Add Student</li>
                    </ul>
                </div>
                <!-- Breadcubs Area End Here -->
                <!-- Add Student Area Start Here -->
                <div class="card height-auto">
                    <div class="card-body">
                        <div class="heading-layout1">
                        </div>
                        <form class="new-added-form" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="">
                                <p class="text-left">Student's Bio Information</p>
                            </div>
                            <div class="row">
                                <div class="col-xl-3 col-lg-6 col-12 form-group">
                                    <label>First Name <span class="required">*</span></label>
                                    <input type="text" name="first_name" value="{{ old('first_name') }}" class="form-control" maxlength="30" placeholder="Enter first name" required>
                                    @error('first_name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-xl-3 col-lg-6 col-12 form-group">
                                    <label>Middle Name</label>
                                    <input type="text" name="other_name" value="{{ old('other_name') }}" class="form-control" maxlength="30" placeholder="Enter middle name">
                                    @error('other_name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-xl-3 col-lg-6 col-12 form-group">
                                    <label>Last Name <span class="required">*</span></label>
                                    <input type="text" name="last_name" value="{{ old('last_name') }}" class="form-control" maxlength="30" placeholder="Enter last name" required>
                                    @error('last_name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-xl-3 col-lg-6 col-12 form-group">
                                    <label>Gender <span class="required">*</span></label>
                                    <select class="select2" name="gender" required>
                                        @if(old('gender') && old('gender') == 'Male')
                                            <option value="{{old('gender')}}" selected>Male</option>
                                            <option value="Female">Female</option>
                                        @elseif(old('gender') && old('gender') == 'Female')
                                            <option value="{{old('gender')}}" selected>Female</option>
                                            <option value="Male">Male</option>
                                        @else
                                            <option value="">Please Select Gender</option>
                                            <option value="Male">Male</option>
                                            <option value="Female">Female</option>
                                        @endif
                                    </select>
                                    @error('gender')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-xl-3 col-lg-6 col-12 form-group">
                                    <label>Date Of Birth <span class="required">*</span></label>
                                    <input type="text" name="dob" value="{{ old('dob') }}" placeholder="dd/mm/yyyy" class="form-control air-datepicker" data-position='bottom right' required>
                                    <i class="far fa-calendar-alt"></i>
                                    @error('dob')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-xl-3 col-lg-6 col-12 form-group">
                                    <label>Place of Birth </label>
                                    <input type="text" name="pob" value="{{ old('pob') }}" class="form-control" maxlength="30" placeholder="Enter place of birth">
                                    @error('pob')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-xl-3 col-lg-6 col-12 form-group">
                                    <label>Nationality </label>
                                    <select class="select2" name="nationality" required>
                                        <option value="{{old('nationality')}}">@if(old('nationality')){{old('nationality')}}@else {{'Select Nationality'}}@endif</option>
                                        <option value="Afghan">Afghan</option>
                                        <option value="Albanian">Albanian</option>
                                        <option value="Algerian">Algerian</option>
                                        <option value="American">American</option>
                                        <option value="Andorran">Andorran</option>
                                        <option value="Angolan">Angolan</option>
                                        <option value="Antiguan">Antiguan</option>
                                        <option value="Argentine">Argentine</option>
                                        <option value="Armenian">Armenian</option>
                                        <option value="Australian">Australian</option>
                                        <option value="Austrian">Austrian</option>
                                        <option value="Azerbaijani">Azerbaijani</option>
                                        <option value="Bahamian">Bahamian</option>
                                        <option value="Bahraini">Bahraini</option>
                                        <option value="Bangladeshi">Bangladeshi</option>
                                        <option value="Barbadian">Barbadian</option>
                                        <option value="Belarusian">Belarusian</option>
                                        <option value="Belgian">Belgian</option>
                                        <option value="Belizean">Belizean</option>
                                        <option value="Beninese">Beninese</option>
                                        <option value="Bhutanese">Bhutanese</option>
                                        <option value="Bolivian">Bolivian</option>
                                        <option value="Bosnian">Bosnian</option>
                                        <option value="Botswanan">Botswanan</option>
                                        <option value="Brazilian">Brazilian</option>
                                        <option value="British">British</option>
                                        <option value="Bruneian">Bruneian</option>
                                        <option value="Bulgarian">Bulgarian</option>
                                        <option value="Burkinabe">Burkinabe</option>
                                        <option value="Burmese">Burmese</option>
                                        <option value="Burundian">Burundian</option>
                                        <option value="Cambodian">Cambodian</option>
                                        <option value="Cameroonian">Cameroonian</option>
                                        <option value="Canadian">Canadian</option>
                                        <option value="Cape Verdean">Cape Verdean</option>
                                        <option value="Central African">Central African</option>
                                        <option value="Chadian">Chadian</option>
                                        <option value="Chilean">Chilean</option>
                                        <option value="Chinese">Chinese</option>
                                        <option value="Colombian">Colombian</option>
                                        <option value="Comoran">Comoran</option>
                                        <option value="Congolese">Congolese</option>
                                        <option value="Costa Rican">Costa Rican</option>
                                        <option value="Croatian">Croatian</option>
                                        <option value="Cuban">Cuban</option>
                                        <option value="Cypriot">Cypriot</option>
                                        <option value="Czech">Czech</option>
                                        <option value="Danish">Danish</option>
                                        <option value="Djiboutian">Djiboutian</option>
                                        <option value="Dominican">Dominican</option>
                                        <option value="Dutch">Dutch</option>
                                        <option value="East Timorese">East Timorese</option>
                                        <option value="Ecuadorean">Ecuadorean</option>
                                        <option value="Egyptian">Egyptian</option>
                                        <option value="Emirati">Emirati</option>
                                        <option value="Equatorial Guinean">Equatorial Guinean</option>
                                        <option value="Eritrean">Eritrean</option>
                                        <option value="Estonian">Estonian</option>
                                        <option value="Ethiopian">Ethiopian</option>
                                        <option value="Fijian">Fijian</option>
                                        <option value="Filipino">Filipino</option>
                                        <option value="Finnish">Finnish</option>
                                        <option value="French">French</option>
                                        <option value="Gabonese">Gabonese</option>
                                        <option value="Gambian">Gambian</option>
                                        <option value="Georgian">Georgian</option>
                                        <option value="German">German</option>
                                        <option value="Ghanaian">Ghanaian</option>
                                        <option value="Greek">Greek</option>
                                        <option value="Grenadian">Grenadian</option>
                                        <option value="Guatemalan">Guatemalan</option>
                                        <option value="Guinean">Guinean</option>
                                        <option value="Guinea-Bissauan">Guinea-Bissauan</option>
                                        <option value="Guyanese">Guyanese</option>
                                        <option value="Haitian">Haitian</option>
                                        <option value="Herzegovinian">Herzegovinian</option>
                                        <option value="Honduran">Honduran</option>
                                        <option value="Hungarian">Hungarian</option>
                                        <option value="Icelandic">Icelandic</option>
                                        <option value="Indian">Indian</option>
                                        <option value="Indonesian">Indonesian</option>
                                        <option value="Iranian">Iranian</option>
                                        <option value="Iraqi">Iraqi</option>
                                        <option value="Irish">Irish</option>
                                        <option value="Israeli">Israeli</option>
                                        <option value="Italian">Italian</option>
                                        <option value="Ivorian">Ivorian</option>
                                        <option value="Jamaican">Jamaican</option>
                                        <option value="Japanese">Japanese</option>
                                        <option value="Jordanian">Jordanian</option>
                                        <option value="Kazakh">Kazakh</option>
                                        <option value="Kenyan">Kenyan</option>
                                        <option value="Kiribati">Kiribati</option>
                                        <option value="Korean">Korean</option>
                                        <option value="Kosovar">Kosovar</option>
                                        <option value="Kuwaiti">Kuwaiti</option>
                                        <option value="Kyrgyz">Kyrgyz</option>
                                        <option value="Lao">Lao</option>
                                        <option value="Latvian">Latvian</option>
                                        <option value="Lebanese">Lebanese</option>
                                        <option value="Liberian">Liberian</option>
                                        <option value="Libyan">Libyan</option>
                                        <option value="Liechtensteiner">Liechtensteiner</option>
                                        <option value="Lithuanian">Lithuanian</option>
                                        <option value="Luxembourger">Luxembourger</option>
                                        <option value="Macedonian">Macedonian</option>
                                        <option value="Malagasy">Malagasy</option>
                                        <option value="Malawian">Malawian</option>
                                        <option value="Malaysian">Malaysian</option>
                                        <option value="Maldivian">Maldivian</option>
                                        <option value="Malian">Malian</option>
                                        <option value="Maltese">Maltese</option>
                                        <option value="Marshallese">Marshallese</option>
                                        <option value="Mauritanian">Mauritanian</option>
                                        <option value="Mauritian">Mauritian</option>
                                        <option value="Mexican">Mexican</option>
                                        <option value="Micronesian">Micronesian</option>
                                        <option value="Moldovan">Moldovan</option>
                                        <option value="Monacan">Monacan</option>
                                        <option value="Mongolian">Mongolian</option>
                                        <option value="Moroccan">Moroccan</option>
                                        <option value="Mozambican">Mozambican</option>
                                        <option value="Namibian">Namibian</option>
                                        <option value="Nauruan">Nauruan</option>
                                        <option value="Nepalese">Nepalese</option>
                                        <option value="New Zealander">New Zealander</option>
                                        <option value="Nicaraguan">Nicaraguan</option>
                                        <option value="Nigerian">Nigerian</option>
                                        <option value="Nigerien">Nigerien</option>
                                        <option value="North Korean">North Korean</option>
                                        <option value="Norwegian">Norwegian</option>
                                        <option value="Omani">Omani</option>
                                        <option value="Pakistani">Pakistani</option>
                                        <option value="Palauan">Palauan</option>
                                        <option value="Palestinian">Palestinian</option>
                                        <option value="Panamanian">Panamanian</option>
                                        <option value="Papua New Guinean">Papua New Guinean</option>
                                        <option value="Paraguayan">Paraguayan</option>
                                        <option value="Peruvian">Peruvian</option>
                                        <option value="Polish">Polish</option>
                                        <option value="Portuguese">Portuguese</option>
                                        <option value="Qatari">Qatari</option>
                                        <option value="Romanian">Romanian</option>
                                        <option value="Russian">Russian</option>
                                        <option value="Rwandan">Rwandan</option>
                                        <option value="Saint Lucian">Saint Lucian</option>
                                        <option value="Salvadoran">Salvadoran</option>
                                        <option value="Samoan">Samoan</option>
                                        <option value="San Marinese">San Marinese</option>
                                        <option value="Sao Tomean">Sao Tomean</option>
                                        <option value="Saudi">Saudi</option>
                                        <option value="Scottish">Scottish</option>
                                        <option value="Senegalese">Senegalese</option>
                                        <option value="Serbian">Serbian</option>
                                        <option value="Seychellois">Seychellois</option>
                                        <option value="Sierra Leonean">Sierra Leonean</option>
                                        <option value="Singaporean">Singaporean</option>
                                        <option value="Slovak">Slovak</option>
                                        <option value="Slovenian">Slovenian</option>
                                        <option value="Solomon Islander">Solomon Islander</option>
                                        <option value="Somali">Somali</option>
                                        <option value="South African">South African</option>
                                        <option value="South Korean">South Korean</option>
                                        <option value="Spanish">Spanish</option>
                                        <option value="Sri Lankan">Sri Lankan</option>
                                        <option value="Sudanese">Sudanese</option>
                                        <option value="Surinamer">Surinamer</option>
                                        <option value="Swazi">Swazi</option>
                                        <option value="Swedish">Swedish</option>
                                        <option value="Swiss">Swiss</option>
                                        <option value="Syrian">Syrian</option>
                                        <option value="Taiwanese">Taiwanese</option>
                                        <option value="Tajik">Tajik</option>
                                        <option value="Tanzanian">Tanzanian</option>
                                        <option value="Thai">Thai</option>
                                        <option value="Togolese">Togolese</option>
                                        <option value="Tongan">Tongan</option>
                                        <option value="Trinidadian">Trinidadian</option>
                                        <option value="Tunisian">Tunisian</option>
                                        <option value="Turkish">Turkish</option>
                                        <option value="Turkmen">Turkmen</option>
                                        <option value="Tuvaluan">Tuvaluan</option>
                                        <option value="Ugandan">Ugandan</option>
                                        <option value="Ukrainian">Ukrainian</option>
                                        <option value="Uruguayan">Uruguayan</option>
                                        <option value="Uzbek">Uzbek</option>
                                        <option value="Vanuatuan">Vanuatuan</option>
                                        <option value="Venezuelan">Venezuelan</option>
                                        <option value="Vietnamese">Vietnamese</option>
                                        <option value="Welsh">Welsh</option>
                                        <option value="Yemeni">Yemeni</option>
                                        <option value="Zambian">Zambian</option>
                                        <option value="Zimbabwean">Zimbabwean</option>
                                    </select>
                                    @error('nationality')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-xl-3 col-lg-6 col-12 form-group">
                                    <label>Address</label>
                                    <input type="text" name="address" value="{{ old('address') }}" class="form-control" maxlength="30" placeholder="Enter address">
                                    @error('address')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-xl-3 col-lg-6 col-12 form-group">
                                    <label>Phone</label>
                                    <input type="text" name="phone" value="{{ old('phone') }}" class="form-control" maxlength="14" placeholder="Enter phone">
                                    @error('phone')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-xl-3 col-lg-6 col-12 form-group">
                                    <label>Mother Name</label>
                                    <input type="text" name="mother_name" value="{{ old('mother_name') }}" class="form-control" maxlength="14" placeholder="Enter mother name">
                                    @error('mother_name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-xl-3 col-lg-6 col-12 form-group">
                                    <label>Mother Phone</label>
                                    <input type="text" name="mother_phone" value="{{ old('mother_phone') }}" class="form-control" maxlength="14" placeholder="Enter mother phone">
                                    @error('mother_phone')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-xl-3 col-lg-6 col-12 form-group">
                                    <label>Mother Email</label>
                                    <input type="email" name="mother_email" value="{{ old('mother_email') }}" class="form-control" maxlength="14" placeholder="Enter mother mail">
                                    @error('mother_email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-xl-3 col-lg-6 col-12 form-group">
                                    <label>Father Name</label>
                                    <input type="text" name="father_name" value="{{ old('father_name') }}" class="form-control" maxlength="14" placeholder="Enter father name">
                                    @error('father_name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-xl-3 col-lg-6 col-12 form-group">
                                    <label>Father Phone</label>
                                    <input type="text" name="father_phone" value="{{ old('father_phone') }}" class="form-control" maxlength="14" placeholder="Enter father phone">
                                    @error('father_phone')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-xl-3 col-lg-6 col-12 form-group">
                                    <label>Father Email</label>
                                    <input type="email" name="father_email" value="{{ old('father_email') }}" class="form-control" maxlength="14" placeholder="Enter father email">
                                    @error('father_email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-xl-3 col-lg-6 col-12 form-group">
                                    <div class="form-group">
                                        <label>Student Photo (150px X 150px)</label>
                                        <div class="image-upload" style="margin: 2px 0 12px;">
                                            <input type="file" class="form-control-file" accept="image/*" name="student_photo" value="{{ old('student_photo') }}" id="imgInp">
                                        </div>
                                        <div class="profile-contentimg">
                                            <img src="" id="blah" style="max-width: 25%;">
                                            <span class="d-none">
                                                <a class="btn btn-sm btn-danger" id="remove-product-img">Remove</a>
                                            </span>
                                        </div>
                                        @if ($errors->has('student_photo'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('student_photo') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="">
                                <p class="text-left">Emergency Contact Person</p>
                            </div>
                            <div class="row">
                                <div class="col-xl-3 col-lg-6 col-12 form-group">
                                    <label>Emergency Contact Person Name</label>
                                    <input type="text" name="emergency_contact_name" value="{{ old('emergency_contact_name') }}" class="form-control" maxlength="30" placeholder="Enter person name">
                                    @error('emergency_contact_name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-xl-3 col-lg-6 col-12 form-group">
                                    <label>Emergency Contact Person Phone</label>
                                    <input type="text" name="emergency_contact_phone" value="{{ old('emergency_contact_phone') }}" class="form-control" maxlength="30" placeholder="Enter person phone">
                                    @error('emergency_contact_phone')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-xl-3 col-lg-6 col-12 form-group">
                                    <label>Emergency Contact Person Address</label>
                                    <input type="text" name="emergency_contact_address" value="{{ old('emergency_contact_address') }}" class="form-control" maxlength="30" placeholder="Enter person address">
                                    @error('emergency_contact_address')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-xl-3 col-lg-6 col-12 form-group">
                                    <label>Emergency Contact Person Email</label>
                                    <input type="email" name="emergency_contact_email" value="{{ old('emergency_contact_email') }}" class="form-control" maxlength="30" placeholder="Enter person email">
                                    @error('emergency_contact_email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-xl-3 col-lg-6 col-12 form-group">
                                    <label>Emergency Contact Person relationship <span class="required">*</span></label>
                                    <input type="text" name="emergency_contact_relationship" value="{{ old('emergency_contact_relationship') }}" class="form-control" maxlength="30" placeholder="Enter person relationship" required>
                                    @error('emergency_contact_relationship')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <br>
                            <div class=""></div>
                            <div class="row">
                                <div class="col-xl-3 col-lg-6 col-12 form-group">
                                    <label>Class Assignment</label>
                                    <select class="select2" name="class" id="class">
                                        @if(old('class'))
                                            @if(!$classes->isEmpty())
                                                @foreach($classes as $class)
                                                    @if(old('class') == $class->id)
                                                        <option value="{{ $class->id }}" selected>{{ $class->class }}</option>
                                                    @else
                                                        <option value="{{ $class->id }}">{{ $class->class_name }}</option>
                                                    @endif   
                                                @endforeach
                                            @endif
                                        @else
                                            @if(!$classes->isEmpty())
                                                <option value="">Select Class</option>
                                                @foreach($classes as $class)
                                                    <option value="{{ $class->id }}">{{ $class->class_name }}</option>
                                                @endforeach
                                            @else
                                                <option value="">No Class Found</option>
                                            @endif
                                        @endif
                                    </select>
                                    @error('class')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    <label for="status" style="margin-top:10px;"><input type="checkbox" id="status" class="pt-2" name="status" checked> Active</label>
                                </div>
                                <div class="col-xl-3 col-lg-6 col-12 form-group">
                                    <label>Academic Year <span class="required year" style="display:none;">*</span></label>
                                    <select class="select2" name="year" id="year">
                                        @if(old('year'))
                                            @if(!$years->isEmpty())
                                                @foreach($years as $year)
                                                    @if(old('year') == $year->id)
                                                        <option value="{{ $year->id }}" selected>{{ $year->year }}</option>
                                                    @else
                                                        <option value="{{ $year->id }}">{{ $year->year }}</option>
                                                    @endif   
                                                @endforeach
                                            @endif
                                        @else
                                            @if(!$years->isEmpty())
                                                <option value="">Select Academic Year</option>
                                                @foreach($years as $year)
                                                    <option value="{{ $year->id }}">{{ $year->year }}</option>
                                                @endforeach
                                            @else
                                                <option value="">No Academic Year Found</option>
                                            @endif
                                        @endif
                                    </select>
                                    @error('year')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-12 form-group mg-t-8">
                                    <button type="submit" class="btn-fill-lg btn-gradient-blue btn-hover-bluedark">Save</button>
                                </div>
                             </div>
                        </form>
                    </div>
                </div>
@endsection('content')
@section('js')
<script src="{{ asset('js/select2.min.js') }}"></script>
<script src="{{ asset('js/datepicker.min.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            // Preview selected image
            $("#imgInp").on('change', function() {
                var input = this;

                if (input.files && input.files[0]) {
                    var reader = new FileReader();

                    reader.onload = function(e) {
                        $('#blah').attr('src', e.target.result).show();
                        $(".profile-contentimg span").removeClass("d-none");
                    }

                    reader.readAsDataURL(input.files[0]);
                }
            });

            // Remove selected image
            $("#remove-product-img").click(function(e) {
                e.preventDefault();

                $('#blah').attr("src", "").hide();
                $(".profile-contentimg span").addClass("d-none");
                $('#imgInp').val("");
            });

            $('#class').on('change', function() {
                if ($(this).val() !== '') {
                  $('#year').attr('required', true);
                  $('span.year').css('display', 'initial');
                } else {
                  $('#year').removeAttr('required');
                  $('span.year').css('display', 'none');
                }
            });
        });
    </script>
@endsection('js')