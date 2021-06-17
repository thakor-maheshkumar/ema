var startDate = new Date();
$(document).ready(function() {
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
    $(document).on('click','#addtreatmentcenter',function(){
      $("#add_new_treatment").modal('show');
      $('#add_treatment_center')[0].reset();
      $('#hydracool_srp_form')[0].reset();
      $('#principal_form')[0].reset();
      $('#treatment_center_id').val('');
      $('#hydracool_srp_id').val('');
      $('#is_ema_value').val('');
      $('#full_company_name').removeAttr('disabled');
      $('.add_new_tcp').show();
      $('.add_new_tcp').addClass('disabled');
      $('#save_treatment_center').show();
      $('#update_treatment_center').hide();
      $('#treatment_center_title').text('Add New Treatment Centre');
      $("label.error").hide();
      $(".error").removeClass("error");
      $("#add_treatment_center :input").prop("disabled", false);
      $("#hydracool_srp_form :input").prop("disabled", false);
      $("#added_treatment_center").prop("disabled", true);
      $("#principal_form :input").prop("disabled", false);
      $('#added_treatment_center_second').prop("disabled", true);
      $('#save_treatment_center_principal').removeAttr('disabled');
      $("#srp_serial_number_non_ema_list").val('').trigger('change');
      $("#group_name").prop("disabled", true);
      $("#country_id").prop("disabled", false);
      $("#country_id").val('').trigger('change');
      $('#country_id-error').hide();
      if(roleName!="distributor principal"){
        $("#distributors").prop("disabled", false);
        $("#distributors").val('').trigger('change');
      }
      $("#is_group").prop("checked", false);
    });

    $(document).on('click','#addnewhydracoolsrp',function(){
      $("#add_new_tcp").removeClass('fade');
      $("#add_new_tcp").modal('hide');
      $("#add_new_hydracool").modal('show');
      $('#manufacturing_date').datepicker('setDate', null);
      $('#sale_date').datepicker('setDate', null);
    });

    $(document).on('click','#add_new_principal',function(){
      $("#add_new_treatment").removeClass('fade');
      $("#add_new_treatment").modal('hide');
      $("#add_new_tcp").modal('show');
    });

  jQuery.fn.DataTable.Api.register( 'buttons.exportData()', function ( options ) {
      var searchData = {};
      $("#treatmence_center_list tfoot tr th").map(function() {
          var fieldnameData = $(this).attr('dataName').split('-');
          fieldname = fieldnameData[0];
          var value = $('#'+$(this).attr('dataName')).val();
          if(fieldname=="status"){
            if(value=='Suspended' || value=='suspended'){
              value=3;
            }else if(value=='Active' || value=='active'){
              value=1;
            }
          }
          searchData[fieldname] =value;
     }).get();

      if (this.context.length){
          var jsonResult = $.ajax({
              url: treatmentcenterlist,
              data:{'search_data':searchData},
              async: false
          });
          return {body: jsonResult.responseJSON, header: $("#treatmence_center_list tfoot tr th .dataTables_sizing").map(function() { return this.innerHTML; }).get()};
      }
  });
  // load the audit list data with datatable
  var treatmence_center_list = $('#treatmence_center_list').DataTable({
      serverSide: true,
      processing: true,
      responsive:true,
      "scrollX": true,
      autoWidth: false,
      ajax: treatmentcenterlist,
      order:['8','desc'],
      dom: 'Blfrtip',
      buttons: [
          {
              extend: 'excel',
              filename: 'Treatment centre',
              title:'',
              customizeData: function ( data ) {
                for (var i=0; i<data.body.length; i++){
                  for (var j=0; j<data.body[i].length; j++ ){
                        if(j < 6){
                          data.body[i][j] = '\u200C' + data.body[i][j];
                        }
                    }
                }
              }
          },
          {
              extend: 'csv',
              filename: 'Treatment centre',
              title:''
          },
          {
              extend: 'pdf',
              filename: 'Treatment centre',
              title:'Treatment centre',
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
                    doc.content[1].table.body[i][6].alignment = 'right';
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
              filename: 'Treatment centre',
              title:'Treatment centre',
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
                'data-attr': 'treatmence_center_list',
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
          {data: 'treatment_ema_code', name: 'treatment_ema_code', width: "170px"},
          {data: 'full_company_name', name: 'full_company_name', width: "150px"},
          {data: 'primary_region', name: 'primary_region', width: "100px"},
          {data: 'name_of_primary_contact', name: 'name_of_primary_contact', width: "150px"},
          {data: 'email_of_primary_contact', name: 'email_of_primary_contact', width: "250px"},
          {data: 'mobile_number_of_primary_contact', name: 'mobile_number_of_primary_contact', width: "170px"},
          {data: 'install_device', name: 'install_device', className: "text-right", width: "150px"},
          {data: 'status', name: 'status', orderable: true, width: "70px"},
          {data: 'created_at', name: 'created_at', orderable: true, visible: false},
          {data: 'action', name: 'action', orderable: false, searchable: false, width: "70px"}
      ],
      fnDrawCallback:function () {
        var table = $('#treatmence_center_list').DataTable();
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
              if(columnText!="Actions" && columnText!="Status"){
                  $(input).appendTo($(column.header()).empty())
                  .on('keyup', function (e) {
                    //if(e.keyCode == 13) {
                      var val = $.fn.dataTable.util.escapeRegex($(this).val());
                      column.search(val ? val : '', true, false).draw();
                    //}
                  });
              }else{
                  $(input).appendTo('Actions');
              }
          });
      }
    }).on('draw.dt', function () {
  var info = treatmence_center_list.page.info();
  if(info.page + 1 === info.pages || info.pages==0) {
    $('.table-responsive').removeClass('datatable_resize');
  } else {
    $('.table-responsive').addClass('datatable_resize');
  }
});

});


