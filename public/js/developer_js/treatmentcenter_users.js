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

 $("#delivery_date").click(function(){
     $(".dropdown-menu").show();
  });

$( "#cosmetic_data" ).scroll(function() {
    $('.dropdown-menu').css({"display": "none"})
});

  $(document).on('click','#add_new_hydracool',function(){
    updateSerialNumberList();
    $('#add_new_hydracool_form').modal('show');
    $('#hydracool_srp_form_users')[0].reset();
    $("#hydracool_srp_form_users :input").prop("disabled", false);
    $('#add_other_hydracool_srp_unit_detail_form').hide();
    getTreatmentCenterName();
    $("#add_serial_number").show();
    $("#update_serial_number").hide();
    $("#srp_serial_number_non_ema").val('').trigger('change');
    $('#hydracool_srp_id').val('');
    $('#add_new').text('Add HydraCool SRP Device');
    $('#save_hydracool_srp_units_users').text('Save');
    $('#is_non_ema').val(1);
    $('#handset_id').val('');
    $('#manufacturing_date').datepicker('setDate', null);
    $('#sale_date').datepicker('setDate', null);

  });
  // load the hydracool srp and units with datatable
  jQuery.fn.DataTable.Api.register( 'buttons.exportData()', function ( options ) {
    var searchData = {};
    $("#hydracool_srp_list tfoot tr th").map(function() {
      var fieldnameData = $(this).attr('dataName').split('-');
      fieldname = fieldnameData[0];
      searchData[fieldname] = $('#'+$(this).attr('dataName')).val();
   }).get();
    if (this.context.length){
        var jsonResult = $.ajax({
            url: list_hydracool_srp,
            data:{'search_data':searchData},
            async: false
        });
        return {body: jsonResult.responseJSON, header: $("#hydracool_srp_list tfoot tr th .dataTables_sizing").map(function() { return this.innerHTML; }).get()};
    }
  });
  $('#hydracool_srp_list').DataTable({
    processing: true,
    serverSide: true,
    responsive:true,
    "scrollX": true,
    autoWidth: false,
    ajax:list_hydracool_srp,
    dom: 'Blfrtip',
    order:['8','desc'],
    scrollCollapse: true,
    buttons: [
        {
            extend: 'excel',
            filename: 'Hydracool SRP ',
            title:'',
            customizeData: function ( data ) {
              for (var i=0; i<data.body.length; i++){
                for (var j=0; j<data.body[i].length; j++ ){
                      if(j < 1){
                        data.body[i][j] = '\u200C' + data.body[i][j];
                      }
                  }
              }
            }
        },
        {
            extend: 'csv',
            filename: 'Hydracool SRP',
            title:''
        },
        {
            extend: 'pdf',
            filename: 'Hydracool SRP',
            title:'Hydracool SRP',
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
                    doc.content[1].table.body[i][1].alignment = 'right';
                    doc.content[1].table.body[i][2].alignment = 'right';
                    doc.content[1].table.body[i][3].alignment = 'right';
                    doc.content[1].table.body[i][4].alignment = 'right';
                    doc.content[1].table.body[i][5].alignment = 'right';
                    doc.content[1].table.body[i][6].alignment = 'right';
                    doc.content[1].table.body[i][7].alignment = 'right';
                }
                doc.content.splice( 1, 0, {
                    margin: [ 0, 0, 10, 12 ],
                    alignment: 'right',
                    image: 'data:image/png;base64,'+$('#base64_image').val()
                } );
            }
        },
        {
            extend: 'print',
            filename: 'Hydracool SPR',
            title:'Hydracool SRP',
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
      {data: 'serial_number', name: 'serial_number', width: "150px"},
      {data: 'last_active', name: 'last_active', className: "text-right", width: "150px"},
      {data: 'install_device', name: 'install_device', className: "text-right", width: "150px"},
      {data: 'last_seven', name: 'last_seven', className: "text-right", width: "170px"},
      {data: 'last_30_days', name: 'last_30_days', className: "text-right", width: "170px"},
      {data: 'last_90_days', name: 'last_90_days', className: "text-right", width: "170px"},
      {data: 'last_12_month', name: 'last_12_month', className: "text-right", width: "180px"},
      {data: 'total_treatment', name: 'total_treatment', className: "text-right", width: "170px"},
      {data: 'created_at', name: 'created_at', orderable: true, visible: false},
      {data: 'action', name: 'action', orderable: false, searchable: false, width: "50px"}
    ],
    columnDefs: [
      {
          "targets": 1,
          "data": "last_active",
          "render": function ( data, type, row, meta ) {
                  return 0;
          }
      },
      {
        "targets": 2,
        "data": "install_device",
        "render": function ( data, type, row, meta ) {
                return 0;
        }
      },
      {
        "targets": 3,
        "data": "last_seven",
        "render": function ( data, type, row, meta ) {
                return 0;
        }
      },
      {
        "targets": 4,
        "data": "last_30_days",
        "render": function ( data, type, row, meta ) {
                return 0;
        }
      },
      {
        "targets": 5,
        "data": "last_90_days",
        "render": function ( data, type, row, meta ) {
                return 0;
        }
      },
      {
        "targets": 6,
        "data": "last_12_month",
        "render": function ( data, type, row, meta ) {
                return 0;
        }
      },
      {
        "targets": 7,
        "data": "total_treatment",
        "render": function ( data, type, row, meta ) {
                return 0;
        }
      },
  ],
    fnDrawCallback:function () {
      var table = $('#hydracool_srp_list').DataTable();
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
                  // }
                });
            }else{
                $(input).appendTo('Actions');
            }
      });
    }
  });

  // load the treatment center Principal with datatable
  jQuery.fn.DataTable.Api.register( 'buttons.exportData()', function ( options ) {
    var searchData = {};
    $("#treatment_center_Principal_list tfoot tr th").map(function() {
      var value = $('#'+$(this).attr('dataName')).val();
      if($(this).attr('dataName')=="status"){
        if(value=='Suspended' || value=='suspended'){
          value=3;
        }else if(value=='Active' || value=='active'){
          value=1;
        }
      }
      if($(this).attr('dataName')=="is_logged_in"){
        if(value=='online' || value=='Online'){
          value=1;
        }else if(value=='Offline' || value=='offline'){
          value=0;
        }
      }
        searchData[$(this).attr('dataName')] = value;
   }).get();
    if (this.context.length){
        var jsonResult = $.ajax({
            url: list_treatmentcenter_Principal,
            data:{'search_data':searchData},
            async: false
        });
        return {body: jsonResult.responseJSON, header: $("#treatment_center_Principal_list tfoot tr th .dataTables_sizing").map(function() { return this.innerHTML; }).get()};
    }
  });

  $('#treatment_center_Principal_list').DataTable({
    processing: true,
    serverSide: true,
    responsive:true,
    "scrollX": true,
    autoWidth: false,
    ajax:list_treatmentcenter_Principal,
    dom: 'Blfrtip',
    order:['7','desc'],
    buttons: [
        {
            extend: 'excel',
            filename: 'TreatmentCentre Principal ',
            title:'',
            customizeData: function ( data ) {
              for (var i=0; i<data.body.length; i++){
                for (var j=0; j<data.body[i].length; j++ ){
                      data.body[i][j] = '\u200C' + data.body[i][j];
                  }
              }
            }
        },

        {
            extend: 'csv',
            filename: 'TreatmentCentre Principal',
            title:''
        },
        {
            extend: 'pdf',
            filename: 'TreatmentCentre Principal',
            orientation : 'landscape',
            title:'TreatmentCentre Principal',
            customize: function ( doc ) {
              var objLayout = {};
              objLayout['hLineWidth'] = function(i) { return .5; };
              objLayout['vLineWidth'] = function(i) { return .5; };
              objLayout['hLineColor'] = function(i) { return '#aaa'; };
              objLayout['vLineColor'] = function(i) { return '#aaa'; };
              doc.content[1].layout = objLayout;
                var rowCount = doc.content[1].table.body.length;
                for (i = 1; i < rowCount; i++) {

                }
                doc.content.splice( 1, 0, {
                    margin: [ 0, 0, 10, 12 ],
                    alignment: 'right',
                    image: 'data:image/png;base64,'+$('#base64_image').val()
                } );
            }
        },
        {
            extend: 'print',
            filename: 'TreatmentCentre Principal',
            title:'TreatmentCentre Principal',
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
                'data-attr': 'treatment_center_Principal_list',
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
        {data: 'name', name: 'name', width: "100px"},
        {data: 'roles_name', name: 'roles_name', width: "150px"},
        {data: 'online', name: 'online', width: "50px"},
        {data: 'status', name: 'status', width: "70px"},
        {data: 'email', name: 'email', width: "250px"},
        {data: 'primary_telephone_number', name: 'primary_telephone_number', width: "100px"},
        {data: 'mobile_telephone_number', name: 'mobile_telephone_number', width: "100px"},
        {data:'created_at',name:'created_at', visible: false},
        {data: 'action', name: 'action', orderable: false, searchable: false, width: "100px"}
    ],
  fnDrawCallback:function () {
    var table = $('#treatment_center_Principal_list').DataTable();
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
            if(columnText!="Actions" && columnText!="Status" && columnText!="Online" && columnText!="User Type" ){
                $(input).appendTo($(column.header()).empty())
                .on('keyup', function (e) {
                  // if(e.keyCode == 13) {
                    var val = $.fn.dataTable.util.escapeRegex($(this).val());
                    column.search(val ? val : '', true, false).draw();
                  // }
                });
            }else{
                $(input).appendTo('Actions');
            }
      });
    }
  });

  $(document).on('click','#add_new_principal',function(){
    $("#add_new_hydracool").removeClass('fade');
    $("#add_new_hydracool").modal('hide');
    $("#add_new_tcp").modal('show');
    $('#principal_user_form')[0].reset();
    $("#principal_username").prop("readonly", false);
    $("#principal_email").prop("readonly", false);
    $("#save_treatment_center_principal").prop("disabled", false);
    $('#add_new_principal_text').text('Add New Treatment Centre User');
    $('#save_treatment_center_principal').text('Save');

  });

