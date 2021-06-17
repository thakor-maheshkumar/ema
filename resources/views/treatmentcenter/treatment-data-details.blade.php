@extends('layouts.app')
@section('title', 'Treatment Data')
@section('content')
<div class="container-fluid">
  <div class="row">
    <div class="col-xl-12 col-lg-12">
      <h1 class="my-2">Treatment Data Centre</h1>
    </div>
  </div>
</div>
<div class="container-fluid">
  <div class="row">
    <div class="col-xl-12 col-lg-12 px-0">
      <nav aria-label="breadcrumb">
        @php
          $url = url()->previous();
          $route = app('router')->getRoutes($url)->match(app('request')->create($url))->getName();
        @endphp
        @if($route == 'list-treatmentcentre-file')
            <ol class="breadcrumb px-3 ">
              <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
              <li class="breadcrumb-item"><a href="{{ route('list-treatmentcentre-file') }}">Treatment Data</a></li>
              <li class="breadcrumb-item active" aria-current="page">{{ $UID }}</li>
            </ol>
        @else
          <ol class="breadcrumb px-3 ">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('treatment-centre-list') }}">Treatment Centre</a></li>
            <li class="breadcrumb-item" aria-current="page"><a href="{{ route('view-treatment-center',$treatmentcenterDetails->id) }}">{{ $treatmentcenterDetails->full_company_name }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">Treatment Data ({{ $UID }})</li>
          </ol>
        @endif
      </nav>
    </div>
  </div>
</div>
<div class="container-fluid">
  <div class="row treatmentdatacenter">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
        <div class="table-responsive">
  <table class="table table-bordered">
    <tr>
      <td><label class="font-weight-bold">EMA Customer Number</label></td>
      <td><label>{{  $UID }}</label></td>
      <td><label class="font-weight-bold">Device</label></td>
      <td><label class="font-weight-bold">S/N</label></td>
      <td><label class="font-weight-bold">Session Time</label></td>
      <td><label class="font-weight-bold">Total Time( Min)</label></td>
      <td><label class="font-weight-bold">Total Time( Hrs)</label></td>
    </tr>
    <tr>
      <td><label class="font-weight-bold">Treatment Centre Name</label></td>
      <td><label>{{ $treatmentcenterDetails->full_company_name }}</label></td>
      <td><label class="font-weight-bold">Main Unit</label></td>
      <td><label>{{  $DSN }}</label></td>
      <td><label>{{ $treatmentjsonData['sessionTimeSeconds'] }}</label></td>
      <td><label>{{ $treatmentjsonData['sessionTime'] }}</label></td>
      <td><label>{{ $treatmentjsonData['sessionTimeHour'] }}</label></td>
    </tr>
    <tr>
      <td rowspan="2" valign="top"><label class="font-weight-bold">Address</label></td>
      <td rowspan="2" valign="top"><label>{{ $treatmentcenterDetails->address_1 }}</label></td>
      <td><label class="font-weight-bold">Hub-Board</label></td>
      <td><label>{{ !empty($arrDeviceSerialNumber) ? $arrDeviceSerialNumber->Hub_Board_unit : '' }}</label></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td><label class="font-weight-bold">AquaB</label></td>
      <td><label>{{ !empty($arrDeviceSerialNumber) ? $arrDeviceSerialNumber->AquaB_unit : '' }}</label></td>
      <td><label>{{ $treatmentjsonData['AquaBSessionTimeSeconds'] }}</label></td>
      <td><label>{{ $treatmentjsonData['AquaBSessionTime'] }}</label></td>
      <td><label>{{ $treatmentjsonData['AquaBSessionTimeHour'] }}</label></td>
    </tr>
    <tr>
      <td><label class="font-weight-bold">Town</label></td>
      <td>&nbsp;</td>
      <td><label class="font-weight-bold">VibroX</label></td>
      <td><label>{{ !empty($arrDeviceSerialNumber) ? $arrDeviceSerialNumber->VibroX_unit : '' }}</label></td>
     <td><label>{{ $treatmentjsonData['VibroXSessionTimeSeconds'] }}</label></td>
      <td><label>{{ $treatmentjsonData['VibroXSessionTime'] }}</label></td>
      <td><label>{{ $treatmentjsonData['VibroXSessionTimeHour'] }}</label></td>
    </tr>
    <tr>
      <td><label class="font-weight-bold">County/State</label></td>
      <td><label>{{ $treatmentcenterDetails->state }}</label></td>
      <td><label class="font-weight-bold">MicroT</label></td>
      <td><label>{{ !empty($arrDeviceSerialNumber) ? $arrDeviceSerialNumber->MicroT_unit : '' }}</label></td>
      <td><label>{{ $treatmentjsonData['MicroTSessionTimeSeconds'] }}</label></td>
      <td><label>{{ $treatmentjsonData['MicroTSessionTime'] }}</label></td>
      <td><label>{{ $treatmentjsonData['MicroTSessionTimeHour'] }}</label></td>
    </tr>
    <tr>
      <td><label class="font-weight-bold">Post/Zip</label></td>
      <td><label>{{ $treatmentcenterDetails->zipcode }}</label></td>
      <td><label class="font-weight-bold">Collagen+</label></td>
      <td><label>{{ !empty($arrDeviceSerialNumber) ? $arrDeviceSerialNumber->Collagen_unit: '' }}</label></td>
      <td><label>{{ $treatmentjsonData['CollagenSessionTimeSeconds'] }}</label></td>
      <td><label>{{ $treatmentjsonData['CollagenSessionTime'] }}</label></td>
      <td><label>{{ $treatmentjsonData['CollagenSessionTimeHour'] }}</label></td>
    </tr>
    <tr>
      <td><label class="font-weight-bold">Country</label></td>
      <td><label>{{ $treatmentcenterDetails->country_name }}</label></td>
      <td><label class="font-weight-bold">UltraB</label></td>
      <td><label>{{ !empty($arrDeviceSerialNumber) ? $arrDeviceSerialNumber->UltraB_unit : '' }}</label></td>
      <td><label>{{ $treatmentjsonData['UltraBSessionTimeSeconds'] }}</label></td>
      <td><label>{{ $treatmentjsonData['UltraBSessionTime'] }}</label></td>
      <td><label>{{ $treatmentjsonData['UltraBSessionTimeHour'] }}</label></td>
    </tr>
    <tr>
      <td><label class="font-weight-bold">Email</label></td>
      <td><label>{{ $treatmentcenterDetails->email_of_primary_contact }}</label></td>
      <td><label class="font-weight-bold">IMSI</label></td>
      <td><label>{{ $treatmentjsonData['imsi']  }}</label></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td><label class="font-weight-bold">Telephone</label></td>
      <td><label>{{ $treatmentcenterDetails->telephone_number_of_primary_contact }}</label></td>
      <td><label class="font-weight-bold">Upload Method</label></td>
      <td><label>Direct</label></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td><label class="font-weight-bold">Fax</label></td>
      <td><label>{{ $treatmentcenterDetails->fax_number }}</label></td>
      <td><label class="font-weight-bold">Upload Date</label></td>
      <td><label>{{ $treatmentjsonData['uploaddate'] }}</label></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td><label class="font-weight-bold">Principle Contact</label></td>
      <td><label>{{ $treatmentcenterDetails->name_of_primary_contact }}</label></td>
      <td><label class="font-weight-bold">Upload Time(UTC)</label></td>
      <td><label>{{ $treatmentjsonData['uploadtime'] }}</label></td>
      <td colspan="2" class="text-center"><label class="font-weight-bold ">Upload Time(Local)</label></td>
      <td><label>{{ $treatmentjsonData['localDate'] }}</label></td>
    </tr>
  </table>
</div>

    </div>
  </div>
  <div class="row mt-3">
      <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
        <div style="max-height: 535px; overflow-y: scroll;">
        <div class="table-responsive ">
          <table width="100%" class="table dataTable treatmentdata gridMainContainer">
  <!-- <tr>

    <td class="tableMainbg">&nbsp;</td>
  </tr> -->
  @if(!empty($arrRootFinal))
  @php $i=1;$parI=1 @endphp
  @foreach ($arrRootFinal as $key=>$mainTreatmentData)
    <tr>
      <td align="center" class="tableMainbg"><label class="font-weight-bold">Mode</label></td>
      <td class="tableMainbg"><label>{{ jsonDataShortCode($mainTreatmentData['mode'],'mod') }}</label></td>
      <td align="center" class="tableMainbg"><label class="font-weight-bold">Skin Type</label></td>
      <td align="center" class="tableMainbg"><label>{{ jsonDataShortCode($mainTreatmentData['SKT'],'skin_type') }}</label></td>
      <td align="center" class="tableMainbg"><label class="font-weight-bold">Skin Condition</label></td>

          @php
          $skinTypeArray = json_decode($mainTreatmentData['SKC']);
          @endphp

          @if(!empty($skinTypeArray))
          @foreach ($skinTypeArray as $skinType)
              <td align="center" class="tableMainbg"><label>{{ jsonDataShortCode($skinType,'skin_condition') }}</label></td>
          @endforeach
          @endif
      <td class="tableMainbg"><label></label></td>
      <td class="tableMainbg"><label></label></td>
      <td class="tableMainbg"><label></label></td>
      <td class="tableMainbg"><label></label></td>
    </tr>
    @if($i==1)
    <tr>
      <td align="center" class="tableSecongbg"><label class="font-weight-bold">Sequence</label></td>
      <td class="tableSecongbg"><label class=" font-weight-bold">Technology</label></td>
      <td align="center" class="tableSecongbg"><label class="font-weight-bold">Treatment Area</label></td>
      <td align="center" class="tableSecongbg"><label class="font-weight-bold">Intensity</label></td>
      <td align="center" class="tableSecongbg"><label class="font-weight-bold">Vacuum</label></td>
      <td align="center" class="tableSecongbg"><label class="font-weight-bold">Pulsed</label></td>
      <td align="center" class="tableSecongbg"><label class="font-weight-bold">Flow</label></td>
      <td align="center" class="tableSecongbg"><label class="font-weight-bold">Time</label></td>
      <td align="center" class="tableSecongbg"><label class="font-weight-bold">Solution</label></td>
      <td align="center" class="tableSecongbg"><label class="font-weight-bold">Cosmetic Pack</label></td>
      <td class="tableSecongbg"><label class="font-weight-bold">View</label></td>
    </tr>
    @endif

    @foreach ($mainTreatmentData['PAR'] as $itemPAR)
    <tr>
      <td align="center"><label>{{ $parI }}</label></td>
      <td><label>{{ jsonDataShortCode($itemPAR['technology'],'technology') }}</label></td>
      <td align="center"><label>{{ jsonDataShortCode($itemPAR['treatment_area'],'body_parts') }}</label></td>
      <td align="center"><label>{{ $itemPAR['intensity_of_vacuum'] }}</label></td>
      <td align="center"><label>{{ $itemPAR['vacuum'] }}</label></td>
      <td align="center"><label>{{ $itemPAR['pulsed'] }}</label></td>
      <td align="center"><label>{{ $itemPAR['flow'] }}</label></td>
      <td align="center"><label>{{ $itemPAR['treatment_time'] }}</label></td>
      <td align="center"><label>{{ $itemPAR['bottle'] }}</label></td>
      <td align="center"><label>{{ $itemPAR['mode_selected'] }}</label></td>
      <td><label><a href="javascript:;" class="viewrawjson" data-jsonData="{{ $itemPAR['rawJsonData'] }}"  title="View Raw Data"><i class="far fa-eye"></i></a></label></td>
      @php
      $parI++
    @endphp
      @endforeach
    </tr>
    @php $i++ @endphp
  @endforeach
  @endif
  @php
    $arrSolutionOneUsed = array();
    $arrSolutionTwoUsed = array();
    $arrSolutionThreeUsed = array();
    $arrSolutionFourUsed = array();
  @endphp
  @if(!empty($arrCSNData))
  @foreach ($arrCSNData as $solutionData)
    @php
      $arrSolutionOneUsed[] = $solutionData['starting_solution']['S1'] - $solutionData['edning_solution']['S1'];
      $arrSolutionTwoUsed[]= $solutionData['starting_solution']['S2'] - $solutionData['edning_solution']['S2'];
      $arrSolutionThreeUsed[] = $solutionData['starting_solution']['S3'] - $solutionData['edning_solution']['S3'];
      $arrSolutionFourUsed[] = $solutionData['starting_solution']['S4'] - $solutionData['edning_solution']['S4'];
    @endphp

</table>


        </div>
        </div>
        <div class="table-responsive mt-n2">

          <table width="100%" class="table dataTable treatmentdata gridMainContainer">
   <tr>
    <td colspan="2" align="center" class="tableMainbg"><label class="font-weight-bold">Starting Solution Levels</label></td>
    <td align="center" class="tableMainbg"><label class="font-weight-bold">S1</label></td>
    <td align="center" class="tableMainbg"><label class="">{{ $solutionData['starting_solution']['S1'] }}</label></td>
    <td align="center" class="tableMainbg"><label class="font-weight-bold">S2</label></td>
    <td align="center" class="tableMainbg"><label class="">{{ $solutionData['starting_solution']['S2'] }}</label></td>
    <td align="center" class="tableMainbg"><label class="font-weight-bold">S3</label></td>
    <td align="center" class="tableMainbg"><label class="">{{ $solutionData['starting_solution']['S3'] }}</label></td>
    <td align="center" class="tableMainbg"><label class="font-weight-bold">S4</label></td>
    <td align="center" class="tableMainbg"><label class="">{{ $solutionData['starting_solution']['S4'] }}</label></td>
    <td class="tableMainbg">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2" align="center" class="tableSecongbg"><label class="font-weight-bold">Finishing Solution Levels</label></td>
    <td align="center" class="tableSecongbg"><label class="font-weight-bold">S1</label></td>
    <td align="center" class="tableSecongbg"><label class="">{{ $solutionData['edning_solution']['S1'] }}</label></td>
    <td align="center" class="tableSecongbg"><label class="font-weight-bold">S2</label></td>
    <td align="center" class="tableSecongbg"><label class="">{{ $solutionData['edning_solution']['S2'] }}</label></td>
    <td align="center" class="tableSecongbg"><label class="font-weight-bold">S3</label></td>
    <td align="center" class="tableSecongbg"><label class="">{{ $solutionData['edning_solution']['S3'] }}</label></td>
    <td align="center" class="tableSecongbg"><label class="font-weight-bold">S4</label></td>
    <td align="center" class="tableSecongbg"><label class="">{{ $solutionData['edning_solution']['S4'] }}</label></td>
    <td class="tableSecongbg"><label></label></td>
  </tr>
  @endforeach
  @endif



  <tr>
    <td colspan="2" align="center" class="tableMainbg"><label class="font-weight-bold">Solution Used</label></td>
    <td align="center" class="tableMainbg"><label class="font-weight-bold">S1</label></td>
    <td align="center" class="tableMainbg"><label class="">{{ array_sum($arrSolutionOneUsed) }}</label></td>
    <td align="center" class="tableMainbg"><label class="font-weight-bold">S2</label></td>
    <td align="center" class="tableMainbg"><label class="">{{ array_sum($arrSolutionTwoUsed) }}</label></td>
    <td align="center" class="tableMainbg"><label class="font-weight-bold">S3</label></td>
    <td align="center" class="tableMainbg"><label class="">{{ array_sum($arrSolutionThreeUsed) }}</label></td>
    <td align="center" class="tableMainbg"><label class="font-weight-bold">S4</label></td>
    <td align="center" class="tableMainbg"><label class="">{{ array_sum($arrSolutionFourUsed) }}</label></td>
    <td class="tableMainbg"><label></label></td>
  </tr>
</table>
        </div>
      </div>
  </div>
  <div class="row mt-3">

      <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
          <div class="gridMainContainer">
            <div class="col-xl-12 col-lg-12"><h2 class="my-2 text-capitalize">Alarms and Diagnostics</h2></div>
             <div class="table-responsive">
            <table width="100%" border="0" class="table dataTable treatmentdata">
            <tr>
              <td align="center" class="tableMainbg"><label class="font-weight-bold">Date</label></td>
              <td align="center" class="tableMainbg"><label class="font-weight-bold">Time(UTC)</label></td>
              <td align="center" class="tableMainbg"><label class="font-weight-bold">Code</label></td>
              <td align="center" class="tableMainbg"><label class="font-weight-bold">Entry</label></td>
            </tr>
            @if(!empty($arrParentWAEData))
            @foreach($arrParentWAEData as $arrParentWAE)
            <tr>
              <td align="center"><label>{{ $arrParentWAE['date'] }}</label></td>
              <td align="center"><label>{{ $arrParentWAE['time'] }}</label></td>
              <td align="center"><label>{{ $arrParentWAE['code'] }}</label></td>
              <td align="center"><label>{{ $arrParentWAE['value'] }}</label></td>
            </tr>
            @endforeach
            @else
            <tr>
              <td align="center" colspan="4"><label>No Record found</label></td>
            </tr>
            @endif
           </table>

           </div>
          </div>
      </div>

  </div>
</div>

<div class="modal fade " id="view_treatment_data" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
       <div class="modal-header">
        <h2 class="modal-title w-100 ">View Treatment Data</h2>
        <button type="button" class="close text-right" data-dismiss="modal" aria-label="Close" id="closeButton"> <span aria-hidden="true">X</span> </button>
      </div>
      <div class="modal-body px-3 max_height">
          <div class="row justify-content-xl-center justify-content-lg-center justify-content-md-center">
            <div class="col-xl-12 col-lg-12 col-md-12">
              <div class="form-row">
               <div class="form-group col-lg-12 col-md-12 col-sm-12 col-12 mb-12">
                <div class="row">
                  <label for="description_details" class="col-12 pr-0 text-left font-weight-bold mb-0">Type:</label>
                  <label id="description_details" class="col-12 text-left">Treatment Json</label>
                </div>
                </div>
                 <div class="form-group col-lg-12 col-md-12 col-sm-12 col-12 mb-12">
                  <div class="row">
                    <label for="treatment_data" class="col-12 pr-0 text-left font-weight-bold mb-0">Treatment Data:</label>
                    <pre id="treatment_data" class="col-12 text-left"></pre>
                  </div>
                </div>
               </div>
              </div>
          </div>
          <div class="row text-center mt-2">
            <div class="col-xl-12 col-lg-12 col-md-12">
              <button class="btn btn-secondary close_print" data-dismiss="modal" aria-label="Close" id="close">Close</button>
              {{-- <button class="btn btn-secondary" id="btnPrint" onclick="javascript:printDiv('printThis')" >Print</button> --}}
            </div>
          </div>
      </div>
    </div>
  </div>
</div>
@endsection
@section('jsdependencies')
<script>
// view treatment data
$(document).on('click','.viewrawjson',function(){
  var jsonData = $(this).attr('data-jsonData');
  //var jsonObj = JSON.parse(jsonData);
//  var jsonPretty = JSON.stringify(jsonObj, null, '\t');
   $('#treatment_data').text(jsonData);
   $('#view_treatment_data').modal('show');
});
</script>
@endsection
