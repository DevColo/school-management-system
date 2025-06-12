@extends('layouts.backend-layout')
@section('title')
Course Credit Cost
@endsection('title')
@section('content')
                <!-- Breadcubs Area Start Here -->
                <div class="breadcrumbs-area">
                    <h3>Course Credit Cost</h3>
                    <ul>
                        <li>
                            <a href="{{ route('home') }}">Home</a>
                        </li>
                        <li>Course Credit Cost</li>
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
                                <div class="col-xl-4 col-lg-4 col-4 form-group">
                                    <label>Credit/Hour <span class="required">*</span></label>
                                    <input type="number" name="credit_hour" id="twoDigitIntOnly" value="{{ old('credit_hour') }}" class="form-control" maxlength="2" placeholder="Enter Credit/Hour" required>
                                    @error('credit_hour')
                                      <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                      </span>
                                    @enderror
                                </div>
                                <div class="col-xl-4 col-lg-4 col-4 form-group">
                                    <label>Currency </label>
                                    <select class="form-control" name="currency">
                                        <option value="USD" selected>USD</option>
                                    </select>
                                    @error('currency')
                                      <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                      </span>
                                    @enderror
                                </div>
                                <div class="col-xl-4 col-lg-4 col-4 form-group">
                                    <label>Cost <span class="required">*</span></label>
                                    <input type="number" name="cost" value="{{ old('cost') }}" class="form-control" id="numericInput" placeholder="Enter Credit/Hour" required>
                                    @error('cost')
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
@section('js')
<script type="text/javascript">
    $(document).ready(function(){
        $('#twoDigitIntOnly').on('input', function () {
            // Remove any non-digit characters
            this.value = this.value.replace(/\D/g, '');

            // Limit to 2 digits
            if (this.value.length > 2) {
                this.value = this.value.slice(0, 2);
            }
        });

        $('#numericInput').on('input', function () {
          let value = $(this).val();

          // Allow only digits and a single dot
          if (!/^\d*\.?\d*$/.test(value)) {
            $(this).val(value.slice(0, -1));
            return;
          }

          const num = parseFloat(value);

          // If value is not a number, allow empty input
          if (value === '' || isNaN(num)) return;

          const parts = value.split('.');
          const whole = parseInt(parts[0], 10);
          const decimal = parts[1] || '';

          if (
            whole > 100 ||                                 
            (whole === 100 && decimal.length > 0) ||      
            decimal.length > 2                           
          ) {
            $(this).val(value.slice(0, -1));
          }
        });
    });
</script>
@endsection('js')