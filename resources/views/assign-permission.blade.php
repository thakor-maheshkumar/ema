@extends('layouts.app')

@section('title', 'Assign Permission')

@section('content')

<div class="modal-body px-xl-3 px-lg-3 px-md-3 px-sm-3 max_height">
  <form method="post" action="{{ route('assign-permission') }}">
    @csrf
    <div class="row justify-content-xl-center justify-content-lg-center justify-content-md-center">
      <div class="col-xl-12 col-lg-12 col-md-12">

        <div class="form-row">
          <div class="form-group col-lg-12 col-md-12 col-sm-12">
            <label for="treatment_ema_code" class="mb-1">Select Role</label>
            <select class="form-control country-select select_dropdown" id="role_id" name="role_id">
              <option value="">--Select Role--</option>
              @foreach($getRolesData as $getRolesRow)
                <option value="{{ $getRolesRow['id'] }}" {{ ($getRolesRow['id']==$roleId ? 'selected' : '') }}>{{ $getRolesRow['name'] }}</option>
              @endforeach
            </select>
          </div>
          <div class="form-group col-lg-12 col-md-12 col-sm-12">
            <label for="treatment_ema_code" class="mb-1">Select Permission</label>
          </div>

          <div class="form-group col-lg-12 col-md-12 col-sm-12">

            @php
            $i=0;
            @endphp
              @foreach($getAllPermissionData as $getAllPermissionRow)
                @php
                  $i++;
                @endphp
                @if($i==1)
                  <div class="row permission-row">
                @endif
                <div class="form-check-inline">
                  <input type="checkbox" id="" class="permission-checkbox" name="permission_val[]" value="{{ $getAllPermissionRow['id'] }}" @if(in_array($getAllPermissionRow['id'], $getAlreadyAssignPermissionToRole)) checked @endif>
                  <label class="permission-name">{{ $getAllPermissionRow['name'] }}</label><br>
                </div>
                @if($i==4)
                  </div>
                  @php
                    $i=0;
                  @endphp
                @endif
              @endforeach
          </div>
        </div>
      </div>
    </div>
    <div class="row text-center mt-2">
      <div class="col-xl-12 col-lg-12 col-md-12">
        <button type="submit" class="btn btn-secondary text-capitalize mt-2" id="update_roles">Update</button>
    </div>
  </form>
</div>

@endsection
@section('jsdependencies')
<script>
  $('.select_dropdown').select2();
  $('#role_id').on('change',function(){
    var selectedRole = $("#role_id option:selected").val();
    if(selectedRole){
      window.location="/assign-permission/"+selectedRole;
    }
  });

</script>
@endsection

<style>
  .permission-name{
    margin-top: 8px;
    padding: 7px;
    font-size: 15px;
  }
  .permission-row{
    padding-left: 100px;
  }
  .permission-checkbox{
    padding: 12px;
  }
</style>