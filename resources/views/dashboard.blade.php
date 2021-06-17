@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid ">
  <div class="row">
    <div class="col-xl-12 col-lg-12">
      <h1 class="mt-2 mb-0 text-capitalize">dashboard</h1>
    </div>
  </div>
</div>
<div class="container-fluid">
  @hasanyrole('system administrator|ema service support|distributor principal|distributor service')
  <div class="row">
    <div class="col-xl-12 col-lg-12">
      <section class="gridMainContainer ">
        <div class="col-xl-12 col-lg-12">
          <div class="row">
            <div class="col-xl-7 col-lg-7 col-md-7 col-sm-5">
              <h2 class="pt-2 float-left text-capitalize mb-2 d-inline ">System Activity Log</h2></div>
              <div class="col-xl-5 col-lg-5 col-md-5 col-sm-5 mt-1">
                <div class="dt-buttons mr-0">
                  <button class="dt-button float-left" tabindex="0" aria-controls="audit-log" type="button" id="clear" data-attr="audit-log"><span>Clear Filters</span></button>
                  <a href="{{ route('audit-list') }}" target="_blanck" class="dt-button float-left" tabindex="0" aria-controls="audit-log" ><span>View All</span></a>
                </div>
              </div>
            </div>
          </div>

          <div class="col-xl-12 col-lg-12 px-0">
            <div class="table-responsive dashbord_table w-100 float-left mb-2" id="dashboardScroll">
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
    @endhasanyrole

    <div class="row pt-1">
      <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 ">
        <div class="row">
          <div class="col-xl-6 col-lg-12 col-md-12 col-sm-12">
            <section class="gridMainContainer ">
              <div class="col-xl-12 col-lg-12">
                <div class="row">
                  <div class="col-xl-7 col-lg-7 col-md-7 col-sm-5">
                    <h2 class="pt-2 float-left text-capitalize mb-2 d-inline ">Summary of Diagnostics and Errors</h2></div>
                    <div class="col-xl-5 col-lg-5 col-md-5 col-sm-5 mt-1">
                      <div class="dt-buttons mr-0">
                        <button class="dt-button float-left" tabindex="0" aria-controls="diagnostic_list_dashboard" type="button" id="clear" data-attr="diagnostic_list_dashboard"><span>Clear Filters</span></button>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-xl-12 col-lg-12 px-0 float-left">
                  <div class="mb-3" id="diagnosticsScroll">
                    <table id="diagnostic_list_dashboard" class="table table-striped table-bordered example" style="width:100%">
                      <thead>
                        <tr>
                          <th>Date</th>
                          <th>Time</th>
                          <th>DSN</th>
                          <th>STA</th>
                          <th>Traffic Light</th>
                          <th>Action</th>
                        </tr>
                      </thead>
                      <tbody>
                      </tbody>
                      <tfoot style="display:none">
                        <tr>
                          <th dataName="date">Date</th>
                          <th dataName="time">Time</th>
                          <th dataName="type">Type</th>
                        </tr>
                      </tfoot>
                    </table>
                  </div>
                </div>
              </section>
            </div>
            <div class="col-xl-6 col-lg-12 col-md-12 col-sm-12 mt-xl-0">
              <section class="gridMainContainer ">
                <div class="col-xl-12 col-lg-12 ">
                  <h2 class="pt-2 float-left text-capitalize text-left mb-2 d-inline">Summary of Installed units</h2>
                </div>

                <div class="col-xl-12 col-lg-12 px-0 float-left">
                  <div class="table-responsive mb-3" id="installedunitsScroll">
                    <table id=" " class="table table-striped table-bordered example" style="width:100%">
                      <thead>
                        <tr>
                          <th>Name</th>
                          <th >Position</th>
                          <th>Office</th>
                          <th width="50">Age</th>
                          <th width="120">Start date</th>

                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td>Tiger Nixon</td>
                          <td>System Architect</td>
                          <td>Edinburgh</td>
                          <td>61</td>
                          <td>2011/04/25</td>

                        </tr>
                        <tr>
                          <td>Garrett Winters</td>
                          <td>Accountant</td>
                          <td>Tokyo</td>
                          <td>63</td>
                          <td>2011/07/25</td>

                        </tr>
                        <tr>
                          <td>Ashton Cox</td>
                          <td>Technical Author</td>
                          <td>San Francisco</td>
                          <td>66</td>
                          <td>2009/01/12</td>

                        </tr>
                        <tr>
                          <td>Cedric Kelly</td>
                          <td>Senior Javascript</td>
                          <td>Edinburgh</td>
                          <td>22</td>
                          <td>2012/03/29</td>

                        </tr>
                        <tr>
                          <td>Airi Satou</td>
                          <td>Accountant</td>
                          <td>Tokyo</td>
                          <td>33</td>
                          <td>2008/11/28</td>

                        </tr>

                      </tbody>
                    </table>
                  </div>
                </div>
              </section>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!--View Audit Log Detail -->
    <div class="modal fade " id="view_audit_log" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog homeModel" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h2 class="modal-title w-100 ">View Audit Log Detail</h2>
            <button type="button" class="close text-right" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">X</span> </button>
          </div>
          <div class="modal-body px-xl-5 px-lg-5 px-md-5 px-sm-5 max_height">
            <div class="row text-center mt-3">
              <div class="col-xl-12 col-lg-12 col-md-12">
                <button class="btn btn-secondary text-capitalize" data-toggle="modal" aria-label="Close">Close</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!--View Audit Log Detail -->

    <!--Edit Distributor -->
    <div id="printThis">
      <div class="modal fade " id="view_log_details" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog homeModel" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h2 class="modal-title w-100 ">System Activity Log</h2>
              <button type="button" class="close text-right" data-dismiss="modal" aria-label="Close" id="closeButton"> <span aria-hidden="true">X</span> </button>
            </div>
            <div class="modal-body px-3 max_height">
              <div class="row justify-content-xl-center justify-content-lg-center justify-content-md-center">
                <div class="col-xl-12 col-lg-12 col-md-12">
                  <div class="form-row">

                    <div class="form-group col-xl-6 col-lg-6 col-md-6 col-sm-6">
                      <div class="row">
                        <label for="module_name" class="col-12 pr-0 text-left font-weight-bold mb-0">Module Name:</label>
                        <label id="module_name_details" class="col-12 text-left modulename"></label>
                      </div>
                    </div>
                    <div class="form-group col-xl-6 col-lg-6 col-md-6 col-sm-6">
                      <div class="row">
                        <label for="activity" class="col-12 pr-0 text-left font-weight-bold mb-0">Activity Perform:</label>
                        <label id="activity_details" class="col-12 text-left modulename"></label>
                      </div>
                    </div>
                    <div class="form-group col-xl-6 col-lg-6 col-md-6 col-sm-6">
                      <div class="row">
                        <label for="description" class="col-12 pr-0 text-left font-weight-bold mb-0">Description:</label>
                        <label id="description_details" class="col-12 text-left"></label>
                      </div>
                    </div>
                    <div class="form-group col-xl-6 col-lg-6 col-md-6 col-sm-6">
                      <div class="row">
                        <label for="ip_address" class="col-12 pr-0 text-left font-weight-bold mb-0">Ip Address:</label>
                        <label id="ip_address_details" class="col-12 text-left"></label>
                      </div>
                    </div>
                    <div class="form-group col-xl-12 col-lg-12 col-md-12 col-sm-12">
                      <label for="requested_data" class="mb-1 font-weight-bold">Requested Data:</label>
                      <div id="requested_data_details" class="mb-1">
                        <table id="datable_1" class="table table-hover display pb-30" > <tbody></tbody> </table></div>
                      </div>

                    </div>
                  </div>
                </div>
                <div class="row text-center mt-2">
                  <div class="col-xl-12 col-lg-12 col-md-12">
                    <button class="btn btn-secondary close_print" data-dismiss="modal" aria-label="Close" id="close">Close</button>
                    <button class="btn btn-secondary print_btn" onclick="javascript:printDiv('printThis')" id="btnPrint">Print</button>
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
    var system_activity_list = '{{ url("system-activity-list") }}';
    var audit_details = '{{ url("view-audit-detail") }}';
    var auditlogList = "{{ route('audit-list') }}";
    var diagnostic_details_dashboards = "{{ route('diagnosticDataDashboard') }}";
    var add_diagnostic_comment = "{{ route('add-diagnostic-comment') }}";
    var diagnostic_data_list = '{{ route("diagnosticData") }}';
  </script>
  <script src="{{ asset('js/developer_js/dashboard.js') }}"></script>
  <script src="{{ asset('js/developer_js/diagnostic.js') }}"></script>
  @endsection