@extends('layouts.app')
@section('title', 'Devices')
@section('content')
<div class="container-fluid">
  <div class="row">
    <div class="col-xl-12 col-lg-12">
      <h1 class="my-2">Devices</h1>
    </div>
  </div>
</div>
<div class="container-fluid">
  <div class="row">
    <div class="col-xl-12 col-lg-12 px-0">
      <nav aria-label="breadcrumb">
        {{ Breadcrumbs::render('devices') }}
      </nav>
    </div>
  </div>
</div>
<div class="container-fluid">
  @hasanyrole('system administrator|ema service support|distributor principal')
  <div class="row">
    <div class="col-xl-12 col-lg-12 ">
      <button class="btn btn-secondary" id="add_new_hydracool">Add New Device</button>
    </div>
  </div>
  @endhasanyrole

  <div class="row">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
    <section class="gridMainContainer">
      <div class="col-xl-12 col-lg-12 ">
        <h2 class="my-2 text-capitalize">HydraCool SRP Device</h2>
      </div>
      <div class="col-xl-12 col-lg-12 px-0">
        <div class="table-responsive mt_minus datatable_resize" id="hydracoolScroll">
          <table id="hydracool_srp_list_device" class="table table-striped table-bordered example" style="width:100%">
            <thead>
              <tr>
                <th>Treatment Centre Name</th>
                <th>Serial Number</th>
                <th>Last Active/Upload</th>
                <th>Installed Devices</th>
                <th>Last 7 Days Treatments</th>
                <th>Last 30  Days Treatments</th>
                <th>Last 90 Days Treatments</th>
                <th>Last 12 Months Treatments</th>
                <th>Total Treatments</th>
                <th>Created Date</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tfoot style="display:none;">
              <tr>
                <th dataName="full_company_name-datatable">Treatment Centre Name</th>
                <th dataName="serial_number-datatable">Serial Number</th>
                <th dataName="last_active-datatable">Last Active/Upload</th>
                <th dataName="install_device-datatable">Installed Devices</th>
                <th dataName="last_seven-datatable">Last 7 Days Treatments</th>
                <th dataName="last_30_days-datatable">Last 30  Days Treatments</th>
                <th dataName="last_90_days-datatable">Last 90 Days Treatments</th>
                <th dataName="last_12_month-datatable">Last 12 Months Treatments</th>
                <th dataName="total_treatment-datatable">Total Treatments</th>
              </tr>
            </tfoot>
          </table>
        </div>
      </div>
    </section>
  </div>
  </div>
</div>

