@extends('layouts.app')
@section('title', 'EMA Users')
@section('content')
<div class="container-fluid">
  <div class="row">
    <div class="col-xl-12 col-lg-12">
      <h1 class="my-2">EMA Users</h1>
    </div>
  </div>
</div>
<div class="container-fluid">
  <div class="row">
    <div class="col-xl-12 col-lg-12 px-0">
      <nav aria-label="breadcrumb">
      {{ Breadcrumbs::render('ema_user') }}
      </nav>
    </div>
  </div>
</div>
<div class="container-fluid">
  @hasanyrole('system administrator')
  <div class="row">
    <div class="col-xl-12 col-lg-12">
      <button class="btn btn-secondary" data-toggle="modal" data-target="#add_new_user">Add New EMA User</button>
    </div>
  </div>
  @endhasanyrole
  <div class="row">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
    <section class="gridMainContainer">
        <div class="col-xl-12 col-lg-12">
          <h2 class="my-2 text-capitalize">View All EMA Users</h2>
        </div>
      <div class="col-xl-12 col-lg-12 px-0">
        <div class="table-responsive mt_minus datatable_resize" id="userScroll">
          <table id="users-table" class="table table-striped table-bordered example" style="width:100%">
            <thead>
              <tr>
                <th>Full Name</th>
                <th>User Type</th>
                <th>Online</th>
                <th>Status</th>
                <th>Email Address</th>
                <th>Telephone</th>
                <th>Mobile</th>
                <th>Created At</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tfoot style="display:none">
              <tr>
                <th dataName="name">Full Name</th>
                <th dataName="role">User Type</th>
                <th>Online</th>
                <th dataName="status">Status</th>
                <th dataName="email">Email Address</th>
                <th dataName="primary_telephone_number">Telephone</th>
                <th dataName="mobile_telephone_number">Mobile</th>
              </tr>
            </tfoot>
          </table>
          </div>
      </div>
    </section>
    </div>
  </div>
</div>
<!--Add New User -->
<div class="modal fade " id="add_new_user" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog homeModel" role="document">
    <div class="modal-content">
       <div class="modal-header">
      <h2 class="modal-title w-100">Add New User</h2>
      <button type="button" class="close text-right" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">X</span> </button>
</div>
      <div class="modal-body px-3 max_height">
        <div class="row justify-content-xl-center justify-content-lg-center justify-content-md-center">
          <div class="col-xl-12 col-lg-12 col-md-12">
            <form action="{{ route('register-user') }}" method="post" id="addnewuser">
              @csrf
              <div class="form-group">
                <div class="custom-control custom-radio custom-control-inline">
                  <input type="radio" class="custom-control-input" id="role1" name="role" value="system administrator">
                  <label class="custom-control-label" for="role1">System Administrator</label>
                </div>
                <div class="custom-control custom-radio custom-control-inline">
                  <input type="radio" class="custom-control-input" id="role2" name="role" value="ema analyst">
                  <label class="custom-control-label" for="role2">EMA Analyst</label>
                </div>
                <div class="custom-control custom-radio custom-control-inline">
                  <input type="radio" class="custom-control-input" id="role3" name="role" value="ema service support">
                  <label class="custom-control-label" for="role3">EMA Service Support</label>
                </div>
                <span class="role_error d-block"></span>
              </div>
              <div class="form-row">
                <div class="form-group col-lg-6 col-md-6 col-sm-6">
                  <label for="add_name" class="mb-1">Full Name</label>
                  <input type="text" name="name" class="form-control" id="add_name" >
                </div>
                <div class="form-group col-lg-6 col-md-6 col-sm-6">
                  <label for="add_username" class="mb-1">Login Username</label>
                  <input type="text" name="username" class="form-control check-remote" id="add_username" >
                </div>


                <div class="form-group col-lg-6 col-md-6 col-sm-6">
                  <label for="add_email" class="mb-1">Email Address </label>
                  <input type="text" name="email" class="form-control check-remote" id="add_email" >
                </div>

                <div class="form-group col-lg-6 col-md-6 col-sm-6">
                  <label for="add_mobno" class="mb-1">Mobile Number</label>
                  <input type="text" name="mobile_telephone_number" class="form-control phoneregx" id="add_mobno" >
                </div>
                <div class="form-group col-lg-6 col-md-6 col-sm-6">
                  <label for="add_teleno" class="mb-1">Telephone Number</label>
                  <input type="text" name="primary_telephone_number" class="form-control phoneregx" id="add_teleno" >
                </div>


              </div>
              <div class="row text-center mt-3">
                <div class="col-xl-12 col-lg-12 col-md-12">
                  <button class="btn btn-secondary" type="submit" id="add_submit">Save</button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!--Add New User -->
