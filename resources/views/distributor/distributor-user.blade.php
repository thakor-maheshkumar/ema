@extends('layouts.app')
@section('title', 'Distributor Users')

@section('content')
<div class="container-fluid">
  <div class="row">
    <div class="col-xl-12 col-lg-12">
      <h1 class="my-2">Distributor Details</h1>
    </div>
  </div>
</div>
<div class="container-fluid">
  <div class="row">
    <div class="col-xl-12 col-lg-12 px-0">
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb px-3 ">
          <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
           @hasrole('distributor principal|distributor service|distributor sales')
          @else
            <li class="breadcrumb-item"><a href="{{route('distributor')}}">Distributors</a></li>
          @endif
          <li class="breadcrumb-item active" aria-current="page">{{$distributor->full_company_name}}</li>
        </ol>
      </nav>
    </div>
  </div>
</div>
<div class="container-fluid">
  <div class="row ">
    <div class="col-xl-12 col-lg-12 mb-2 ">
      <div class="row d-flex align-items-end ">
        <div class="col-xl-12 col-lg-12 col-md-12">
          <div class="userInfo">
            <p class="mb-2">
              @if(trim($distributor->building_name)!=""){{$distributor->building_name}}, @endif

               @if(trim($distributor->address1)!="") {{$distributor->address1}}, @endif

             @if(trim($distributor->address2)!=""){{$distributor->address2}}, @endif

             @if(trim($distributor->address3)!="") {{$distributor->address3}}, @endif

             @if(trim($distributor->full_address)!="") {{$distributor->full_address}}, @endif

             {{$distributor->state}},
             {{strtoupper($distributor->zipcode)}}
             {{$distributor->country->name."."}}
            </p>
            <ul class="d-inline list-unstyled">
              <li><img src="{{asset("images/manager.svg")}}"  width="15" height="15" />{{$distributor->name_of_primary_contact}}</li>

              <li><img src="{{asset("images/call.svg")}}"  width="15" height="15" />
                <a href="tel:{{addSymbol($distributor->telephone_number_of_primary_contact)}}">{{addSymbol($distributor->telephone_number_of_primary_contact)}}</a>
              </li>
              <li><img src="{{asset("images/phone.svg")}}"  width="15" height="15" />
               <a href="tel:{{addSymbol($distributor->mobile_number_of_primary_contact)}}">{{addSymbol($distributor->mobile_number_of_primary_contact)}}</a>
              </li>
              <li><img src="{{asset("images/mail.svg")}}"  width="15" height="15" /><a href="mailto:{{$distributor['email_of_primary_contact']}}">{{$distributor->email_of_primary_contact}}</a></li>
              @if($distributor->web_site!="")
              <li><img src="{{asset("images/world-wide-web.svg")}}"  width="15" height="15" /> <a href="{{addhttp($distributor->web_site)}}" class="big" target="_blank">{{$distributor->web_site}}</a></li>
              @endif
            </ul>
          </div>
        </div>
        <div class="col-xl-3 col-lg-3 col-md-3 text-xl-right text-lg-right"> </div>
      </div>
    </div>
  </div>
  <div class="row ">
    <div class="col-xl-12 col-lg-12"> @hasanyrole('distributor sales|distributor service')
      <button class="btn btn-secondary editDetail mt-2" data-toggle="modal" data-target="#edit_distributor_detail" data-distributorId="{{$distributor->id}}">View Distributor</button>
      @endhasanyrole

      @hasanyrole('system administrator|distributor principal')
      <button class="btn btn-secondary editDetail mt-2" data-toggle="modal" data-target="#edit_distributor_detail" data-distributorId="{{$distributor->id}}">Edit Distributor</button>
      <button class="btn btn-secondary mt-2" id="add_new_user_modal">Add New Distributor User</button>
      @endhasanyrole </div>
  </div>
   <div class="row mt-1">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
      <section class="gridMainContainer">
        <div class="col-xl-12 col-lg-12 ">
            <h2 class="my-2 text-capitalize">Distributor Users</h2>
        </div>
        <div class="col-xl-12 col-lg-12 px-0">
          <div class="table-responsive mt_minus" id="distributorScroll">
            <table id="distributor_user_list" class="table table-striped table-bordered example" style="width:100%">
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
  @hasanyrole('system administrator|ema analyst|ema service support|distributor principal|distributor service|distributor sales')
  <div class="row mt-1">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
      <section class="gridMainContainer">
        <div class="col-xl-12 col-lg-12 ">
          <h2 class="my-2 text-capitalize">Treatment Centres</h2>
        </div>
        <div class="col-xl-12 col-lg-12 px-0">
          <div class="table-responsive mt_minus" id="treatmentScroll">
            <table id="distributor_treatment" class="table table-striped table-bordered example" style="width:100%">
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
              <tfoot style="display:none">
                <tr>
                  <th dataName="treatment_ema_code-datatable">Treatment Centre Code</th>
                  <th dataName="full_company_name-datatable">Company Name</th>
                  <th dataName="primary_region-datatable">Region</th>
                  <th dataName="name_of_primary_contact-datatable">Primary Contact</th>
                  <th dataName="email_of_primary_contact-datatable">Primary Contact Email</th>
                  <th dataName="mobile_number_of_primary_contact-datatable">Primary Contact Mobile</th>
                  <th dataName="install_device-datatable">Installed Device</th>
                  <th dataName="status-datatable">Status</th>
                </tr>
              </tfoot>
            </table>
          </div>
        </div>
      </section>
    </div>
  </div>
  @endhasanyrole </div>