<!--Add New Hydracool -->
<div class="modal fade " id="add_new_hydracool_form" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog homeModel" role="document">
    <div class="modal-content">
       <div class="modal-header">
      <h2 class="modal-title w-100 " id="add_new">Add HydraCool SRP Device</h2>
      <button type="button" class="close text-right" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">X</span> </button>
    </div>
      <div class="modal-body px-xl-3 px-lg-3 px-md-3 px-sm-3 max_height">
        <form id="hydracool_srp_form">
          <input type="hidden" id="is_non_ema">
          <div class="form-row">
            <div class="form-group col-lg-6 col-md-6 col-sm-6">
                <label for="sel1" class="mb-1">Treatment Centre</label>
                <select class="form-control country-select select_dropdown" id="treatmentcentre_id" name="treatmentcentre_id">
                  <option value="">--Select Treatment Centre--</option>
                  @foreach($getTreatmentCenterList as $getTreatmentCenterRow)
                    <option value="{{ $getTreatmentCenterRow['id'] }}" data-isEMA ="{{ $getTreatmentCenterRow['is_ema'] }}" >{{ $getTreatmentCenterRow['full_company_name'] ." - ".$getTreatmentCenterRow['treatment_ema_code'] }}</option>
                  @endforeach
                </select>
              </div>

              <div class="form-group col-lg-6 col-md-6 col-sm-6" id="update_serial_number" style="display: none">
                <label for="srp_serial_number" class="mb-1">HydraCool SRP Serial Number</label>

                <input type="text" class="form-control check-remote" id="srp_serial_number" name="srp_serial_number">
              </div>

              <div class="form-group col-lg-6 col-md-6 col-sm-6" id="add_serial_number">
                <label for="srp_serial_number_non_ema" class="mb-1">HydraCool SRP Serial Number</label>
                <select class="form-control" id="srp_serial_number_non_ema" name="srp_serial_number_non_ema">
                  <option value="">Select OR Enter Serial number</option>
                  @if(!empty($getHydraCoolSrpData))
                      @foreach($getHydraCoolSrpData as $hydraCoolSrprow)
                  <option value="{{ $hydraCoolSrprow['hydracoolsrp_id'] }}">{{ $hydraCoolSrprow['serial_number'] }}</option>
                      @endforeach
                  @endif
                </select>
              </div>

              <div class="form-group col-lg-6 col-md-6 col-sm-6">
                <label for="Hub_Board_unit" class="mb-1">Hub-Board Serial Number</label>
                <input type="text" class="form-control unit_name checkhandsetunique check-remote" id="Hub_Board_unit" name="Hub_Board_unit"
                uniqueHandset="device">
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
                <input type="text" class="form-control unit_name checkhandsetunique check-remote" id="MicroT_unit" name="MicroT_unit" uniqueHandset="device">
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
                <div class="datepicker date input-group p-0 shadow-sm">
                    <input type="text" class="form-control px-4 hydracooldate" id="manufacturing_date" name="manufacturing_date" readonly>
                    <div class="input-group-append"><span class="input-group-text px-4"><i class="fa fa-calendar-alt"></i></span></div>
                </div>
                <div id="manufacturing_date_error"> </div>
              </div>


              <div class="form-group col-lg-6 col-md-6 col-sm-6 ">
                <label for="sale_date" class="mb-1">Date of Sale</label>
                <div class="datepicker date input-group p-0 shadow-sm">
                    <input type="text" class="form-control px-4 hydracooldate" id="sale_date" name="sale_date" readonly>
                    <div class="input-group-append"><span class="input-group-text px-4"><i class="fa fa-calendar-alt"></i></span></div>
                </div>
                <div id="sale_date_error"> </div>
              </div>

              <input type="hidden" id="handset_id">
                <div class="form-group col-lg-6 col-md-6 col-sm-6">
                  <input type="hidden" id="hydracool_srp_id" name="hydracool_srp_id">
                  <div class="custom-control custom-checkbox" id="demo_area" style="display: none;">
                    <input type="checkbox" class="custom-control-input" id="is_demo" name="is_demo">
                    <label class="custom-control-label" for="is_demo">Is Demo?</label>
                  </div>
                </div>
          </div>
          <div class="row text-center">
            <div class="col-xl-12 col-lg-12 col-md-12"> <a href="javascript:;" style="display:none" class="btn btn-secondary text-capitalize add_other_hydracool_srp_unit" id="add_other_hydracool_srp_unit">Add Another HydraCool SRP Device</a>
              <button type="submit"  class="btn btn-secondary text-capitalize" id="save_hydracool_srp_units">Save</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
</div>
<!--Add New Hydracool -->


