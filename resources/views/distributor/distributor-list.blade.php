@extends('layouts.app')
@section('title', 'Distributor')
@section('content')
<div class="container-fluid">
  <div class="row">
    <div class="col-xl-12 col-lg-12">
      <h1 class="my-2">Distributors</h1>
    </div>
  </div>
</div>
<div class="container-fluid">
  <div class="row">
    <div class="col-xl-12 col-lg-12 px-0">
      <nav aria-label="breadcrumb">
        {{ Breadcrumbs::render('distributor') }}
      </nav>
    </div>
  </div>
</div>
<div class="container-fluid">
  @hasanyrole('system administrator')
  <div class="row">
    <div class="col-xl-12 col-lg-12 ">
      <button class="btn btn-secondary" data-toggle="modal" data-target="#add_distributor"
      id="adddistributordisabled">Add New Distributor</button>
    </div>
  </div>
  @endhasanyrole
  <div class="row">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
      <section class="gridMainContainer">
        <div class="col-xl-12 col-lg-12 ">
          <h2 class="my-2 text-capitalize">Distributors information</h2>
        </div>
        <div class="col-xl-12 col-lg-12 px-0">
          <div class="table-responsive mt_minus datatable_resize" id="listScroll">
            <table id="distributor_list" class="table table-striped table-bordered example" style="width:100%">
              <thead>
                <tr>
                  <th>Distributor Code</th>
                  <th>Company Name</th>
                  <th>Region</th>
                  <th>Primary Contact</th>
                  <th>Primary Contact email</th>
                  <th>Primary Contact Mobile</th>
                  <th>Number of Treatment Centres</th>
                  <th>Number of Devices</th>
                  <th>Status</th>
                  <th>Created Date</th>
                  <th>Actions</th>
                </tr>
              </thead>
               <tfoot style="display:none">
                      <tr>
                        <th dataName="distributor_code">Distributor Code</th>
                        <th dataName="full_company_name">Company Name</th>
                        <th dataName="primary_region">Region</th>
                        <th dataName="name_of_primary_contact">Primary Contact</th>
                        <th dataName="email_of_primary_contact">Primary Contact email</th>
                        <th dataName="mobile_number_of_primary_contact">Primary Contact Mobile</th>
                        <th dataName="total_treatmentcetre">Number of Treatment Centres</th>
                        <th dataName="total_install_device">Number of Devices</th>
                        <th dataName="status">Status</th>
                      </tr>
                </tfoot>
            </table>
          </div>
        </div>
      </section>
    </div>
  </div>
