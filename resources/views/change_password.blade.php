@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
  <div class="row">
    <div class="col-xl-12 col-lg-12">
      <h1 class="my-3">System Administrator Settings</h1>
    </div>
  </div>
</div>
<div class="container-fluid ">
  <div class="row">
    <div class="col-xl-12 col-lg-12 px-0">
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb px-4 ">
          <li class="breadcrumb-item"><a href="#">Home</a></li>
          <li class="breadcrumb-item active" aria-current="page">Change Password</li>
        </ol>
      </nav>
    </div>
  </div>
</div>
<div class="container-fluid mb-5">
  <div class="row px-4">
    <div class="col-xl-12 col-lg-12 bg-light pb-5">
    
      <div class="row justify-content-xl-center justify-content-lg-center justify-content-md-center  justify-content-sm-center">
        <div class="col-xl-7 col-lg-10 col-md-10 col-sm-10">
          <h2 class="text-capitalize w-100 my-4 pt-5">Change Password</h2>
          <form method="post" action="{{route('changepassword')}}" id="changepassword">
            @csrf
            @foreach ($errors->all() as $error)
              <div class="alert alert-danger">{{ $error }}</div>
            @endforeach
            <div class="form-group">
              <label for="exampleInputEmail1" class="mb-1">Enter Old Password</label> 
              <input type="password" id="current_password" class="form-control" name="current_password">
            </div>
            <div class="form-group">
              <label for="exampleInputEmail1" class="mb-1">Enter New Password</label> 
              <input type="password" id="new_password" class="form-control" name="new_password">
            </div>
            <div class="form-group">
              <label for="exampleInputEmail1" class="mb-1">Confirm Password</label> 
              <input type="password" id="new_confirm_password" class="form-control" name="new_confirm_password">
            </div>
            <div class="row">
            <div class="col-xl-12 col-lg-12 mb-5">
              <button data-toggle="modal" class="btn btn-success px-5 text-right mb-3 mt-2 font-weight-bold">Save</button>
            </div>
          </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>


@endsection

@section('jsdependencies')
<script src="{{ asset('js/developer_js/change_password.js') }}"></script>
<script>
        // toastr.success('Are you the 6 fingered man?');
        @if(Session::has('logout_message'))
            var shortCutFunction ="{{ Session::get('logout-alert-type') }}";
            var msg = "{{ Session::get('logout_message') }}";
            if(shortCutFunction == 'success'){
              toastr.options = {
                "preventDuplicates": true,
              }
                toastr.success('', msg);
                setTimeout(function(){
                  var url = "{{ route('logout') }}";
                  $.ajax({
                    type:"POST",
                    url:url,
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    success: function() {
                      location.reload(true);
                    }
                  });                  
                }, 4000);
            }
      @endif
    </script>

@endsection 