// added  rule for check treatment centre code 5 digit length
jQuery.validator.addMethod("exactlength", function(value, element, param) {
  return this.optional(element) || value.length == param;
 }, $.validator.format("Please enter exactly {0} characters."));

// treatment center validation rules
  $("#add_treatment_center").submit(function(e) {
      e.preventDefault();
  }).validate({
    onkeyup: function(element) {$(element).valid()},
      rules: {
        treatment_ema_code:{
          required:true,
          pattern:/^[0-9a-zA-Z/ -]+$/,
          maxlength:10,
          remote:{
            url:uniquecentercode,
            data:{id: function() { return $('#treatment_center_id').val(); } },
            headers:{'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            type:'post'
        }
        },
        full_company_name:{
          required:true,
          commanRegex:true,
        },
        abbreviated_company_name:{
          commanRegex:true,
        },
        group_name: {
            required: function(elem)
            {
                return $("#is_group:checked").length > 0;
            },
            commanRegex:true,
        },
        building_name:{
          commanRegex:true,
        },
        address_1:{
          required:true,
          commanRegex:true,
        },
        address_2:{
          commanRegex:true,
        },
        address_3:{
          commanRegex:true,
        },
        full_address:{
          commanRegex:true,
        },

        state:{
          required:true,
          commanRegex:true,
        },
        zipcode: {
          required: true,
        },
        name_of_primary_contact:{
          required:true,
          commanRegex:true,
        },
        position:{
          required:true,
          commanRegex:true,
        },
        telephone_number_of_primary_contact: {
          required: true,
          telephoneNumber:true,
          maxlength:20
        },
        mobile_number_of_primary_contact: {
          required: true,
          telephoneNumber:true,
          maxlength:20
        },
        fax_number: {
          pattern:/^(?=.*[0-9])[- +()0-9]+$/,
          maxlength:20
        },
        web_site: {
          pattern:/^((ftp|http|https):\/\/)?(www.)?(?!.*(ftp|http|https|www.))[a-zA-Z0-9_-]+(\.[a-zA-Z]+)+((\/)[\w#]+)*(\/\w+\?[a-zA-Z0-9_]+=\w+(&[a-zA-Z0-9_]+=\w+)(^\/{1,2})*)?/
        },
        country_id:"required",
        email_of_primary_contact: {
          required: true,
          validemail:true,
          maxlength: 50
        },

      },
      messages: {
        treatment_ema_code: {
          pattern:"Only alphanumeric, space, '-' and '/' are allowed",
          remote:'Center code already exist',
        },
        email_of_primary_contact:{
          validemail:"Invalid email format"
        },
        telephone_number_of_primary_contact: {
          required: "Please enter telephone number",
        },
        mobile_number_of_primary_contact: {
          required: "Please enter mobile number",
        },
        country_id: {
          required: "Please Select Country",
        },
        fax_number:{
          pattern:"Only numbers, space, '+', '-', '()' are allowed",
        },
        web_site:{
          pattern:"Please enter a valid URL"
        }
      },
      errorPlacement: function(error, element) {
        var elem = $(element);
        if (elem.hasClass("select2-hidden-accessible")) {
            element = $("#select2-" + elem.attr("id") + "-container").parent();
            error.insertAfter(element);
        } else {
            error.insertAfter(element);
        }
      },
      submitHandler: function(form) {
        $('#save_treatment_center').prop( "disabled", true );
        $('.loader_container').show();
        var formData = $('#add_treatment_center').serializeArray().reduce(function(obj, item) {
            obj[item.name] = item.value;
            return obj;
          }, {});

          $.ajax({
            url:addcenter,
            data:{formData},
            type:"POST",
            dataType:"JSON",
            headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success:function(data){
              if(data.success=="true"){
                var treatment_ema_code = $('#treatment_ema_code').val();
                $('#treatmence_center_list').DataTable().ajax.reload();
                toastr['success'](data.message);
                  $('#treatment_center_id').val(data.treatment_center_id);
                  $('#added_treatment_center').val(data.full_company_name+"-"+treatment_ema_code);
                  $('#added_treatment_center_second').val(data.full_company_name+"-"+treatment_ema_code);

                  $('.add_new_tcp').attr('id','add_new_principal');
                  $('.add_new_tcp').removeClass('disabled');


                $("#add_treatment_center :input").prop("disabled", true);
                $("#country_id").prop("disabled", true);
                $("#distributors").prop("disabled", true);

              }else{
                $('#save_treatment_center').removeAttr('disabled');
                toastr['error'](data.message);
              }
              $('.loader_container').hide();
            },
            error: function(data) {
              $('.loader_container').hide();
              $('#save_treatment_center').removeAttr('disabled');
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
            },
            focusInvalid: false,
            invalidHandler: function(form, validator) {

                if (!validator.numberOfInvalids())
                    return;

                $('html, body').animate({
                    scrollTop: $(validator.errorList[0].element).offset().top
                }, 1000);

            },
          });
      }
  });


//hydracool srp and units validation rules
  $("#hydracool_srp_form").submit(function(e) {
    e.preventDefault();
  }).validate({
    onkeyup: function(element) {$(element).valid()},
    rules: {
      srp_serial_number_non_ema_list: {
        required: "#srp_serial_number_non_ema_list:visible",
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
    srp_serial_number:{
      remote:'serial number already exist'
    },
    srp_serial_number_non_ema_list:{
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
        $('#save_hydracool_srp_units').prop( "disabled", true );
        var hydracool_srp_id = $('#hydracool_srp_id').val();
        var is_ema_value = $('#is_ema_value').val();
        var ajaxUrl =  addhydracoolsrp;

        var formData ={};
        var srpunits ={};

        formData['serial_number'] = $("#srp_serial_number").val();
        formData['manufacturer_name'] = $("#manufacturer_name").val();
        formData['manufacturing_date'] = $("#manufacturing_date").val();
        formData['sale_date'] = $("#sale_date").val();

        if(hydracool_srp_id) {
            ajaxUrl = addhydracoolsrpnonema;
            formData['hydracool_srp_id'] = hydracool_srp_id;
        }

        if(is_ema_value=='0'){
          formData['serial_number'] = $("#srp_serial_number_non_ema_list").val();
        }

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
        formData['treatment_center_id'] = $('#treatment_center_id').val();

        if($('#is_demo').is(':checked')){
          formData['is_demo'] = 1;
        }else{
          formData['is_demo'] = 0;
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
                $('#add_other_hydracool_srp_unit').show();
                $('#hydracool_srp_list').DataTable().ajax.reload();
                $('#treatmence_center_list').DataTable().ajax.reload();
                $("#hydracool_srp_form :input").prop("disabled", true);

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
  $(document).on('click','#add_other_hydracool_srp_unit',function(){
    $(this).hide();
    var getPreviousTreatmentCentreName = $('#added_treatment_center').val();
    $('#hydracool_srp_form')[0].reset();
    $("#hydracool_srp_form :input").prop("disabled", false);
    $("#added_treatment_center").val(getPreviousTreatmentCentreName);
    $("#added_treatment_center").prop("disabled", true);
    $("#srp_serial_number_non_ema_list").prop("disabled", false);
    $("#srp_serial_number_non_ema_list").val('').trigger('change');
    $('#save_hydracool_srp_units').prop( "disabled", false );
    // $('.add_other_hydracool_srp_unit').prop( "disabled", true );
  });

// treatment center validation rules
  $("#principal_form").submit(function(e) {
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
    messages:{
      email:{
        validemail:"Invalid email format",
      },
    },
      submitHandler: function(form) {
        $('.loader_container').show();
        $('#save_treatment_center_principal').prop( "disabled", true );
          var formData = $('#principal_form').serializeArray().reduce(function(obj, item) {
            obj[item.name] = item.value;
            return obj;
          }, {});
          $('.loader_container').show();
          formData['treatment_center_id'] = $('#treatment_center_id').val();
          $.ajax({
            url:addprincipal,
            data:{formData},
            type:"POST",
            dataType:"JSON",
            headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success:function(data){
              if(data.success=='true'){
                toastr['success'](data.message);
                $('#save_treatment_center_principal').prop( "disabled", true );
                $("#principal_form :input").prop("disabled", true);
                // $("#add_new_tcp").removeClass('fade');
                // $("#add_new_tcp").modal('hide');
                // $("#add_new_hydracool").modal('hide');
                $('#treatmence_center_list').DataTable().ajax.reload();

                  // $('.addnewhydracoolsrp').attr('id','addnewhydracoolsrp');
                  // $('.addnewhydracoolsrp').removeClass('disabled');

                  if (!$("#is_ema").is(":checked")) {
                    alert('fdsfs');
                      $('#ema_area').hide();
                      $('#non_ema_area').show();
                      $('#is_ema_value').val(0);
                      $('#demo_area').remove();
                  }else{
                    alert('no checked');
                      $('#ema_area').show();
                      $('#non_ema_area').hide();
                      $('#is_ema_value').val('');
                  }

              }else{
                $('#save_treatment_center_principal').removeAttr('disabled');
                $('#update_treatment_center').removeAttr('disabled');
                toastr['error'](data.message);
              }
              $('.loader_container').hide();
            },
            error:function(data){
              $('.loader_container').hide();
              $('#save_treatment_center_principal').removeAttr('disabled');
              $('#update_treatment_center').removeAttr('disabled');
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

// check value is exits
    // hydracool srp serial number
  $('#srp_serial_number_non_ema_list').select2({
    tags: true
  });

  $('.select_dropdown').select2();

  // get the ser unit by srp serial number
  $('#srp_serial_number_non_ema_list').on('change',function(){
    var getSelectedValue = $("#srp_serial_number_non_ema_list option:selected").text();
    if(getSelectedValue!="undefined" && getSelectedValue!="" && getSelectedValue!="Select OR Enter Serial number" ){
      $('#srp_serial_number_non_ema_list-error').remove('');
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

          }else if(typeof(data.is_exists) != "undefined"){
            toastr['error']('Serial number already exists');
            $('#srp_serial_number_non_ema_list').val('').trigger("change");
          }else{
            $('#AquaB_unit').val('');
            $('#Collagen_unit').val('');
            $('#Hub_Board_unit').val('');
            $('#MicroT_unit').val('');
            $('#UltraB_unit').val('');
            $('#VibroX_unit').val('');
            $('#hydracool_srp_id').val('');
          }
        },
        error:function(){
          toastr['error']('Something went wrong');
        }

      });
    }

  });

// edit treatment center
  $(document).on('click','.editTreatmentCenter',function(){
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

          $('#add_new_treatment').modal('show');

        }else{
          toastr['error'](data.message);
        }
      },
      error:function(){
        toastr['error']('something went wrong');
      }

    });

  });

// update treatment center
  $(document).on('click','#update_treatment_center',function(e){

    e.preventDefault();
    if($('#add_treatment_center').valid()){
      $('#update_treatment_center').prop( "disabled", true );
      var treatment_center_id = $('#treatment_center_id').val();
      var formData = $('#add_treatment_center').serializeArray().reduce(function(obj, item) {
          obj[item.name] = item.value;
          return obj;
        }, {});
        if ($("#is_ema").is(":checked")) {
          formData['is_ema'] = 1;
        }else{
          formData['is_ema'] = 0;
        }
        formData['treatmentCenterId'] = treatment_center_id;
        $('.loader_container').show();
        $.ajax({
          url:updatecenter,
          data:{formData},
          type:"POST",
          dataType:"JSON",
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          success:function(data){
            if(data.success=="true"){
              toastr['success'](data.message);
              $("#add_new_treatment").removeClass('fade');
              $("#add_new_treatment").modal('hide');
              $('#add_treatment_center')[0].reset();
              $('#treatment_center_id').val('');
              $('#treatmence_center_list').DataTable().ajax.reload();
              if(isDetails==1){
                setTimeout(function(){ window.location.reload()}, 1000);
              }
            }else{
              $('#save_treatment_center').removeAttr('disabled');
              toastr['error'](data.message);
            }
            $('.loader_container').hide();
          },
          error:function(data){
            $('.loader_container').hide();
            if( data.status === 422 ) {
                $('.form-control').removeClass('is-invalid');
                $('.invalid-feedback').remove();
                var errors = data.responseJSON.errors;
                $.each(errors, function (key, val) {
                    $('input[name='+key+']').addClass('is-invalid');
                    $('<span class="invalid-feedback" role="alert"><strong>'+val[0]+'</strong></span>').insertAfter( 'input[name='+key+']');
                });
                $('#update_treatment_center').removeAttr('disabled');
            }
          }

        });
    }
  });


  $(document).on('click','.deleTetreatmentCenter',function(){
    var getCurrenttreatmentCenterId = $(this).attr('data-treatmentCenterId');
    var getCurrenttreatmentCenterName = $(this).attr('data-treatmentCenterName');

  toastr.warning("Are you sure want to delete "+getCurrenttreatmentCenterName+" treatment centre ?<br /><br /><button type='button' id='confirm_yes' class='btn btn-secondary'>Yes</button>  <button type='button' id='confirm_no' class='btn btn-secondary'>No</button>",'Delete treatment centre',
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
                url:deletecenter,
                data:{'treatment_center_id':getCurrenttreatmentCenterId},
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
                    $('#treatmence_center_list').DataTable().ajax.reload();
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

  $('#is_group').on('click',function(){
      if($(this).prop("checked") == true){
        $('#group_name').prop('disabled',false);
      }else{
        $('#group_name').prop('disabled',true);
        $('#group_name').val('');
        $('#group_name-error').remove();
        $('#group_name').removeClass('error');
      }
  });

  $(document).on('click','.suspendtreatmentcenter',function(){
  var getCurrenttreatmentCenterId = $(this).attr('data-treatmentcenterId');
  var getCurrenttreatmentCenterName=$(this).attr('data-treatmentcenterName');

  toastr.warning("Are you sure want to suspend "+getCurrenttreatmentCenterName+" treatment centre ?<br /><br /><button type='button' id='confirm_yes' class='btn btn-secondary'>Yes</button>  <button type='button' id='confirm_no' class='btn btn-secondary'>No</button>",'Suspend treatment centre',
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
                url:suspendcenter,
                data:{'treatment_center_id':getCurrenttreatmentCenterId},
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
                    $('#treatmence_center_list').DataTable().ajax.reload();
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

  $(document).on('click','.releasetmentcenter',function(){
    var getCurrenttreatmentCenterId = $(this).attr('data-treatmentcenterId');
    var getCurrenttreatmentCenterName=$(this).attr('data-treatmentcenterName');

    toastr.warning("Are you sure want to release "+getCurrenttreatmentCenterName+" treatment centre ?<br /><br /><button type='button' id='confirm_yes' class='btn btn-secondary'>Yes</button>  <button type='button' id='confirm_no' class='btn btn-secondary'>No</button>",'Release treatment centre',
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
                  url:releasecenter,
                  data:{'treatment_center_id':getCurrenttreatmentCenterId},
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
                      $('#treatmence_center_list').DataTable().ajax.reload();
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


document.onreadystatechange = function() {
  if (document.readyState !== "complete") {
    setTimeout(function(){
       var elementheight = $('.dataTables_scrollBody').height();
       var devide = 27;
       var result = parseInt(elementheight / devide);
       $('#treatmence_center_list').DataTable().page.len(result).draw();
    }, 100);
  }
};