<!--Add New Distributor -->
<div class="modal fade " id="add_new_user" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog editdistributor" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h2 class="modal-title w-100 ">Add New Distributor User</h2>
        <button type="button" class="close text-right" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">X</span> </button>
      </div>
      <div class="modal-body px-3 max_height">
        <div class="row justify-content-xl-center justify-content-lg-center justify-content-md-center">
          <div class="col-xl-12 col-lg-12 col-md-12">
            <form action="javascript:void(0)" id="addnewdistributoruser" method="post">
              <div class="form-group">


                <div class="custom-control custom-radio custom-control-inline">
                  <input type="radio" class="custom-control-input" id="role3" name="role" value="9">
                  <label class="custom-control-label" for="role3">Distributor Principal </label>
                </div>
                <div class="custom-control custom-radio custom-control-inline">
                  <input type="radio" class="custom-control-input" id="role2" name="role" value="8">
                  <label class="custom-control-label" for="role2">Distributor Service</label>
                </div>
                <div class="custom-control custom-radio custom-control-inline">
                  <input type="radio" class="custom-control-input" id="role1" name="role" value="7">
                  <label class="custom-control-label" for="role1">Distributor Sales</label>
                </div>

                <p class="role_error"></p>
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
                  <label for="add_username" class="mb-1">Distributor Name</label>
                  <input type="text" name="fk_distributor_company" class="form-control" id="" value="{{$distributor->full_company_name}}" disabled>
                  <input type="hidden" name="fk_distributor_id" value="{{$distributor->id}}">
                </div>
                <div class="form-group col-lg-6 col-md-6 col-sm-6">
                  <label for="add_email" class="mb-1">Email Address</label>
                  <input type="text" name="email" class="form-control check-remote" id="add_email">
                </div>
                <div class="form-group col-lg-6 col-md-6 col-sm-6">
                  <label for="add_teleno" class="mb-1">Telephone Number</label>
                  <input type="text" name="primary_telephone_number" class="form-control phoneregx" id="add_teleno" >
                </div>
                <div class="form-group col-lg-6 col-md-6 col-sm-6">
                  <label for="add_mobno" class="mb-1">Mobile Number</label>
                  <input type="text" name="mobile_telephone_number" class="form-control phoneregx" id="add_mobno" >
                </div>
              </div>
              <div class="row text-center mt-3">
                <div class="col-xl-12 col-lg-12 col-md-12">
                  <button class="btn btn-secondary"  type="submit"
                  id="save_distributor_user">Save</button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!--Edit New Distributor -->
