@extends('layouts.app')

@section('content')
<div class="container">
   @if(!empty($errors->all()))
      @foreach($errors->all() as $error)
          <div class="alert alert-danger col-lg-10 mb-5" role="alert">
          {{ $error }}
          </div>
      @endforeach
    @endif
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Create User</div>

                <div class="card-body">
                   
                    <form action="{{ route('register-user') }}" method="post">
                       @csrf
                      <div class="container">
                        <p>Please fill in this form to create an account.</p>
                        <hr>

                        <label for="name"><b>Name</b></label>
                        <input type="text" placeholder="Enter Name" name="name" required>

                        <label for="username"><b>UserName</b></label>
                        <input type="text" placeholder="Enter User name" name="username" required>

                        <label for="email"><b>Email</b></label>
                        <input type="text" placeholder="Enter Email" name="email" required>

                        <label for="password"><b>Password</b></label>
                        <input type="password" placeholder="Enter Password" name="password" required>

                        <hr>
                        <select class="form-control" name="role">
                          <option>--Select Role--</option>
                          @foreach($getAllRoles as $roles)
                            <option value="{{ $roles->name }}">{{ $roles->name }}</option>
                          @endforeach
                        </select>

                        <hr>


                        <button type="submit" class="registerbtn">Register</button>
                      </div>
                      
                      <div class="container signin">
                        <p>Already have an account? <a href="#">Sign in</a>.</p>
                      </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('cssdependencies')
<style type="text/css">
    
.signin {
  background-color: #f1f1f1;
  text-align: center;
}
.container {
  padding: 16px;
  background-color: white;
}
/* Full-width input fields */
input[type=text], input[type=password] {
  width: 100%;
  padding: 15px;
  margin: 5px 0 22px 0;
  display: inline-block;
  border: none;
  background: #f1f1f1;
}

input[type=text]:focus, input[type=password]:focus {
  background-color: #ddd;
  outline: none;
}


/* Overwrite default styles of hr */
hr {
  border: 1px solid #f1f1f1;
  margin-bottom: 25px;
}

/* Set a style for the submit button */
.registerbtn {
  background-color: #4CAF50;
  color: white;
  padding: 16px 20px;
  margin: 8px 0;
  border: none;
  cursor: pointer;
  width: 100%;
  opacity: 0.9;
}

.registerbtn:hover {
  opacity: 1;
}

</style>
@endsection