</div>
<!--Add New Distributor -->
<div class="modal fade " id="add_distributor" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog bigModel" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h2 class="modal-title w-100 ">Add New Distributor</h2>
        <button type="button" class="close text-right" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">X</span> </button>
      </div>
      <div class="modal-body px-xl-3 px-lg-3 px-md-3 px-sm-3 max_height">
        <div class="row justify-content-xl-center justify-content-lg-center justify-content-md-center">
          <div class="col-xl-12 col-lg-12 col-md-12">
            <form method="post" action="javascript:void(0)" id="add_form">
              <div class="row">
                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 ">
                <div class="col-lg-12 col-md-12 col-sm-12 mb-1 p-0"><label for="name" class="mb-1 border-bottom w-100 pb-2 font-weight-bold">Distributor Details</label></div>
                    <div class="form-group">
                      <label for="exampleInputEmail1" class="mb-1">Distributor Company Name</label>
                      <input type="text" class="form-control distinctphones" id="full_company_name" name="full_company_name">
                    </div>


                    <div class="form-group">
                  <label for="exampleInputEmail2" class="mb-1">Abbreviated Company Name</label>
                  <input type="text" class="form-control left" id="abbreviated_company_name" name="abbreviated_company_name">
                </div>
                <div class="form-group">
                  <label for="exampleInputEmail1" class="mb-1">Distributor Code</label>
                  <input type="text" class="form-control distinctphones check-remote" id="distributor_code"
                  name="distributor_code">
                </div>
                  <div class="form-group">
                  <div class="custom-control custom-checkbox mb-3">
                    <input type="checkbox" class="custom-control-input" id="is_group" name="is_group">
                    <label class="custom-control-label" for="is_group">Is Distributor part of a Group?</label>
                  </div>
                  <label for="group_name" class="mb-1">Group Name</label>
                  <input type="text" disabled class="form-control group_name" id="group_name" name="group_name">
                </div>
                <div class="form-group">
                  <label for="exampleInputEmail2" class="mb-1">Web Site</label>
                  <input type="text" class="form-control" id="web_site" name="web_site">
                </div>


                </div>
                <div class="col-col-xl-4 col-lg-4 col-md-4 col-sm-4 ">
                  <div class="col-lg-12 col-md-12 col-sm-12 mb-1 p-0"><label for="name" class="mb-1 border-bottom w-100 pb-2 font-weight-bold">Address Details</label></div>
                    <div class="form-group ">
                      <label for="exampleInputEmail2" class="mb-1">Building Name</label>
                      <input type="text" class="form-control left" id="building_name" name="building_name">
                    </div>
                     <div class="form-group ">
                  <label for="exampleInputEmail2" class="mb-1">Address Line 1</label>
                  <input type="text" class="form-control left" id="address1" name="address1">
                </div>
                <div class="form-group ">
                  <label for="exampleInputEmail2" class="mb-1">Address Line 2</label>
                  <input type="text" class="form-control left" id="address2" name="address2">
                </div>
                <div class="form-group ">
                  <label for="exampleInputEmail2" class="mb-1">Address Line 3</label>
                  <input type="text" class="form-control left" id="address3" name="address3">
                </div>
                <div class="form-group">
                  <label for="exampleInputEmail2" class="mb-1">Address Line 4</label>
                  <input type="text" class="form-control left" id="full_address" name="full_address">
                </div>
                <div class="form-group">
                  <label for="exampleInputEmail2" class="mb-1">State/County</label>
                  <input type="text" class="form-control left" id="state" name="state">
                </div>
                 <div class="form-group">
                  <label for="exampleInputEmail2" class="mb-1">Post/Zip code</label>
                  <input type="text" class="form-control left" id="zipcode" name="zipcode">
                </div>
                <div class="form-group">
                  <label for="exampleInputEmail2" class="mb-1">Country</label>
                  <select class="form-control country-select select_dropdown" name="country_id" id="country_id">
                    <option value="">--Select a Country--</option>
                    @foreach($country as $countrys)
                    <option value="{{$countrys->id}}">{{$countrys->name}}</option>
                    @endforeach
                  </select>
                </div>
                </div>
                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 ">
                  <div class="col-lg-12 col-md-12 col-sm-12 mb-1 p-0"><label for="name" class="mb-1 border-bottom w-100 pb-2 font-weight-bold">Primary Contact Details</label></div>
                    <div class="form-group">
                      <label for="exampleInputEmail2" class="mb-1">Name</label>
                      <input type="text" class="form-control" id="name_of_primary_contact" name="name_of_primary_contact">
                  </div>
                  <div class="form-group">
                  <label for="exampleInputEmail2" class="mb-1">Position</label>
                  <input type="text" class="form-control left" id="position" name="position">
                </div>
                <div class="form-group">
                  <label for="exampleInputEmail2" class="mb-1">Telephone Number</label>
                  <input type="text" class="form-control phoneregx" id="telephone_number_of_primary_contact" name="telephone_number_of_primary_contact">
                </div>
                <div class="form-group">
                  <label for="exampleInputEmail2" class="mb-1">Mobile Number</label>
                  <input type="text" class="form-control phoneregx" id="mobile_number_of_primary_contact" name="mobile_number_of_primary_contact">
                </div>
                <div class="form-group">
                  <label for="exampleInputEmail2" class="mb-1">Fax Number</label>
                  <input type="text" class="form-control phoneregx" id="fax_number" name="fax_number">
                </div>
                <div class="form-group">
                  <label for="exampleInputEmail2" class="mb-1">Email Address</label>
                  <input type="text" class="form-control" id="email_of_primary_contact" name="email_of_primary_contact">
                </div>
                </div>
              </div>
              <div class="row text-center mt-2">
                <div class="col-xl-12 col-lg-12 col-md-12">
                  <button class="btn btn-secondary" data-toggle="modal" data-target="#SuccessfullMessage" id="popUpClose">Save</button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!--Add New Distributor -->
