$(document).ready(function(){

jQuery.fn.DataTable.Api.register( 'buttons.exportData()', function ( options ) {
        var searchData = {};
        $("#distributor_list tfoot tr th").map(function() {
            searchData[$(this).attr('dataName')] = $('#'+$(this).attr('dataName')).val();
       }).get();
        if (this.context.length){
            var jsonResult = $.ajax({
                url: list_distributor,
                data:{'search_data':searchData},
                async: false
            });
            return {body: jsonResult.responseJSON, header: $("#distributor_list tfoot tr th .dataTables_sizing").map(function() { return this.innerHTML; }).get()};
        }
    });

  var distributor_list = $('#distributor_list').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        "scrollX": true,
        ajax: list_distributor,
        order:['9','desc'],
        dom: 'Blfrtip',
        autoWidth: false,
        buttons: [
          {
              extend: 'excel',
              filename: 'Distributor',
              title:'',
              customizeData: function ( data ) {
                for (var i=0; i<data.body.length; i++){
                  for (var j=0; j<data.body[i].length; j++ ){
                      if(j <6 ){
                        data.body[i][j] = '\u200C' + data.body[i][j];
                      }
                    }
                }
            }
          },
          {
              extend: 'csv',
              filename: 'Distributor',
              title:''
          },
          {
              extend: 'pdf',
              filename: 'Distributor',
              title:'',
              orientation : 'landscape',
              customize: function ( doc ) {
                var objLayout = {};
                objLayout['hLineWidth'] = function(i) { return .5; };
                objLayout['vLineWidth'] = function(i) { return .5; };
                objLayout['hLineColor'] = function(i) { return '#aaa'; };
                objLayout['vLineColor'] = function(i) { return '#aaa'; };
                doc.content[0].layout = objLayout;
                var rowCount = doc.content[0].table.body.length;
                for (i = 1; i < rowCount; i++) {
                    doc.content[0].table.body[i][6].alignment = 'right';
                    doc.content[0].table.body[i][7].alignment = 'right';
                }
                  doc.content.splice( 0, 0, {
                      margin: [ 0, 0, 10, 12 ],
                      alignment: 'right',
                      image: 'data:image/png;base64,'+$('#base64_image').val()
                  } );
              }
          },
          {
              extend: 'print',
              filename: 'Distributor',
              title:'',
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
                'data-attr': 'distributor_list',
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
        {data: 'distributor_code', name: 'distributor_code', width: "150px"},
        {data: 'full_company_name', name: 'full_company_name', width: "150px"},
        {data:'primary_region',name:'primary_region', width: "100px"},
        {data:'name_of_primary_contact',name:'name_of_primary_contact', width: "150px"},
        {data:'email_of_primary_contact',name:'email_of_primary_contact', width: "250px"},
        {data:'mobile_number_of_primary_contact',name:'mobile_number_of_primary_contact', width: "200px"},
        {data:'total_treatmentcetre',name:'total_treatmentcetre', className: "text-right", width: "200px"},
        {data:'total_install_device',name:'total_install_device', className: "text-right", width: "200px"},
        {data: 'status', name: 'status', width: "70px"},
        {data: 'created_at', name: 'created_at', orderable: true, visible: false},
        {data: 'action', name: 'action', orderable: false, searchable: false, width: "80px"},
    ],
    fnDrawCallback:function () {
        var table = $('#distributor_treatment').DataTable();
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
}).on('draw.dt', function () {
  var info = distributor_list.page.info();
  if(info.page + 1 === info.pages || info.pages==0) {
    $('.table-responsive').removeClass('datatable_resize');
  } else {
    $('.table-responsive').addClass('datatable_resize');
  }
});


$('#add_distributor').on('hidden.bs.modal', function(){
  //remove the backdrop
  $('.modal-backdrop').remove();
});

$(function() {

  enable_groupName();

  $("#is_group").click(enable_groupName);

});

function enable_groupName() {

  if (this.checked) {

    $("input.group_name").removeAttr("disabled");

  } else {

    $("input.group_name").attr("disabled", true);

    $('input.group_name').val('');

    $('#group_name-error').remove();

    $('#group_name').removeClass('error');

  }

}
$("#is_group1").change(function() {
    var ischecked= $(this).is(':checked');
    if(!ischecked){
      $('.company_group_name').prop("disabled", true);
      $('#company_group_name').val('');
    }else{
      $('.company_group_name').prop("disabled", false);
    }

});

$("#is_group1").change(function() {
    var ischecked= $(this).is(':checked');
    if(!ischecked){
      $('.group_name').prop("disabled", true);
      $('#group_nameOne').val('');
    }else{
      $('.group_name').prop("disabled", false);
    }

});

  $('.select_dropdown').select2();
  $('.select_dropdown_edit').select2();

$(document).on("click",'.editDetail',function(){
  var distributor_id=$(this).attr('data-distributorId');
  $.ajax({
    url:list_distributor,
    data:{"id":distributor_id},
    dataType:"JSON",
    success:function(data){
      if(data.success==1){
        $('#edit_distributor_detail2').modal('show');
        $('#id1').val(data.data.id);
        $('#internal_idEdit').val(data.data.internal_id);
        $('#distributor_codeEdit').val(data.data.distributor_code);
        $('#full_company_nameEdit').val(data.data.full_company_name);
        $('#abbreviated_company_nameEdit').val(data.data.abbreviated_company_name);
        if(data.data.group_name == ''){
          $('#is_group1').prop("checked", false);
          $('.group_name').prop("disabled", true);
        }else{
          $('#is_group1').prop("checked", true);
          $('#group_nameOne').val(data.data.group_name);
          $('.group_name').prop("disabled", false);
        }
        $('#full_addressEdit').val(data.data.full_address);
        $('#building_nameEdit').val(data.data.building_name);
        $('#address1Edit').val(data.data.address1);
        $('#address2Edit').val(data.data.address2);
        $('#address3Edit').val(data.data.address3);
        $('#stateEdit').val(data.data.state);
        $('#zipcodeEdit').val(data.data.zipcode);
        $('#positionEdit').val(data.data.position);
        $('#country_idEdit').val(data.data.country_id).trigger("change");
        $('#country_codeEdit').val(data.data.country_code);
        $('#fax_numberEdit').val(data.data.fax_number);
        $('#web_siteEdit').val(data.data.web_site);
        $('#name_of_primary_contactEdit').val(data.data.name_of_primary_contact);
        $('#telephone_number_of_primary_contactEdit').val(data.data.telephone_number_of_primary_contact);
        $('#mobile_number_of_primary_contactEdit').val(data.data.mobile_number_of_primary_contact);
        $('#email_of_primary_contactEdit').val(data.data.email_of_primary_contact);
        //$("#group_nameOne").prop("disabled", false);
        //$("#is_group1").prop("checked", true);

      }
    }
  })
});



  $(document).on('click','.suspenddistributor',function(){
    var distributor_id=$(this).attr('data-distributorId');
    var distributor_name=$(this).attr('data-distributorName');
    toastr.warning("Are you sure you want to suspend "+distributor_name+" distributor?<br /><br /><button type='button' id='confirm_yes' class='btn btn-secondary'>Yes</button>  <button type='button' id='confirm_no' class='btn btn-secondary'>No</button>","Suspend Distributor",
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
                url:'suspend-distributor/'+distributor_id,
                type:'get',
                data:{"id":distributor_id},
                dataType:"JSON",
                success:function(data){
                  $('#toast-container').remove();
                  toastr.options = {
                    "preventDuplicates": true,
                    "timeOut": "3000",
                  }
                if(data.success=="true"){
                    toastr['success'](data.message);
                  $('#distributor_list').DataTable().ajax.reload();
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
$(document).on('click','.releasedistributor',function(){
    var distributor_id=$(this).attr('data-distributorId');
    var distributor_name=$(this).attr('data-distributorname');
    toastr.warning("Are you sure you want to release  "+distributor_name+" distributor?<br /><br /><button type='button' id='confirm_yes' class='btn btn-secondary'>Yes</button>  <button type='button' id='confirm_no' class='btn btn-secondary'>No</button>","Release Distributor",
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
                url:'release-distributor/'+distributor_id,
                type:'get',
                data:{"id":distributor_id},
                dataType:"JSON",
                success:function(data){
                $('#toast-container').remove();
                toastr.options = {
                    "preventDuplicates": true,
                    "timeOut": "3000",
                }
                if(data.success=="true"){
                    toastr['success'](data.message);
                  $('#distributor_list').DataTable().ajax.reload();
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

///Code for delete Distributor ///
  $(document).on('click','.delete-detail',function(){
    var distributor_id=$(this).attr('data-distributorId');
    var distributor_name=$(this).attr('data-distributordeleteName');
    toastr.warning("Are you sure you want to delete "+distributor_name+" distributor ?<br /><br /><button type='button' id='confirm_yes' class='btn btn-secondary '>Yes</button>  <button type='button' id='confirm_no' class='btn btn-secondary'>No</button>","Delete User",
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
                url:'delete-distributor/'+distributor_id,
                type:'get',
                data:{"id":distributor_id},
                dataType:"JSON",
                success:function(data){
                $('#toast-container').remove();
                toastr.options = {
                    "preventDuplicates": true,
                    "timeOut": "3000",
                }
                if(data.success=="true"){
                    toastr['success'](data.message);
                  $('#distributor_list').DataTable().ajax.reload();
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

////Distributor user ////
jQuery.fn.DataTable.Api.register( 'buttons.exportData()', function ( options ) {
        var searchData = {};
        $("#distributor_user_list tfoot tr th").map(function() {
            searchData[$(this).attr('dataName')] = $('#'+$(this).attr('dataName')).val();
       }).get();
        if (this.context.length){
            var jsonResult = $.ajax({
                url: list_distributor_user+'?distributor_id='+distributor_id,
                data:{'search_data':searchData},
                async: false
            });
            return {body: jsonResult.responseJSON, header: $("#distributor_user_list tfoot tr th .dataTables_sizing").map(function() { return this.innerHTML; }).get()};
        }
    });

  $('#distributor_user_list').DataTable({
    processing: true,
    serverSide: true,
    responsive:true,
    "scrollX": true,
    ajax: list_distributor_user+'?distributor_id='+distributor_id,
    order:['7','desc'],
    dom: 'Blfrtip',
    autoWidth: false,
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
    buttons: [
    {
      extend: 'excel',
      filename: 'Distributor User',
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
      filename: 'Distributor User',
      title:''
    },
    {
      extend: 'pdf',
      filename: 'Distributor User',
      title:'',
      customize: function ( doc ) {
        var objLayout = {};
        objLayout['hLineWidth'] = function(i) { return .5; };
        objLayout['vLineWidth'] = function(i) { return .5; };
        objLayout['hLineColor'] = function(i) { return '#aaa'; };
        objLayout['vLineColor'] = function(i) { return '#aaa'; };
        doc.content[0].layout = objLayout;
        var rowCount = doc.content[0].table.body.length;
        for (i = 1; i < rowCount; i++) {

        }
        doc.content.splice( 0, 0, {
          margin: [ 0, 0, 10, 12 ],
          alignment: 'right',
          image: 'data:image/png;base64,'+$('#base64_image').val()
        } );
      }
    },
    {
      extend: 'print',
      filename: 'Distributor User',
      title:'',
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
        'data-attr': 'distributor_user_list',
      },
    }
    ],
        columns: [
            {data: 'name', name:'name', width: "100px"},
            {data: 'roles_name', name: 'roles_name', width: "100px"},
            {data: 'online', name: 'online', width: "50px"},
            {data: 'status', name: 'status', width: "50px"},
            {data: 'email', name: 'email', width: "100px"},
            {data: 'primary_telephone_number', name: 'primary_telephone_number', width: "100px"},
            {data: 'mobile_telephone_number', name: 'mobile_telephone_number', width: "100px"},
            {data: 'created_at',name:'created_at', orderable: true, visible: false},
            {data: 'action', name: 'action', orderable: false, searchable: false, width: "100px"}
        ],
        fnDrawCallback:function () {
            var table = $('#distributor_user_list').DataTable();
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
                var input = document.createElement('input');
                var columnText = $(column.header()).text();
                var columnAttrname = $(column.footer()).attr('dataName');
                var input = document.createElement('input');
                input.setAttribute("id", columnAttrname);
                $(input).attr( 'style', 'text-align: center;width: 100%');
                input.placeholder = columnText;
                if(columnText!="Actions" && columnText!="Online" && columnText!="Status"){
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

$(document).on("click", ".viewDetails" , function() {
        var Id = $(this).attr('data-UserId');
        $('.loader_container').show();
        $.ajax({
        url: "/show-distributor-user/"+Id,
        type: "GET",
        dataType:"JSON",
        success:function(response){
            if(response.success == true){
                $('#view_log_details').modal('show');
                $('#show_name').text(response.data.name);
                $('#show_username').text(response.data.username);
                $('#show_email').text(response.data.email);
                $('#show_role').text(response.data.role_acl_name);
                $('#show_teleno').text(response.data.primary_telephone_number);
                $('#show_mobno').text(response.data.mobile_telephone_number);
            }else{
                toastr['error'](response.message);
            }
            $('.loader_container').hide();
        },
        error:function(){
          $('.loader_container').hide();
            toastr['error']('Something wrong,please try again');
        }
    });
});

$(document).on("click",'.editDistributorUser',function(){
          var distributor_id=$(this).attr('data-UserId');
          $.ajax({
                  url:edit_distributor_user,
                  data:{"id":distributor_id},
                  dataType:"JSON",
                        success:function(response){
                                $('#edit_user').modal('show');
                                $('#user_id').val(response.data.id);
                                $('#edit_name').val(response.data.name);
                                $('#edit_username').val(response.data.username).attr('readonly', 'readonly');
                                $('#edit_email').val(response.data.email);
                                $('#edit_telno').val(response.data.primary_telephone_number);
                                $('#edit_mobno').val(response.data.mobile_telephone_number);
                                $("input[name='role'][value='" + response.data.roles[0].id + "']").prop('checked', true);

                              }
                });
          });

$('body').on('click', '.delete-user', function () {
        var user_id = $(this).attr("data-UserId");
        var user_name=$(this).attr("data-UserName");
        toastr.warning("Are you sure you want to delete "+user_name+" user ?<br /><br /><button type='button' id='confirmDeleteYes' class='btn btn-secondary'>Yes</button> <button type='button' id='confirmDeleteNo' class='btn btn-secondary'>No</button>", "Delete User",
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
            $("#confirmDeleteYes").click(function(){
                console.log('clicked yes');
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $('.loader_container').show();
            $.ajax({
                url: "/user/delete/"+user_id,
                type: "DELETE",
                data: {"userId":user_id,'module':'distributor'},
                success: function( response ) {
                    $('#toast-container').remove();
                    toastr.options = {
                        "preventDuplicates": true,
                        "timeOut": "1000",
                    }
                    if(response.success==true){
                        toastr['success'](response.message);
                        $('#distributor_user_list').DataTable().ajax.reload();
                    }else{
                        toastr['error'](response.message);
                    }
                    $('.loader_container').hide();
                },
                error: function(response,status,message) {
                  $('.loader_container').hide();
                }
            });
        });
        $("#confirmDeleteNo").click(function(){
            $('.toast-close-button').click();
        });
    }
});
});

$(document).on('click','.suspenduser',function(){
      var user_id = $(this).attr("data-UserId");
      var user_name=$(this).attr("data-UserName");
      toastr.warning("Are you sure you want to suspend "+user_name+" user ?<br /><br /><button type='button' id='confirm_yes' class='btn btn-secondary'>Yes</button>  <button type='button' id='confirm_no' class='btn btn-secondary'>No</button>","Suspend User",
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
                    type:'POST',
                    data: {"userId":user_id,'module':'distributor'},
                    dataType:"JSON",
                    success:function(data){
                    $('#toast-container').remove();
                          toastr.options = {
                          "preventDuplicates": true,
                          "timeOut": "3000",
                        }
                    if(data.status==true){
                          toastr['success'](data.message);
                          $('#distributor_user_list').DataTable().ajax.reload();

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
$(document).on('click','.releaseuser',function(){
      var user_id=$(this).attr('data-UserId');
      var user_name=$(this).attr('data-UserNameRelease');
      toastr.warning("Are you sure you want to release "+user_name+" user ?<br /><br /><button type='button' id='confirm_yes' class='btn btn-secondary '>Yes</button>  <button type='button' id='confirm_no' class='btn btn-secondary'>No</button>","Release User",
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
                    data: {"userId":user_id,'module':'distributor'},
                  dataType:"JSON",
                  success:function(data){
                  $('#toast-container').remove();
                  toastr.options = {
                    "preventDuplicates": true,
                    "timeOut": "3000",
                }
                  if(data.status==true){
                    toastr['success'](data.message);
                    $('#distributor_user_list').DataTable().ajax.reload();

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


$(document).on('click', '.forcelogout', function () {
    var user_id = $(this).attr("data-UserId");
    var user_name=$(this).attr("data-UserName");
    toastr.info("Are you sure you want to force logout "+user_name+" user ?<br /><br /><button type='button' id='confirmationRevertYes' class='btn btn-secondary'>Yes</button> <button type='button' id='confirmationRevertno' class='btn btn-secondary'>No</button>", "Force Logout User",
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
                    data: {"userId":user_id,'module':'distributor'},
                    success: function( data ) {
                            $('#toast-container').remove();
                            toastr.options = {
                                "preventDuplicates": true,
                                "timeOut": "1000",
                            }
                            if(data.status==true){
                                toastr['success'](data.message);
                                $('#distributor_user_list').DataTable().ajax.reload();
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


$('body').on('click', '.change-password', function () {
    var user_id = $(this).attr("data-UserId");
    var user_name=$(this).attr("data-UserName");
    toastr.warning("Are you sure you want to reset password of "+user_name+" user ?<br /><br /><button type='button' id='confirmationResetPassYes' class='btn btn-secondary '>Yes</button> <button type='button' id='confirmationResetPassNo' class='btn btn-secondary '>No</button>", "Reset Password",
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
                console.log('clicked yes');
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $('.loader_container').show();
                $.ajax({
                    url: "/reset_password/"+user_id,
                    type: "POST",
                    data: {"userId":user_id,'module':'distributor'},
                    success: function( data ) {
                            $('#toast-container').remove();
                            toastr.options = {
                                "preventDuplicates": true,
                                "timeOut": "1000",
                            }
                            if(data.status==true){
                                toastr['success'](data.message);
                                $('#distributor_user_list').DataTable().ajax.reload();
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

///// Data Table of Treatment center list data

jQuery.fn.DataTable.Api.register( 'buttons.exportData()', function ( options ) {
        var searchData = {};
        $("#distributor_treatment tfoot tr th").map(function() {
            var fieldnameData = $(this).attr('dataName').split('-');
            fieldname = fieldnameData[0];
            var value = $('#'+$(this).attr('dataName')).val();
            searchData[fieldname] =value;
       }).get();
        if (this.context.length){
            var jsonResult = $.ajax({
                url: distributors_treatmentcenter_list+'?distributor_id='+distributor_id,
                data:{'search_data':searchData},
                async: false
            });
            return {body: jsonResult.responseJSON, header: $("#distributor_treatment tfoot tr th .dataTables_sizing").map(function() { return this.innerHTML; }).get()};
        }
    });

  $('#distributor_treatment').DataTable({
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
        processing: true,
        serverSide: true,
        responsive:true,
        "scrollX": true,
        ajax: distributors_treatmentcenter_list+'?distributor_id='+distributor_id,
        order:['8','desc'],
        dom: 'Blfrtip',

        autoWidth: false,
        buttons: [
          {
              extend: 'excel',
              filename: 'Treatment Center',
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
              filename: 'Treatment Center',
              title:''
          },
          {
              extend: 'pdf',
              filename: 'Treatment Center',
              title:'',
              orientation : 'landscape',
              customize: function ( doc ) {
                var objLayout = {};
                objLayout['hLineWidth'] = function(i) { return .5; };
                objLayout['vLineWidth'] = function(i) { return .5; };
                objLayout['hLineColor'] = function(i) { return '#aaa'; };
                objLayout['vLineColor'] = function(i) { return '#aaa'; };
                doc.content[0].layout = objLayout;
                var rowCount = doc.content[0].table.body.length;
                for (i = 1; i < rowCount; i++) {
                    doc.content[0].table.body[i][6].alignment = 'right';
                }
                  doc.content.splice( 0, 0, {
                      margin: [ 0, 0, 10, 12 ],
                      alignment: 'right',
                      image: 'data:image/png;base64,'+$('#base64_image').val()
                  } );
              }
          },
          {
              extend: 'print',
              filename: 'Treatment Center',
              title:'',
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
                'data-attr': 'distributor_treatment',
            },
          }
      ],
        columns: [
            {data: 'treatment_ema_code', name: 'treatment_ema_code', width: "200px"},
            {data: 'full_company_name', name: 'full_company_name', width: "150px"},
            {data: 'primary_region', name: 'primary_region', width: "100px"},
            {data: 'name_of_primary_contact', name: 'name_of_primary_contact', className: "text-right", width: "150px"},
            {data: 'email_of_primary_contact', name: 'email_of_primary_contact', width: "250px"},
            {data: 'mobile_number_of_primary_contact', name: 'mobile_number_of_primary_contact', width: "200px"},
            {data: 'install_device', name: 'install_device', className: "text-right", width: "150px"},
            {data: 'status', name: 'status', width: "70px"},
            {data: 'created_at', name: 'created_at', orderable: true, visible: false, width: "100px"},
            {data: 'action', name: 'action', orderable: false, searchable: false, width: "70px"}

        ],
        fnDrawCallback:function () {
            var table = $('#distributor_treatment').DataTable();
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
                var input = document.createElement('input');
                var columnText = $(column.header()).text();
                var columnAttrname = $(column.footer()).attr('dataName');
                var input = document.createElement('input');
                input.setAttribute("id", columnAttrname);
                $(input).attr( 'style', 'text-align: center;width: 100%');
                input.placeholder = columnText;
                if(columnText!="Actions" && columnText!="Status"){
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

// edit treatment center
$(document).on('click','.viewtreatmentcenterDetails',function(){
    var getTreatmentCenterId = $(this).attr('data-treatmentCenterId');
    $.ajax({
      url:"/show/treatmentCenter/"+getTreatmentCenterId,
      data:{'treatment_center_id':getTreatmentCenterId},
      type:"get",
      dataType:"JSON",
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      success:function(data){
        if(data.success==true){
          var details = data.data;
          $('#treatment_treatment_ema_code').text(details.treatment_ema_code);
          $('#treatment_full_company_name').text(details.full_company_name);
          if(details.abbreviated_company_name){
            $('#treatment_abbreviated_company_name').text(details.abbreviated_company_name);
          }
          if(details.group_name=='' || details.group_name==null){
            $("#treatment_group_name_checkbox").text('No');
          }else{
            $("#treatment_group_name_checkbox").text('Yes');
          }
          if(details.group_name){
            $('#treatment_group_name').text(details.group_name);
          }
          if(details.building_name){
            $('#treatment_building_name').text(details.building_name);
          }
          $('#treatment_full_address').text(details.full_address);
          if(details.address_1){
            $('#treatment_address_1').text(details.address_1);
          }
          if(details.address_2){
            $('#treatment_address_2').text(details.address_2);
          }
          if(details.address_3){
            $('#treatment_address_3').text(details.address_3);
          }
          $('#treatment_country').text(details.country.name);
          $('#treatment_state').text(details.state);
          $('#treatment_zipcode').text(details.zipcode);
          $('#treatment_name_of_primary_contact').text(details.name_of_primary_contact);
          $('#treatment_position').text(details.position);
          $('#treatment_telephone_number_of_primary_contact').text(details.telephone_number_of_primary_contact);
          $('#treatment_mobile_number_of_primary_contact').text(details.mobile_number_of_primary_contact);
          if(details.fax_number){
            $('#treatment_fax_number').text(details.fax_number);
          }
          $('#treatment_email_of_primary_contact').text(details.email_of_primary_contact);
          if(details.web_site){
            $('#treatment_web_site').text(details.web_site);
          }
          $('#treatment_center_id').text(details.id);
          if(details.distributor_name){
            $('#treatment_distributors').text(details.distributor_name);
          }
          if(details.is_ema==1){
            $('#treatment_is_ema').text('Yes')
          }else{
            $('#treatment_is_ema').text('No')
          }
        $('#view_treatment_details').modal('show');
        }else{
          toastr['error'](data.message);
        }
      },
      error:function(){
        toastr['error']('something went wrong');
      }
    });
  });

});

$('#add_new_user_modal').on('click',function(){
  $('#add_new_user').modal('show');
  $('input[name="role"]').prop('checked', false);
});



document.onreadystatechange = function() {
  if (document.readyState !== "complete") {
    setTimeout(function(){
       var elementheight = $('.dataTables_scrollBody').height();
       var devide = 27;
       var result = parseInt(elementheight / devide);
       $('#distributor_list').DataTable().page.len(result).draw();
    }, 100);
  }
};
