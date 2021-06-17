@extends('layouts.app')
@section('title', 'Treatment Centre Principal')
@section('content')
<div class="container-fluid">
  <div class="row">
    <div class="col-xl-12 col-lg-12">
      <h1 class="my-2">Treatment Centre</h1>
    </div>
  </div>
</div>
<div class="container-fluid">
  <div class="row">
    <div class="col-xl-12 col-lg-12 px-0">
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb px-3 ">
          <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
          @hasrole('treatment centre manager')
          @else
          <li class="breadcrumb-item"><a href="{{ route('treatment-centre-list') }}">Treatment Centre</a></li>
          @endrole
          <li class="breadcrumb-item active" aria-current="page">{{ $treatmentcenterDetails['full_company_name'] }}</li>
        </ol>
      </nav>
    </div>
  </div>
</div>
<div class="container-fluid">
  <div class="row">
    <div class="col-xl-12 col-lg-12 mb-3">
      <div class="row d-flex align-items-end ">
        <div class="col-xl-12 col-lg-12 col-md-12">
          <div class="userInfo">
            <p class="mb-2">@if( trim($treatmentcenterDetails['building_name'])!="")
              {{ $treatmentcenterDetails['building_name']}}, @endif
              @if(trim($treatmentcenterDetails['address_1'])!=""){{ $treatmentcenterDetails['address_1'] }}, @endif
              @if(trim($treatmentcenterDetails['address_2'])!=""){{ $treatmentcenterDetails['address_2'] }}, @endif
              @if(trim($treatmentcenterDetails['address_3'])!=""){{ $treatmentcenterDetails['address_3'] }}, @endif
              @if(trim($treatmentcenterDetails['full_address'])!="")
              {{ $treatmentcenterDetails['full_address'] }}, @endif
              @if(trim($treatmentcenterDetails['state'])!="")
              {{ $treatmentcenterDetails['state'] }},@endif

              @if($treatmentcenterDetails['zipcode']!=" ")
              {{strtoupper($treatmentcenterDetails['zipcode']) }}@endif

              @if($treatmentcenterDetails['country_name']!="")
              {{ $treatmentcenterDetails['country_name']."." }} @endif

            </p>
            <ul class="d-inline list-unstyled">
              <li><img src="{{asset("images/manager.svg")}}"  width="15" height="15" />{{ $treatmentcenterDetails['name_of_primary_contact'] }}</li>
              <li>
                <img src="{{asset("images/call.svg")}}"  width="15" height="15" />
                <a href="tel:{{ addSymbol($treatmentcenterDetails['telephone_number_of_primary_contact']) }}">{{ addSymbol($treatmentcenterDetails['telephone_number_of_primary_contact']) }}</a></li>
                <li><img src="{{asset("images/phone.svg")}}"  width="15" height="15" /><a href="tel:{{ addSymbol($treatmentcenterDetails['mobile_number_of_primary_contact']) }}">{{ addSymbol($treatmentcenterDetails['mobile_number_of_primary_contact']) }} </a>
                </li>

                <li><img src="{{asset("images/mail.svg")}}"  width="15" height="15" /><a href="mailto:{{$treatmentcenterDetails['email_of_primary_contact']}}">{{ $treatmentcenterDetails['email_of_primary_contact'] }}</a></li>
                @if($treatmentcenterDetails['web_site']!="")
                <li><img src="{{asset("images/world-wide-web.svg")}}"  width="15" height="15" />  <a href="{{addhttp($treatmentcenterDetails['web_site'])}}" class="big" target="_blank"> {{ $treatmentcenterDetails['web_site'] }}</a></li> @endif
                @if($treatmentcenterDetails['distributo_name']!="")
                <li><img src="{{asset("images/sitemap.svg")}}"  width="15" height="15" />  {{ $treatmentcenterDetails['distributo_name'] }}</li> @endif
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-xl-12 col-lg-12 ">
        <div class="row d-flex align-items-end ">
          <div class="col-xl-12 col-lg-12 col-md-12">
            @hasanyrole('system administrator|treatment centre manager|distributor principal|distributor service|distributor sales')
            <button class="btn btn-secondary mt-2" id="print_treatmentcentre_data">Edit Treatment Centre</button>
            @endhasanyrole

            @hasanyrole('system administrator|treatment centre manager|distributor principal')
            <button class="btn btn-secondary mt-2 " id="add_new_principal">Add New Treatment Centre User</button>
            @endhasanyrole </div>
          </div>
        </div>
      </div>
      <div class="row mb-4">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
          <section class="gridMainContainer">
            <div class="col-xl-12 col-lg-12 ">
              <h2 class="my-2 text-capitalize">Treatment Centre Users</h2>
            </div>
            <div class="col-xl-12 col-lg-12 px-0">
              <div class="table-responsive mt_minus" id="principalScroll">
                <table id="treatment_center_Principal_list" class="table table-striped table-bordered example" style="width:100%">
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
                  <tfoot style="display: none;">
                    <tr>
                      <th dataName="username">Full Name</th>
                      <th dataName="role">User Type</th>
                      <th>Online</th>
                      <th dataName="status">Status</th>
                      <th dataName="email">Email</th>
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
      @if($only_priniple==0)
      @hasanyrole('system administrator|ema service support|distributor principal')
      <div class="row d-flex align-items-end">
        <div class="col-xl-12 col-lg-12 col-md-12">
          <button class="btn btn-secondary " id="add_new_hydracool">Add HydraCool SRP Device</button>
        </div>
      </div>
      @endhasanyrole

      @hasanyrole('system administrator|ema service support|ema analyst|distributor principal|distributor service|distributor sales|treatment centre manager')
      <div class="row mb-4">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
          <section class="gridMainContainer">
            <div class="col-xl-12 col-lg-12 ">
              <h2 class="my-2 text-capitalize">HydraCool SRP Device</h2>
            </div>
            <div class="col-xl-12 col-lg-12 px-0">
              <div class="table-responsive mt_minus" id="srplistScroll">
                <table id="hydracool_srp_list" class="table table-striped table-bordered example" style="width:100%">
                  <thead>
                    <tr>
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
                  <tfoot style="display: none;">
                    <tr>
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
      @endhasanyrole
      @endif

      @hasanyrole('system administrator|distributor principal|distributor sales|distributor service')
      <div class="row d-flex align-items-end ">
        <div class="col-xl-12 col-lg-12 col-md-12">
          <button class="btn btn-secondary" id="add_new_cosmetic_solution">Add Cosmetic Deliveries</button>
        </div>
      </div>
      @endhasanyrole

      {{-- Cosmetic solution list --}}
      @hasanyrole('system administrator|distributor principal|distributor sales|distributor service|ema analyst|ema service support')
      <div class="row mb-4">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
          <section class="gridMainContainer">
            <div class="col-xl-12 col-lg-12 ">
              <h2 class="my-2 text-capitalize">Cosmetic Deliveries</h2>
            </div>
            <div class="col-xl-12 col-lg-12 px-0">
              <div class="table-responsive mt_minus" id="cosmeticlistScroll">
                <table id="cosmetic_list" class="table table-striped table-bordered example" style="width:100%">
                  <thead>
                    <tr>
                      <th>4 Bottle Pack</th>
                      <th>Single Bottles of S1</th>
                      <th>Single Bottles of S2</th>
                      <th>Single Bottles of S3</th>
                      <th>Single Bottles of S4</th>
                      <th>HydraCool SRP Bright packs</th>
                      <th>HydraCool SRP Fresh Packs</th>
                      <th>Boosters Packs</th>
                      <th>Tip Packs</th>
                      <th>Delivery Date</th>
                      <th>Created Date</th>
                      <th>Actions</th>
                    </tr>
                  </thead>
                  <tfoot style="display: none;">
                    <tr>
                      <th dataName="solution_bottle_pack-cosmetic">4 Bottles Pack</th>
                      <th dataName="solution_1-cosmetic">Single Bottles of S1</th>
                      <th dataName="solution_2-cosmetic">Single Bottles of S2</th>
                      <th dataName="solution_3-cosmetic">Single Bottles of S3</th>
                      <th dataName="solution_4-cosmetic">Single Bottles of S4</th>
                      <th dataName="cosmetic_bright_pack-cosmetic">HydraCool SRP Bright packs</th>
                      <th dataName="cosmetic_fresh_pack-cosmetic">HydraCool SRP Fresh Packs</th>
                      <th dataName="booster_packs-cosmetic">Boosters Packs</th>
                      <th dataName="aquaB_tips-cosmetic">Tip Packs</th>
                      <th dataName="delivery_date-cosmetic">Delivery Date</th>
                    </tr>
                  </tfoot>
                </table>
              </div>
            </div>
          </section>
        </div>
      </div>
      @endhasanyrole

      <div class="row d-flex align-items-end ">
        <div class="col-xl-12 col-lg-12 col-md-12"> @hasanyrole('system administrator|treatment centre manager|distributor principal|distributor sales|ema analyst') <a class="btn btn-secondary text_white" data-toggle="modal" data-target="#add_new_treatmentcenter_file">Upload Treatment Data</a> @endhasanyrole </div>
      </div>
      {{-- Treatment data list --}}
      @hasanyrole('system administrator|treatment centre manager|distributor principal|distributor sales|ema analyst')
      <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
          <section class="gridMainContainer">
            <div class="col-xl-12 col-lg-12 ">
              <h2 class="my-2 text-capitalize">Treatment Data</h2>
            </div>
            <div class="col-xl-12 col-lg-12">
              <div class="table-responsive mt_minus" id="fileScroll">
                <table id="treatmentcentre_list_file" class="table table-striped table-bordered example" style="width:100%">
                  <thead>
                    <tr>
                      <th>Client Number</th>
                      <th>Local Date</th>
                      <th>Local Time</th>
                      <th>Session Time</th>
                      <th>AquaB</th>
                      <th>VibroX</th>
                      <th>MicroT</th>
                      <th>Collagen+</th>
                      <th>UltraB</th>
                      <th>S1</th>
                      <th>S2</th>
                      <th>S3</th>
                      <th>S4</th>
                      <th>Fresh</th>
                      <th>Bright</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tfoot style="display: none;">
                    <tr>
                      <th dataName="client_id-datatable">Client Number</th>
                      <th dataName="localDate-datatable">Local Date</th>
                      <th dataName="localTime-datatable">Local Time</th>
                      <th dataName="sessionTime-datatable">Session Time</th>
                      <th dataName="AquaBSessionTime-datatable">AquaB</th>
                      <th dataName="VibroXSessionTime-datatable">VibroX</th>
                      <th dataName="MicroTSessionTime-datatable">MicroT</th>
                      <th dataName="CollagenSessionTime-datatable">Collagen+</th>
                      <th dataName="UltraBSessionTime-datatable">UltraB</th>
                    </tr>
                  </tfoot>
                </table>
              </div>
            </div>
          </section>
        </div>
      </div>
      @endhasanyrole
    </div>
    <div class="modal fade " id="cosmetic_data" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
      <div class="modal-dialog cosmeticData" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h2 class="modal-title w-100">Delivery To {{ $treatmentcenterDetails['full_company_name'] }}</h2>
            <button type="button" class="close text-right" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">X</span> </button>
          </div>
          <div class="modal-body px-xl-3 px-lg-3 px-md-3 px-sm-3 max_height">
            <form id="cosmetic_form">
              <div class="row justify-content-xl-center justify-content-lg-center justify-content-md-center">
                <div class="col-xl-12 col-lg-12 col-md-12">
                  <div class="form-row">
                    <div class="col-lg-12 col-md-12 col-sm-12 mb-3">
                      <label for="name" class="mb-1 border-bottom w-100 pb-2">HydraCool SRP Solutions (1 Pack contains 1xS1, 1xS2, 1xS3, 1xS4 Bottles)</label>
                    </div>
                  </div>
                  <div class="form-row">
                    <div class="form-group col-lg-6 col-md-6 col-sm-6">
                      <label class="float-left min_label_width mt-1">{{ App\CoreSetting::where('name','solution_bottle_pack')->first()->value  }} Bottle Pack</label>
                      <div class="float-left">
                        <input type="text" class="form-control width_input" name="solution_bottle_pack" id="solution_bottle_pack">
                      </div>
                      <div id="solution_bottle_pack_error"> </div>
                    </div>
                  </div>
                  <div class="form-row">
                    <div class="form-group col-lg-6 col-md-6 col-sm-6">
                      <label class="float-left min_label_width mt-1">Single Bottles of S1</label>
                      <div class="float-left">
                        <input type="text" class="form-control width_input" name="solution_1" id="solution_1">
                      </div>
                      <div id="solution_1_error"> </div>
                    </div>
                    <div class="form-group col-lg-6 col-md-6 col-sm-6">
                      <label class="float-left min_label_width mt-1">Single Bottles of S2</label>
                      <div class="float-left">
                        <input type="text" class="form-control width_input" name="solution_2"  id="solution_2">
                      </div>
                      <div id="solution_2_error"> </div>
                    </div>
                    <div class="form-group col-lg-6 col-md-6 col-sm-6">
                      <label class="float-left min_label_width mt-1">Single Bottles of S3</label>
                      <div class="float-left">
                        <input type="text" class="form-control width_input" name="solution_3"  id="solution_3">
                      </div>
                      <div id="solution_3_error"> </div>
                    </div>
                    <div class="form-group col-lg-6 col-md-6 col-sm-6">
                      <label class="float-left min_label_width mt-1">Single Bottles of S4</label>
                      <div class="float-left">
                        <input type="text" class="form-control width_input" name="solution_4"  id="solution_4">
                      </div>
                      <div id="solution_4_error"> </div>
                    </div>
                  </div>
                  <div class="form-row">
                    <div class="col-lg-12 col-md-12 col-sm-12 mb-3">
                      <label for="name" class="mb-1 border-bottom w-100 pb-2">HydraCool SRP Cosmetic Packs (1 Pack contains {{ App\CoreSetting::where('name','cosmetic_fresh_pack')->first()->value  }} packs)</label>
                    </div>
                  </div>
                  <div class="form-row">
                    <div class="form-group col-lg-6 col-md-6 col-sm-6">
                      <label class="float-left min_label_width mt-1">HydraCool SRP Bright packs</label>
                      <div class="float-left">
                        <input type="text" class="form-control width_input" name="cosmetic_bright_pack" id="cosmetic_bright_pack">
                      </div>
                      <div id="cosmetic_bright_pack_error"> </div>
                    </div>

                    <div class="form-group col-lg-6 col-md-6 col-sm-6">
                      <label class="float-left min_label_width mt-1">HydraCool SRP Fresh Packs</label>
                      <div class="float-left">
                        <input type="text" class="form-control width_input" name="cosmetic_fresh_pack"  id="cosmetic_fresh_pack">
                      </div>
                      <div id="cosmetic_fresh_pack_error"> </div>
                    </div>

                  </div>
                  <div class="form-row">
                    <div class="col-lg-12 col-md-12 col-sm-12 mb-3 ">
                      <label for="name" class="mb-1 border-bottom w-100 pb-2">HydraCool SRP Booster Syringe Packs (1 Pack contains {{ App\CoreSetting::where('name','booster_packs')->first()->value  }} packs)</label>
                    </div>
                  </div>
                  <div class="form-row">
                    <div class="form-group col-lg-7 col-md-7 col-sm-7">
                      <label class="float-left min_label_width mt-1">Boosters Packs</label>
                      <div class="float-left">
                        <input type="text" class="form-control width_input" name="booster_packs" id="booster_packs">
                      </div>
                      <div id="booster_packs_error"> </div>
                    </div>
                  </div>
                  <div class="form-row">
                    <div class="col-lg-12 col-md-12 col-sm-12 mb-3 ">
                      <label for="name" class="mb-1 border-bottom w-100 pb-2">HydraCool SRP AquaB Tips (1 Pack contains {{ App\CoreSetting::where('name','aquaB_tips')->first()->value  }} blister packs, each having 1xSmall Tip, 1x Large Tip, 1x Cleaning Tip)</label>
                    </div>
                  </div>
                  <div class="form-row">
                    <div class="form-group col-lg-6 col-md-6 col-sm-6">
                      <label class="float-left min_label_width mt-1">Tip Packs</label>
                      <div class="float-left">
                        <input type="text" class="form-control width_input" name="aquaB_tips" id="aquaB_tips">
                      </div>
                      <div id="tip_pack_error">  </div>
                    </div>
                  </div>
                  <div class="form-row ">
                    <div class="form-group col-lg-6 col-md-6 col-sm-6 ">
                      <label for="delivery_date" class="mb-1">Delivery Date</label>
                      <div class="datepicker date input-group p-0 shadow-sm">
                        <input type="text" class="form-control px-4" id="delivery_date" name="delivery_date" readonly>
                        <div class="input-group-append"><span class="input-group-text px-4"><i class="fa fa-calendar-alt"></i></span></div>
                      </div>
                      <div id="delivery_date_error"> </div>
                    </div>

                  </div>
                  <input type="hidden" id="cosmetic_id">
                  <div class="row text-center mt-2">
                    <div class="col-xl-12 col-lg-12 col-md-12">
                      <button type="submit" class="btn btn-secondary text-capitalize" id="save_cosmetic_data">Add</button>
                    </div>
                  </div>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    <!--Add New Hydracool -->
    <div class="modal fade " id="add_new_hydracool_form" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog homeModel" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h2 class="modal-title w-100" id="add_new">Add HydraCool SRP Device</h2>
            <button type="button" class="close text-right" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">X</span> </button>
          </div>
          <div class="modal-body px-xl-3 px-lg-3 px-md-3 px-sm-3 max_height">
            <form id="hydracool_srp_form_users">
              <input type="hidden" id="is_non_ema">
              <div class="form-row">
                <div class="form-group col-lg-6 col-md-6 col-sm-6">
                  <label for="added_treatment_center" class="mb-1">Treatment Centre</label>
                  <input type="text" disabled class="form-control" id="added_treatment_center" name="added_treatment_center">
                </div>
                <div class="form-group col-lg-6 col-md-6 col-sm-6" id="update_serial_number" style="display: none">
                  <label for="srp_serial_number" class="mb-1">HydraCool SRP  Serial Number</label>
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

                <div class="form-group col-lg-6 col-md-6 col-sm-6 ">
                  <input type="hidden" id="hydracool_srp_id" name="hydracool_srp_id">
                  <div class="custom-control custom-checkbox" id="demo_area">
                    <input type="checkbox" class="custom-control-input" id="is_demo" name="is_demo">
                    <label class="custom-control-label" for="is_demo">Is Demo?</label>
                  </div>
                </div>
              </div>
              <input type="hidden" id="handset_id">
              <div class="row text-center">
                <div class="col-xl-12 col-lg-12 col-md-12"> <a href="javascript:;" style="display:none" class="btn btn-secondary  text-capitalize add_other_hydracool_srp_unit" id="add_other_hydracool_srp_unit_detail_form">Add other HydraCool SRP Device</a>
                  <button type="submit"  class="btn btn-secondary text-capitalize" id="save_hydracool_srp_units_users">Save</button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    <!--Add New Treatment Centre Principal -->
    <div class="modal fade " id="add_new_tcp" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
      <div class="modal-dialog homeModel" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h2 class="modal-title w-100" id="add_new_principal_text">Add New Treatment Centre User</h2>
            <button type="button" class="close text-right" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">X</span> </button>
          </div>
          <div class="modal-body px-xl-3 px-lg-3 px-md-3 px-sm-3 max_height">
            <form id="principal_user_form">
              <div class="row justify-content-xl-center justify-content-lg-center justify-content-md-center">
                <div class="col-xl-12 col-lg-12 col-md-12">
                  <div class="form-row">
                    <div class="form-group col-lg-6 col-md-6 col-sm-6">
                      <label for="name" class="mb-1">Full Name</label>
                      <input type="text" class="form-control" id="principal_name" name="name">
                    </div>
                    <div class="form-group col-lg-6 col-md-6 col-sm-6">
                      <label for="username" class="mb-1">Login Username</label>
                      <input type="text" class="form-control check-remote" id="principal_username" name="username">
                    </div>
                  </div>
                  <div class="form-row">
                    <div class="form-group col-lg-6 col-md-6 col-sm-6">
                      <label for="email" class="mb-1">Treatment Centre Name</label>
                      <input type="text" class="form-control" id="treatment_centrename" name="treatment_centrename" value="{{  $treatmentcenterDetails['full_company_name'] }}-{{ $treatmentcenterDetails['treatment_ema_code'] }}" disabled>
                    </div>

                    <div class="form-group col-lg-6 col-md-6 col-sm-6">
                      <label for="email" class="mb-1">Email Address</label>
                      <input type="text" class="form-control check-remote" id="principal_email" name="email">
                    </div>

                  </div>
                  <div class="form-row">
                    <div class="form-group col-lg-6 col-md-6 col-sm-6">
                      <label for="primary_telephone_number" class="mb-1">Telephone Number</label>
                      <input type="text" class="form-control phoneregx" id="principal_primary_telephone_number" name="primary_telephone_number">
                    </div>
                    <div class="form-group col-lg-6 col-md-6 col-sm-6">
                      <label for="mobile_telephone_number" class="mb-1">Mobile Number</label>
                      <input type="text" class="form-control phoneregx" id="principal_mobile_telephone_number" name="mobile_telephone_number">
                    </div>
                    <input type="hidden" id="user_id" name="user_id">
                  </div>
                </div>
              </div>
              <div class="row text-center mt-2">
                <div class="col-xl-12 col-lg-12 col-md-12">
                  <button type="submit" class="btn btn-secondary text-capitalize" id="save_treatment_center_principal">save</button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    <div id="printDetail">
      <div class="modal fade " id="view_hydracool_srp_details" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog homeModel" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h2 class="modal-title w-100">View HydraCool SRP Device Details</h2>
              <button type="button" class="close text-right" data-dismiss="modal" aria-label="Close" id="closeButtonDetail"> <span aria-hidden="true">X</span> </button>
            </div>
            <div class="modal-body px-3 max_height">
              <div class="row justify-content-xl-center justify-content-lg-center justify-content-md-center">
                <div class="col-xl-12 col-lg-12 col-md-12">
                  <div class="form-row">
                    <div class="form-group col-lg-6 col-md-6 col-12 col-sm-6 mb-2">
                      <div class="row">
                        <label for="center_name" class="col-12 pr-0 text-left font-weight-bold mb-0">Treatment Centre:</label>
                        <label id="center_name" class="col-12 text-left"></label>
                      </div>
                    </div>
                    <div class="form-group col-lg-6 col-md-6 col-12 col-sm-6 mb-2">
                      <div class="row">
                        <label for="serial_number" class="col-12 pr-0 text-left font-weight-bold mb-0">HydraCool SRP  Serial Number:</label>
                        <label id="serial_number" class="col-12 text-left"></label>
                      </div>
                    </div>
                    <div class="form-group col-lg-6 col-md-6 col-12 col-sm-6 mb-2">
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
                  <button class="btn btn-secondary close_print" data-dismiss="modal" aria-label="Close" id="closedetail">Close</button>
                  <button class="btn btn-secondary print_btn" onclick="javascript:printDiv('printDetail')" id="btnPrintDetail">Print</button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div id="printThis">
      <div class="modal fade " id="view_log_details" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog homeModel" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h2 class="modal-title">Principal Details</h2>
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
                        <label for="show_teleno" class="col-12 pr-0 text-left font-weight-bold mb-0">Telephone Number:</label>
                        <label id="show_teleno" class="col-12 text-left"></label>
                      </div>
                    </div>
                    <div class="form-group col-lg-6 col-md-6 col-sm-6 col-12 mb-2">
                      <div class="row">
                        <label for="show_mobno" class="col-12 pr-0 text-left font-weight-bold mb-0">Mobile Number:</label>
                        <label id="show_mobno" class="col-12 text-left"></label>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row text-center mt-">
                <div class="col-xl-12 col-lg-12 col-md-12">
                  <button class="btn btn-secondary close_print" data-dismiss="modal" aria-label="Close" id="close">Close</button>
                  <button class="btn btn-secondary print_btn"  onclick="javascript:printDiv('printThis')" id="btnPrint"> Print</button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

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
              <div class="row justify-content-xl-center justify-content-lg-center justify-content-md-center">
                <div class="col-xl-12 col-lg-12 col-md-12">
                  <div class="row">
                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4">
                      <div class="col-lg-12 col-md-12 col-sm-12 mb-1 p-0">
                        <label for="name" class="mb-1 border-bottom w-100 pb-2 font-weight-bold">Treatment Centre Details</label>
                      </div>
                      <div class="form-group">
                        <label for="full_company_name" class="mb-1">Treatment Centre Name</label>
                        <input type="text" class="form-control centredetails" id="full_company_name" name="full_company_name">
                      </div>
                      <div class="form-group">
                        <label for="abbreviated_company_name" class="mb-1">Abbreviated Company Name</label>
                        <input type="text" class="form-control centredetails" id="abbreviated_company_name" name="abbreviated_company_name">
                      </div>
                      <div class="form-group">
                        <label for="treatment_ema_code" class="mb-1">Treatment Centre Code</label>
                        <input type="text" class="form-control checkvalueExistsCode centredetails" id="treatment_ema_code" name="treatment_ema_code">
                      </div>
                      <div class="form-group" >
                        <div class="custom-control custom-checkbox mb-3" id="group_area_div">
                          <input type="checkbox" class="custom-control-input centredetails" id="is_group" name="is_group">
                          <label class="custom-control-label " for="is_group" id="group_area">Is Distributor part of a Group?</label>
                        </div>
                        <label for="group_name" class="mb-1">Group Name</label>
                        <input type="text" disabled class="form-control centredetails" id="group_name" name="group_name">
                      </div>
                      <div class="form-group">
                        <label for="web_site" class="mb-1">Web Site</label>
                        <input type="text" class="form-control centredetails" id="web_site" name="web_site">
                      </div>
                      <div class="form-group">
                        <label for="sel1" class="mb-1">Distributor Company Name</label>
                        <select class="form-control select_dropdown centredetails" id="distributors" name="distributors">
                          <option value="">--Select Disrtibutor--</option>
                          @foreach($getDistributorData as $getDistributorRow)
                          <option value="{{ $getDistributorRow['id'] }}">{{ $getDistributorRow['full_company_name'] ." - ".$getDistributorRow['distributor_code'] }}</option>
                          @endforeach
                        </select>
                      </div>
                      <div class="form-group" >
                        <div class="custom-control custom-checkbox" id="ema_area_details_div">
                          <input type="checkbox" class="custom-control-input centredetails" id="is_ema" name="is_ema">
                          <label class="custom-control-label" id="ema_area_details" for="is_ema">Is EMA?</label>
                        </div>
                      </div>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4">
                      <div class="col-lg-12 col-md-12 col-sm-12 mb-1 p-0">
                        <label for="name" class="mb-1 border-bottom w-100 pb-2 font-weight-bold">Address Details</label>
                      </div>
                      <div class="form-group">
                        <label for="building_name" class="mb-1">Building Name</label>
                        <input type="text" class="form-control centredetails" id="building_name" name="building_name">
                      </div>
                      <div class="form-group">
                        <label for="address1" class="mb-1">Address Line 1</label>
                        <input type="text" class="form-control centredetails" id="address_1" name="address_1">
                      </div>
                      <div class="form-group">
                        <label for="address2" class="mb-1">Address Line 2</label>
                        <input type="text" class="form-control centredetails" id="address_2" name="address_2">
                      </div>
                      <div class="form-group">
                        <label for="address3" class="mb-1">Address Line 3</label>
                        <input type="text" class="form-control centredetails" id="address_3" name="address_3">
                      </div>
                      <div class="form-group">
                        <label for="full_address" class="mb-1">Address Line 4</label>
                        <input type="text" class="form-control centredetails" id="full_address" name="full_address">
                      </div>
                      <div class="form-group">
                        <label for="state" class="mb-1">State/County</label>
                        <input type="text" class="form-control centredetails" id="state" name="state">
                      </div>
                      <div class="form-group">
                        <label for="zipcode" class="mb-1">Post/Zip code</label>
                        <input type="text" class="form-control centredetails" id="zipcode" name="zipcode">
                      </div>
                      <div class="form-group">
                        <label for="country_id" class="mb-1">Country</label>
                        <select class="form-control country-select select_dropdown centredetails" id="country_id" name="country_id">
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
                        <input type="text" class="form-control centredetails" id="name_of_primary_contact" name="name_of_primary_contact">
                      </div>
                      <div class="form-group">
                        <label for="position" class="mb-1">Position</label>
                        <input type="text" class="form-control centredetails" id="position" name="position">
                      </div>
                      <div class="form-group">
                        <label for="telephone_number_of_primary_contact" class="mb-1">Telephone Number</label>
                        <input type="text" class="form-control centredetails phoneregx" id="telephone_number_of_primary_contact" name="telephone_number_of_primary_contact">
                      </div>
                      <div class="form-group">
                        <label for="mobile_number_of_primary_contact" class="mb-1">Mobile number</label>
                        <input type="text" class="form-control centredetails phoneregx" id="mobile_number_of_primary_contact" name="mobile_number_of_primary_contact">
                      </div>
                      <div class="form-group">
                        <label for="fax_number" class="mb-1">Fax Number</label>
                        <input type="text" class="form-control centredetails phoneregx" id="fax_number" name="fax_number">
                      </div>
                      <div class="form-group">
                        <label for="email_of_primary_contact" class="mb-1">Email Address</label>
                        <input type="text" class="form-control centredetails" id="email_of_primary_contact" name="email_of_primary_contact">
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row text-center mt-2">
                <div class="col-xl-12 col-lg-12 col-md-12">
                  <button type="submit" class="btn btn-secondary text-capitalize mt-2" id="update_treatment_center" style="display:none">Update</button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    <!--Add New Treatment Centre -->

    <!--view  and print Treatment Centre -->
    <div id="printTreatmentcentreData">
      <div class="modal fade" id="treatmentcentre_print_data" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
        <div class="modal-dialog bigModel" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h2 class="modal-title w-100" id="treatment_center_title">View Treatment Centre</h2>
              <button type="button" class="close text-right" data-dismiss="modal" aria-label="Close" id="distributor_close_button"> <span aria-hidden="true">X</span> </button>

            </div>
            <div class="modal-body px-xl-3 px-lg-3 px-md-3 px-sm-3 max_height">
              <div class="row justify-content-xl-center justify-content-lg-center justify-content-md-center">
                <div class="col-xl-12 col-lg-12 col-md-12">
                  <div class="row">
                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4">
                      <div class="col-lg-12 col-md-12 col-sm-12 mb-1 p-0">
                        <label for="name" class="mb-1 border-bottom w-100 pb-2 font-weight-bold">Treatment Centre Details</label>
                      </div>
                      <div class="form-group">
                        <label for="full_company_name" class="pr-0 text-left font-weight-bold mb-0">Treatment Centre Name:</label>
                        <div class="text-left mt-n1"><label class="mb-1">{{ $treatmentcenterDetails['full_company_name'] }}</label></div>
                      </div>
                      <div class="form-group ">
                        <label for="abbreviated_company_name" class="pr-0 text-left font-weight-bold mb-0">Abbreviated Company Name:</label>
                        <div class="text-left mt-n1"><label class="mb-1">{{ ( ($treatmentcenterDetails['abbreviated_company_name']) ? $treatmentcenterDetails['abbreviated_company_name'] : '--') }}</label></div>
                      </div>
                      <div class="form-group ">
                        <label for="treatment_ema_code" class="pr-0 text-left font-weight-bold mb-0">Treatment Centre Code:</label>
                        <div class="text-left mt-n1"><label class="mb-1">{{ $treatmentcenterDetails['treatment_ema_code'] }}</label></div>
                      </div>
                      <div class="form-group ">
                        <div class="w-100 mb-3" id="group_area_div">
                          <label class="pr-0 text-left font-weight-bold" for="is_group" id="group_area mb-0">Is Company part of a Group :</label>
                          <div class="text-left mt-n1"><label class="mb-1">{{ ( ($treatmentcenterDetails['group_name']) ? 'Yes' : 'No') }}</label></div>
                        </div>
                      </div>
                      <div class="form-group ">
                        <label for="group_name" class="pr-0 text-left font-weight-bold mb-0">Group Name:</label>
                        <div class="text-left mt-n1">
                          <label class="text-left mb-0">{{ ( ($treatmentcenterDetails['group_name']) ? $treatmentcenterDetails['group_name'] : '--') }}</label>
                        </div>
                      </div>
                      <div class="form-group">
                        <label for="web_site" class=" pr-0 text-left font-weight-bold mb-0">Web Site:</label>
                        <div class="text-left mt-n1"><label class="mb-1">{{ ( ($treatmentcenterDetails['web_site']) ? $treatmentcenterDetails['web_site'] : '--') }}</label></div>
                      </div>
                      <div class="form-group ">
                        <label for="sel1" class=" pr-0 text-left font-weight-bold mb-0">Distributor Company Name:</label>
                        @if($treatmentcenterDetails['distributors']!=null)
                        @foreach($getDistributorData as $getDistributorRow)
                        @if($getDistributorRow['id'] == $treatmentcenterDetails['distributors'])
                        <div class="text-left mt-n1"><label class="mb-1">{{ (   ($getDistributorRow['full_company_name']) ? $getDistributorRow['full_company_name'] : '--') }}</label></div>
                        @endif
                        @endforeach
                        @else
                        <div class="text-left mt-n1"><label class="mb-1">--</label></div>
                        @endif
                      </div>
                      <div class="form-group">
                        <div class="" id="ema_area_details_div">
                          <label class=" font-weight-bold mb-0" id="ema_area_details " for="is_ema">Is EMA?:</label>
                          <div class="text-left mt-n1"><label class="mb-1">{{ ( ($treatmentcenterDetails['is_ema']) ? 'Yes' : 'No') }}</label></div>
                        </div>
                      </div>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4">
                      <div class="col-lg-12 col-md-12 col-sm-12 mb-1 p-0">
                        <label for="name" class="mb-1 border-bottom w-100 pb-2 font-weight-bold">Address Details</label>
                      </div>
                      <div class="form-group ">
                        <label for="building_name" class="pr-0 text-left font-weight-bold mb-0">Building Name:</label>
                        <div class="text-left mt-n1"><label class="mb-1">{{ ( ($treatmentcenterDetails['building_name']) ? $treatmentcenterDetails['building_name'] : '--') }}</label></div>

                      </div>
                      <div class="form-group ">
                        <label for="address1" class=" pr-0 text-left font-weight-bold mb-0">Address Line 1:</label>
                        <div class="text-left mt-n1"><label class="mb-1">{{ $treatmentcenterDetails['address_1'] }}</label></div>
                      </div>
                      <div class="form-group ">
                        <label for="address2" class="pr-0 text-left font-weight-bold mb-0">Address Line 2:</label>
                        <div class="text-left mt-n1"><label class="mb-1">{{ ( ($treatmentcenterDetails['address_2']) ? $treatmentcenterDetails['address_2'] : '--') }}</label></div>
                      </div>
                      <div class="form-group">
                        <label for="address3" class="pr-0 text-left font-weight-bold mb-0">Address Line 3:</label>
                        <div class="text-left mt-n1"><label class="mb-1">{{ ( ($treatmentcenterDetails['address_3']) ? $treatmentcenterDetails['address_3'] : '--') }}</label></div>
                      </div>
                      <div class="form-group">
                        <label for="full_address" class="pr-0 text-left font-weight-bold mb-0">Address Line 4:</label>
                        <div class="text-left mt-n1"><label class="mb-1">{{ ( ($treatmentcenterDetails['full_address']) ? $treatmentcenterDetails['full_address'] : '--') }}</label></div>
                      </div>
                      <div class="form-group ">
                        <label for="state" class="pr-0 text-left font-weight-bold mb-0">State/County:</label>
                        <div class="text-left mt-n1"><label class="mb-1">{{ $treatmentcenterDetails['state'] }}</label></div>
                      </div>
                      <div class="form-group">
                        <label for="zipcode" class="pr-0 text-left font-weight-bold mb-0">Post/Zip code:</label>
                        <div class="text-left mt-n1"><label class="mb-1">
                          {{ strtoupper($treatmentcenterDetails['zipcode']) }}</label></div>
                        </div>
                        <div class="form-group">
                          <label for="country_id" class="pr-0 text-left font-weight-bold mb-0">Country:</label>
                          @foreach($getCountryData as $getCountryRow)
                          @if($getCountryRow['id'] == $treatmentcenterDetails['country_id'])
                          <div class="text-left mt-n1"><label class="mb-1">{{ $getCountryRow['name'] }}</label></div>
                          @endif
                          @endforeach
                        </div>

                      </div>
                      <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4">
                        <div class="col-lg-12 col-md-12 col-sm-12 mb-1 p-0">
                          <label for="name" class="mb-1 border-bottom w-100 pb-2 font-weight-bold">Primary Contact Details</label>
                        </div>
                        <div class="form-group">
                          <label for="name_of_primary_contact" class="pr-0 text-left font-weight-bold mb-0">Name:</label>
                          <div class="text-left mt-n1"><label class="mb-1">{{ $treatmentcenterDetails['name_of_primary_contact'] }}</label></div>
                        </div>
                        <div class="form-group ">
                          <label for="position" class=" pr-0 text-left font-weight-bold mb-0">Position:</label>
                          <div class="text-left mt-n1"><label class="mb-1">{{ $treatmentcenterDetails['position'] }}</label></div>
                        </div>
                        <div class="form-group ">
                          <label for="telephone_number_of_primary_contact" class="pr-0 text-left font-weight-bold mb-0">Telephone Number:</label>
                          <div class="text-left mt-n1"><label class="mb-1">{{ $treatmentcenterDetails['telephone_number_of_primary_contact'] }}</label></div>
                        </div>
                        <div class="form-group">
                          <label for="mobile_number_of_primary_contact" class="pr-0 text-left font-weight-bold mb-0">Mobile Number:</label>
                          <div class="text-left mt-n1"><label class="mb-1">{{ $treatmentcenterDetails['mobile_number_of_primary_contact'] }}</label></div>
                        </div>
                        <div class="form-group">
                          <label for="fax_number" class="pr-0 text-left font-weight-bold mb-0">Fax Number:</label>
                          <div class="text-left mt-n1"><label class="mb-1">{{ ( ($treatmentcenterDetails['fax_number']) ? $treatmentcenterDetails['fax_number'] : '--') }}</label></div>
                        </div>
                        <div class="form-group">
                          <label for="email_of_primary_contact" class="pr-0 text-left font-weight-bold mb-0">Email Address:</label>
                          <div class="text-left mt-n1"><label class="mb-1">{{ $treatmentcenterDetails['email_of_primary_contact'] }}</label></div>
                        </div>

                      </div>
                    </div>
                  </div>
                </div>
                <div class="row text-center mt-2">
                  <div class="col-xl-12 col-lg-12 col-md-12"> <a type="submit" class="btn btn-secondary text-capitalize mt-2 updateTreatmentCenter" id="updateTreatmentCenter" data-treatmentCenterId="{{ $getTreatmentCenterId }}">Edit</a> <a type="submit" class="btn btn-secondary text-capitalize mt-2" onclick="javascript:printDiv('printTreatmentcentreData')" id="printTreatmentCentreDetail" style="">Print</a> </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!--view  and print Treatment Centre -->

      <input type="hidden" value="{{ $getTreatmentCenterId }}" id="treatment_center_id">
      <!--Add treatment data file -->

      <div class="modal fade " id="add_new_treatmentcenter_file" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog " role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h2 class="modal-title">Upload Treatment Data</h2>
              <button type="button" class="close text-right" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">X</span> </button>
            </div>
            <div class="modal-body px-xl-3 px-lg-3 px-md-3 px-sm-3 max_height">
              <form id="file_upload">
                @csrf
                <div class="row justify-content-xl-center justify-content-lg-center justify-content-md-center">
                  <div class="col-xl-12 col-lg-12 col-md-12">
                    <div class="input-group mb-3">
                      <div class="w-100">
                        <div id="imageUpload" class="dropzone">
                        </div>
                        <span id="file-error" class="error" for="file" style="display: none;color:red">This field is required.</span>
                        <span id="file-extenstion" class="error" for="file" style="display: none;color: red">You can't upload files of this type.</span>
                      </div>
                    </div>
                    <div id="error_area"></div>
                  </div>
                </div>
                <div class="row text-center mt-2">
                  <div class="col-xl-12 col-lg-12 col-md-12">
                    <button type="submit" id="save_treatmentcentre_file" class="btn btn-secondary text-right">Save</button>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
      <!--Add treatment data file -->


      <div id="printcosmeticdata">
        <div class="modal fade " id="view_cosmetic_details" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog homeModel" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h2 class="modal-title w-100">View Cosmetic And Solutions Details</h2>
                <button type="button" class="close text-right" data-dismiss="modal" aria-label="Close" id="closeButtonDetail"> <span aria-hidden="true">X</span> </button>
              </div>
              <div class="modal-body px-3 max_height">
                <div class="row justify-content-xl-center justify-content-lg-center justify-content-md-center">
                  <div class="col-xl-12 col-lg-12 col-md-12">
                    <div class="form-row">
                      <div class="form-group col-lg-6 col-md-6 col-sm-6">
                        <div class="row">
                          <label for="solution_bottle_pack" class="col-12 pr-0 text-left font-weight-bold mb-0">4 Bottles Pack:</label>
                          <label id="solution_bottle_pack_view" class="col-12 text-left"></label>
                        </div>
                      </div>
                      <div class="form-group col-lg-6 col-md-6 col-sm-6">
                        <div class="row">
                          <label for="solution_1_view" class="col-12 pr-0 text-left font-weight-bold mb-0">Solution 1:</label>
                          <label id="solution_1_view" class="col-12 text-left"></label>
                        </div>
                      </div>
                      <div class="form-group col-lg-6 col-md-6 col-sm-6">
                        <div class="row">
                          <label for="solution_2_view" class="col-12 pr-0 text-left font-weight-bold mb-0">Solution 2:</label>
                          <label id="solution_2_view" class="col-12 text-left"></label>
                        </div>
                      </div>
                      <div class="form-group col-lg-6 col-md-6 col-sm-6">
                        <div class="row">
                          <label for="solution_3_view" class="col-12 pr-0 text-left font-weight-bold mb-0">Solution 3:</label>
                          <label id="solution_3_view" class="col-12 text-left"></label>
                        </div>
                      </div>
                      <div class="form-group col-lg-6 col-md-6 col-sm-6">
                        <div class="row">
                          <label for="solution_4_view" class="col-12 pr-0 text-left font-weight-bold mb-0">Solution 4:</label>
                          <label id="solution_4_view" class="col-12 text-left"></label>
                        </div>
                      </div>
                      <div class="form-group col-lg-6 col-md-6 col-sm-6">
                        <div class="row">
                          <label for="cosmetic_fresh_pack_view" class="col-12 pr-0 text-left font-weight-bold mb-0">Fresh Pack:</label>
                          <label id="cosmetic_fresh_pack_view" class="col-12 text-left"></label>
                        </div>
                      </div>
                      <div class="form-group col-lg-6 col-md-6 col-sm-6">
                        <div class="row">
                          <label for="cosmetic_bright_pack_view" class="col-12 pr-0 text-left font-weight-bold mb-0">Bright Pack:</label>
                          <label id="cosmetic_bright_pack_view" class="col-12 text-left"></label>
                        </div>
                      </div>
                      <div class="form-group col-lg-6 col-md-6 col-sm-6">
                        <div class="row">
                          <label for="booster_packs_view" class="col-12 pr-0 text-left font-weight-bold mb-0">Booster Packs:</label>
                          <label id="booster_packs_view" class="col-12 text-left"></label>
                        </div>
                      </div>
                      <div class="form-group col-lg-6 col-md-6 col-sm-6">
                        <div class="row">
                          <label for="aquaB_tips_view" class="col-12 pr-0 text-left font-weight-bold mb-0">Tip Packs:</label>
                          <label id="aquaB_tips_view" class="col-12 text-left"></label>
                        </div>
                      </div>
                      <div class="form-group col-lg-6 col-md-6 col-sm-6">
                        <div class="row">
                          <label for="delivery_date_view" class="col-12 pr-0 text-left font-weight-bold mb-0">Delivery Date:</label>
                          <label id="delivery_date_view" class="col-12 text-left"></label>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row text-center mt-2">
                  <div class="col-xl-12 col-lg-12 col-md-12">
                    <button class="btn btn-secondary" data-dismiss="modal" aria-label="Close" id="print_cosmetic_data_close">Close</button>
                    <button class="btn btn-secondary" onclick="javascript:printDiv('printcosmeticdata')" id="print_cosmetic_data">Print</button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>


      <input type="hidden" id="treatmentcenter_name">
      @endsection

      @section('jsdependencies')


      <script>
        var list_hydracool_srp = '{{ url("view-treatment-centre/$getTreatmentCenterId") }}';
        var list_treatmentcenter_Principal = '{{ url("list-treatment-centre-Principal/$getTreatmentCenterId") }}';
        var getTreatmentCenterId = '{{ $getTreatmentCenterId }}';
        var treatmentcenterdetails = '{{ url("get-treatment-centre-details") }}';
        var checkserialnumber = '{{ url("check-serialnumber") }}';
        var addhydracoolsrp = '{{ url("add-hydracoolsrp") }}';
        var gethydracoolsrpdetails = '{{ url("get-hydracoolsrp-details") }}';
        var updatehydracoolsrp = '{{ url("update-hydracoolsrp") }}';
        var suspendhydracoolsrp = '{{ url("suspend-hydracoolsrp") }}';
        var releasehydracoolsrp = '{{ url("release-hydracoolsrp") }}';
        var deletehydracoolsrp = '{{ url("delete-hydracoolsrp") }}';
        var addprincipal = '{{ url("add-treatmentcentre-user") }}';
        var viewprincipal = '{{ url("view-treatmentcentre-user") }}';
        var updateprincipal = '{{ url("update-treatmentcentre-user") }}';
        var checkUsernameExists = '{{ url("check-user-exists") }}';
        var addcosmeticdata = '{{ url("add-cosmeticdata") }}';
        var deleteCosmeticData = '{{ url("delete-cosmeticdata") }}';
        var updatecosmeticdata = '{{ url("update-cosmeticdata") }}';
        var getcosmeticdata = '{{ url("get-cosmeticdata/$getTreatmentCenterId") }}';
        var updatecenter = '{{ url("update-treatment-centre") }}';
        var treatmentcenterlist = '';
        var checkhandsetunique = '{{ url("check-handsetValueUnique") }}';
        var getTreatmentCenterId = '{{ $getTreatmentCenterId }}';
        var saveTreatmentCentreFile = '{{ route("save-treatmentcentrefile-upload") }}';
        var treatmentcenterdetails = '{{ url("get-treatment-centre-details") }}';
        var listtreatmentCentreFiles = '{{ url("list-treatmentcentre-file") }}';
        var viewcosmeticdtails = '{{ url("view-cosmeticdata") }}';
        var uniqueusername="{{url('/uniqueusername')}}";
        var uniqueuseremail="{{url('/uniqueuseremail')}}";
        var uniqueserialnumber="{{url('/uniqueserialnumber')}}";
        var uniquecentercode  ="{{url('unique-treamentcenter-code')}}";
        var isDetails = 1;
        var gethydracoolSrpUnitslist = '{{ url("get-srpunit-by-serialnumber") }}';
        var addhydracoolsrpnonema = '{{ url("add-non-ema-hydracoolsrp") }}';
        var getAllHandsetValue = [];
        var getAllHydraCoolSrp = '{{ url("getAllHydraCoolSrp") }}';
        var getTreatmentCentreData = '{{ url("get-treatment-data") }}';
        var getTreatmentCentreJsonData = '{{ url("get-treatmentjson-data") }}';
        var userRole = "{{ Auth::user()->roles->first()->name }}";

      </script>

      <script src="{{ asset('js/developer_js/treatmentcenter_users.js') }}"></script>
      <script src="{{ asset('js/developer_js/cosmetic.js') }}"></script>
      <script src="{{ asset('js/developer_js/treatmentcenter.js') }}"></script>
      <script src="{{ asset('js/developer_js/treatmentcenter_file_upload.js') }}"></script>


      @endsection