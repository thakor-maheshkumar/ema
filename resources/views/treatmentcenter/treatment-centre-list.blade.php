@extends('layouts.app')
@section('title', 'Treatment Centres')
@section('content')
<div class="container-fluid">
  <div class="row">
    <div class="col-xl-12 col-lg-12">
      <h1 class="my-2">Treatment Centres</h1>
    </div>
  </div>
</div>
<div class="container-fluid">
  <div class="row">
    <div class="col-xl-12 col-lg-12 px-0">
      <nav aria-label="breadcrumb">
        {{ Breadcrumbs::render('treatment-centre-list') }}
      </nav>
    </div>
  </div>
</div>
<div class="container-fluid">
  @hasanyrole('system administrator|distributor principal')
  <div class="row">
    <div class="col-xl-12 col-lg-12 ">
      <button class="btn btn-secondary" id="addtreatmentcenter">Add New Treatment Centre</button>
    </div>
  </div>
  @endhasanyrole
  <div class="row">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
    <section class="gridMainContainer">
      <div class="col-xl-12 col-lg-12 ">
        <h2 class="my-2 text-capitalize">Treatment centre information</h2>
      </div>
      <div class="col-xl-12 col-lg-12 px-0">
        <div class="table-responsive mt_minus datatable_resize" id="listScroll">
          <table id="treatmence_center_list" class="table table-striped table-bordered example display nowrap" style="width:100%">
          <thead>
              <tr>
                <th>Treatment Centre Code</th>
                <th>Company Name</th>
                <th>Region</th>
                <th>Primary Contact</th>
                <th>Primary Contact Email</th>
                <th>Primary Contact Mobile</th>
                <th>Installed Device</th>
                <th>Status</th>
                <th>Created At</th>
                <th>Actions</th>
              </tr>
          </thead>
          <tfoot style="display: none;">
            <tr>
              <th dataName="treatment_ema_code-datatable">Treatment Centre Code</th>
              <th dataName="full_company_name-datatable">Company Name</th>
              <th dataName="primary_region-datatable">Region</th>
              <th dataName="name_of_primary_contact-datatable">Primary Contact</th>
              <th dataName="email_of_primary_contact-datatable">Primary Contact Email</th>
              <th dataName="mobile_number_of_primary_contact-datatable">Primary Contact Mobile</th>
              <th dataName="install_device-datatable">Installed Device</th>
              <th dataName="status-datatable">Status</th>
              <th dataName="created_at">Created At</th>
            </tr>
        </tfoot>
          </table>
        </div>
      </div>
    </section>
  </div>
  </div>
</div>
<input type="hidden" id="treatment_center_id" value="">
<input type="hidden" id="hydracool_srp_id" value="">
<input type="hidden" id="is_ema_value" value="">