<div class="modal fade " id="edit_user" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog editdistributor" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h2 class="modal-title w-100 ">Edit Distributor User</h2>
        <button type="button" class="close text-right" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">X</span> </button>
      </div>
      <div class="modal-body px-3 max_height">
        <div class="row justify-content-xl-center justify-content-lg-center justify-content-md-center">
          <div class="col-xl-12 col-lg-12 col-md-12">
            <form action="javascript:void(0)" id="update_user_form" method="post">
              <div class="form-group">


                <div class="custom-control custom-radio custom-control-inline">
                  <input type="radio" class="custom-control-input role" id="role3Edit" name="role" value="9" style="z-index:1">
                  <label class="custom-control-label" for="role3">Distributor Principal </label>
                </div>
                <div class="custom-control custom-radio custom-control-inline">
                  <input type="radio" class="custom-control-input role" id="role2Edit" name="role" value="8" style="z-index:1">
                  <label class="custom-control-label" for="role2">Distributor Service</label>
                </div>
                <div class="custom-control custom-radio custom-control-inline">
                  <input type="radio" class="custom-control-input role" id="role1Edit" name="role" value="7" style="z-index:1">
                  <label class="custom-control-label" for="role1">Distributor Sales</label>
                </div>
              </div>
              <div class="form-row">
                <div class="form-group col-lg-6 col-md-6 col-sm-6">
                  <label for="add_name" class="mb-1">Full Name</label>
                  <input type="text" name="name" class="form-control" id="edit_name" >
                </div>
                <input type="hidden" name="user_id" id="user_id">
                <div class="form-group col-lg-6 col-md-6 col-sm-6">
                  <label for="add_username" class="mb-1">Login Username</label>
                  <input type="text" name="username" class="form-control check-remote" id="edit_username" >
                </div>
                <div class="form-group col-lg-6 col-md-6 col-sm-6">
                  <label for="add_username" class="mb-1">Distributor Name</label>
                  <input type="text" name="fk_distributor_company_edit" class="form-control" id="fk_distributor_company_edit" value="{{$distributor->full_company_name}}" disabled>
                  <input type="hidden" name="fk_distributor_id" value="{{$distributor->id}}"
                  id="id">
                </div>
                <div class="form-group col-lg-6 col-md-6 col-sm-6">
                  <label for="add_email" class="mb-1">Email Address</label>
                  <input type="text" name="email" class="form-control check-remote" id="edit_email">
                </div>
                <div class="form-group col-lg-6 col-md-6 col-sm-6">
                  <label for="add_teleno" class="mb-1">Telephone Number</label>
                  <input type="text" name="primary_telephone_number" class="form-control phoneregx" id="edit_telno">
                </div>
                <div class="form-group col-lg-6 col-md-6 col-sm-6">
                  <label for="add_mobno" class="mb-1">Mobile Number</label>
                  <input type="text" name="mobile_telephone_number" class="form-control phoneregx" id="edit_mobno" >
                </div>
              </div>
              <div class="row text-center mt-3">
                <div class="col-xl-12 col-lg-12 col-md-12">
                  <button class="btn btn-secondary"  type="submit">Update</button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- Edit Distributor User -->
<div id="printDistributorData">
<div class="modal fade " id="edit_distributor_detail" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog bigModel" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h2 class="modal-title w-100" id="distributorPrintHeader">View Distributor</h2>
        <button type="button" class="close text-right" data-dismiss="modal" aria-label="Close"
            id="distributor_close_button"> <span aria-hidden="true">X</span> </button>
      </div>
      <div class="modal-body px-xl-3 px-lg-3 px-md-3 px-sm-3 max_height">
        <div class="row justify-content-xl-center justify-content-lg-center justify-content-md-center">
          <div class="col-xl-12 col-lg-12 col-md-12">
            <div class="row">
              <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4">
                <div class="col-lg-12 col-md-12 col-sm-12 mb-1 p-0">
                  <label for="name" class="mb-1 border-bottom w-100 pb-2 font-weight-bold">Distributor Details</label>
                </div>
                <div class="form-group">
                    <label for="exampleInputEmail1" class=" pr-0 text-left" style="font-weight: bold;">Distributor Company Name:</label>
                    <div class="text-left">
                      <label for="exampleInputEmail1" class="mb-1" id="distirbutor_company_detail">{{$distributor->full_company_name}}</label>
                    </div>
                </div>
                <div class="form-group">
                    <label for="exampleInputEmail2" class="pr-0 text-left" style="font-weight: bold;">Abbreviated Company Name:</label>
                    <div class="text-left">
                      <label for="exampleInputEmail2" class="mb-1" id="abbreviated_company_detail">{{$distributor->abbreviated_company_name}}</label>
                    </div>
                </div>
                <div class="form-group">
                    <label for="exampleInputEmail1" class=" pr-0 text-left" style="font-weight: bold;">Distributor Code:</label>
                    <div class="text-left">
                      <label for="exampleInputEmail1" class="mb-1" id="distributor_code_detail">{{$distributor->distributor_code}}</label>
                    </div>
                  </div>
                <div class="form-group">
                    <label for="exampleInputEmail2" class="pr-0 text-left" style="font-weight: bold;">Is Distributor part of a Group?:</label>
                    <div class="text-left">
                      <label for="exampleInputEmail2" class="mb-1" id="abbreviated_company_detail">{{ ( ($distributor->group_name) ? 'Yes' : 'No') }}</label>
                    </div>
                  </div>
                <div class="form-group viewDistributorCompany">
                    <label for="group_name" class="pr-0 text-left" style="font-weight: bold;">Group Name:</label>
                    <div class="text-left">
                      <label for="group_name" class="mb-1" id="group_name_detail">{{ ( ($distributor->group_name) ? $distributor->group_name : '--') }}</label>
                    </div>
                </div>
                <div class="form-group">
                    <label for="exampleInputEmail2" class="pr-0 text-left" style="font-weight: bold;">Web Site:</label>
                    <div class="text-left">
                      <label for="exampleInputEmail2" class="mb-1" id="website_detail">{{ ( ($distributor->web_site) ? $distributor->web_site : '--') }}</label>
                    </div>
                </div>
              </div>
              <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4">
                <div class="col-lg-12 col-md-12 col-sm-12 mb-1 p-0">
                  <label for="name" class="mb-1 border-bottom w-100 pb-2 font-weight-bold">Address Details</label>
                </div>
                <div class="form-group">
  <label for="exampleInputEmail2" class="pr-0 text-left" style="font-weight: bold;">Building Name:</label>
  <div class="text-left">
    <label for="exampleInputEmail2" class="mb-1" id="building_detail">{{ ( ($distributor->building_name) ? $distributor->building_name : '--') }}</label>
  </div>
