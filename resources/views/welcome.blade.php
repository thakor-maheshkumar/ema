@extends('layouts.app')

@section('title', 'Home Page')

@section('content')
<div class="container-fluid">
  <div class="row">
    <div class="col-xl-12 col-lg-12 homebg d-flex align-items-center text-center justify-content-center">
     <!--  <h1 class="text-center text-uppercase font-weight-bold">empowering people to maintain youth & beauty</h1> -->
    </div>
  </div>
</div>
<!-- About EMA -->
<div class="modal fade " id="aboutema" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-xl homeModel" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h2 class="modal-title w-100">About US</h2>
      <button type="button" class="close text-right" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">X</span> </button>
    </div>
      <div class="modal-body text-center px-3 mx-3 pb-3">
        <div class="row justify-content-xl-center justify-content-lg-center">
          <div class="col-lg-11 col-xl-11"> <img src="{{asset('images/emalogo-black-new.png')}}" class="img-fluid mb-3"/>

            <p class="mb-4">EMA Aesthetics Ltd is established in Ireland and specialises
              in the fields of professional aesthetics and skincare technology.
              The company currently owns HydraCool and SRS brands.</p>
            <p>We are present in 39 countries worldwide and operate in Ireland, Belgium,
              UAE and Singapore. EMA commits to sustainable growth,
              product development and skincare innovation globally.”</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- About EMA -->
<!--Contact EMA -->
<!--Contact EMA -->
<div class="modal fade " id="contactema" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog homeModel" role="document">
    <div class="modal-content">
      <div class="modal-header">
      <h2 class="modal-title text-left w-100">Contact EMA</h2>
      <button type="button" class="close text-right" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">X</span> </button>
    </div>
      <div class="modal-body px-3 max_height">
        <div class="row justify-content-xl-center justify-content-lg-center justify-content-md-center">
          <div class="col-xl-12 col-lg-12 col-md-12">
            <form action="{{ url('contact-detail-register') }}" method="post" id="addContactUser">
              @csrf
              <div class="form-row">
                <div class="form-group col-lg-6 col-md-6 col-sm-6">
                  <label for="add_name" class="mb-1">First Name</label>
                  <input type="text"  class="form-control" id="first_name"
                  name="first_name">
                </div>
                <div class="form-group col-lg-6 col-md-6 col-sm-6">
                  <label for="add_last_name" class="mb-1">Last Name</label>
                  <input type="text" class="form-control" id="last_name"
                  name="last_name">
                </div>
                <div class="form-group col-lg-6 col-md-6 col-sm-6">
                  <label for="company_name" class="mb-1">Company Name</label>
                  <input type="text" id="company_name" name="company_name" class="form-control distinctphones">
                </div>
                <div class="form-group col-lg-6 col-md-6 col-sm-6">
                  <label for="job_role" class="mb-1">Job Role</label>
                  <input type="text" id="job_role" name="job_role" class="form-control">
                </div>
                <div class="form-group col-lg-6 col-md-6 col-sm-6">
                  <label for="exampleInputEmail2" class="mb-1">Country</label>
                  <select class="form-control country-select select_dropdown" name="country_id">
                    <option value="">--Select Country--</option>
                    @foreach($getCountryData as $getCountryRow)
                    <option value="{{ $getCountryRow['id'] }}">{{ $getCountryRow['name'] }}</option>
                    @endforeach
                  </select>
                </div>
                <div class="form-group col-lg-6 col-md-6 col-sm-6">
                  <label for="email_address" class="mb-1">Email Address </label>
                  <input type="text" id="email_address" name="email_address" class="form-control">
                </div>
                <div class="form-group col-lg-6 col-md-6 col-sm-6">
                  <label for="exampleInputEmail2" class="mb-1">Contact Telephone Number</label>
                  <input type="text" name="contact_telephone_number" class="form-control phoneregx" id="contact_telephone_number" >
                </div>
                <div class="form-group col-lg-6 col-md-6 col-sm-6">
                  <label for="add_mobno" class="mb-1">Mobile Number</label>
                  <input type="text" name="mobile_number" class="form-control phoneregx" id="mobile_number" >
                </div>
                <div class="form-group col-lg-12 col-md-12 col-sm-12">
                  <label for="help" class="mb-1">Tell us how we can help you </label>
                  <textarea rows="3" id="message" class="form-control" name="message"></textarea>
                </div>
              </div>
              <div class="row text-center mt-3">
                <div class="col-xl-12 col-lg-12 col-md-12">
                  <button class="btn btn-secondary" type="submit" name="submit">Submit</button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!--Contact EMA -->
<!--Login -->
@include('includes.login')
<!--Login -->
<!--Forgot Password -->
<div class="modal fade" id="forgotpassword" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog loginmodel" role="document">
    <div class="modal-content">
       <div class="modal-header">
        <h2 class="modal-title w-100">Forgot Password</h2>
      <button type="button" class="close text-right" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">X</span> </button>
    </div>
      <div class="modal-body text-center px-3 text-center ">
        <div class="row justify-content-xl-center justify-content-lg-center">
          <div class="col-lg-11 col-xl-11"> <img src="{{asset('images/emalogo-black-new.png')}}" class="img-fluid mb-3"/>

            <form id="forgot_password_form" method="POST" action="{{ route('resetPassword') }}">
                @csrf
              <div class="form-group text-left mb-3">
                <input type="text" name="forgot_username" id="forgot_username" class="form-control @error('forgot_username') is-invalid @enderror px-3 py-2 border-radius" placeholder="Enter Your Username">
                @error('forgot_username')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
              </div>
              <div class="form-group pt-3 mt-3">
                <button class="btn btn-secondary text-capitalize" id="send_new_password"> send new password</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
@section('jsdependencies')

@if($errors->has('forgot_username'))
    <script>
    $(function() {
        $('#forgotpassword').modal({
            show: true,
            backdrop: 'static',
            keyboard: false
        });
    });
    </script>
@endif
<!--Forgot Password -->
<script type="text/javascript">
  $(document).ready(function(){

    $("#forgot_password").click(function(){
      $("#login").modal('hide');
    });

    $('[data-toggle="tooltip"]').tooltip();

    $("#forgot_password_form").validate({
      rules: {
        forgot_username: {
          required: true
        },
      },
    });

    $("#login_form").validate({
      rules: {
        username: {
          required: true
        },
        password: { required: true}
      },
    });
  });

  @if(Session::has('message'))
  var shortCutFunction ="{{ Session::get('alert-type') }}";
  var msg = "{{ Session::get('message') }}";
  toastr.options = {
    "preventDuplicates": true,
  }
  var $toast = toastr[shortCutFunction](msg);
  @endif

@if(Session::has('contact-message'))
  var shortCutFunction ="{{ Session::get('alert-type') }}";
  var msg = "{{ Session::get('contact-message') }}";
  toastr.options = {
    "preventDuplicates": true,
  }
  var $toast = toastr[shortCutFunction](msg);
  @endif



  @if(Session::has('session_message'))
  var type = "{{ Session::get('session-alert-type', 'info') }}";
  switch(type){
    case 'success':
    toastr.success("{{ Session::get('session_message') }}");
    break;
  }
  @endif

  $('#forgot_username').on('keyup',function(){
    $('.invalid-feedback').remove();
    $(this).removeClass('is-invalid');
  });

  $('#send_new_password').on('click',function(){
    $('.invalid-feedback').remove();
    $('#forgot_username').removeClass('is-invalid');
  });

</script>
@stop