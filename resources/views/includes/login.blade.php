<div class="modal fade" id="login" tabindex="-1" role="dialog" aria-labelledby="loginModal" aria-hidden="true">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h2 class="modal-title w-100">Login</h2>
      <button type="button" class="close text-right" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">X</span> </button>
    </div>
      <div class="modal-body text-center px-3 text-center ">
        <div class="row justify-content-xl-center justify-content-lg-center">
          <div class="col-lg-11 col-xl-11"> <img src="{{asset('images/emalogo-black-new.png')}}" class="img-fluid mb-2"/>

            <p>Only Authorized Users</p>
            <form id="login_form" method="POST" action="{{ route('login') }}">
               @csrf
              <div class="form-group text-left position-relative">
                <label for="username">Username:</label>
                <br>

                <input type="text" name="username" id="username" class="form-control @error('username') is-invalid @enderror py-2 border-radius">
                @error('username')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
              </div>
              <div class="form-group text-left position-relative">
                <label for="password" >Password:</label>
                <br>

                <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror py-2 border-radius">
                @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
              </div>
              <div class="form-group mt-2"> <a href="#" data-toggle="modal" data-target="#forgotpassword" class="small" id="forgot_password">Forgot Password?</a> </div>
              <div class="form-group pt-3">
                <button class="btn btn-secondary" type="submit" id="login_user"> Login</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@section('jsdependencies')
@parent

@if($errors->has('username') || $errors->has('password') || session('message'))
    <script>
    $(function() {
        $('#login').modal({
            show: true,
            backdrop: 'static',
            keyboard: false
        });
    });
    $('#login_user').on('click',function(){
    $('.invalid-feedback').remove();
    $('#username').removeClass('is-invalid');
    $('#password').removeClass('is-invalid');
  });
    </script>
@endif
@endsection