</div>
<div class="form-group">
  <label for="exampleInputEmail2" class="pr-0 text-left" style="font-weight: bold;">Address Line 1:</label>
  <div class="text-left">
    <label for="exampleInputEmail2" class="mb-1" id="address1_detail">{{$distributor->address1}}</label>
  </div>
</div>
<div class="form-group">
  <label for="exampleInputEmail2" class="pr-0 text-left" style="font-weight: bold;">Address Line 2:</label>
  <div class="text-left">
    <label for="exampleInputEmail2" class="mb-1" id="address2_detail">{{ ( ($distributor->address2) ? $distributor->address2 : '--') }}</label>
  </div>
</div>
<div class="form-group">
  <label for="exampleInputEmail2" class="pr-0 text-left" style="font-weight: bold;">Address Line 3:</label>
  <div class="text-left">
    <label for="exampleInputEmail2" class="mb-1" id="address_detail">{{ ( ($distributor->address3) ? $distributor->address3 : '--') }}</label>
  </div>
</div>
<div class="form-group">
  <label for="exampleInputEmail2" class="pr-0 text-left" style="font-weight: bold;">Address Line 4:</label>
  <div class="text-left">
    <label for="exampleInputEmail2" class="mb-1" id="address4_detail">{{ ( ($distributor->full_address) ? $distributor->full_address : '--') }}</label>
  </div>
</div>
<div class="form-group">
  <label for="exampleInputEmail2" class="pr-0 text-left" style="font-weight: bold;">State/County:</label>
  <div class="text-left">
    <label for="exampleInputEmail2" class="mb-1" id="state_detail">{{$distributor->state}}</label>
  </div>
</div>
<div class="form-group">
                    <label for="exampleInputEmail2" class="pr-0 text-left" style="font-weight: bold;">Post/Zip code:</label>
                    <div class="text-left">
                      <label for="exampleInputEmail2" class="mb-1" id="zipcode_detail">{{strtoupper($distributor->zipcode)}}</label>
                    </div>
                </div>
<div class="form-group">
  <label for="exampleInputEmail2" class="pr-0 text-left" style="font-weight: bold;">Country:</label>
  <div class="text-left">
    <label for="exampleInputEmail2" class="mb-1" id="country_detail">{{$distributor->country->name}}</label>
  </div>
</div>

              </div>
              <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4">
                <div class="col-lg-12 col-md-12 col-sm-12 mb-1 p-0">
                  <label for="name" class="mb-1 border-bottom w-100 pb-2 font-weight-bold">Primary Contact Details</label>
                </div>
                <div class="form-group">
  <label for="exampleInputEmail2" class="pr-0 text-left" style="font-weight: bold;">Name:</label>
  <div class="text-left">
    <label for="exampleInputEmail2" class="mb-1" id="primary_contact_detail">{{$distributor->name_of_primary_contact}}</label>
  </div>