<!--View hydracool srp and units -->
<div id="printThis">
<div class="modal fade " id="view_hydracool_srp_details" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog homeModel" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h2 class="modal-title w-100 ">View HydraCool SRP Device Details</h2>
        <button type="button" class="close text-right" data-dismiss="modal" aria-label="Close" id="closeButtonDetail"> <span aria-hidden="true">X</span> </button>
      </div>
      <div class="modal-body px-3 max_height">
          <div class="row justify-content-xl-center justify-content-lg-center justify-content-md-center">
            <div class="col-xl-12 col-lg-12 col-md-12">
              <div class="form-row">
                <div class="form-group col-lg-6 col-md-6 col-sm-6 col-12 mb-2">
                 <div class="row">
                  <label for="center_name" class="col-12 pr-0 text-left font-weight-bold mb-0">Treatment Centre:</label>
                  <label id="center_name" class="col-12 text-left"></label>
                </div>
                </div>
                <div class="form-group col-lg-6 col-md-6 col-sm-6 col-12 mb-2">
                  <div class="row">
                  <label for="serial_number" class="col-12 pr-0 text-left font-weight-bold mb-0">HydraCool SRP Serial Number:</label>
                  <label id="serial_number" class="col-12 text-left"></label>
                  </div>
                </div>
               <div class="form-group col-lg-6 col-md-6 col-sm-6 col-12 mb-2">
                <div class="row">
                  <label for="hub_board_details" class="col-12 pr-0 text-left font-weight-bold mb-0">Hub-Board Serial Number:</label>
                  <label id="hub_board_details" class="col-12 text-left"></label>
                  </div>
                </div>
               <div class="form-group col-lg-6 col-md-6 col-sm-6 col-12 mb-2">
                <div class="row">
                  <label for="AquaB_details" class="col-12 pr-0 text-left font-weight-bold mb-0">AquaB Serial Number:</label>
                  <label id="AquaB_details" class="col-12 text-left"></label>
                  </div>
                </div>
              <div class="form-group col-lg-6 col-md-6 col-sm-6 col-12 mb-2">
                  <div class="row">
                  <label for="VibroX_details" class="col-12 pr-0 text-left font-weight-bold mb-0">Vibrox Serial Number:</label>
                  <label id="VibroX_details" class="col-12 text-left"></label>
                  </div>
                </div>
               <div class="form-group col-lg-6 col-md-6 col-sm-6 col-12 mb-2">
                 <div class="row">
                  <label for="MicroT_details" class="col-12 pr-0 text-left font-weight-bold mb-0">MicroT Serial Number:</label>
                  <label id="MicroT_details" class="col-12 text-left"></label>
                </div>
                </div>
                <div class="form-group col-lg-6 col-md-6 col-sm-6 col-12 mb-2">
                   <div class="row">
                  <label for="Collagen_details" class="col-12 pr-0 text-left font-weight-bold mb-0">Collagen+ Serial Number:</label>
                  <label id="Collagen_details" class="col-12 text-left"></label>
                </div>
                </div>

              <div class="form-group col-lg-6 col-md-6 col-sm-6 col-12 mb-2">
                 <div class="row">
                  <label for="UltraB_details" class="col-12 pr-0 text-left font-weight-bold mb-0">UltraB Serial Number:</label>
                  <label id="UltraB_details" class="col-12 text-left"></label>
                </div>
                </div>
              <div class="form-group col-lg-6 col-md-6 col-sm-6 col-12 mb-2">
                <div class="row">
                  <label for="demo_details" class="col-12 pr-0 text-left font-weight-bold mb-0">Demo:</label>
                  <label id="demo_details" class="col-12 text-left"></label>
                </div>
                </div>
                  </div>
              </div>
          </div>
          <div class="row text-center mt-2">
            <div class="col-xl-12 col-lg-12 col-md-12">
              <button class="btn btn-secondary close_print" data-dismiss="modal" aria-label="Close" id="close">Close</button>
              <button class="btn btn-secondary" id="btnPrint" onclick="javascript:printDiv('printThis')">Print</button>
            </div>
          </div>
      </div>
    </div>
  </div>
</div>
</div>
<!--Successfully Message -->

<input type="hidden" id="treatment_center_id" >

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
<!-- Successfully Message -->
@endsection

@section('jsdependencies')
  <script>
    var hydracoolsrp_list = '{{ route('devices') }}';
    var checkserialnumber = '{{ url("check-serialnumber") }}';
    var addhydracoolsrp = '{{ url("add-hydracoolsrp") }}';
    var gethydracoolsrpdetails = '{{ url("get-hydracoolsrp-details") }}';
    var updatehydracoolsrp = '{{ url("update-hydracoolsrp") }}';
    var suspendhydracoolsrp = '{{ url("suspend-hydracoolsrp") }}';
    var releasehydracoolsrp = '{{ url("release-hydracoolsrp") }}';
    var deletehydracoolsrp = '{{ url("delete-hydracoolsrp") }}';
    var checkhandsetunique = '{{ url("check-handsetValueUnique") }}';
    var unique= '{{ url("unique-serial-number") }}';
    var uniqueserialnumber="{{url('/uniqueserialnumber')}}";
    var addhydracoolsrpnonema = '{{ url("add-non-ema-hydracoolsrp") }}';
    var gethydracoolSrpUnitslist = '{{ url("get-srpunit-by-serialnumber") }}';
    var getAllHandsetValue = [];
    var getAllHydraCoolSrp = '{{ url("getAllHydraCoolSrp") }}';
    var userRole = "{{ Auth::user()->roles->first()->name }}";
  </script>
<script src="{{ asset('js/developer_js/devices.js') }}"></script>
@endsection