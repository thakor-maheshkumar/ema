@extends('layouts.app')
@section('title', 'Treatment Centre Files')
@section('content')
<div class="container-fluid ">
  <div class="row">
    <div class="col-xl-12 col-lg-12">
      <h1 class="my-2">Treatment Data</h1>
    </div>
  </div>
</div>

<div class="container-fluid ">
  <div class="row">
    <div class="col-xl-12 col-lg-12 px-0">
      <nav aria-label="breadcrumb">
        {{ Breadcrumbs::render('list-treatmentcentre-file') }}
      </nav>
    </div>
  </div>
</div>

<div class="container-fluid">
@if($isAdd=='1')
  <div class="row px-3">
    <div class="col-xl-12 col-lg-12 px-0">
      <div class="row d-flex align-items-end ">
        <div class="col-xl-12 col-lg-12 col-md-12">
          <button class="btn btn-secondary" data-toggle="modal" data-target="#add_new_treatmentcenter_file">Upload File</button>
         </div>
      </div>
    </div>
  </div>
@endif
  <div class="row">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
    <section class="gridMainContainer">
      <div class="col-xl-12 col-lg-12 ">
        <h2 class="my-2 text-capitalize">Treatment Data</h2>
      </div>
      <div class="col-xl-12 col-lg-12 px-0">
        <div class="table-responsive mt_minus datatable_resize" id="treatmentcntreScroll">
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
</div>


<!--Add New Media library -->
<div class="modal fade " id="add_new_treatmentcenter_file" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog " role="document">
    <div class="modal-content">
      <div class="modal-header">
      <h2 class="modal-title">Upload and Support Data</h2>
      <button type="button" class="close text-right" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">X</span> </button>
    </div>
      <div class="modal-body px-xl-3 px-lg-3 px-md-3 px-sm-3 max_height">
        <form id="file_upload">
          <div class="row justify-content-xl-center justify-content-lg-center justify-content-md-center">
            <div class="col-xl-12 col-lg-12 col-md-12">

                <div class="input-group mb-3">
                  <div class="custom-file">
                    <input type="file" class="custom-file-input " id="treatment_file" name="treatment_file">
                    <label class="custom-file-label border-radius-none py-2" for="treatment_file">Choose file</label>
                  </div>
                </div>
                <div id="error_area"></div>
            </div>
          </div>
          <div class="row text-center mt-2">
            <div class="col-xl-12 col-lg-12 col-md-12">
              <button type="submit" id="save_treatmentcentre_file" class="btn btn-secondary text-right">Submit</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<!--Add New Media library -->


@endsection

@section('jsdependencies')
<script>
  var getTreatmentCenterId = '{{ $getTreatmentCenterId }}';
  var saveTreatmentCentreFile = '{{ route("save-treatmentcentrefile-upload") }}';
  var treatmentcenterdetails = '{{ url("get-treatment-centre-details") }}';
  var listtreatmentCentreFiles = '{{ url("list-treatmentcentre-file") }}';
</script>

<script src="{{ asset('js/developer_js/treatmentcenter_file_upload.js') }}"></script>
@endsection