</div>
<div class="form-group">
  <label for="exampleInputEmail2" class="pr-0 text-left" style="font-weight: bold;">Position:</label>
  <div class="text-left">
    <label for="exampleInputEmail2" class="mb-1" id="position_detail">{{$distributor->position}}</label>
  </div>
</div>
<div class="form-group ">
  <label for="exampleInputEmail2" class=" pr-0 text-left" style="font-weight: bold;">Telephone Number:</label>
  <div class="text-left">
    <label for="exampleInputEmail2" class="mb-1" id="telephone_number_detail">{{$distributor->telephone_number_of_primary_contact}}</label>
  </div>
</div>
<div class="form-group">
  <label for="exampleInputEmail2" class="pr-0 text-left" style="font-weight: bold;">Mobile Number:</label>
  <div class="text-left">
    <label for="exampleInputEmail2" class="mb-1" id="mobile_number_detail">{{$distributor->mobile_number_of_primary_contact}}</label>
  </div>
</div>
<div class="form-group">
  <label for="exampleInputEmail2" class="pr-0 text-left" style="font-weight: bold;">Fax Number :</label>
  <div class="text-left">
    <label for="exampleInputEmail2" class="mb-1" id="fax_number_detail">{{ ( ($distributor->fax_number) ? $distributor->fax_number : '--') }}</label>
  </div>
</div>
<div class="form-group">
  <label for="exampleInputEmail2" class="pr-0 text-left" style="font-weight: bold;">Email Address:</label>
  <div class="text-left">
    <label for="exampleInputEmail2" class="mb-1" id="email_detail">{{$distributor->email_of_primary_contact}}</label>
  </div>
</div>

              </div>
            </div>
            <div class="col-xl-12 col-lg-12 col-md-12 text-center mt-2"> @hasanyrole('system administrator|distributor principal')
              <button class="btn btn-secondary" id="edit_distributor_detail_data" type="button">Edit</button>
              @endhasanyrole
              <button class="btn btn-secondary print_btn" onclick="javascript:printDiv('printDistributorData')" id="printDistributorDetail" type="button">Print</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>


<!--View User Detail -->
<div id="printThis">
  <div class="modal fade " id="view_log_details" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog homeModel" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h2 class="modal-title w-100">User Details</h2>
          <button type="button" class="close text-right" data-dismiss="modal" aria-label="Close" id="closeButton"> <span aria-hidden="true">X</span> </button>
        </div>
        <div class="modal-body px-3 max_height">
          <div class="row justify-content-xl-center justify-content-lg-center justify-content-md-center" id="print" style="background-color: ">
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
                <div class="form-group col-lg-6 col-md-6 col-sm-6 col-12 mb-2">
                  <div class="row">
                    <label for="show_mobno" class="col-12 pr-0 text-left font-weight-bold mb-0">Mobile Number:</label>
                    <label id="show_mobno" class="col-12 text-left"></label>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="row text-center mt-3">
            <div class="col-xl-12 col-lg-12 col-md-12" id="btnAb">
              <button class="btn btn-secondary close_print" data-dismiss="modal" aria-label="Close" id="close">Close</button>
              <button class="btn btn-secondary print_btn" onclick="javascript:printDiv('printThis')" id="btnPrint"> Print</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>



