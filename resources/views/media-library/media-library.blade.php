@extends('layouts.app')
@section('title', 'Media Library')
@section('content')
<div class="container-fluid">
  <div class="row">
    <div class="col-xl-12 col-lg-12">
      <h1 class="my-2">Media Library</h1>
    </div>
  </div>
</div>
<div class="container-fluid ">
  <div class="row">
    <div class="col-xl-12 col-lg-12 px-0">
      <nav aria-label="breadcrumb">
        {{ Breadcrumbs::render('media-library') }}
      </nav>
    </div>
  </div>
</div>
<div class="container-fluid">

  @hasanyrole('system administrator')
  <div class="row">
    <div class="col-xl-12 col-lg-12 ">
      <div class="row d-flex align-items-end ">
        <div class="col-xl-12 col-lg-12 col-md-12">
          <button class="btn btn-secondary" data-toggle="modal" data-target="#add_new_media_library">Add Media to Library</button>
         </div>
      </div>
    </div>
  </div>
  @endhasanyrole

  <div class="row">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
    <section class="gridMainContainer">
      <div class="col-xl-12 col-lg-12 ">
        <h2 class="my-2 text-capitalize">Media Library</h2>
      </div>
      <div class="col-xl-12 col-lg-12 px-0">
        <div class="table-responsive mt_minus datatable_resize" id="librarylistScroll">
          <table id="media_library_list" class="table table-striped table-bordered example" style="width:100%">
            <thead>
              <tr>
                <th>Document Name</th>
                <th>Category Name</th>
                <th>Device Name</th>
                <th>Status</th>
                <th>Created Date</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tfoot style="display:none;">
              <tr>
                <th dataName="document_name-datatable">Document Name</th>
                <th dataName="category_name-datatable">Category Name</th>
                <th dataName="device_name-datatable">Device List</th>
                <th dataName="status-datatable">Status</th>
                <th dataName="created_at-datatable">Created Date</th>
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
<div class="modal fade " id="add_new_media_library" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog homeModel" role="document">
    <div class="modal-content">
      <div class="modal-header">
      <h2 class="modal-title w-100" id="event_click">Add Media to Library</h2>
      <button type="button" class="close text-right" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">X</span> </button>
    </div>
      <div class="modal-body px-xl-3 px-lg-3 px-md-3 px-sm-3 max_height">
        <form id="media_library_form">
          <div class="row justify-content-xl-center justify-content-lg-center justify-content-md-center">
            <div class="col-xl-12 col-lg-12 col-md-12">
              <div class="form-row ">
                <div class="form-group col-xl-12 col-lg-12 col-md-12">
                  <label for="document_name" class="mb-1">Document Name</label>
                  <input type="text" class="form-control" id="document_name" name="document_name">
                </div>

                  <div class="form-group col-xl-6 col-lg-6 col-md-6 col-sm-6">
                    <label for="category_id" class="mb-1">Category</label>
                    <select class="form-control country-select select_dropdown" id="category_id" name="category_id">
                      <option value="">--Select Category--</option>
                      @foreach($getSupportCategory as $getSupportCategoryRow)
                        <option value="{{ $getSupportCategoryRow['id'] }}">{{ $getSupportCategoryRow['category_name'] }}</option>
                      @endforeach
                    </select>
                  </div>
                  <div class="form-group col-xl-6 col-lg-6 col-md-6 col-sm-6">
                    <div class="form-group">
                      <label for="fk_hydracool_srp_id" class="mb-1">Device List</label>
                      <select class="form-control country-select select_dropdown" id="fk_hydracool_srp_id" name="fk_hydracool_srp_id">
                      <option value="">--Select Serial Number--</option>
                      @foreach($getHydraCoolSrpSerial as $getHydraCoolSrpSerialRow)
                        <option value="{{ $getHydraCoolSrpSerialRow['id'] }}">{{ $getHydraCoolSrpSerialRow['serial_number'] }}</option>
                      @endforeach
                    </select>
                    </div>
                  </div>

                <div class="input-group mb-3 col-xl-12 col-lg-12 col-md-12 ">
                  <div class="custom-file">
                    <input type="file" class="custom-file-input" id="support_file" name="support_file">
                    <label class="custom-file-label " for="support_file">Choose file</label>
                  </div>
                  <div id="error_area" class="w-100 mt-n2"></div>
                </div>

                <div class="input-group mb-3 col-xl-12 col-lg-12 col-md-12 ">
                  <div class="w-100">
                       <label for="document_name" class="mb-1">Description</label>
                  <textarea class="form-control" id="description" name="description"></textarea>
                  </div>
                </div>

                </div>

            </div>
          </div>
          <div class="row text-center mt-2">
            <div class="col-xl-12 col-lg-12 col-md-12">
              <button type="submit" id="save_media_library" class="btn btn-secondary mb-2 mt-2">Submit</button>
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
  var saveMedialLibrary = '{{ route("save-media-library") }}';
  var listMedialLibrary = '{{ route("media-library") }}';
  var deleteMedialLibrary = '{{ route("delete-media-library") }}';
</script>
<script src="{{ asset('js/developer_js/media_library.js') }}"></script>
@endsection