<!--Edit New  User -->
<div class="modal fade " id="edit_user" tabindex="-1" role="dialog" aria-labelledby="edit_userLable" aria-hidden="true">
  <div class="modal-dialog homeModel" role="document">
    <div class="modal-content">
      <div class="modal-header">
      <h2 class="modal-title w-100">Edit User</h2>
      <button type="button" class="close text-right" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">X</span> </button>
    </div>
      <div class="modal-body px-3 max_height">
        <div class="row justify-content-xl-center justify-content-lg-center justify-content-md-center">
          <div class="col-xl-12 col-lg-12 col-md-12">
            <form id="edituser" method="post">
              @csrf
              <input type="hidden" name="edit_id" id="edit_id"/>
              <div class="form-group">
                <div class="custom-control custom-radio custom-control-inline">
                  <input type="radio" class="custom-control-input" id="edit_role1" name="edit_role" value="system administrator">
                  <label class="custom-control-label" for="edit_role1">System Administrator</label>
                </div>
                <div class="custom-control custom-radio custom-control-inline">
                  <input type="radio" class="custom-control-input" id="edit_role2" name="edit_role" value="ema analyst">
                  <label class="custom-control-label" for="edit_role2">EMA Analyst</label>
                </div>
                <div class="custom-control custom-radio custom-control-inline">
                  <input type="radio" class="custom-control-input" id="edit_role3" name="edit_role" value="ema service support">
                  <label class="custom-control-label" for="edit_role3">EMA Service Support</label>
                </div>
              </div>
              <div class="form-row">
                <div class="form-group col-lg-6 col-md-6 col-sm-6">
                  <label for="edit_name" class="mb-1">Full Name</label>
                  <input type="text" name="edit_name" class="form-control" id="edit_name" >
                </div>
                <div class="form-group col-lg-6 col-md-6 col-sm-6">
                  <label for="edit_username" class="mb-1">Login Username</label>
                  <input type="text" name="edit_username" class="form-control check-remote" id="edit_username">
                </div>


                <div class="form-group col-lg-6 col-md-6 col-sm-6">
                  <label for="edit_email" class="mb-1">Email Address</label>
                  <input type="text" name="email" class="form-control check-remote" id="edit_email" >
                </div>
                <div class="form-group col-lg-6 col-md-6 col-sm-6">
                  <label for="edit_mobno" class="mb-1">Mobile Number</label>
                  <input type="text" name="edit_mobno" class="form-control phoneregx" id="edit_mobno" >
                </div>
                <div class="form-group col-lg-6 col-md-6 col-sm-6">
                  <label for="edit_telno" class="mb-1">Telephone Number</label>
                  <input type="text" name="edit_telno" class="form-control phoneregx" id="edit_telno" >
                </div>

              </div>
              <div class="row text-center mt-3">
                <div class="col-xl-12 col-lg-12 col-md-12">
                  <button class="btn btn-secondary" type="submit" id="edit_submit">Update</button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!--Edit New  User -->