<!--view  and print Treatment Centre -->
<div id="printTreatmentcentreData">
  <div class="modal fade" id="view_treatment_details" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog homeModel" role="document">
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
                    <div class="text-left"><label class="mb-1" id="treatment_full_company_name"></label></div>
                  </div>
                  <div class="form-group ">
                    <label for="abbreviated_company_name" class="pr-0 text-left font-weight-bold mb-0">Abbreviated Company Name:</label>
                    <div class="text-left"><label class="mb-1" id="treatment_abbreviated_company_name">--</label></div>
                  </div>
                  <div class="form-group ">
                    <label for="treatment_ema_code" class="pr-0 text-left font-weight-bold mb-0">Treatment Centre Code:</label>
                    <div class="text-left"><label class="mb-1" id="treatment_treatment_ema_code"></label></div>
                  </div>
                  <div class="form-group ">
                    <div class="w-100 mb-3" id="group_area_div">
                      <label class="pr-0 text-left font-weight-bold" for="is_group" id="group_area mb-0">Is Company part of a Group :</label>
                      <div class="text-left"><label class="mb-1" id="treatment_group_name_checkbox"></label></div>
                    </div>
                  </div>
                  <div class="form-group ">
                    <label for="group_name" class="pr-0 text-left font-weight-bold mb-0">Group Name:</label>
                    <label class="text-left mb-0" id="treatment_group_name">--</label>
                  </div>
                  <div class="form-group">
                    <label for="web_site" class=" pr-0 text-left font-weight-bold mb-0">Web Site:</label>
                    <div class="text-left"><label class="mb-1" id="treatment_web_site">--</label></div>
                  </div>
                  <div class="form-group ">
                    <label for="sel1" class=" pr-0 text-left font-weight-bold mb-0">Distributor Company Name:</label>
                    <div class="text-left"><label class="mb-1" id="treatment_distributors">--</label></div>
                  </div>
                  <div class="form-group">
                    <div class="" id="ema_area_details_div">
                      <label class=" font-weight-bold mb-0" id="ema_area_details " for="is_ema">Is EMA?:</label>
                      <div class="text-left"><label class="mb-1" id="treatment_is_ema">--</label></div>
                    </div>
                  </div>
                </div>
                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4">
                  <div class="col-lg-12 col-md-12 col-sm-12 mb-1 p-0">
                    <label for="name" class="mb-1 border-bottom w-100 pb-2 font-weight-bold">Address Details</label>
                  </div>
                  <div class="form-group ">
                        <label for="building_name" class="pr-0 text-left font-weight-bold mb-0">Building Name:</label>
                        <div class="text-left"><label class="mb-1" id="treatment_building_name">--</label></div>

                    </div>
                    <div class="form-group ">
                        <label for="address1" class=" pr-0 text-left font-weight-bold mb-0">Address Line 1:</label>
                        <div class="text-left"><label class="mb-1" id="treatment_full_address"></label></div>
                    </div>
                    <div class="form-group ">
                        <label for="address2" class="pr-0 text-left font-weight-bold mb-0">Address Line 2:</label>
                        <div class="text-left"><label class="mb-1" id="treatment_address_1">--</label></div>
                    </div>
                    <div class="form-group">
                        <label for="address3" class="pr-0 text-left font-weight-bold mb-0">Address Line 3:</label>
                        <div class="text-left"><label class="mb-1" id="treatment_address_2">--</label></div>
                    </div>
                    <div class="form-group">
                        <label for="full_address" class="pr-0 text-left font-weight-bold mb-0">Address Line 4:</label>
                        <div class="text-left"><label class="mb-1" id="treatment_address_3">--</label></div>
                    </div>
                    <div class="form-group ">
                        <label for="state" class="pr-0 text-left font-weight-bold mb-0">State/County:</label>
                        <div class="text-left"><label class="mb-1" id="treatment_state"></label></div>
                    </div>
                    <div class="form-group">
                        <label for="zipcode" class="pr-0 text-left font-weight-bold mb-0">Post/Zip code:</label>
                        <div class="text-left"><label class="mb-1" id="treatment_zipcode"></label></div>
                    </div>
                    <div class="form-group">
                        <label for="country_id" class="pr-0 text-left font-weight-bold mb-0">Country:</label>
                        <div class="text-left"><label class="mb-1" id="treatment_country"></label></div>
                    </div>

                </div>
                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4">
                  <div class="col-lg-12 col-md-12 col-sm-12 mb-1 p-0">
                    <label for="name" class="mb-1 border-bottom w-100 pb-2 font-weight-bold">Primary Contact Details</label>
                  </div>
                  <div class="form-group">
                        <label for="name_of_primary_contact" class="pr-0 text-left font-weight-bold mb-0">Name:</label>
                        <div class="text-left"><label class="mb-1" id="treatment_name_of_primary_contact"></label></div>
                    </div>
                    <div class="form-group ">
                        <label for="position" class=" pr-0 text-left font-weight-bold mb-0">Position:</label>
                        <div class="text-left"><label class="mb-1" id="treatment_position"></label></div>
                    </div>
                    <div class="form-group ">
                        <label for="telephone_number_of_primary_contact" class="pr-0 text-left font-weight-bold mb-0">Telephone Number:</label>
                      <div class="text-left"><label class="mb-1" id="treatment_telephone_number_of_primary_contact"></label></div>
                    </div>
                    <div class="form-group">
                        <label for="mobile_number_of_primary_contact" class="pr-0 text-left font-weight-bold mb-0">Mobile Number:</label>
                        <div class="text-left"><label class="mb-1" id="treatment_mobile_number_of_primary_contact"></label></div>
                    </div>
                    <div class="form-group">
                        <label for="fax_number" class="pr-0 text-left font-weight-bold mb-0">Fax:</label>
                        <div class="text-left"><label class="mb-1" id="treatment_fax_number">--</label></div>
                    </div>
                    <div class="form-group">
                        <label for="email_of_primary_contact" class="pr-0 text-left font-weight-bold mb-0">Email:</label>
                        <div class="text-left"><label class="mb-1" id="treatment_email_of_primary_contact"></label></div>
                    </div>

                </div>
              </div>
            </div>
          </div>
          <div class="row text-center mt-2">
            <div class="col-xl-12 col-lg-12 col-md-12">
              <a type="submit" class="btn btn-secondary text-capitalize mt-2  close_print" data-dismiss="modal" aria-label="Close" id="close">close</a>
              <a type="submit" class="btn btn-secondary text-capitalize mt-2" onclick="javascript:printDiv('printTreatmentcentreData')" id="printTreatmentCentreDetail" style="">Print</a> </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>



