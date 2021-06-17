@extends('layouts.app')
@section('title', 'Audit Log')
@section('content')
<div class="container-fluid">
  <div class="row">
    <div class="col-xl-12 col-lg-12">
      <h1 class="my-2">Audit Log Data</h1>
    </div>
  </div>
</div>
<div class="container-fluid">
  <div class="row">
    <div class="col-xl-12 col-lg-12 px-0">
      <nav aria-label="breadcrumb">
        {{ Breadcrumbs::render('audit-list') }}
      </nav>
    </div>
  </div>
</div>
<div class="container-fluid">

  <div class="row mt-1">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
    <section class="gridMainContainer mt-0">
              <div class="col-xl-12 col-lg-12 ">
                  <h2 class="my-2">Audit Log List</h2>
              </div>
              <div class="col-xl-12 col-lg-12 px-0">
                <div class="table-responsive mt_minus datatable_resize" id="auditlogScroll">
                <table id="audit-log" class="table table-striped table-bordered example" style="width:100%">
                    <thead>
                      <tr>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Name</th>
                        <th>Company</th>
                        <th>Event</th>
                        <th>Description</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tfoot style="display:none">
                      <tr>
                        <th dataName="created_at">Date</th>
                        <th dataName="audit_timestamp">Time</th>
                        <th dataName="name">Name</th>
                        <th dataName="company_name">Company</th>
                        <th dataName="module_activity">Event</th>
                        <th dataName="description">Description</th>
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
        <h2 class="modal-title w-100 ">View Audit Log</h2>
        <button type="button" class="close text-right" data-dismiss="modal" aria-label="Close" id="closeButton"> <span aria-hidden="true">X</span> </button>
      </div>
      <div class="modal-body px-3 max_height">
          <div class="row justify-content-xl-center justify-content-lg-center justify-content-md-center">
            <div class="col-xl-12 col-lg-12 col-md-12">
              <div class="form-row">

                <div class="form-group col-lg-6 col-md-6 col-sm-6 col-12 mb-2">
                  <div class="row">
                  <label for="module_name" class="col-12 pr-0 text-left font-weight-bold mb-0">Module Name:</label>
                  <label id="module_name_details" class="col-12 text-left modulename"></label>
                </div>
                </div>
               <div class="form-group col-lg-6 col-md-6 col-sm-6 col-12 mb-2">
                <div class="row">
                  <label for="activity" class="col-12 pr-0 text-left font-weight-bold mb-0">Activity Perform:</label>
                  <label id="activity_details" class="col-12 text-left modulename"></label>
                </div>
                </div>
               <div class="form-group col-lg-6 col-md-6 col-sm-6 col-12 mb-2">
                <div class="row">
                  <label for="description" class="col-12 pr-0 text-left font-weight-bold mb-0">Description:</label>
                  <label id="description_details" class="col-12 text-left"></label>
                </div>
                </div>
                 <div class="form-group col-lg-6 col-md-6 col-sm-6 col-12 mb-2">
                <div class="row">
                  <label for="ip_address" class="col-12 pr-0 text-left font-weight-bold mb-0">Ip Address:</label>
                  <label id="ip_address_details" class="col-12 text-left"></label>
                </div>
                </div>
                <div class="form-group col-lg-12 col-md-12 col-sm-12 col-12 my-3">
                  <label for="requested_data" class="col-12 pl-0 text-left font-weight-bold">Requested Data:</label>
                  <div id="requested_data_details" class="mb-1">
                    <table id="datable_1" class="table table-hover display  pb-30" > <tbody></tbody> </table></div>
                </div>

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
@endsection

@section('jsdependencies')
  <script>
    var audit_list = '{{ url("audit-list") }}';
    var audit_details = '{{ url("view-audit-detail") }}';
    var download_audit_log = '{{ url("downalod-audit-log") }}';
  </script>
  <script src="{{ asset('js/developer_js/audit_log.js') }}"></script>
@endsection
