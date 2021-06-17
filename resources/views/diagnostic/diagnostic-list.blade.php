@extends('layouts.app')
@section('title', 'Diagnostic Data')
@section('content')
<div class="container-fluid ">
  <div class="row">
    <div class="col-xl-12 col-lg-12">
      <h1 class="my-2">Diagnostic Data</h1>
    </div>
  </div>
</div>

<div class="container-fluid ">
  <div class="row">
    <div class="col-xl-12 col-lg-12 px-0">
      <nav aria-label="breadcrumb">
        {{ Breadcrumbs::render('diagnosticData') }}
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
        <h2 class="my-2 text-capitalize">Diagnostic Data</h2>
      </div>
      <div class="col-xl-12 col-lg-12 px-0">
        <div class="table-responsive mt_minus datatable_resize" id="diagnosticScroll">
          <table id="diagnostic_list" class="table table-striped table-bordered example" style="width:100%">
            <thead>
              <tr>
                <th>Date</th>
                <th>Time</th>
                <th>Treatment Centre</th>
                <th>SN of Unit</th>
                <th>Type (Diagnostic/Alarm)</th>
                <th>Description</th>
                <th>Last Comment</th>
                <th>Status</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tfoot style="display:none">
              <tr>
                <th dataName="TIME_UTC_date">Date</th>
                <th dataName="TIME_UTC_time">Time</th>
                <th dataName="treatment_center_name">Treatment Centre</th>
                <th dataName="serial_number">SN of Unit</th>
                <th dataName="diagnostic_type">Type (Diagnostic/Alarm)</th>
                <th dataName="description">Description</th>
                <th dataName="last_comment">Last Comment</th>
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

<!--Edit Distributor -->
<div id="printThis">
<div class="modal fade " id="view_log_details" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog homeModel" role="document">
    <div class="modal-content">
       <div class="modal-header">
        <h2 class="modal-title w-100 ">View Diagnostic Data</h2>
        <button type="button" class="close text-right" data-dismiss="modal" aria-label="Close" id="closeButton"> <span aria-hidden="true">X</span> </button>
      </div>
      <div class="modal-body px-3 max_height">
        <div class="row justify-content-xl-center justify-content-lg-center justify-content-md-center">
          <div class="col-xl-12 col-lg-12 col-md-12">
            <div class="form-row viewdatajson">
              </div>
            </div>
          </div>
          <div class="row text-center mt-2">
            <div class="col-xl-12 col-lg-12 col-md-12">
              <button class="btn btn-secondary close_print" data-dismiss="modal" aria-label="Close" id="close">Close</button>
              <button class="btn btn-secondary" id="btnPrint" onclick="javascript:printDiv('printThis')" >Print</button>
            </div>
          </div>
        </div>
    </div>
  </div>
</div>
</div>

<div id="printThis">
  <div class="modal fade " id="view_diagnostic_data" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog homeModel" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h2 class="modal-title w-100 ">Diagnostic Data</h2>
          <button type="button" class="close text-right" data-dismiss="modal" aria-label="Close" id="closeButton"> <span aria-hidden="true">X</span> </button>
        </div>
        <div class="modal-body px-3 max_height">
          <div class="row justify-content-xl-center justify-content-lg-center justify-content-md-center">
            <div class="col-xl-12 col-lg-12 col-md-12">
              <div class="form-row">

                <div class="form-group col-xl-6 col-lg-6 col-md-6 col-sm-6">
                  <div class="row">
                    <label for="date" class="col-12 pr-0 text-left font-weight-bold mb-0">Date:</label>
                    <label id="diagnostic_date" class="col-12 text-left modulename"></label>
                  </div>
                </div>

                <div class="form-group col-xl-6 col-lg-6 col-md-6 col-sm-6">
                  <div class="row">
                    <label for="date" class="col-12 pr-0 text-left font-weight-bold mb-0">Time:</label>
                    <label id="diagnostic_time" class="col-12 text-left modulename"></label>
                  </div>
                </div>

                <div class="form-group col-xl-6 col-lg-6 col-md-6 col-sm-6">
                  <div class="row">
                    <label for="dsn" class="col-12 pr-0 text-left font-weight-bold mb-0">DSN:</label>
                    <label id="diagnostic_dsn" class="col-12 text-left modulename"></label>
                  </div>
                </div>

                <div class="form-group col-xl-6 col-lg-6 col-md-6 col-sm-6">
                  <div class="row">
                    <label for="dsn" class="col-12 pr-0 text-left font-weight-bold mb-0">Treatment Centre Name:</label>
                    <label id="diagnostic_treatment_centre_name" class="col-12 text-left modulename"></label>
                  </div>
                </div>

                <div class="form-group col-xl-6 col-lg-6 col-md-6 col-sm-6">
                  <div class="row">
                    <label for="dsn" class="col-12 pr-0 text-left font-weight-bold mb-0">Distributor Name:</label>
                    <label id="diagnostic_distributor_name" class="col-12 text-left modulename"></label>
                  </div>
                </div>

                <div class="form-group col-xl-6 col-lg-6 col-md-6 col-sm-6">
                  <div class="row">
                    <label for="dsn" class="col-12 pr-0 text-left font-weight-bold mb-0">Alarm or Diagnostic information:</label>
                    <label id="diagnostic_alarm" class="col-12 text-left modulename"></label>
                  </div>
                </div>

                <div class="form-group col-xl-6 col-lg-6 col-md-6 col-sm-6">
                  
                    <label for="diagnostic_status" class="text-left font-weight-bold mb-0">Status</label>
                    <select class="form-control js-example-basic-single" id="diagnostic_status" name="diagnostic_status">
                      <option value="">--Select Status--</option>
                      <option value="Action Pending">Action Pending</option>
                      <option value="Action Progress">Action Progress</option>
                      <option value="Action Completed">Action Completed</option>
                    </select>
                 
                </div>

                <div class="input-group col-xl-12 col-lg-12 col-md-12 ">
                  <div class="w-100">
                    <label for="diagnostic_comment" class="mb-1">Comment</label>
                    <textarea class="form-control" id="diagnostic_comment" name="diagnostic_comment"></textarea>
                  </div>
                </div>
              </div>
            </div>
            <input type="hidden" id="json_id">
          </div>
          <div class="row text-center my-3">
            <div class="col-xl-12 col-lg-12 col-md-12">
              <button class="btn btn-secondary" id="save_diagnostic_comment">Save</button>
            </div>
          </div>

        </form>
        <div class="col-xl-12 col-lg-12 px-0">
          <div class="table-responsive dashbord_table w-100 float-left mb-2" id="">
            <table id="comment-log" class="table table-striped table-bordered example" style="width:100%">
              <thead>
                <tr>
                  <th>Comment</th>
                  <th>Added By</th>
                  <th>Added Date</th>
                </tr>
              </thead>
              <tbody id="comment_list"></tbody>
            </table>
          </div>
        </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('jsdependencies')
<script>
  var diagnostic_details = '{{ route("diagnosticDetails") }}';
  var diagnostic_data_list = '{{ route("diagnosticData") }}';
  var diagnostic_details_dashboards = "{{ route('diagnosticDataDashboard') }}";
  var add_diagnostic_comment = "{{ route('add-diagnostic-comment') }}";
</script>

<script src="{{ asset('js/developer_js/diagnostic.js') }}"></script>
@endsection