//hydracool srp and units validation rules
$("#hydracool_srp_form_users").submit(function(e) {
  e.preventDefault();
}).validate({
  onkeyup: function(element) {$(element).valid()},
  rules: {
    srp_serial_number_non_ema: {
      required: "#srp_serial_number_non_ema:visible",
      commanRegex:true,
      remote:{
            url:uniqueserialnumber,
            data:{'handset_value': function() { return $('#hydracool_srp_id').val(); } },
            headers:{'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            type:'post',
      }
    },
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
    srp_serial_number_non_ema:{
      remote:'serial number already exist'
    },
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
    } else {
        error.insertAfter(element);
    }
  },
    submitHandler: function(form) {
      $('.loader_container').show();
      $('#save_hydracool_srp_units_users').prop( "disabled", true );

      var formData ={};
      var srpunits ={};

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

      if(is_non_ema){
        formData['serial_number'] = $("#srp_serial_number_non_ema option:selected").text();
        if(getHydraCoolSrpId){
          ajaxUrl  = addhydracoolsrpnonema;
        }else{
          ajaxUrl  = addhydracoolsrp;
        }

      }else{
        formData['serial_number'] = $("#srp_serial_number").val();
      }
      formData['hydracool_srp_id'] = getHydraCoolSrpId;
      formData['manufacturer_name'] = $("#manufacturer_name").val();
      formData['manufacturing_date'] = $("#manufacturing_date").val();
      formData['sale_date'] = $("#sale_date").val();

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
                  $('#add_other_hydracool_srp_unit_detail_form').show();
              }else{
                $('#add_new_hydracool_form').modal('hide');
              }

              $("#hydracool_srp_form_users :input").prop("disabled", true);
              $('#hydracool_srp_list').DataTable().ajax.reload();
              $('.loader_container').hide();
              getAllHandsetValue = [];

          }else{
            $('#save_hydracool_srp_units_users').removeAttr('disabled');
            toastr['error'](data.message);
          }

        },
        error:function(data){
          $('.loader_container').hide();
          $('#save_hydracool_srp_units_users').removeAttr('disabled');
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
            $('.loader_container').hide();
        }
      });
    },
    focusInvalid: false,
    invalidHandler: function(form, validator) {
       $('.datepicker').datepicker('hide');
    },
});