<!--Add New Treatment Centre -->
  <div class="modal fade " id="add_new_treatment" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
  <div class="modal-dialog bigModel" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h2 class="modal-title w-100" id="treatment_center_title">Add New Treatment Centre</h2>
        <button type="button" class="close text-right" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">X</span> </button>
      </div>
      <div class="modal-body px-xl-3 px-lg-3 px-md-3 px-sm-3 max_height">
        <form id="add_treatment_center">
          <div class="row">
            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4">
              <div class="col-lg-12 col-md-12 col-sm-12 mb-1 p-0">
                <label for="name" class="mb-1 border-bottom w-100 pb-2 font-weight-bold">Treatment Centre Details</label>
              </div>
              <div class="form-group">
                <label for="full_company_name" class="mb-1">Treatment Centre Name</label>
                <input type="text" class="form-control" id="full_company_name" name="full_company_name">
              </div>
              <div class="form-group">
                <label for="abbreviated_company_name" class="mb-1">Abbreviated Company Name</label>
                <input type="text" class="form-control" id="abbreviated_company_name" name="abbreviated_company_name">
              </div>
              <div class="form-group">
                <label for="treatment_ema_code" class="mb-1">Treatment Centre Code</label>
                <input type="text" class="form-control check-remote" id="treatment_ema_code" name="treatment_ema_code">
              </div>
              <div class="form-group">
                <div class="custom-control custom-checkbox mb-3">
                  <input type="checkbox" class="custom-control-input" id="is_group" name="is_group">
                  <label class="custom-control-label" for="is_group">Is Distributor part of a Group?</label>
                </div>
                <label for="group_name" class="mb-1">Group Name</label>
                <input type="text" disabled class="form-control" id="group_name" name="group_name">
              </div>
              <div class="form-group">
                <label for="web_site" class="mb-1">Web Site</label>
                <input type="text" class="form-control" id="web_site" name="web_site">
              </div>
              <div class="form-group">
                <label for="sel1" class="mb-1">Distributor Company Name</label>
                @php
                $getDistributorCompanyData = getDistributorCompanyDataById();
                @endphp
                @role('distributor principal')
                @foreach($getDistributorData as $getDistributorRow)
                @if($getDistributorRow['id'] == $getDistributorCompanyData->distributor_company_id)
                <label class="float-left w-100">{{ $getDistributorRow['full_company_name'] ." - ".$getDistributorRow['distributor_code'] }}</label>
                <input type="hidden" value="{{ $getDistributorRow['id'] }}"  id="distributors" name="distributors">
                @endif
                @endforeach
                @else
                <select class="form-control select_dropdown" id="distributors" name="distributors">
                  <option value="">--Select Distributor--</option>
                    @foreach($getDistributorData as $getDistributorRow)
                      <option value="{{ $getDistributorRow['id'] }}">{{ $getDistributorRow['full_company_name'] ." - ".$getDistributorRow['distributor_code'] }}</option>
                    @endforeach
                </select>
                @endrole </div>
              <div class="form-group">
                <div class="custom-control custom-checkbox">
                  <input type="checkbox" class="custom-control-input" id="is_ema" name="is_ema">
                  <label class="custom-control-label" for="is_ema">Is EMA?</label>
                </div>
              </div>
            </div>
            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4">
              <div class="col-lg-12 col-md-12 col-sm-12 mb-1 p-0">
                <label for="name" class="mb-1 border-bottom w-100 pb-2 font-weight-bold">Address Details</label>
              </div>
              <div class="form-group">
                <label for="building_name" class="mb-1">Building Name</label>
                <input type="text" class="form-control" id="building_name" name="building_name">
              </div>
              <div class="form-group">
                <label for="address1" class="mb-1">Address Line 1</label>
                <input type="text" class="form-control" id="address_1" name="address_1">
              </div>
              <div class="form-group">
                <label for="address2" class="mb-1">Address Line 2</label>
                <input type="text" class="form-control" id="address_2" name="address_2">
              </div>
              <div class="form-group">
                <label for="address3" class="mb-1">Address Line 3</label>
                <input type="text" class="form-control" id="address_3" name="address_3">
              </div>
              <div class="form-group">
                <label for="full_address" class="mb-1">Address Line 4</label>
                <input type="text" class="form-control" id="full_address" name="full_address">
              </div>
              <div class="form-group">
                <label for="state" class="mb-1">State/County</label>
                <input type="text" class="form-control" id="state" name="state">
              </div>
              <div class="form-group">
                <label for="zipcode" class="mb-1">Post/Zip code</label>
                <input type="text" class="form-control" id="zipcode" name="zipcode">
              </div>
              <div class="form-group">
                <label for="country_id" class="mb-1">Country</label>
                <select class="form-control country-select select_dropdown" id="country_id" name="country_id">
                  <option value="">--Select Country--</option>


                    @foreach($getCountryData as $getCountryRow)


                  <option value="{{ $getCountryRow['id'] }}">{{ $getCountryRow['name'] }}</option>


                    @endforeach


                </select>
              </div>
            </div>
           <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4">
              <div class="col-lg-12 col-md-12 col-sm-12 mb-1 p-0">
                <label for="name" class="mb-1 border-bottom w-100 pb-2 font-weight-bold">Primary Contact Details</label>
              </div>
              <div class="form-group">
                  <label for="name_of_primary_contact" class="mb-1">Name</label>
                  <input type="text" class="form-control" id="name_of_primary_contact" name="name_of_primary_contact">
                </div>
                <div class="form-group">
                  <label for="position" class="mb-1">Position</label>
                  <input type="text" class="form-control" id="position" name="position">
                </div>
                <div class="form-group">
                  <label for="telephone_number_of_primary_contact" class="mb-1">Telephone Number</label>
                  <input type="text" class="form-control phoneregx" id="telephone_number_of_primary_contact" name="telephone_number_of_primary_contact">
                </div>
                <div class="form-group">
                  <label for="mobile_number_of_primary_contact" class="mb-1">Mobile Number</label>
                  <input type="text" class="form-control phoneregx" id="mobile_number_of_primary_contact" name="mobile_number_of_primary_contact">
                </div>
                <div class="form-group">
                  <label for="fax_number" class="mb-1">Fax Number </label>
                  <input type="text" class="form-control phoneregx" id="fax_number" name="fax_number">
                </div>
              <div class="form-group">
                  <label for="email_of_primary_contact" class="mb-1">Email Address</label>
                  <input type="text" class="form-control" id="email_of_primary_contact" name="email_of_primary_contact">
                </div>

            </div>
          </div>
          <div class="row text-center mt-2">
            <div class="col-xl-12 col-lg-12 col-md-12">
              <button type="submit" class="btn btn-secondary text-capitalize mt-2" id="save_treatment_center">Save</button>
              <button type="submit" class="btn btn-secondary text-capitalize mt-2" id="update_treatment_center" style="display:none">Update</button>
              <a href="javascript:;" class="btn btn-secondary text-capitalize mt-2 mt-lg-2 mt-md-2 add_new_tcp disabled" id="">Add Treatment Centre Principal</a>
             </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<!--Add New Treatment Centre -->

