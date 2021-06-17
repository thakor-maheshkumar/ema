$(document).ready(function() {
  // INITIALIZE DATEPICKER PLUGIN
  $('.datepicker').datepicker({
      clearBtn: true,
      format: "dd-mm-yyyy"
  });

  $('#add_new_cosmetic_solution').on('click',function(){
    $('.datepicker').datepicker('setDate', null);
    $('#cosmetic_data').modal('show');
    $('#cosmetic_id').val('');
    $('#save_cosmetic_data').html('Add');
  });

jQuery.fn.DataTable.Api.register('buttons.exportData()', function ( options ) {
    var searchData = {};
    $("#cosmetic_list tfoot tr th").map(function() {
        var fieldnameData = $(this).attr('dataName').split('-');
        fieldname = fieldnameData[0];
        searchData[fieldname] = $('#'+$(this).attr('dataName')).val();
   }).get();

    if (this.context.length){
        var jsonResult = $.ajax({
            url: getcosmeticdata,
            data:{'search_data':searchData},
            async: false
        });
        return {body: jsonResult.responseJSON, header: $("#cosmetic_list tfoot tr th .dataTables_sizing").map(function() { return this.innerHTML; }).get()};
    }
});
// load the audit list data with datatable
    $('#cosmetic_list').DataTable({
        serverSide: true,
        processing: true,
        responsive:true,
        "scrollX": true,
        autoWidth: false,
        ajax: getcosmeticdata,
        order:['10','desc'],
        dom: 'Blfrtip',
        buttons: [
            {
                extend: 'excel',
                filename: 'Cosmetic Data',
                title:''
            },
            {
                extend: 'csv',
                filename: 'Cosmetic Data',
                title:''
            },
            {
                extend: 'pdf',
                filename: 'Cosmetic Data',
                title:'Cosmetic Data',
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
                        doc.content[1].table.body[i][0].alignment = 'right';
                        doc.content[1].table.body[i][1].alignment = 'right';
                        doc.content[1].table.body[i][2].alignment = 'right';
                        doc.content[1].table.body[i][3].alignment = 'right';
                        doc.content[1].table.body[i][4].alignment = 'right';
                        doc.content[1].table.body[i][5].alignment = 'right';
                        doc.content[1].table.body[i][6].alignment = 'right';
                        doc.content[1].table.body[i][7].alignment = 'right';
                        doc.content[1].table.body[i][8].alignment = 'right';
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
                filename: 'Cosmetic Data',
                title:'Cosmetic Data',
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
                    'data-attr': 'cosmetic_list',
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
            {data: 'solution_bottle_pack', name: 'solution_bottle_pack', className: "text-right", width: "150px"},
            {data: 'solution_1', name: 'solution_1', className: "text-right", width: "150px"},
            {data: 'solution_2', name: 'solution_2', className: "text-right", width: "150px"},
            {data: 'solution_3', name: 'solution_3', className: "text-right", width: "150px"},
            {data: 'solution_4', name: 'solution_4', className: "text-right", width: "150px"},
            {data: 'cosmetic_fresh_pack', name: 'cosmetic_fresh_pack', className: "text-right", width: "200px"},
            {data: 'cosmetic_bright_pack', name: 'cosmetic_bright_pack', className: "text-right", width: "200px"},
            {data: 'booster_packs', name: 'booster_packs', className: "text-right", width: "150px"},
            {data: 'aquaB_tips', name: 'aquaB_tips', className: "text-right", width: "150px"},
            {data: 'delivery_date', name: 'delivery_date', width: "150px"},
            {data: 'created_at', name: 'created_at', orderable: true, visible: false},
            {data: 'action', name: 'action', orderable: false, searchable: false, width: "50px"}
        ],
        fnDrawCallback:function () {
          var table = $('#cosmetic_list').DataTable();
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
                if(columnText!="Actions"){
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
});


// cosmetic solution validation
$("#cosmetic_form").submit(function(e) {
  e.preventDefault();
}).validate({
    onkeyup: function(element) {$(element).valid()},
  rules: {
    solution_bottle_pack:{
        number:true
    },
    solution_1:{
        number:true
    },
    solution_2:{
        number:true
    },
    solution_3:{
        number:true
    },
    solution_4:{
        number:true
    },
    cosmetic_fresh_pack:{
        number:true
    },
    cosmetic_bright_pack:{
        number:true
    },
    booster_packs:{
        number:true
    },
    aquaB_tips:{
        number:true
    },
    delivery_date: "required",
  },
  errorPlacement: function(error, element) {
    var elem = $(element);
    if (elem.attr("name")=="delivery_date") {
        error.appendTo('#delivery_date_error');
    }else if (elem.attr("name")=="solution_bottle_pack") {
        error.appendTo('#solution_bottle_pack_error');
    }else if (elem.attr("name")=="solution_1") {
        error.appendTo('#solution_1_error');
    }else if (elem.attr("name")=="solution_2") {
      error.appendTo('#solution_2_error');
    }else if (elem.attr("name")=="solution_3") {
      error.appendTo('#solution_3_error');
    }else if (elem.attr("name")=="solution_4") {
      error.appendTo('#solution_4_error');
    }else if (elem.attr("name")=="cosmetic_fresh_pack") {
      error.appendTo('#cosmetic_fresh_pack_error');
    }else if (elem.attr("name")=="cosmetic_bright_pack") {
      error.appendTo('#cosmetic_bright_pack_error');
    }else if (elem.attr("name")=="booster_packs") {
      error.appendTo('#booster_packs_error');
    }else if (elem.attr("name")=="solution_2") {
      error.appendTo('#solution_2_error');
    }else if (elem.attr("name")=="aquaB_tips") {
      error.appendTo('#tip_pack_error');
    }else {
        error.insertAfter(element);
    }
  },
  submitHandler: function(form) {
    $('.loader_container').show();
      $('#save_cosmetic_data').prop( "disabled", true );
      var url = addcosmeticdata;
      var formData = $('#cosmetic_form').serializeArray().reduce(function(obj, item) {
          obj[item.name] = item.value;
          return obj;
      }, {});
      formData['treatment_center_id'] = getTreatmentCenterId;
      var cosmetic_id = $('#cosmetic_id').val();
      if(cosmetic_id){
            url =updatecosmeticdata;
            formData['cosmetic_id'] = cosmetic_id;
      }

      $.ajax({
          url:url,
          data:{formData},
          type:"POST",
          dataType:"JSON",
          headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          success:function(data){
              toastr['success'](data.message);
              $('#save_cosmetic_data').prop( "disabled", false );
              $('#cosmetic_list').DataTable().ajax.reload();
              $('#cosmetic_data').modal('hide');
              $('.loader_container').hide();
          },
          error: function(data) {
            $('.loader_container').hide();
          $('#save_cosmetic_data').prop( "disabled", false );
          if( data.status === 422 ) {
              $('.form-control').removeClass('is-invalid');
              $('.invalid-feedback').remove();
              var errors = data.responseJSON.errors;
              $.each(errors, function (key, val) {
                  $('input[name='+key+']').addClass('is-invalid');
                  $('<span class="invalid-feedback" role="alert"><strong>'+val[0]+'</strong></span>').insertAfter( 'input[name='+key+']');
              });
          }else{
              $('#save_cosmetic_data').prop( "disabled", false );
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
  },
  focusInvalid: false,
    invalidHandler: function(form, validator) {
       $('.datepicker').datepicker('hide');
    },
});


// edit cosmetic solutions
$(document).on('click','.editcosmeticdata',function(){
    var getCosmeticId = $(this).attr('data-cosmeticId');

    if(getCosmeticId){
      $.ajax({
        url:viewcosmeticdtails,
        data:{'cosmetic_data_id':getCosmeticId,'treatment_center_id':getTreatmentCenterId},
        type:"POST",
        dataType:"JSON",
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success:function(data){
          if(data.success==true){
            var cosmetic_details = data.cosmetic_details;
            $('#solution_bottle_pack').val(cosmetic_details.solution_bottle_pack);
            $('#solution_1').val(cosmetic_details.solution_1);
            $('#solution_2').val(cosmetic_details.solution_2);
            $('#solution_3').val(cosmetic_details.solution_3);
            $('#solution_4').val(cosmetic_details.solution_4);
            $('#cosmetic_fresh_pack').val(cosmetic_details.cosmetic_fresh_pack);
            $('#cosmetic_bright_pack').val(cosmetic_details.cosmetic_bright_pack);
            $('#booster_packs').val(cosmetic_details.booster_packs);
            $('#aquaB_tips').val(cosmetic_details.aquaB_tips);
            $('#delivery_date').val(cosmetic_details.delivery_date);
            $('#save_cosmetic_data').text('Update');
            $('#cosmetic_id').val(cosmetic_details.id);
            $('#cosmetic_data').modal('show');
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

// delete cosmetic data
$(document).on('click','.deletecosmeticdata',function(){
    var cosmeticId = $(this).attr('data-cosmeticId');

  toastr.warning("Are you sure want to delete?<br /><br /><button type='button' id='confirm_yes' class='btn btn-secondary'>Yes</button>  <button type='button' id='confirm_no' class='btn btn-secondary'>No</button>",'Delete treatment center',
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
                url:deleteCosmeticData,
                data:{'cosmetic_id':cosmeticId,'treatment_center_id':getTreatmentCenterId},
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
                    $('#cosmetic_list').DataTable().ajax.reload();
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


// view cosmetic solutions
$(document).on('click','.viewcosmeticdata',function(){
    var getCosmeticId = $(this).attr('data-cosmeticId');
   $('.loader_container').show();
    if(getCosmeticId){
      $.ajax({
        url:viewcosmeticdtails,
        data:{'cosmetic_data_id':getCosmeticId,'treatment_center_id':getTreatmentCenterId},
        type:"POST",
        dataType:"JSON",
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success:function(data){
          if(data.success==true){
            var cosmetic_details = data.cosmetic_details;
            $('#solution_bottle_pack_view').html(cosmetic_details.solution_bottle_pack);
            $('#solution_1_view').html(cosmetic_details.solution_1);
            $('#solution_2_view').html(cosmetic_details.solution_2);
            $('#solution_3_view').html(cosmetic_details.solution_3);
            $('#solution_4_view').html(cosmetic_details.solution_4);
            $('#cosmetic_fresh_pack_view').html(cosmetic_details.cosmetic_fresh_pack);
            $('#cosmetic_bright_pack_view').html(cosmetic_details.cosmetic_bright_pack);
            $('#booster_packs_view').html(cosmetic_details.booster_packs);
            $('#aquaB_tips_view').html(cosmetic_details.aquaB_tips);
            $('#delivery_date_view').html(cosmetic_details.delivery_date);
            $('#view_cosmetic_details').modal('show');
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