<div class="modal fade" id="edit_distributor_detail_popup" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                  <div class="col-lg-12 col-md-12 col-sm-12 mb-1 p-0"><label for="name" class="mb-1 border-bottom w-100 pb-2 font-weight-bold">Distributor Details</label></div>
                    <div class="form-group">
                  <label for="exampleInputEmail1" class="mb-1">Distributor Company Name</label>
                  <input type="text" class="form-control" id="full_company_nameEdit" name="full_company_name" value="{{$distributor->full_company_name}}">
                </div>
                <div class="form-group">
                  <label for="exampleInputEmail2" class="mb-1">Abbreviated Company Name</label>
                  <input type="text" class="form-control" name="abbreviated_company_name" value="{{$distributor->abbreviated_company_name}}">
                </div>
                <div class="form-group">
                  <label for="exampleInputEmail1" class="mb-1">Distributor Code</label>
                  <input type="text" class="form-control" name="distributor_code" value="{{$distributor->distributor_code}}">
                  <input type="hidden" name="id" id="id1">
                </div>
                <div class="form-group">
                  <div class="custom-control custom-checkbox mb-3">
                    <input type="checkbox" class="custom-control-input" id="is_group1" name="is_group" @if($distributor->
                    group_name) checked @endif>
                    <label class="custom-control-label" for="is_group1">Is Distributor part of a Group?</label>
                  </div>
                  <label for="group_name" class="mb-1">Group Name</label>
                  <input type="text" id="company_group_name"   class="form-control company_group_name" name="group_name" value="{{$distributor->group_name}}" @if($distributor->
                  group_name=='') disabled @endif> </div>
                  <div class="form-group ">
                  <label for="exampleInputEmail2" class="mb-1">Web Site</label>
                  <input type="text" class="form-control" name="web_site"
                value="{{$distributor->web_site}}">
                </div>


                </div>
                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4">
                  <div class="col-lg-12 col-md-12 col-sm-12 mb-1 p-0">
                    <label for="name" class="mb-1 border-bottom w-100 pb-2 font-weight-bold">Address Details</label>
                  </div>
                  <div class="form-group">
                    <label for="exampleInputEmail2" class="mb-1">Building Name</label>
                    <input type="text" class="form-control left" name="building_name" value="{{$distributor->building_name}}">
                  </div>
                  <div class="form-group">
                    <label for="exampleInputEmail2" class="mb-1">Address Line 1</label>
                    <input type="text" class="form-control left" name="address1" value="{{$distributor->address1}}">
                  </div>
                  <div class="form-group">
                    <label for="exampleInputEmail2" class="mb-1">Address Line 2</label>
                    <input type="text" class="form-control left" name="address2"
                value="{{$distributor->address2}}">
                  </div>
                  <div class="form-group">
                    <label for="exampleInputEmail2" class="mb-1">Address Line 3</label>
                    <input type="text" class="form-control left" name="address3" value="{{$distributor->address3}}">
                  </div>
                  <div class="form-group">
                    <label for="exampleInputEmail2" class="mb-1">Address Line 4</label>
                    <input type="text" class="form-control" name="full_address"
                value="{{$distributor->full_address}}">
                  </div>
                  <div class="form-group">
                    <label for="exampleInputEmail2" class="mb-1">State/County</label>
                    <input type="text" class="form-control left" name="state"
                value="{{$distributor->state}}">
                  </div>
                  <div class="form-group">
                    <label for="exampleInputEmail2" class="mb-1">Post/Zip code</label>
                    <input type="text" class="form-control left" name="zipcode"
                value="{{$distributor->zipcode}}">
                  </div>
                  <div class="form-group">
                    <label for="exampleInputEmail2" class="mb-1">Country</label>
                    <select class="form-control select_dropdown_edit"
                  name="country_id">
                      <option value="">--Select a Country--</option>


                  @foreach(App\Country::orderBy('name')->get(); as $country)


                      <option value="{{$country->id}}" @if($country->id == $distributor->country->id) selected='selected' @endif>{{$country->name}}</option>


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
                  <input type="text" class="form-control" name="name_of_primary_contact"
                value="{{$distributor->name_of_primary_contact}}">
                </div>
                  <div class="form-group">
                  <label for="exampleInputEmail2" class="mb-1">Position</label>
                  <input type="text" class="form-control left" name="position"
                value="{{$distributor->position}}">
                </div>
                <div class="form-group">
                  <label for="exampleInputEmail2" class="mb-1">Telephone Number</label>
                  <input type="text" class="form-control phoneregx" name="telephone_number_of_primary_contact" value="{{$distributor->telephone_number_of_primary_contact}}">
                </div>
                <div class="form-group">
                  <label for="exampleInputEmail2" class="mb-1">Mobile Number</label>
                  <input type="text" class="form-control phoneregx"  name="mobile_number_of_primary_contact" value="{{$distributor->mobile_number_of_primary_contact}}">
                </div>
                <div class="form-group">
                  <label for="exampleInputEmail2" class="mb-1">Fax</label>
                  <input type="text" class="form-control phoneregx" name="fax_number" value="{{$distributor->fax_number}}">
                </div>
                <div class="form-group">
                  <label for="exampleInputEmail2" class="mb-1">Email Address</label>
                  <input type="text" class="form-control" name="email_of_primary_contact"
                value="{{$distributor->email_of_primary_contact}}">
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
<!--Successfully Message -->
@endsection

