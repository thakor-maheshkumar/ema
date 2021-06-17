@extends('layouts.app')
@section('title', 'Distributor')
@section('content')
<div class="container-fluid">
  <div class="row">
    <div class="col-xl-12 col-lg-12">
      <h1 class="my-2">Help</h1>
    </div>
  </div>
</div>
<div class="container-fluid">
  <div class="row">
    <div class="col-xl-12 col-lg-12 px-0">
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb px-3 ">
          <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
          <li class="breadcrumb-item active" aria-current="page">Help</li>
        </ol>
      </nav>
    </div>
  </div>
</div>
<div class="container-fluid">
  <div class="row">
    <div class="col-xl-12 col-lg-12 ">
      <div class="row">
        <div class="col-xl-3 col-lg-3 col-md-3">
          <div class="nav-side-menu">
            <h2 class="my-2 border-bottom pb-2">Contents</h2>
            <i class="fa fa-bars fa-2x toggle-btn" data-toggle="collapse" data-target="#menu-content"></i>
            <div class="menu-list">
              <ul id="menu-content" class="menu-content collapse out">
                <h4 class="font-weight-bold mt-3 mb-0">Overview</h4>
                <li  data-toggle="collapse" data-target="#usertypes" class="collapsed active"> <a href="#usertype">User Type<span class="arrow"></span></a> </li>
                <ul class="sub-menu collapse" id="usertypes">
                  <li class="active"><a href="#systemadministratoruser">System Administrator User</a></li>
                  <li><a href="#emaanalystuser">EMA Analyst User</a></li>
                  <li><a href="#emaservicesupportuser">EMA Service Support User</a></li>
                  <li><a href="#distributorprimarycontact">Distributor Primary Contact </a></li>
                  <li><a href="#distributorprincipleuser">Distributor Principle User</a></li>
                  <li><a href="#distributorservicesupportuser">Distributor Service Support User</a></li>
                  <li><a href="#distributorsalesuser">Distributor Sales User</a></li>
                  <li><a href="#treatmentcentreprimarycontact">Treatment Centre Primary Contact</a></li>
                  <li><a href="#treatmentcentreprincipleuser" >Treatment Centre Principle User</a></li>
                </ul>
                <li  data-toggle="collapse" data-target="#companytypes" class="collapsed"> <a href="#">Company Types <span class="arrow"></span></a> </li>
                <ul class="sub-menu collapse" id="companytypes">
                  <li class="active"><a href="#ema">EMA</a></li>
                  <li><a href="#distributor">Distributor</a></li>
                  <li><a href="#treatmentcentre">Treatment Centre</a></li>
                </ul>
              </ul>
            </div>
          </div>
        </div>
        <div class="col-xl-9 col-lg-9 col-md-9 ">
          <div class="helpcontainer w-100">
            <div class="row mb-4" id="usertype">
              <div class="col-xl-12 col-lg-12 col-md-12 mt-4">
                <h3 class="font-weight-bold">User Types</h3>
                <ul>
                  <li>The EMA Portal supports six different user types. Each user type has different responsibilities and functional access rights to the EMA Portal.</li>
                  <li>The full list of rights for each user can be found in the Permitted Actions Tables for each user type </li>
                </ul>
              </div>
            </div>
            <div class="row mb-4" id="systemadministratoruser">
              <div class="col-xl-12 col-lg-12 col-md-12 mt-4">
                <h3 class="font-weight-bold">System Administrator User</h3>
                <ul>
                  <li>The System Administrator User is the highest authority on the EMA Portal and can undertake all functions. There must always be at least one System Administrator User active on the system at all times.</li>
                  <li>The System Administrator User can create all user types and subsequently modify them, including password changes/resets and delete them.</li>
                  <li>They can view all diagnostic information, including viewing current server activity such as login attempts and uploads, together with any failure conditions.</li>
                  <li>They are able to view audit logs showing the actions undertaken by any User.</li>
                  <li>Only the System Administrator User can upload media files to the Library.</li>
                  <li>The System Administrator User can also process Treatment Upload files on behalf of the Treatment Centres.</li>
                  <li>There are no limits on how many System Administrator Users can be created; but it will not be possible to delete all System Administrator Users and the final System Administrator User will have the maximum password attempts security requirement suspended.</li>
                  <li>A System Administrator User cannot delete themselves</li>
                  <li>The System Administrator User has unique access, via My Profile, to settings that effect the entire operation of the service, such as how many times a user can enter an incorrect password before the account is suspended for security reasons.</li>
                </ul>
              </div>
            </div>
            <div class="row mb-4" id="emaanalystuser">
               <div class="col-xl-12 col-lg-12 col-md-12 mt-4">
                <h3 class="font-weight-bold">EMA Analyst User</h3>
                <ul>
                  <li>The EMA Analyst User has view only access to the majority of the data stored in the EMA Portal but has limited ability to amend or delete it. The primary function of the EMA Analyst is to monitor and report on the performance of the HydraCool SRP devices by region and against protocols in order to improve client outcomes.</li>
                  <li>The EMA Analyst User Type will also have access to the output and alarms generated by the stored procedures that monitor the performance of the client base and solution usage.</li>
                  <li>The EMA Analyst has access to the support library of user/support material and training/marketing videos.</li>
                </ul>
              </div>
            </div>
            <div class="row mb-4" id="emaservicesupportuser">
               <div class="col-xl-12 col-lg-12 col-md-12 mt-4">
                <h3 class="font-weight-bold">EMA Service Support User</h3>
                <ul>
                  <li>The EMA Service Support User only has access to the diagnostic and error log data uploaded from the HydraCool SRP unit. They cannot access any client treatment data.</li>
                  <li>The EMA Service Support User will also have some privileges in respect to password resets, suspensions, releases, force outs and access to corporate data held about the Treatment Centres and Distributors.</li>
                  <li>The EMA Service Support User has access to the support library of user/support material and training/marketing videos.</li>
                </ul>
              </div>
            </div>
            <div class="row mb-4" id="distributorprimarycontact">
               <div class="col-xl-12 col-lg-12 col-md-12 mt-4">
                <h3 class="font-weight-bold">Distributor Primary Contact </h3>
                <ul>
                  <li>The Distributor Primary Contact is the main relationship contact between EMA Aesthetics and the Regional Distributor.</li>
                  <li>The Distributor Primary Contact is a non-interactive participant of the EMA Portal. The Distributor Primary Contact will receive emails from the EMA Portal for critical events such as Company Suspension. </li>
                </ul>
              </div>
            </div>
            <div class="row mb-4" id="distributorprincipleuser">
               <div class="col-xl-12 col-lg-12 col-md-12 mt-4">
                <h3 class="font-weight-bold">Distributor Principle User</h3>
                <ul>
                  <li>The Distributor Principle User has full functional access to the Users of the Distributor they are the principle of.</li>
                  <li>The Distributor Principle User has extensive management rights over the status of the Treatment Centres they are associated with, and the Users at those Treatment Centres.</li>
                  <li>The Distributor Principle User has full access to Treatment Data from the HydraCool devices at their associated Treatment Centres.</li>
                  <li>The Distributor Principle User has full access to the support library of the user/support material and training/marketing videos.</li>
                </ul>
              </div>
            </div>
            <div class="row mb-4" id="distributorservicesupportuser">
               <div class="col-xl-12 col-lg-12 col-md-12 mt-4">
                <h3 class="font-weight-bold">Distributor Service Support User</h3>
                <ul>
                  <li>Distributor Service Support Users can only access information relating to Treatment Centres associated with their Distributor.</li>
                  <li>The Distributor Service Support User only has access to the diagnostic and error log data uploaded from the HydraCool SRP units for the Treatment Centres associated with their Distributor. They cannot access any client treatment data.</li>
                  <li>The Distributor Service Support User will also have some privileges in respect of password resets, suspensions, releases, force outs and access to corporate data held about the Treatment Centres associated with their Distributor.</li>
                  <li>The Distributor Service Support User has access to the support library of user/support material and training/marketing videos.</li>
                </ul>
              </div>
            </div>
            <div class="row mb-4" id="distributorsalesuser">
              <div class="col-xl-12 col-lg-12 col-md-12 mt-4">
                <h3 class="font-weight-bold">Distributor Sales User</h3>
                <ul>
                  <li>Distributor Sales Users can only access information relating to Treatment Centres associated with their Distributor. </li>
                  <li>The Distributor Sales User has view only access to the majority of the data stored in the EMA Portal for the Treatment Centres associated with their Distributor but has limited ability to amend or delete it. The primary function of Distributor Sales User is to monitor and report on the performance of the HydraCool SRP devices in their region and against protocols in order to improve client outcomes.</li>
                  <li>The Distributor Sales User will also have access to the output and alarms generated by the stored procedures that monitor the performance of the client base and solution usage.</li>
                  <li>The Distributor Sales User has access to the support library of user/support material and training/marketing videos.</li>
                </ul>
              </div>
            </div>
            <div class="row mb-4" id="treatmentcentreprimarycontact">
               <div class="col-xl-12 col-lg-12 col-md-12 mt-4">
                <h3 class="font-weight-bold">Treatment Centre Primary Contact</h3>
                <ul>
                  <li>The Treatment Centre Primary Contact is the main relationship contact between EMA Aesthetics or the regional Distributor and the Treatment Centre.</li>
                  <li>The Treatment Centre Primary Contact is a non-interactive participant of the EMA Portal. The Treatment Centre Primary Contact will receive emails from the EMA Portal for critical events such as Company Suspension. </li>
                </ul>
              </div>
            </div>
            <div class="row mb-4" id="treatmentcentreprincipleuser">
               <div class="col-xl-12 col-lg-12 col-md-12 mt-4">
                <h3 class="font-weight-bold">Treatment Centre Principle User</h3>
                <ul>
                  <li>The Treatment Centre Principle User has full functional access to the Users of the Treatment Centre they are the principle of.</li>
                  <li>The Treatment Centre Principle User has full data access to Treatment Data from their HydraCool devices.</li>
                  <li>The Treatment Centre Principle has access to the support library of user/support material and training/marketing videos.</li>
                </ul>
              </div>
            </div>
            <div class="row mb-4" id="ema">
               <div class="col-xl-12 col-lg-12 col-md-12 mt-4">
                <h3 class="font-weight-bold">EMA</h3>
                <ul>
                  <li>EMA is EMA Aesthetics and EMA Users are either employees of EMA Aesthetics or trusted parties of EMA Aesthetics who have been granted access to the EMA Portal by a System Administrator.</li>
                  <li>EMA Aesthetics is the owner and operator of the EMA Portal.</li>
                  <li>Only EMA System Administrators can create Distributor Companies on the EMA Portal.</li>
                </ul>
              </div>
            </div>
            <div class="row mb-4" id="distributor">
               <div class="col-xl-12 col-lg-12 col-md-12 mt-4">
                <h3 class="font-weight-bold">Distributor</h3>
                <ul>
                  <li>A Distributor is a company that has signed agreements with EMA Aesthetics for the provision and support of HydraCool SRP devices within a region and who are responsible for the provision of consumables in that region.</li>
                  <li>Treatment Centres are directly supported by the Distributor in the same region.</li>
                  <li>A Distributor can create a Treatment Centre on the EMA Portal and fully manage it, the users associated with it and the devices allocated to that Treatment Centre.</li>
                </ul>
              </div>
            </div>
            <div class="row mb-4" id="treatmentcentre">
               <div class="col-xl-12 col-lg-12 col-md-12 mt-4">
                <h3 class="font-weight-bold">Treatment Centre</h3>
                <ul>
                  <li>The Treatment Centres are the end users and owners of the HydraCool SRP devices and consumers of treatment solutions, and cosmetics formulated and supplied by EMA Aesthetics.</li>
                  <li>The Treatment Centres are supported by the regional Distributors.</li>
                  <li>The Treatment Centres undertake client treatments and the data relating to the provision of those treatments is uploaded to the EMA Portal for record keeping and analysis purposes.</li>
                  <li>All Treatment data is anonymised. </li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
<!-- @section('jsdependencies')
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
@endsection -->
