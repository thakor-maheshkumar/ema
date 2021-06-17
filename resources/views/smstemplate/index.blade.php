@extends('layouts.app')
@section('title', 'SMS Templates')
@section('content')
<div class="container-fluid">
  <div class="row">
    <div class="col-xl-12 col-lg-12">
      <h1 class="my-2">SMS Templates</h1>
    </div>
  </div>
</div>
<div class="container-fluid">
  <div class="row">
    <div class="col-xl-12 col-lg-12 px-0">
      <nav aria-label="breadcrumb">
        {{ Breadcrumbs::render('SMSTemplate') }}
      </nav>
    </div>
  </div>
</div>
<div class="container-fluid">
  <div class="row">
    <div class="col-xl-12 col-lg-12 mb-3">
      <button class="btn btn-secondary" id="add_new_smstemplate">Add New SMS Template</button>
    </div>
  </div>
  <div class="row">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
      <section class="gridMainContainer">
        <div class="col-xl-12 col-lg-12 ">
          <h2 class="my-2 text-capitalize">View SMS Templates</h2>
        </div>
        <div class="col-xl-12 col-lg-12 px-0">
          <div class="table-responsive">
            <table id="sms_template_list" class="table table-striped table-bordered example" style="width:100%">
              <thead>
                <tr>
                  <th>Id</th>
                  <th>Name</th>
                  <th>Slug</th>
                  <th>Content</th>
                  <th>Action</th>
                </tr>
              </thead>            
            </table>
          </div>
        </div>
      </section>
    </div>
  </div>
</div>

<!--Add New SMS Template -->
<div class="modal fade " id="add_new_smstemplate_form" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog homeModel" role="document">
    <div class="modal-content">
     <div class="modal-header">
      <h2 class="modal-title w-100 " id="add_new"></h2>
      <button type="button" class="close text-right" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">X</span> </button>
    </div>
    <div class="modal-body px-xl-3 px-lg-3 px-md-3 px-sm-3 max_height">
      <form id="sms_template_form" name="sms_template_form" method="POST" action="{{ route('createSMSTemplate') }}">
       {{csrf_field()}}
       <input type="hidden" name="hidden_sms_template_id" id="hidden_sms_template_id" value="">
       <div class="form-row">
        <div class="form-group col-lg-6 col-md-6 col-sm-6">
          <label for="sms_template_name" class="mb-1">SMS Template Name</label>
          <input type="text" class="form-control @error('sms_template_name') is-invalid @enderror" id="sms_template_name" name="sms_template_name" value="{{old('sms_template_name')}}">
          @error('sms_template_name')
          <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
          </span>
          @enderror
        </div>
        <div class="form-group col-lg-6 col-md-6 col-sm-6">
          <label for="sms_template_slug" class="mb-1">Slug</label>
          <input type="text" class="form-control @error('sms_template_slug') is-invalid @enderror" id="sms_template_slug" name="sms_template_slug" value="{{old('sms_template_slug')}}">
          @error('sms_template_slug')
          <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
          </span>
          @enderror
        </div>
        <div class="form-group col-lg-12 col-md-12 col-sm-12">
          <label for="sms_template_content" class="mb-1">Content</label>
          <textarea class="form-control @error('sms_template_content') is-invalid @enderror" id="sms_template_content" name="sms_template_content">{{old('sms_template_content')}}</textarea>
          @error('sms_template_content')
          <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
          </span>
          @enderror         
        </div>
      </div>
      <div class="row text-center mb-2">
        <div class="col-xl-12 col-lg-12 col-md-12">
          <button type="submit" class="btn btn-secondary text-capitalize" id="save_sms_template">Save</button>
        </div>
      </div>
    </form>
  </div>
</div>
</div>
</div>
</div>
<!--Add New Hydracool -->
@endsection

@section('jsdependencies')
<script src="{{ asset('js/developer_js/SMS_template.js') }}"></script>
<script>
  var smsTemplate_list = '{{ route('SMSTemplate') }}';
  var getSMSTemplatepdetails = '{{ route("get-SMSTemplate-details") }}';
  var deleteSMSTemplate = '{{ route("delete-SMSTemplate") }}'; 

  @if($errors->has('sms_template_name'))    
  $(function() {
    $('#add_new_smstemplate_form').modal({
      show: true,
      backdrop: 'static',
      keyboard: false
    });
  });    
  @endif
</script>
@endsection