@section('jsdependencies')
<script>
$(document).ready(function() {
    $('#example').DataTable();
} );
</script>
<script type="text/javascript">
  $(document).ready(function(){
  $("#popUpClose").click(function(){
    $("#add_new_hydracool, .modal-backdrop.show").hide();
  });
});
</script>
<script type="text/javascript">
  $(document ).ready(function() {
      $('#edit_distributor_detail_data').click(function(){
        $('#edit_distributor_detail').modal('hide');
        $('#edit_distributor_detail_popup').modal('show');

      })

      $('#edit_distributor_detail_popup').on("shown.bs.modal", function() {
          $("body").addClass("modal-open");
      });

      $('#distributor_close_button').click(function(){
        $("#edit_distributor_detail").removeClass('fullWidthInput');
      })

  });
</script>
<script>
  var addDistributoruser="{{url('/add-distributor-user')}}";
  var list_distributor_user="{{url('view-distributor-user')}}";
  var update_distributor="{{url('update-distributor')}}";

  var edit_distributor_user="{{url('edit-distributor-user')}}";
  var list_distributor="{{url('/distributor')}}";
  var update_distributor_user="{{url('/update-distributor-user')}}";
  var uniqueuseremail="{{url('/uniqueuseremail')}}";
  var uniqueusername="{{url('/uniqueusername')}}";
  var uniqueDistributorCode="{{url('uniqueDistributorCode')}}";
  var distributors_treatmentcenter_list="{{url('distributors-treatmentcenter-list')}}";
  var distributor_id = '{{$distributor->id}}';
  var treatmentcenterdetails = '{{ url("get-treatment-centre-details") }}';
  var base_url = '{{ url('/') }}';
  var isDetails=1;
</script>
<script src="{{asset('js/developer_js/distributor.js')}}"></script>
<script src="{{asset('js/developer_js/disributorValidation.js')}}"></script>
@endsection