// add other hydracool srp units
$(document).on('click','#add_other_hydracool_srp_unit_detail_form',function(){
  $('#hydracool_srp_form_users')[0].reset();
  $('#add_other_hydracool_srp_unit_detail_form').hide();
  $("#add_serial_number").show();
  $("#update_serial_number").hide();
  $("#srp_serial_number_non_ema").val('').trigger('change');
  $("#hydracool_srp_form_users :input").prop("disabled", false);
  $("#srp_serial_number_non_ema").prop("disabled", false);
  $("#srp_serial_number_non_ema").val('').trigger('change');
  getTreatmentCenterName();
  updateSerialNumberList();
  $('#handset_id').val('');
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
          $("#hydracool_srp_form_users :input").prop("disabled", false);
          $('#add_new').text('Edit HydraCool SRP Device');
          $('#save_hydracool_srp_units_users').text('Update');
          $('#add_other_hydracool_srp_unit_detail_form').hide();
          $('#handset_id').val(data.handset_id);
          if(userRole!="system administrator"){
              $("#srp_serial_number").prop("disabled", true);
          }
          if(data.is_demo==1){
            $('#is_demo').prop('checked',true);
          }else{
            $('#is_demo').prop('checked',false);
          }
          getTreatmentCenterName();

        }else{
          toastr['error'](data.message);
        }
      },
      error:function(){
        toastr['error']('something went wrong');
      }

    });
  }

});

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
          $('#view_hydracool_srp_details').modal('show');
          if(data.is_demo==1){
            $('#demo_details').text('Yes');
          }else{
            $('#demo_details').text('No');
          }
          getTreatmentCenterName();

        }else{
          toastr['error'](data.message);
        }
        $('.loader_container').hide();
      },
      error:function(){
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
                  $('#hydracool_srp_list').DataTable().ajax.reload();
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
                $('#hydracool_srp_list').DataTable().ajax.reload();
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
  var getHydraCoolSrpSerialNumber=$(this).attr('data-hydracoolsrpSerialNumber');

toastr.warning("Are you sure want to delete "+getHydraCoolSrpSerialNumber+" HydraCool SRP Device?<br /><br /><button type='button' id='confirm_yes' class='btn btn-secondary'>Yes</button>  <button type='button' id='confirm_no' class='btn btn-secondary'>No</button>",'Delete Hydracool SRP',
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
                  $('#hydracool_srp_list').DataTable().ajax.reload();
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

// treatment center validation rules
$("#principal_user_form").submit(function(e) {
  e.preventDefault();
}).validate({
  onkeyup: function(element) {$(element).valid()},
    rules: {
      name: {
        required:true,
        fullnameRegex:true,
      },
      username:{
        required:true,
        usernameRegex:true,
        remote:{
            url:uniqueusername,
            data:{id: function() { return $('#user_id').val(); } },
            headers:{'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            type:'post'
      }
      },
      email: {
        required:true,
        validemail:true,
        remote:{
        url:uniqueuseremail,
        data:{id: function() { return $('#user_id').val(); } },
        headers:{'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        type:'post',
      },
      },
      mobile_telephone_number: {
        required: true,
        telephoneNumber:true,
        maxlength:20
      },

      primary_telephone_number: {
        required: true,
        telephoneNumber:true,
        maxlength:20
      },
    },
    messages: {
      name:{
        required:'Please enter full name',
      },
      username:{
        required:'Please enter Login Username',
        remote:'The Login username is already been taken',
      },
      primary_telephone_number: {
        required: "Please enter telephone number",
      },
      mobile_telephone_number: {
        required: "Please enter mobile number",
      },
      email:{
        required:'Please enter email address',
        validemail:"Invalid email format",
        remote:'Email address is already assigned',
      },
    },
    submitHandler: function(form) {

      $('#save_treatment_center_principal').prop( "disabled", true );
        var formData = $('#principal_user_form').serializeArray().reduce(function(obj, item) {
          obj[item.name] = item.value;
          return obj;
        }, {});
        var getUserId = $('#user_id').val();
        var ajaxUrl = addprincipal;
        if(getUserId){
          ajaxUrl = updateprincipal;
        }
        formData['treatment_center_id'] = getTreatmentCenterId;
        $('.loader_container').show();
        $.ajax({
          url:ajaxUrl,
          data:{formData},
          type:"POST",
          dataType:"JSON",
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          success:function(data){
            if(data.success=='true'){
              toastr['success'](data.message);
              $("#add_new_tcp").removeClass('fade');
              $("#add_new_tcp").modal('hide');
              $('#treatment_center_Principal_list').DataTable().ajax.reload();

            }else{
              $('#save_treatment_center_principal').removeAttr('disabled');
              toastr['error'](data.message);
            }
            $('.loader_container').hide();
          },
          error:function(data){
            $('.loader_container').hide();
            $('#save_treatment_center_principal').removeAttr('disabled');
            if( data.status === 422 ) {
                $('.form-control').removeClass('is-invalid');
                $('.invalid-feedback').remove();
                var errors = data.responseJSON.errors;
                $.each(errors, function (key, val) {
                    $('input[name='+key+']').addClass('is-invalid');
                    $('<span class="invalid-feedback" role="alert"><strong>'+val[0]+'</strong></span>').insertAfter( 'input[name='+key+']');
                });

            }else{
              toastr['error']('Something wrong try again');
            }
          }

        });
    }
});

$("#add_new_tcp").on('hide.bs.modal', function(){
    $('#user_id').val('');
});

$(document).on('click', '.edittreatmentcenteruser', function () {
  var user_id = $(this).attr("data-treatmentcenteruserid");

  $("label.error").hide();
  $(".error").removeClass("error");
  $.ajax({
    url: viewprincipal,
    type: "GET",
    data:{'principal_id':user_id},
    dataType:"json",
    success: function(data) {
        var response = data.users_list[0];
        $('#principal_name').val(response.name);
        $('#principal_username').val(response.username).prop('readonly', true);
        $('#principal_email').val(response.email).val(response.email);
        $("#save_treatment_center_principal").prop("disabled", false);
        $('#principal_primary_telephone_number').val(response.primary_telephone_number);
        $('#principal_mobile_telephone_number').val(response.mobile_telephone_number);
        $('#user_id').val(response.id);
        $('#add_new_tcp').modal('show');
        $('#add_new_principal_text').text('Edit Treatment Centre User');
        $('#save_treatment_center_principal').text('Update');

    },
    error: function(response,status,message) {
      toastr['error']('Something wrong try again');
    }
  });
});

$(document).on('click','.suspendtreatmentcenteruser',function(){
  var user_id = $(this).attr('data-treatmentCenterUserId');
  var user_name=$(this).attr('data-treatmentCenterUserName');

  toastr.warning("Are you sure you want to suspend "+user_name+" principal ?<br /><br /><button type='button' id='confirm_yes' class='btn btn-secondary'>Yes</button>  <button type='button' id='confirm_no' class='btn btn-secondary'>No</button>",'Suspend Treatment centre principal',
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
            $.ajaxSetup({
              headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              }
            });
            $('.loader_container').show();
            $.ajax({
              url: "/suspend/"+user_id,
              type: "POST",
              data: {"userId":user_id,'module':'treatmentcentre'},
              success: function( data ) {
                      $('#toast-container').remove();
                      toastr.options = {
                          "preventDuplicates": true,
                          "timeOut": "1000",
                      }
                      if(data.status==true){
                          toastr['success'](data.message);
                          $('#treatment_center_Principal_list').DataTable().ajax.reload();
                      }else{
                          toastr['error'](data.message);
                      }
                      $('.loader_container').hide();
              },
              error: function(response,status,message) {
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


$(document).on('click','.releasetreatmentcenteruser',function(){
  var user_id = $(this).attr('data-treatmentCenterUserId');
  var user_name=$(this).attr('data-treatmentCenterUserName');

toastr.warning("Are you sure you want to release "+user_name+" principal ?<br /><br /><button type='button' id='confirm_yes' class='btn btn-secondary'>Yes</button>  <button type='button' id='confirm_no' class='btn btn-secondary'>No</button>",'Delete Treatment centre principal',
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
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $('.loader_container').show();
            $.ajax({
                url: "/release/"+user_id,
                type: "POST",
                data: {"userId":user_id,'module':'treatmentcentre'},
                success: function( data ) {
                        $('#toast-container').remove();
                        toastr.options = {
                            "preventDuplicates": true,
                            "timeOut": "1000",
                        }
                        if(data.status==true){
                            toastr['success'](data.message);
                            $('#treatment_center_Principal_list').DataTable().ajax.reload();
                        }else{
                            toastr['error'](data.message);
                        }
                        $('.loader_container').hide();
                },
                error: function(response,status,message) {
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

$(document).on('click','.deletetreatmentcenteruser',function(){
  var user_id = $(this).attr('data-treatmentCenterUserId');
  var user_name=$(this).attr('data-treatmentCenterUserName');

toastr.warning("Are you sure you want to delete "+user_name+" principal ?<br /><br /><button type='button' id='confirm_yes' class='btn btn-secondary'>Yes</button>  <button type='button' id='confirm_no' class='btn btn-secondary'>No</button>",'Delete Treatment centre principal',
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
            $.ajaxSetup({
              headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              }
            });
            $('.loader_container').show();
            $.ajax({
                url: "/user/delete/"+user_id,
                type: "DELETE",
                data: {"userId":user_id,'module':'treatmentcentre'},
                success: function( response ) {
                    $('#toast-container').remove();
                    toastr.options = {
                        "preventDuplicates": true,
                        "timeOut": "1000",
                    }
                    if(response.success==true){
                        toastr['success'](response.message);
                        $('#treatment_center_Principal_list').DataTable().ajax.reload();
                    }else{
                        toastr['error'](response.message);
                    }
                    $('.loader_container').hide();
                },
                error: function(response,status,message) {
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

$(document).on("click", ".viewtreatmentcenteruser" , function() {
  var user_id = $(this).attr('data-treatmentCenterUserId');
  $('.loader_container').show();
  $.ajax({
    url: viewprincipal,
    type: "GET",
    data:{'principal_id':user_id},
    dataType:"json",
    success: function(data) {
        var response = data.users_list[0];
          $('#view_log_details').modal('show');
          $('#show_name').text(response.name);
          $('#show_username').text(response.username);
          $('#show_email').text(response.email);
          $('#show_teleno').text(response.primary_telephone_number);
          $('#show_mobno').text(response.mobile_telephone_number);
          $('.loader_container').hide();
    },
    error: function(response,status,message) {
      $('.loader_container').hide();
      toastr['error']('Something wrong try again');
    }
  });
});


$('body').on('click', '.change-password', function () {
  var user_id = $(this).attr("id");
  var user_name=$(this).attr('data-treatmentCenterUserName');

  toastr.warning("Are you sure you want to reset password of  "+user_name+" principal ?<br /><br /><button type='button' id='confirmationResetPassYes' class='btn btn-secondary'>Yes</button> <button type='button' id='confirmationResetPassNo' class='btn btn-secondary '>No</button>", "Reset Password",
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
          $("#confirmationResetPassYes").click(function(){
            $('#toast-container').remove();
              $.ajaxSetup({
                  headers: {
                      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                  }
              });
              $('.loader_container').show();
              $.ajax({
                  url: "/reset_password/"+user_id,
                  type: "POST",
                  data: {"userId":user_id,'module':'treatmentcentre'},
                  success: function( data ) {
                          $('#toast-container').remove();
                          toastr.options = {
                              "preventDuplicates": true,
                              "timeOut": "1000",
                          }
                          if(data.status==true){
                              toastr['success'](data.message);
                              $('#treatment_center_Principal_list').DataTable().ajax.reload();
                          }else{
                              toastr['error'](data.message);
                          }
                          $('.loader_container').hide();
                  },
                  error: function(response,status,message) {
                    $('.loader_container').hide();
                  }
              });
          });
          $("#confirmationResetPassNo").click(function(){
              $('.toast-close-button').click();
          });
      }
  });

});

// check value is exits
$('.checkvalueExists').on('blur',function(){
  var field = $(this).attr('name');
  var value = $(this).val();
  var current_obj = $(this);
  if(value){
    $.ajax({
      url:checkUsernameExists,
      data:{"field":field,"value":value},
      type:"POST",
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      success:function(data){
        $('.form-control').removeClass('is-invalid');
        $('.invalid-feedback').remove();
        if(data > 0){

          if(field=="username"){
            var lable="Login Username";
            $('input[name='+field+']').addClass('is-invalid');
            $('<span class="invalid-feedback" role="alert"><strong>The Login username is already been taken</strong></span>').insertAfter( 'input[name='+field+']');
          }else{
            $('input[name='+field+']').addClass('is-invalid');
            $('<span class="invalid-feedback" role="alert"><strong>Email address is already assigned</strong></span>').insertAfter( 'input[name='+field+']');
          }
          current_obj.val('');
        }
      },
      error:function(){
        toastr['error']('something went wrong');
      }
    });
  }
});

$('body').on('click', '.forcelogout', function () {
  var user_id = $(this).attr("id");
  var user_name=$(this).attr("data-treatmentCenterUserName");

  toastr.info("Are you sure you want to force logout "+user_name+" principal ?<br /><br /><button type='button' id='confirmationRevertYes' class='btn btn-secondary '>Yes</button> <button type='button' id='confirmationRevertno' class='btn btn-secondary'>No</button>", "Force Logout User",
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
          $("#confirmationRevertYes").click(function(){
              console.log('clicked yes');

              $.ajaxSetup({
                  headers: {
                      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                  }
              });
              $('.loader_container').show();
              $.ajax({
                  url: "/force/"+user_id,
                  type: "POST",
                  data: {"userId":user_id,'module':'treatmentcentre'},
                  success: function( data ) {
                          $('#toast-container').remove();
                          toastr.options = {
                              "preventDuplicates": true,
                              "timeOut": "1000",
                          }
                          if(data.status==true){
                              toastr['success'](data.message);
                              $('#treatment_center_Principal_list').DataTable().ajax.reload();
                          }else{
                              toastr['error'](data.message);
                          }
                          $('.loader_container').hide();
                  },
                  error: function(response,status,message) {
                    $('.loader_container').hide();
                  }
              });
          });
          $("#confirmationRevertno").click(function(){
              $('.toast-close-button').click();
          });
      }
  });

});


$(document).on('click','#print_treatmentcentre_data',function(){
  $('#treatmentcentre_print_data').modal('show');
  $('#updateTreatmentCenter').show();
  $('#printTreatmentCentreDetail').show();
});

// edit treatment center
$(document).on('click','.updateTreatmentCenter',function(){
  var getTreatmentCenterId = $(this).attr('data-treatmentCenterId');
  $('.addnewhydracoolsrp').hide();
  $('#save_treatment_center').hide();
  $('#update_treatment_center').show();
  $('#treatment_center_title').text('Edit Treatment Centre');
  $("label.error").hide();
  $(".error").removeClass("error");
  $("#add_treatment_center :input").prop("disabled", false);
  $("#country_id").prop("disabled", false);
  $("#distributors").prop("disabled", false);

  $.ajax({
    url:treatmentcenterdetails,
    data:{'treatment_center_id':getTreatmentCenterId},
    type:"POST",
    dataType:"JSON",
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    success:function(data){
      if(data.success=='true'){
        var details = data.center_details;
        $('#treatment_ema_code').val(details.treatment_ema_code);
        $('#full_company_name').val(details.full_company_name);
        $('#abbreviated_company_name').val(details.abbreviated_company_name);
        if(details.group_name=='' || details.group_name==null){
          $("#group_name").prop("disabled", true);

        }else{
          $('#is_group').attr("checked",true);
        }
        $('#group_name').val(details.group_name);
        $('#building_name').val(details.building_name);
        $('#full_address').val(details.full_address);
        $('#address_1').val(details.address_1);
        $('#address_2').val(details.address_2);
        $('#address_3').val(details.address_3);
        $('#country_id').val(details.country_id).trigger("change");
        $('#state').val(details.state);
        $('#zipcode').val(details.zipcode);
        $('#name_of_primary_contact').val(details.name_of_primary_contact);
        $('#position').val(details.position);
        $('#telephone_number_of_primary_contact').val(details.telephone_number_of_primary_contact);
        $('#mobile_number_of_primary_contact').val(details.mobile_number_of_primary_contact);
        $('#fax_number').val(details.fax_number);
        $('#email_of_primary_contact').val(details.email_of_primary_contact);
        $('#web_site').val(details.web_site);
        $('#treatment_center_id').val(details.id);
        $('#distributors').val(details.distributors).trigger("change");
        if(details.is_ema==1){
          $('#is_ema').prop('checked', true);
        }
      $('#treatmentcentre_print_data').modal('hide');
      setTimeout(function(){
        $('#add_new_treatment').modal('show');
        $('body').addClass('modal-open');
      }, 500);
      }else{
        toastr['error'](data.message);
      }
    },
    error:function(){
      toastr['error']('something went wrong');
    }
  });
});



// get treatment center name
function  getTreatmentCenterName(){
  $.ajax({
    url:treatmentcenterdetails,
    data:{"treatment_center_id":getTreatmentCenterId},
    type:"POST",
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    success:function(data){
      if(data.success=='true'){
        details = data.center_details;
        $('#added_treatment_center').val(details.full_company_name+"-"+details.treatment_ema_code);
        $('#center_name').text(details.full_company_name+"-"+details.treatment_ema_code);
        $('#added_treatment_center').attr('disabled',true);
        if(details.is_ema==1){
          $('#demo_area').show();
        }else{
          $('#demo_area').hide();
          $('#is_demo').prop('checked',false);
        }
      }else{
        toastr['error'](data.message);
      }
    },
    error:function(){
      $('#srp_serial_number').removeAttr('disabled');
      toastr['error']('something went wrong');
    }

  });
}
$('#srp_serial_number_non_ema').select2({
  tags: true
});

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
          updateSerialNumberList();
        }else{
          $('#AquaB_unit').val('');
          $('#Collagen_unit').val('');
          $('#Hub_Board_unit').val('');
          $('#MicroT_unit').val('');
          $('#UltraB_unit').val('');
          $('#VibroX_unit').val('');
          $('#hydracool_srp_id').val('');
          //$("label.error").show();
          //$(".error").removeClass("error");
        }
      },
      error:function(){
        toastr['error']('Something went wrong');
      }

    });
  }

});