<!--Successfully Message -->
<div class="modal fade" id="SuccessfullMessage" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog model-md" role="document">
    <div class="modal-content">
      <button type="button" class="close text-right" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">X</span> </button>
      <div class="modal-body text-center px-5">
        <p class="my-5"><i class="far fa-check-circle mt-5" ></i></p>
        <h2 class="mt-3 mb-5">You have created the successfully.</h2>
      </div>
    </div>
  </div>
</div>

<div id="printThis">
<div class="modal fade " id="view_log_details" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
  <div class="modal-dialog homeModel" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h2 class="modal-title w-100">User Details</h2>
        <button type="button" class="close text-right" data-dismiss="modal" aria-label="Close" id="closeButton"> <span aria-hidden="true">X</span> </button>
      </div>
      <div class="modal-body px-3 max_height">
          <div class="row justify-content-xl-center justify-content-lg-center justify-content-md-center">
            <div class="col-xl-12 col-lg-12 col-md-12">

              <div class="form-row">
                <div class="form-group col-lg-6 col-md-6 col-sm-6 col-12 mb-2">
                  <div class="row">
                    <label for="show_name" class="col-12 pr-0 text-left font-weight-bold mb-0">Full Name:</label>
                    <label id="show_name" class="col-12 text-left"></label>
                  </div>
                </div>
                <div class="form-group col-lg-6 col-md-6 col-sm-6 col-12 mb-2">
                  <div class="row">
                    <label for="show_username" class="col-12 pr-0 text-left font-weight-bold mb-0">Login Username:</label>
                    <label id="show_username" class="col-12 text-left"></label>
                  </div>
                </div>
                <div class="form-group col-lg-6 col-md-6 col-sm-6 col-12 mb-2">
                  <div class="row">
                    <label for="show_email" class="col-12 pr-0 text-left font-weight-bold mb-0">Email Address:</label>
                    <label id="show_email" class="col-12 text-left"></label>
                  </div>
                </div>
                <div class="form-group col-lg-6 col-md-6 col-sm-6 col-12 mb-2">
                  <div class="row">
                    <label for="show_mobno" class="col-12 pr-0 text-left font-weight-bold mb-0" id="">Mobile Number:</label>
                    <label id="show_mobno" class="col-12 text-left"></label>
                  </div>
                </div>
                <div class="form-group col-lg-6 col-md-6 col-sm-6 col-12 mb-2">
                  <div class="row">
                    <label for="show_role" class="col-12 pr-0 text-left font-weight-bold mb-0">User Role:</label>
                    <label id="show_role" class="col-12 text-left"></label>
                  </div>
                </div>
                <div class="form-group col-lg-6 col-md-6 col-sm-6 col-12 mb-2">
                  <div class="row">
                    <label for="show_teleno" class="col-12 pr-0 text-left font-weight-bold mb-0">Telephone Number:</label>
                    <label id="show_teleno" class="col-12 text-left"></label>
                  </div>
                </div>

              </div>
            </div>
          </div>
          <div class="row text-center mt-3">
            <div class="col-xl-12 col-lg-12 col-md-12">
              <button class="btn btn-secondary close_print" data-dismiss="modal" aria-label="Close" id="close">Close</button>
              <button class="btn btn-secondary" id="btnPrint" onclick="javascript:printDiv('printThis')"> Print</button>
            </div>
          </div>
      </div>
    </div>
  </div>
</div>
</div>
@endsection
<!-- Successfully Message -->
@section('jsdependencies')
<script>
var userlist = "{{route('users-list')}}";
var register_user = "{{ route('register-user')}}";
var checkUsernameExists = '{{ url("check-user-exists") }}';
var uniqueuseremail="{{url('/uniqueuseremail')}}";
var uniqueusername="{{url('/uniqueusername')}}";

var base_url = '{{ url('/') }}';

</script>
<script src="{{ asset('js/developer_js/users.js') }}"></script>
@endsection