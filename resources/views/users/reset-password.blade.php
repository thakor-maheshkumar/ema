@extends('layouts.app')
@section('title', 'Change Password')
@section('content')
<div class="container-fluid">
  <div class="row">
    <div class="col-xl-12 col-lg-12 homebg d-flex align-items-center text-center justify-content-center">
     <!--  <h1 class="text-center text-uppercase font-weight-bold">empowering people to maintain youth & beauty</h1> -->
    </div>
  </div>
</div>

<!--Reset Password -->
<div class="modal fade" id="reset_password" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h2 class="modal-title w-100">Generate New Password</h2>
      </div>
      <div class="modal-body text-center px-3 pb-4 text-center ">
        <div class="row justify-content-xl-center justify-content-lg-center">
          <div class="col-lg-11 col-xl-11">
           <img src="{{asset('images/emalogo-black-new.png')}}" class="img-fluid mb-3"/>

            
            <form id="form_reset_password" method="POST" action="{{ route('updatePassword') }}">
                @csrf
              <div class="form-group text-left mb-3">
                <input id="new_password" type="password" class="form-control @error('new_password') is-invalid @enderror" name="new_password" required autocomplete="new-password" id="new_password" placeholder="Enter new password">
                @error('new_password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
              </div>
              <div class="form-group text-left mb-3">
              <input id="password-confirm" type="password" class="form-control @error('password_confirmation') is-invalid @enderror" name="password_confirmation" required autocomplete="new-password" placeholder="Enter confirm password">
              @error('password_confirmation')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
              </div>
              <div class="form-group pt-3 mb-3">
                <button class="btn btn-secondary text-capitalize"> Update password</button>
              </div>
              <div class="form-group">
                <a href="{{ route('logout') }}" onclick="event.preventDefault();
          document.getElementById('logout-form').submit();">{{ __('Logout') }}</a>
              </div>

              
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!--Reset Password -->
@endsection