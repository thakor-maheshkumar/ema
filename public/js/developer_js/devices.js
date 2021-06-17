var startDate = new Date();
$(document).ready(function(){
  $('#manufacturing_date').datepicker({
    clearBtn: true,
    format: "dd-mm-yyyy",
  }).on('hide', function(e) {
    var startDate = $('#manufacturing_date').val();
     $('#sale_date').datepicker('setStartDate', startDate);
     $('#sale_date').val('');
  });

  $('#sale_date').datepicker({
    clearBtn: true,
    format: "dd-mm-yyyy",
    startDate: startDate,
    });



  $(document).on('click','#add_new_hydracool',function(){
    updateSerialNumberList();
    $('#add_new_hydracool_form').modal('show');
    $("#hydracool_srp_form :input").prop("disabled", false);
    $('#add_other_hydracool_srp_unit').hide();
    $('#add_new').text('Add HydraCool SRP Device');
    $('#demo_area').hide();
    $('#is_demo').prop('checked',false);
    $('#save_hydracool_srp_units').text('Save');
    $('#update_serial_number').hide();
    $("#srp_serial_number_non_ema").val('').trigger('change');
    $('#add_serial_number').show();
    $('#is_non_ema').val(1);
    $('#handset_id').val('');
    $('#manufacturing_date').datepicker('setDate',null)
    $('#sale_date').datepicker('setDate',null)
  });


  $('.select_dropdown').select2();

  // check selected treatment centre is ema or non-ema
  $('#treatmentcentre_id').on('select2:select',function(event){
    var getIsEMAVal = $(event.currentTarget).find("option:selected").attr('data-isEMA');
    var getSelectedValue =  $(event.currentTarget).find("option:selected").val();
    if(getIsEMAVal=='1'){
      $('#demo_area').show();
    }else{
      $('#demo_area').hide();
      $('#is_demo').prop('checked',false);
    }
    $('#treatment_center_id').val(getSelectedValue);
  });

  // load the hydracool srp and units with datatable
  jQuery.fn.DataTable.Api.register( 'buttons.exportData()', function ( options ) {
    var searchData = {};
    $("#hydracool_srp_list_device tfoot tr th").map(function() {
      var value = $('#'+$(this).attr('dataName')).val();
      var fieldnameData = $(this).attr('dataName').split('-');
      fieldname = fieldnameData[0];

      if($(this).attr('dataName')=="is_demo"){
        if(value=='No' || value=='no'){
          value=0;
        }else if(value=='Yes' || value=='yes'){
          value=1;
        }
      }
        searchData[fieldname] = value;
   }).get();
    if (this.context.length){
        var jsonResult = $.ajax({
            url: hydracoolsrp_list,
            data:{'search_data':searchData},
            async: false
        });
        return {body: jsonResult.responseJSON, header: $("#hydracool_srp_list_device tfoot tr th .dataTables_sizing").map(function() { return this.innerHTML; }).get()};
    }
});
  var hydracool_srp_list = $('#hydracool_srp_list_device').DataTable({
    processing: true,
    serverSide: true,
    responsive:true,
    "scrollX": true,
    autoWidth: false,
    ajax:hydracoolsrp_list,
    dom: 'Blfrtip',
    order:['9','desc'],
    buttons: [
          {
              extend: 'excel',
              filename: 'Device',
              title:'',
              customizeData: function ( data ) {
                for (var i=0; i<data.body.length; i++){
                  for (var j=0; j<data.body[i].length; j++ ){
                      if(j <2 ){
                        data.body[i][j] = '\u200C' + data.body[i][j];
                      }
                    }
                }
            }
          },
          {
              extend: 'csv',
              filename: 'Device',
              title:''
          },
          {
              extend: 'pdf',
              filename: 'Device',
              title:'Device',
              orientation : 'landscape',
              customize: function ( doc ) {
                var objLayout = {};
                objLayout['hLineWidth'] = function(i) { return .5; };
                objLayout['vLineWidth'] = function(i) { return .5; };
                objLayout['hLineColor'] = function(i) { return '#aaa'; };
                objLayout['vLineColor'] = function(i) { return '#aaa'; };
                doc.content[1].layout = objLayout;
                var rowCount = doc.content[1].table.body.length;
                for (i = 1; i < rowCount; i++) {
                    doc.content[1].table.body[i][0].alignment = 'left';
                    doc.content[1].table.body[i][1].alignment = 'left';
                    doc.content[1].table.body[i][2].alignment = 'right';
                    doc.content[1].table.body[i][3].alignment = 'right';
                    doc.content[1].table.body[i][4].alignment = 'right';
                    doc.content[1].table.body[i][5].alignment = 'right';
                }
                  doc.content.splice( 1, 0, {
                      margin: [ 0, 0, 10, 12 ],
                      alignment: 'right',
                      image: 'data:image/png;base64,'+$('#base64_image').val()
                  } );
                  doc.defaultStyle.alignment = 'right';
                  doc.styles.tableHeader.alignment = 'right';
              }
          },
          {
              extend: 'print',
              filename: 'Device',
              title:'Device',
              customize: function ( win ) {
                  $(win.document.body)
                      .css('font-size','14px')
                      .prepend(
                          '<img src="'+$('#logo_url').attr('src')+'" style="float:right;margin-bottom:10px;" />'
                      );
              }
          },
          {
            text: 'Clear Filters',
            attr:  {
                id: 'clear',
                'data-attr': 'hydracool_srp_list',
            },
          }
      ],
    language: {
      "lengthMenu": 'Show <select class="custom-select custom-select-sm form-control form-control-sm">'+
        '<option value="10">10</option>'+
        '<option value="20">20</option>'+
        '<option value="30">30</option>'+
        '<option value="40">40</option>'+
        '<option value="50">50</option>'+
        '</select>',

        'processing': '<div class="datatable-loader"><img src="'+$('#logo_white').attr('src')+'" /><div class="sk-chase mt-3 mx-n3"><div class="sk-chase-dot"></div> <div class="sk-chase-dot"></div> <div class="sk-chase-dot"></div> <div class="sk-chase-dot"></div> <div class="sk-chase-dot"></div> <div class="sk-chase-dot"></div></div></div>'

    },
    columns: [
        {data: 'full_company_name', name: 'full_company_name', width: "200px"},
        {data: 'serial_number', name: 'serial_number', width: "150px"},
        {data: 'last_active', name: 'last_active', className: "text-right", width: "150px"},
        {data: 'install_device', name: 'install_device', className: "text-right", width: "150px"},
        {data: 'last_seven', name: 'last_seven', className: "text-right", width: "170px"},
        {data: 'last_30_days', name: 'last_30_days', className: "text-right", width: "170px"},
        {data: 'last_90_days', name: 'last_90_days', className: "text-right", width: "170px"},
        {data: 'last_12_month', name: 'last_12_month', className: "text-right", width: "180px"},
        {data: 'total_treatment', name: 'total_treatment', className: "text-right", width: "150px"},
        {data: 'created_at', name: 'created_at', orderable: true, visible: false},
        {data: 'action', name: 'action', orderable: false, searchable: false, width: "50px"}
    ],
    columnDefs: [
      {
          "targets": 2,
          "data": "last_active",
          "render": function ( data, type, row, meta ) {
                  return 0;
          }
      },
      {
        "targets": 3,
        "data": "install_device",
        "render": function ( data, type, row, meta ) {
                return 0;
        }
      },
      {
        "targets": 4,
        "data": "last_seven",
        "render": function ( data, type, row, meta ) {
                return 0;
        }
      },
      {
        "targets": 5,
        "data": "last_30_days",
        "render": function ( data, type, row, meta ) {
            return 0;
        }
      },
      {
        "targets": 6,
        "data": "last_90_days",
        "render": function ( data, type, row, meta ) {
              return 0;
        }
      },
      {
        "targets": 7,
        "data": "last_12_month",
        "render": function ( data, type, row, meta ) {
                return 0;
        }
      },
      {
        "targets": 8,
        "data": "total_treatment",
        "render": function ( data, type, row, meta ) {
                return 0;
        }
      },
  ],
    fnDrawCallback:function () {
      var table = $('#hydracool_srp_list_device').DataTable();
       if (table.data().length === 0){
          table.buttons('.buttons-html5').disable();
          table.buttons('.buttons-print').disable();
       }else{
          table.buttons('.buttons-html5').enable();
          table.buttons('.buttons-print').enable();
       }
    },
    initComplete: function () {
      this.api().columns().every(function () {
        var column = this;
        var columnText = $(column.header()).text();
        var columnAttrname = $(column.footer()).attr('dataName');
            var input = document.createElement('input');
            input.setAttribute("id", columnAttrname);
            $(input).attr( 'style', 'text-align: center;width: 100%');
            input.placeholder = columnText;
            if(columnText!="Actions" && columnText!="Last Active/Upload" && columnText!="Installed Devices" && columnText!="Last 7 Days Treatments" && columnText!="Last 30  Days Treatments" && columnText!="Last 90 Days Treatments" && columnText!="Last 12 Months Treatments" && columnText!="Total Treatments" ){
                $(input).appendTo($(column.header()).empty())
                .on('keyup', function (e) {
                  // if(e.keyCode == 13) {
                    var val = $.fn.dataTable.util.escapeRegex($(this).val());
                    column.search(val ? val : '', true, false).draw();
                });
            }else{
                $(input).appendTo('Actions');
            }
        });
    }
  }).on('draw.dt', function () {
    var info = hydracool_srp_list.page.info();
    if(info.page + 1 === info.pages || info.pages==0) {
      $('.table-responsive').removeClass('datatable_resize');
    } else {
      $('.table-responsive').addClass('datatable_resize');
    }
  });

});