<!--Add New Hydracool -->
  <div class="modal fade " id="add_new_hydracool" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
  <div class="modal-dialog homeModel" role="document">
    <div class="modal-content">
      <div class="modal-header">
      <h2 class="modal-title w-100">Add HydraCool SRP Device</h2>
      <button type="button" class="close text-right" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">X</span> </button>
    </div>
      <div class="modal-body px-xl-3 px-lg-3 px-md-3 px-sm-3 max_height">
        <form id="hydracool_srp_form">
          <div class="row justify-content-xl-center justify-content-lg-center justify-content-md-center">
            <div class="col-xl-12 col-lg-12 col-md-12">
              <div class="form-row">
                <div class="form-group col-lg-6 col-md-6 col-sm-6">
                  <label for="added_treatment_center" class="mb-1">Treatment Centre</label>
                  <input type="text" disabled class="form-control checkhandsetunique" id="added_treatment_center" name="added_treatment_center">
                </div>
                <div class="form-group col-lg-6 col-md-6 col-sm-6" style="display:none" id="non_ema_area">
                  <label for="srp_serial_number_non_ema_list" class="mb-1">HydraCool SRP Serial Number</label>
                  <select class="form-control" id="srp_serial_number_non_ema_list" name="srp_serial_number_non_ema_list">
                    <option value="">Select OR Enter Serial number</option>
                    @if(!empty($getHydraCoolSrpData))
                        @foreach($getHydraCoolSrpData as $hydraCoolSrprow)
                    <option value="{{ $hydraCoolSrprow['hydracoolsrp_id'] }}">{{ $hydraCoolSrprow['serial_number'] }}</option>
                        @endforeach
                    @endif
                  </select>
                </div>
                <div class="form-group col-lg-6 col-md-6 col-sm-6" id="ema_area">
                  <label for="srp_serial_number" class="mb-1">HydraCool SRP Serial Number</label>
                  <input type="text" class="form-control " id="srp_serial_number" name="srp_serial_number">
                </div>
                <div class="form-group col-lg-6 col-md-6 col-sm-6">
                  <label for="Hub_Board_unit" class="mb-1">Hub-Board Serial Number</label>
                  <input type="text" class="form-control unit_name checkhandsetunique check-remote" id="Hub_Board_unit" name="Hub_Board_unit" uniqueHandset="device">
                </div>
                <div class="form-group col-lg-6 col-md-6 col-sm-6">
                  <label for="AquaB_unit" class="mb-1">AquaB Serial Number</label>
                  <input type="text" class="form-control unit_name checkhandsetunique check-remote" id="AquaB_unit" name="AquaB_unit" uniqueHandset="device">
                </div>
                <div class="form-group col-lg-6 col-md-6 col-sm-6">
                  <label for="VibroX_unit" class="mb-1">Vibrox Serial Number</label>
                  <input type="text" class="form-control unit_name checkhandsetunique check-remote" id="VibroX_unit" name="VibroX_unit" uniqueHandset="device">
                </div>
                <div class="form-group col-lg-6 col-md-6 col-sm-6">
                  <label for="MicroT_unit" class="mb-1">MicroT Serial Number</label>
                  <input type="text" class="form-control unit_name checkhandsetunique" id="MicroT_unit" name="MicroT_unit" uniqueHandset="device">
                </div>
                <div class="form-group col-lg-6 col-md-6 col-sm-6">
                  <label for="Collagen_unit" class="mb-1">Collagen+ Serial Number</label>
                  <input type="text" class="form-control unit_name checkhandsetunique check-remote" id="Collagen_unit" name="Collagen_unit" uniqueHandset="device">
                </div>

                <div class="form-group col-lg-6 col-md-6 col-sm-6">
                  <label for="UltraB_unit" class="mb-1">UltraB Serial Number</label>
                  <input type="text" class="form-control unit_name checkhandsetunique check-remote" id="UltraB_unit" name="UltraB_unit" uniqueHandset="device">
                </div>

                <div class="form-group col-lg-6 col-md-6 col-sm-6">
                  <label for="manufacturer_name" class="mb-1">Manufacturer Name</label>
                  <input type="text" class="form-control" id="manufacturer_name" name="manufacturer_name">
                </div>

                <div class="form-group col-lg-6 col-md-6 col-sm-6 ">
                  <label for="manufacturing_date" class="mb-1">Manufacturing Date</label>
                  <div class="treatmentcentre_datepicker date input-group p-0 shadow-sm">
                      <input type="text" class="form-control px-4 hydracooldate" id="manufacturing_date" name="manufacturing_date" readonly>
                      <div class="input-group-append"><span class="input-group-text px-4"><i class="fa fa-calendar-alt"></i></span></div>
                  </div>
                  <div id="manufacturing_date_error"> </div>
                </div>


                <div class="form-group col-lg-6 col-md-6 col-sm-6 ">
                  <label for="sale_date" class="mb-1">Date of Sale</label>
                  <div class="treatmentcentre_datepicker date input-group p-0 shadow-sm">
                      <input type="text" class="form-control px-4 hydracooldate" id="sale_date" name="sale_date" readonly>
                      <div class="input-group-append"><span class="input-group-text px-4"><i class="fa fa-calendar-alt"></i></span></div>
                  </div>
                  <div id="sale_date_error"> </div>
                </div>


                <div class="form-group col-lg-6 col-md-6 col-sm-6">
                  <div class="custom-control custom-checkbox" id="demo_area">
                    <input type="checkbox" class="custom-control-input" id="is_demo" name="is_demo">
                    <label class="custom-control-label" for="is_demo">Is Demo?</label>
                  </div>
                </div>

              </div>
            </div>
          </div>
          <input type="hidden" id="handset_id">
          <div class="row text-center">
            <div class="col-xl-12 col-lg-12 col-md-12"> <a href="javascript:;" style="display:none" class="btn btn-secondary  text-capitalize mt-2 add_other_hydracool_srp_unit" id="add_other_hydracool_srp_unit">Add Another HydraCool SRP Device</a>
              <button type="submit"  class="btn btn-secondary text-capitalize mt-2" id="save_hydracool_srp_units">Save</button>

             </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<!--Add New Hydracool -->