<!--Edit Distributor -->
<div class="modal fade " id="edit_distributor_detail2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog bigModel" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h2 class="modal-title w-100">Edit Distributor</h2>
        <button type="button" class="close text-right" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">X</span> </button>
      </div>
      <div class="modal-body px-xl-3 px-lg-3 px-md-3 px-sm-3 max_height">
        <div class="row justify-content-xl-center justify-content-lg-center justify-content-md-center">
          <div class="col-xl-12 col-lg-12 col-md-12">
            <form id="update_form" action="#" method="post">
              @csrf
              <div class="row">
                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4">
                  <div class="col-lg-12 col-md-12 col-sm-12 mb-1 p-0">
                    <label for="name" class="mb-1 border-bottom w-100 pb-2 font-weight-bold">Distributor Details</label>
                  </div>
                  <div class="form-group">
                  <label for="exampleInputEmail1" class="mb-1">Distributor Company Name</label>
                  <input type="text" class="form-control" id="full_company_nameEdit" name="full_company_name">
                </div>
                <div class="form-group">
                  <label for="exampleInputEmail2" class="mb-1">Abbreviated Company Name</label>
                  <input type="text" class="form-control" id="abbreviated_company_nameEdit" name="abbreviated_company_name">
                </div>
                <div class="form-group">
                  <label for="exampleInputEmail1" class="mb-1">Distributor Code</label>
                  <input type="text" class="form-control check-remote" id="distributor_codeEdit" name="distributor_code">
                  <input type="hidden" name="id" id="id1">
                </div>
                <div class="form-group">
                  <div class="custom-control custom-checkbox mb-3">
                    <input type="checkbox" class="custom-control-input" id="is_group1" name="is_group">
                    <label class="custom-control-label" for="is_group1">Is Distributor part of a Group?</label>
                  </div>
                  <label for="group_name" class="mb-1">Group Name</label>
                  <input type="text" class="form-control group_name" id="group_nameOne" name="group_name">
                </div>
                <div class="form-group">
                  <label for="exampleInputEmail2" class="mb-1">Web Site</label>
                  <input type="text" class="form-control" id="web_siteEdit" name="web_site">
                </div>

                </div>
                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4">
                  <div class="col-lg-12 col-md-12 col-sm-12 mb-1 p-0">
                    <label for="name" class="mb-1 border-bottom w-100 pb-2 font-weight-bold">Address Details</label>
                  </div>
                  <div class="form-group">
                  <label for="exampleInputEmail2" class="mb-1">Building Name</label>
                  <input type="text" class="form-control left" id="building_nameEdit" name="building_name">
                </div>
                  <div class="form-group">
                  <label for="exampleInputEmail2" class="mb-1">Address Line 1</label>
                  <input type="text" class="form-control left" id="address1Edit" name="address1">
                </div>
                <div class="form-group">
                  <label for="exampleInputEmail2" class="mb-1">Address Line 2</label>
                  <input type="text" class="form-control left" id="address2Edit" name="address2">
                </div>
                <div class="form-group">
                  <label for="exampleInputEmail2" class="mb-1">Address Line 3</label>
                  <input type="text" class="form-control left" id="address3Edit" name="address3">
                </div>
                <div class="form-group">
                  <label for="exampleInputEmail2" class="mb-1">Address Line 4</label>
                  <input type="text" class="form-control" id="full_addressEdit" name="full_address">
                </div>
                <div class="form-group">
                  <label for="exampleInputEmail2" class="mb-1">State/County</label>
                  <input type="text" class="form-control left" id="stateEdit" name="state">
                </div>
                <div class="form-group">
                  <label for="exampleInputEmail2" class="mb-1">Post/Zipcode</label>
                  <input type="text" class="form-control left" id="zipcodeEdit" name="zipcode">
                </div>
                <div class="form-group">
                  <label for="exampleInputEmail2" class="mb-1">Country</label>
                  <select class="form-control country-select select_dropdown" name="country_id" id="country_idEdit" style="width: 100%">

                  @foreach($country as $countrys)

                    <option value="{{$countrys->id}}">{{$countrys->name}}</option>

                  @endforeach

                  </select>
                </div>

                </div>
                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4">
                  <div class="col-lg-12 col-md-12 col-sm-12 mb-1 p-0">
                    <label for="name" class="mb-1 border-bottom w-100 pb-2 font-weight-bold">Primary Contact Details</label>
                  </div>
                  <div class="form-group">
                  <label for="exampleInputEmail2" class="mb-1">Name</label>
                  <input type="text" class="form-control" id="name_of_primary_contactEdit" name="name_of_primary_contact">
                </div>
                <div class="form-group">
                  <label for="exampleInputEmail2" class="mb-1">Position</label>
                  <input type="text" class="form-control left" id="positionEdit" name="position">
                </div>
                <div class="form-group">
                  <label for="exampleInputEmail2" class="mb-1">Telephone Number</label>
                  <input type="text" class="form-control phoneregx" id="telephone_number_of_primary_contactEdit" name="telephone_number_of_primary_contact">
                </div>
                <div class="form-group">
                  <label for="exampleInputEmail2" class="mb-1">Mobile Number</label>
                  <input type="text" class="form-control phoneregx" id="mobile_number_of_primary_contactEdit" name="mobile_number_of_primary_contact">
                </div>
                <div class="form-group">
                  <label for="exampleInputEmail2" class="mb-1">Fax Number </label>
                  <input type="text" class="form-control phoneregx" id="fax_numberEdit" name="fax_number">
                </div>
                <div class="form-group">
                  <label for="exampleInputEmail2" class="mb-1">Email Address</label>
                  <input type="text" class="form-control" id="email_of_primary_contactEdit" name="email_of_primary_contact">
                </div>

                </div>
              </div>
              <div class="row text-center mt-2">
                <div class="col-xl-12 col-lg-12 col-md-12">
                  <button class="btn btn-secondary" id="submit">Update</button>
                </div>
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
<script>
  var add_distributor="{{url('add-distributor')}}";
  var list_distributor="{{url('distributor')}}";
  var update_distributor="{{url('update-distributor')}}";
  var list_distributor_user="{{url('view-distributor-user')}}";
  var uniqueuseremail="{{url('/uniqueuseremail')}}";
  var uniqueusername="{{url('/uniqueusername')}}";
  var distributors_treatmentcenter_list="{{url('distributors-treatmentcenter-list')}}";
  var distributor_id="0";
  var uniqueDistributorCode="{{url('uniqueDistributorCode')}}";
  var base_url = '{{ url('/') }}';
  var isDetails=0;

</script>
<script src="{{ asset('js/developer_js/distributor.js') }}"></script>
<script src="{{ asset('js/developer_js/disributorValidation.js')}}"></script>
@endsection