//hydracool srp and units validation rules
$("#hydracool_srp_form").submit(function(e) {
  e.preventDefault();
}).validate({
  onkeyup: function(element) {$(element).valid()},
  rules: {
    srp_serial_number: {
      required: "#srp_serial_number:visible",
        commanRegex:true,
        remote:{
              url:uniqueserialnumber,
              data:{id: function() { return $('#hydracool_srp_id').val(); } },
              headers:{'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
              type:'post',
        }
      },
      srp_serial_number_non_ema: {
        required: "#srp_serial_number_non_ema:visible",
        commanRegex:true,
        remote:{
              url:uniqueserialnumber,
              data:{id: function() { return $('#hydracool_srp_id').val(); } },
              headers:{'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
              type:'post',
        }
      },
      Hub_Board_unit: {
        remote:{
              url:checkhandsetunique,
              data:{'handset_value': function() { return $('#Hub_Board_unit').val(); },'exceptId': function() { return $('#handset_id').val(); }},
              headers:{'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
              type:'post',
        }
      },
      AquaB_unit: {
        remote:{
              url:checkhandsetunique,
              data:{'handset_value': function() { return $('#AquaB_unit').val(); },'exceptId': function() { return $('#handset_id').val(); }},
              headers:{'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
              type:'post',
        }
      },
      VibroX_unit: {
        remote:{
              url:checkhandsetunique,
              data:{'handset_value': function() { return $('#VibroX_unit').val(); },'exceptId': function() { return $('#handset_id').val(); }},
              headers:{'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
              type:'post',
        }
      },
      MicroT_unit: {
        remote:{
              url:checkhandsetunique,
              data:{'handset_value': function() { return $('#MicroT_unit').val(); },'exceptId': function() { return $('#handset_id').val(); }},
              headers:{'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
              type:'post',
        }
      },
      UltraB_unit: {
        remote:{
              url:checkhandsetunique,
              data:{'handset_value': function() { return $('#UltraB_unit').val(); },'exceptId': function() { return $('#handset_id').val(); }},
              headers:{'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
              type:'post',
        }
      },
      Collagen_unit: {
        remote:{
              url:checkhandsetunique,
              data:{'handset_value': function() { return $('#Collagen_unit').val(); },'exceptId': function() { return $('#handset_id').val(); }},
              headers:{'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
              type:'post',
        }
      },
      treatmentcentre_id: {
       required:true
      },

      manufacturer_name: {
        required:true
      },

      manufacturing_date: {
        required:true
      },

      sale_date: {
        required:true
      },
  },
  messages:{
    srp_serial_number:{
      remote:'serial number already exist'
    },
    Hub_Board_unit:{
      remote:'Handset number already exist'
    },
    AquaB_unit:{
      remote:'Handset number already exist'
    },
    VibroX_unit:{
      remote:'Handset number already exist'
    },
    MicroT_unit:{
      remote:'Handset number already exist'
    },
    Collagen_unit:{
      remote:'Handset number already exist'
    },
    UltraB_unit:{
      remote:'Handset number already exist'
    },
    manufacturer_name:{
      required:'Manufacturer Name is required'
    },

    manufacturing_date:{
      required:'Manufacturing Date is required'
    },

    sale_date:{
      required:'Date of Sale is required'
    },
  },
  errorPlacement: function(error, element) {
    var elem = $(element);
    if (elem.hasClass("select2-hidden-accessible")) {
        element = $("#select2-" + elem.attr("id") + "-container").parent();
        error.insertAfter(element);
    }else if (elem.attr("name")=="manufacturing_date") {
        error.appendTo('#manufacturing_date_error');
    }else if (elem.attr("name")=="sale_date") {
      error.appendTo('#sale_date_error');
    }else {
        error.insertAfter(element);
    }
  },
    submitHandler: function(form) {
      $('.loader_container').show();
      $('#save_hydracool_srp_units').prop( "disabled", true );
      var formData ={};
      var srpunits ={};
      var getTreatmentCenterId =  $('#treatment_center_id').val();
      formData['serial_number'] = $("#srp_serial_number").val();

      $('.unit_name').each(function(){
          var getNameAttr = $(this).attr('name');
          var getValue = $(this).val();
          if(getValue){
            srpunits[getNameAttr] = getValue;
          }else{
            srpunits[getNameAttr] = '--';
          }
      });

      formData['srp_units'] = srpunits;
      formData['treatment_center_id'] = getTreatmentCenterId;
      if($('#is_demo').is(':checked')){
        formData['is_demo'] = 1;
      }else{
        formData['is_demo'] = 0;
      }
      var ajaxUrl = updatehydracoolsrp;
      var getHydraCoolSrpId = $('#hydracool_srp_id').val();
      var is_non_ema = $('#is_non_ema').val();
      formData['hydracool_srp_id'] = getHydraCoolSrpId;
      formData['manufacturer_name'] = $("#manufacturer_name").val();
      formData['manufacturing_date'] = $("#manufacturing_date").val();
      formData['sale_date'] = $("#sale_date").val();
      if(is_non_ema){
        if(getHydraCoolSrpId){
          ajaxUrl  = addhydracoolsrpnonema;
        }else{
          ajaxUrl  = addhydracoolsrp;
        }
        formData['serial_number'] = $("#srp_serial_number_non_ema option:selected").text();
      }else{
        formData['serial_number'] = $("#srp_serial_number").val();
      }

      $.ajax({
        url:ajaxUrl,
        data:{formData},
        type:"POST",
        dataType:"JSON",
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success:function(data){
          if(data.success=="true"){
              toastr['success'](data.message);
              if(!getHydraCoolSrpId){
                  $('#add_other_hydracool_srp_unit').show();
              }else{
                $('#add_new_hydracool_form').modal('hide');
              }

              $("#hydracool_srp_form :input").prop("disabled", true);
              $('#hydracool_srp_list_device').DataTable().ajax.reload();
              getAllHandsetValue = [];


          }else{
            $('#save_hydracool_srp_units').removeAttr('disabled');
            toastr['error'](data.message);
          }
          $('.loader_container').hide();
        },
        error:function(data){
          $('.loader_container').hide();
          $('#save_hydracool_srp_units').removeAttr('disabled');
            if( data.status === 422 ) {
                $('.form-control').removeClass('is-invalid');
                $('.invalid-feedback').remove();
                var errors = data.responseJSON.errors;
                $.each(errors, function (key, val) {
                  if(key=="serial_number"){
                      key = "srp_serial_number";
                  }
                    $('input[name='+key+']').addClass('is-invalid');
                    $('<span class="invalid-feedback" role="alert"><strong>'+val[0]+'</strong></span>').insertAfter( 'input[name='+key+']');
                });

            }else{
              toastr['error']('Something wrong try again');
            }

        }
      });
    },
    focusInvalid: false,
    invalidHandler: function(form, validator) {
       $('#manufacturing_date').datepicker('hide');
       $('#sale_date').datepicker('hide');
    },
});




// add other hydracool srp units
$(document).on('click','#add_other_hydracool_srp_unit',function(){
  $('#hydracool_srp_form')[0].reset();
  $('#add_other_hydracool_srp_unit').hide();
  $("#hydracool_srp_form :input").prop("disabled", false);
  $('#treatmentcentre_id').val('').trigger("change");
  $('#treatmentcentre_id-error').remove();
  $("#srp_serial_number_non_ema").prop("disabled", false);
  $("#srp_serial_number_non_ema").val('').trigger('change');
  $('#handset_id').val('');
  updateSerialNumberList();
});

// edit hydracool spr and its units
$(document).on('click','.edithydracoolsrp',function(){
  var getHydraCoolSrpId = $(this).attr('data-hydracoolsrpId');
  if(getHydraCoolSrpId){
    $.ajax({
      url:gethydracoolsrpdetails,
      data:{'hydracool_srp_id':getHydraCoolSrpId},
      type:"POST",
      dataType:"JSON",
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      success:function(data){
        if(data.success=='true'){
          $('#update_serial_number').show();
          $('#add_serial_number').hide();
          $('#is_non_ema').val('');
          var unit_detils = data.units_list;
          $('#AquaB_unit').val((unit_detils.AquaB_unit == '--' ? '' : unit_detils.AquaB_unit));
          $('#Collagen_unit').val((unit_detils.Collagen_unit=='--' ? '' : unit_detils.Collagen_unit));
          $('#Hub_Board_unit').val((unit_detils.Hub_Board_unit=='--' ? '' : unit_detils.Hub_Board_unit));
          $('#MicroT_unit').val((unit_detils.MicroT_unit=='--'? '' :unit_detils.MicroT_unit));
          $('#UltraB_unit').val((unit_detils.UltraB_unit == '--' ? '' : unit_detils.UltraB_unit));
          $('#VibroX_unit').val((unit_detils.VibroX_unit == '--' ? '' : unit_detils.VibroX_unit));
          $('#hydracool_srp_id').val(data.hydracool_srp_id);

          $('#srp_serial_number').val(data.serial_number);
          $('#manufacturer_name').val(data.manufacturer_name);
          $('#sale_date').val(data.sale_date);
          $('#manufacturing_date').val(data.manufacturing_date);
          $('#add_new_hydracool_form').modal('show');
          $("#hydracool_srp_form :input").prop("disabled", false);
          $('#add_new').text('Edit HydraCool SRP Device');
          $('#treatmentcentre_id').val(data.treatment_center_id).trigger("change");
          $('#treatment_center_id').val(data.treatment_center_id);
          $('#add_other_hydracool_srp_unit').hide();
          $('#handset_id').val(data.handset_id);
          $('#save_hydracool_srp_units').text('Update');
          if(userRole!="system administrator"){
              $("#srp_serial_number").prop("disabled", true);
          }
          if(data.is_demo==1){
            $('#is_demo').prop('checked',true);
          }else{
            $('#is_demo').prop('checked',false);
          }
        }else{
          toastr['error'](data.message);
        }
      },
      error:function(){
        toastr['error']('something went wrong');
      }

    });
  }

})

// view hydracool srp and unit details
$(document).on('click','.viewHydracoolsrp',function(){
  var getHydraCoolSrpId = $(this).attr('data-hydracoolsrpId');

  if(getHydraCoolSrpId){
    $('.loader_container').show();
    $.ajax({
      url:gethydracoolsrpdetails,
      data:{'hydracool_srp_id':getHydraCoolSrpId},
      type:"POST",
      dataType:"JSON",
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      success:function(data){
        if(data.success=='true'){
          var unit_detils = data.units_list;
          $('#serial_number').text(data.serial_number);
          $('#AquaB_details').text(unit_detils.AquaB_unit);
          $('#Collagen_details').text(unit_detils.Collagen_unit);
          $('#hub_board_details').text(unit_detils.Hub_Board_unit);
          $('#MicroT_details').text(unit_detils.MicroT_unit);
          $('#UltraB_details').text(unit_detils.UltraB_unit);
          $('#VibroX_details').text(unit_detils.VibroX_unit);
          $('#center_name').text(data.treatment_center_name);
          $('#view_hydracool_srp_details').modal('show');
          if(data.is_demo==1){
            $('#demo_details').text('Yes');
          }else{
            $('#demo_details').text('No');
          }

        }else{
          toastr['error'](data.message);
        }
        $('.loader_container').hide();
      },
      error:function(){
        $('.loader_container').hide();
        toastr['error']('something went wrong');
      }

    });
  }

});


$(document).on('click','.suspendhydracoolsrp',function(){
  var getHydraCoolSrpId = $(this).attr('data-hydracoolsrpId');
  toastr.warning("Are you sure you want to suspend this HydraCool SRP?<br /><br /><button type='button' id='confirm_yes' class='btn btn-secondary'>Yes</button>  <button type='button' id='confirm_no' class='btn btn-secondary'>No</button>",'Suspend Hydracool SRP',
  {
      "closeButton": true,
      "positionClass": "toast-top-center",
      "preventDuplicates": true,
      "onclick": null,
      "timeOut": 0,
      "extendedTimeOut": 0,
      "showEasing": "swing",
      "hideEasing": "linear",
      "showMethod": "fadeIn",
      "hideMethod": "fadeOut",
      "tapToDismiss": false,
      "closeOnHover": false,
      onShown: function (toast) {
          $("#confirm_yes").click(function(){

            $.ajax({
              url:suspendhydracoolsrp,
              data:{'hyracool_srp_id':getHydraCoolSrpId},
              type:"POST",
              dataType:"JSON",
              headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              },
              success:function(data){
                if(data.success=="true"){
                  toastr['success'](data.message);
                  $('#hydracool_srp_list_device').DataTable().ajax.reload();
                }else{
                  toastr['error'](data.message);
                }
              },
              error:function(data){
                  toastr['error'](data.message);
              }
            });

          });
          $("#confirm_no").click(function(){
              $('.toast-close-button').click();
          });

        }
  });

 });

 $(document).on('click','.releasehydracoolsrp',function(){
  var getHydraCoolSrpId = $(this).attr('data-hydracoolsrpId');
  toastr.warning("Are you sure you want to release this Hydracool Srp?<br /><br /><button type='button' id='confirm_yes' class='btn btn-secondary'>Yes</button>  <button type='button' id='confirm_no' class='btn btn-secondary'>No</button>",'Release Hydracool SRP',
  {
      "closeButton": true,
      "positionClass": "toast-top-center",
      "preventDuplicates": true,
      "onclick": null,
      "timeOut": 0,
      "extendedTimeOut": 0,
      "showEasing": "swing",
      "hideEasing": "linear",
      "showMethod": "fadeIn",
      "hideMethod": "fadeOut",
      "tapToDismiss": false,
      "closeOnHover": false,
      onShown: function (toast) {
          $("#confirm_yes").click(function(){

            $.ajax({
              url:releasehydracoolsrp,
              data:{'hyracool_srp_id':getHydraCoolSrpId},
              type:"POST",
              dataType:"JSON",
              headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              },
              success:function(data){
                if(data.success=="true"){
                  toastr['success'](data.message);
                  $('#hydracool_srp_list_device').DataTable().ajax.reload();
                }else{
                  toastr['error'](data.message);
                }
              },
              error:function(data){
                  toastr['error'](data.message);
              }
            });

          });
          $("#confirm_no").click(function(){
              $('.toast-close-button').click();
          });

        }
  });
 });

$(document).on('click','.deleteHydracoolsrp',function(){
  var getHydraCoolSrpId = $(this).attr('data-hydracoolsrpId');
  var getHydraCoolSerialNumber=$(this).attr('data-hydracoolsrpSerialNumber');

toastr.warning("Are you sure want to delete "+getHydraCoolSerialNumber+" HydraCool SRP Device?<br /><br /><button type='button' id='confirm_yes' class='btn btn-secondary'>Yes</button>  <button type='button' id='confirm_no' class='btn btn-secondary'>No</button>",'Delete Hydracool SRP',
  {
      "closeButton": true,
      "positionClass": "toast-top-center",
      "preventDuplicates": true,
      "onclick": null,
      "timeOut": 0,
      "extendedTimeOut": 0,
      "showEasing": "swing",
      "hideEasing": "linear",
      "showMethod": "fadeIn",
      "hideMethod": "fadeOut",
      "tapToDismiss": false,
      "closeOnHover": false,
      onShown: function (toast) {
          $("#confirm_yes").click(function(){
            $('.loader_container').show();
            $.ajax({
              url:deletehydracoolsrp,
              data:{'hyracool_srp_id':getHydraCoolSrpId},
              type:"POST",
              dataType:"JSON",
              headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              },
              success:function(data){
                $('#toast-container').remove();
                toastr.options = {
                    "preventDuplicates": true,
                    "timeOut": "3000",
                }
                if(data.success=="true"){

                  toastr['success'](data.message);
                  $('#hydracool_srp_list_device').DataTable().ajax.reload();
                }else{
                  toastr['error'](data.message);
                }
                $('.loader_container').hide();
              },
              error:function(data){
                $('.loader_container').hide();
                  toastr['error'](data.message);
              }

            });

          });
          $("#confirm_no").click(function(){
              $('.toast-close-button').click();
          });

        }
  });

});
$('#srp_serial_number_non_ema').select2({
  tags: true,
});


// get the ser unit by srp serial number
$('#srp_serial_number_non_ema').on('change',function(){
  var getSelectedValue = $("#srp_serial_number_non_ema option:selected").text();
  if(getSelectedValue!="undefined" && getSelectedValue!="" && getSelectedValue!="Select OR Enter Serial number" ){
    $('#srp_serial_number_non_ema-error').remove('');
    $.ajax({
      url:gethydracoolSrpUnitslist,
      data:{'serial_number':getSelectedValue},
      type:"POST",
      dataType:"JSON",
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      success:function(data){
        if(data.success=='true'){
          var unit_detils = data.units_list;
          $('#AquaB_unit').val((unit_detils.AquaB_unit == '--' ? '' : unit_detils.AquaB_unit));
          $('#Collagen_unit').val((unit_detils.Collagen_unit=='--' ? '' : unit_detils.Collagen_unit));
          $('#Hub_Board_unit').val((unit_detils.Hub_Board_unit=='--' ? '' : unit_detils.Hub_Board_unit));
          $('#MicroT_unit').val((unit_detils.MicroT_unit=='--'? '' :unit_detils.MicroT_unit));
          $('#UltraB_unit').val((unit_detils.UltraB_unit == '--' ? '' : unit_detils.UltraB_unit));
          $('#VibroX_unit').val((unit_detils.VibroX_unit == '--' ? '' : unit_detils.VibroX_unit));
          $('#hydracool_srp_id').val(data.hydracoolsrp_id);
          $('#handset_id').val(data.handset_id);

          //$("label.error").hide();
          //$(".error").removeClass("error");
        }else if(typeof(data.is_exists) != "undefined"){
          toastr['error']('Serial number already exists');
          // $('#srp_serial_number_non_ema').val('').trigger("change");
          updateSerialNumberList();
        }else{
          $('#AquaB_unit').val('');
          $('#Collagen_unit').val('');
          $('#Hub_Board_unit').val('');
          $('#MicroT_unit').val('');
          $('#UltraB_unit').val('');
          $('#VibroX_unit').val('');
          $('#hydracool_srp_id').val('');
          //$("label.error").hide();
          //$(".error").removeClass("error");
        }
      },
      error:function(){
        toastr['error']('Something went wrong');
      }

    });
  }

});


document.onreadystatechange = function() {
  if (document.readyState !== "complete") {
    setTimeout(function(){
       var elementheight = $('.dataTables_scrollBody').height();
       var devide = 27;
       var result = parseInt(elementheight / devide);
       $('#hydracool_srp_list_device').DataTable().page.len(result).draw();
    }, 100);
  }
};