<!--Add New Treatment Centre Principal -->
<div class="modal fade " id="add_new_tcp" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
  <div class="modal-dialog homeModel" role="document">
    <div class="modal-content">
      <div class="modal-header">
      <h2 class="modal-title w-100">Add New Treatment Centre Principal</h2>
      <button type="button" class="close text-right" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">X</span> </button>
    </div>
      <div class="modal-body px-xl-3 px-lg-3 px-md-3 px-sm-3 max_height">
        <form id="principal_form">
          <div class="row justify-content-xl-center justify-content-lg-center justify-content-md-center">
            <div class="col-xl-12 col-lg-12 col-md-12">
              <div class="form-row">
                <div class="form-group col-lg-6 col-md-6 col-sm-6">
                  <label for="name" class="mb-1">Full Name</label>
                  <input type="text" class="form-control" id="name" name="name">
                </div>
                <div class="form-group col-lg-6 col-md-6 col-sm-6">
                  <label for="username" class="mb-1">Login Username</label>
                  <input type="text" class="form-control check-remote" id="username" name="username">
                </div>
                <div class="form-group col-lg-6 col-md-6 col-sm-6">
                  <label for="added_treatment_center_second" class="mb-1">Treatment Centre Name</label>
                  <input type="text" disabled class="form-control" id="added_treatment_center_second" name="">
                </div>
                <div class="form-group col-lg-6 col-md-6 col-sm-6">
                  <label for="email" class="mb-1">Email Address</label>
                  <input type="text" class="form-control check-remote" id="email" name="email">
                </div>
                <div class="form-group col-lg-6 col-md-6 col-sm-6">
                  <label for="primary_telephone_number" class="mb-1">Telephone Number</label>
                  <input type="text" class="form-control phoneregx" id="primary_telephone_number" name="primary_telephone_number">
                </div>
                <div class="form-group col-lg-6 col-md-6 col-sm-6">
                  <label for="mobile_telephone_number" class="mb-1">Mobile Number</label>
                  <input type="text" class="form-control phoneregx" id="mobile_telephone_number" name="mobile_telephone_number">
                </div>
              </div>
            </div>
          </div>
          <div class="row text-center mt-2">
            <div class="col-xl-12 col-lg-12 col-md-12">
              <button type="submit" class="btn btn-secondary text-capitalize mt-2" id="save_treatment_center_principal">save</button>
              <a class="btn btn-secondary text-capitalize mt-2 text_white addnewhydracoolsrp"  id="addnewhydracoolsrp">Add HydraCool SRP Device</a>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!--Add New Treatment Centre Principal -->

<!--Successfully Message -->
<div class="modal fade" id="SuccessfullMessage" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
  <div class="modal-dialog model-md" role="document">
    <div class="modal-content">
     <button type="button" class="close text-right" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">X</span> </button>
      <div class="modal-body text-center px-5">
        <p class="my-5"><i class="far fa-check-circle mt-5" ></i></p>
        <h2 class="mt-3 mb-5" id="succeess_msg">You have created the successfully.</h2>
      </div>
    </div>
  </div>
</div>
<!-- Successfully Message -->



@endsection

@section('jsdependencies')
  <script>
    var addcenter = '{{ url("add-treatment-centre") }}';
    var treatmentcenterlist = '{{ url("treatment-centre-list") }}';
    var checkvalueExists = '{{ url("check-value-exists") }}';
    var addhydracoolsrp = '{{ url("add-hydracoolsrp") }}';
    var addhydracoolsrpnonema = '{{ url("add-non-ema-hydracoolsrp") }}';
    var checkserialnumber = '{{ url("check-serialnumber") }}';
    var addprincipal = '{{ url("add-treatmentcentre-user") }}';
    var gethydracoolSrpUnitslist = '{{ url("get-srpunit-by-serialnumber") }}';
    var treatmentcenterdetails = '{{ url("get-treatment-centre-details") }}';
    var updatecenter = '{{ url("update-treatment-centre") }}';
    var deletecenter = '{{ url("delete-treatment-centre") }}';
    var suspendcenter = '{{ url("suspend-treatment-centre") }}';
    var releasecenter = '{{ url("release-treatment-centre") }}';
    var isDetails = 0;
    var checkhandsetunique = '{{ url("check-handsetValueUnique") }}';
    var uniquecentercode  ="{{url('unique-treamentcenter-code')}}";
    var uniqueusername="{{url('/uniqueusername')}}";
    var uniqueuseremail="{{url('/uniqueuseremail')}}";
    var uniqueserialnumber="{{url('/uniqueserialnumber')}}";

    @role('distributor principal')
    var roleName = 'distributor principal';
    @else
    var roleName = '';
    @endrole
  </script>
<script src="{{ asset('js/developer_js/treatmentcenter.js') }}"></script>
@endsection