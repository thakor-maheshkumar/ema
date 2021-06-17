$("#addnewuser").submit(function(e) {
    e.preventDefault();
}).validate({
    onkeyup: function(element) {$(element).valid()},
    rules: {
        name: {
            required: true,
            fullnameRegex:true,
        },
        username:{
            required: true,
            usernameRegex:true,
            remote:{
            url:uniqueusername,
            headers:{'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            type:'post'
      }
        },
        role:{
            required: true
        },
        primary_telephone_number: {
            required: true,
            telephoneNumber:true,
            maxlength:20
        },
        mobile_telephone_number: {
            required: true,
            telephoneNumber:true,
            maxlength:20
        },
        email: {
        required:true,
        validemail:true,
        remote:{
        url:uniqueuseremail,
        headers:{'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        type:'post',
      }
      },
    },
    messages: {
        name: {
            required: "Please enter full name",
        },
        username:{
            required: "Please enter Login Username",
            remote:'The Login username is already been taken',
        },
        role:{
            required: "Please select user role",
        },
        primary_telephone_number: {
            required: "Please enter telephone number",
        },
        email: {
          required: "Please enter valid email address",
          validemail:"Invalid email format",
          remote:'Email address is already assigned'
        },
        mobile_telephone_number: {
            required: "Please enter mobile number",
        },
    },
    errorPlacement: function(error, element) {
        if(element.attr("name") == "role") {
          error.appendTo($('.role_error') );
        } else {
          error.insertAfter(element);
        }
      },
    submitHandler: function(form) {
    $('.loader_container').show();
     $('#add_submit').prop('disabled', true);
     $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
      });
      $.ajax({
        url: register_user ,
        type: "POST",
        data: $('#addnewuser').serialize(),
        success: function( response ) {
            console.log(response);
            if(response.success==true){
                $("#addnewuser").trigger("reset");
                $('#add_new_user').modal('hide');
                toastr.options = {
                    "preventDuplicates": true,
                    "timeOut": "1000",
                }
                toastr.success('User created successfully');
                $('#users-table').DataTable().ajax.reload();
                $('#add_submit').prop('disabled', false);
            }
            $('.loader_container').hide();
        },
        error: function(response,status,message) {
             $('.loader_container').hide();
            if( response.status === 422 ) {
                $('.form-control').removeClass('is-invalid');
                $('.invalid-feedback').remove();
                var errors = response.responseJSON.errors;
                $.each(errors, function (key, val) {
                    $('input[name='+key+']').addClass('is-invalid');
                    $('<span class="invalid-feedback" role="alert"><strong>'+val[0]+'</strong></span>').insertAfter( 'input[name='+key+']');
                });
                $('#add_submit').prop('disabled', false);
            }
        }

      });
    }
  });
  $("#add_email").focus(function(){
    $('#add_email').removeClass('is-invalid');
    $('.invalid-feedback').remove();
  });
  $("#add_username").focus(function(){
    $('#add_username').removeClass('is-invalid');
    $('.invalid-feedback').remove();
  });

$("#edituser").submit(function(e) {
    e.preventDefault();
}).validate({
    onkeyup: function(element) {$(element).valid()},
rules: {
    edit_name: {
        required: true,
        fullnameRegex:true,

    },
    edit_telno: {
        required: true,
        telephoneNumber:true,
        maxlength:20
    },
    edit_mobno: {
        required: true,
        telephoneNumber:true,
        maxlength:20
    },
    email: {
            required: true,
            validemail:true,
            remote:{
            url:base_url+'/uniqueuseremail',
            data:{id: function() { return $('#edit_id').val(); } },
            headers:{'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            type:'post',
      }
        },
},
messages: {
    name: {
        required: "Please enter name",
    },
    edit_telno: {
        required: "Please enter primary telephone number",
    },
    edit_mobno: {
        required: "Please enter mobile number",
    },
     email: {
          required: "Please enter valid email address",
          validemail:"Invalid email format",
          remote:'Email address is already assigned',
        },

},
submitHandler: function(form) {
    $('.loader_container').show();
    $('#edit_submit').prop('disabled', true);
    var id = $('#edit_id').val();

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            'Accept': 'application/json',
        }
    });
    $.ajax({
    url: "/update-user/"+id,
    type: "POST",
    data: $('#edituser').serialize(),
    success: function( response ) {
        if(response.success == true){
            $("#edituser")[0].reset();
            $('#edit_user').modal('hide');
            toastr.options = {
                "preventDuplicates": true,
                "timeOut": "1000",
            }
            toastr['success'](response.message);
            // $('#SuccessfullMessage').modal('show');
            $('#edit_submit').prop('disabled', false);
            $('#users-table').DataTable().ajax.reload();
        }
        $('.loader_container').hide();
    },
    error: function(response,status,message) {
         $('.loader_container').hide();
        if( response.status === 422 ) {
            $('.form-control').removeClass('is-invalid');
            $('.invalid-feedback').remove();
            var errors = response.responseJSON.errors;
            $.each(errors, function (key, val) {
                $('input[name='+key+']').addClass('is-invalid');
                $('<span class="invalid-feedback" role="alert"><strong>'+val[0]+'</strong></span>').insertAfter( 'input[name='+key+']');
            });
            $('#edit_submit').prop('disabled', false);
        }
    }

    });
}
});

$(document).ready(function() {

    jQuery.fn.DataTable.Api.register( 'buttons.exportData()', function ( options ) {
        var searchData = {};
        $("#users-table tfoot tr th").map(function() {
            searchData[$(this).attr('dataName')] = $('#'+$(this).attr('dataName')).val();
            // console.log(searchData);
       }).get();
        if (this.context.length){
            var jsonResult = $.ajax({
                url: userlist,
                data:{'search_data':searchData},
                async: false
            });
            return {body: jsonResult.responseJSON, header: $("#users-table tfoot tr th .dataTables_sizing").map(function() { return this.innerHTML; }).get()};
        }
    });
    var users_table = $('#users-table').DataTable({
        processing: true,
        serverSide: true,
        responsive:true,
        "scrollX": true,
        ajax: userlist,
        order:['7','desc'],
        dom: 'Blfrtip',
        autoWidth: false,
        buttons: [
            {
                extend: 'excel',
                filename: 'Users List',
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
                filename: 'Users List',
                title:''
            },
            {
                extend: 'pdf',
                filename: 'Users List',
                title:'Users List',
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
                filename: 'Users List',
                title:'Users List',
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
                    'data-attr': 'users-table',
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
            {data: 'role', name: 'role', width: "150px"},
            {data: 'online', name: 'online', width: "50px"},
            {data: 'status', name: 'status', width: "70px"},
            {data: 'email', name: 'email', width: "250px"},
            {data: 'primary_telephone_number', name: 'primary_telephone_number', width: "100px"},
            {data: 'mobile_telephone_number', name: 'mobile_telephone_number', width: "100px"},
            {data: 'created_at', name: 'created_at', orderable: true, visible: false},
            {data: 'action', name: 'action', orderable: false, searchable: false, width: "100px"}
        ],
        columnDefs: [
            {
                "targets": 1,
                "data": "role",
                "render": function ( data, type, row, meta ) {
                    if(data == 'ema analyst'){
                        return 'EMA Analyst';
                    }else if(data == 'ema service support'){
                        return 'EMA Service Support';
                    }else if(data == 'system administrator'){
                        return 'System Administrator';
                    }else{
                        return data.substr(0,1).toUpperCase()+data.substr(1);
                    }
                }
            }
        ],
        fnDrawCallback:function () {
            var table = $('#users-table').DataTable();
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
                $(input).attr( 'style', 'text-align: center;width: 100%');
                input.setAttribute("id", columnAttrname);
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
    }).on('draw.dt', function () {
        var info = users_table.page.info();
        if(info.page + 1 === info.pages || info.pages==0) {
          $('.table-responsive').removeClass('datatable_resize');
        } else {
          $('.table-responsive').addClass('datatable_resize');
        }
      });
} );

$('body').on('click', '.editCustomer', function () {
      var user_id = $(this).attr("id");
      $.ajax({
        url: "/ema_users/edit/"+user_id,
        type: "GET",
        dataType:"json",
        success: function( response ) {
            $('#edit_name').val(response.data.name);
            $('#edit_username').val(response.data.username).prop('disabled', true);
            $('#edit_email').val(response.data.email);
            $('#edit_telno').val(response.data.primary_telephone_number);
            $('#edit_mobno').val(response.data.mobile_telephone_number);
            $('#edit_id').val(response.data.id);
            $("input[name='edit_role'][value='" + response.data.roles[0].name + "']").prop('checked', true);
        },
        error: function(response,status,message) {

        }
      });
   });

$('body').on('click', '.forcelogout', function () {
    var user_id = $(this).attr("id");
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
                    data: {"userId":user_id},
                    success: function( data ) {
                            $('#toast-container').remove();
                            toastr.options = {
                                "preventDuplicates": true,
                                "timeOut": "1000",
                            }
                            if(data.status==true){
                                toastr['success'](data.message);
                                $('#users-table').DataTable().ajax.reload();
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

$('body').on('click', '.suspenduser', function () {
    var user_id = $(this).attr("id");
    var user_name=$(this).attr("data-UserName");
    toastr.warning("Are you sure you want to suspend "+user_name+" user ?<br /><br /><button type='button' id='confirmationSuspendYes' class='btn btn-secondary'>Yes</button> <button type='button' id='confirmationSuspendNo' class='btn btn-secondary'>No</button>", "Suspend User",
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
            $("#confirmationSuspendYes").click(function(){
                console.log('clicked yes');

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $('.loader_container').show();
                $.ajax({
                    url: "/suspend/"+user_id,
                    type: "POST",
                    data: {"userId":user_id},
                    success: function( data ) {
                            $('#toast-container').remove();
                            toastr.options = {
                                "preventDuplicates": true,
                                "timeOut": "1000",
                            }
                            if(data.status==true){
                                toastr['success'](data.message);
                                $('#users-table').DataTable().ajax.reload();
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
            $("#confirmationSuspendNo").click(function(){
                $('.toast-close-button').click();
            });
        }
    });

});

$('body').on('click', '.releaseuser', function () {
    var user_id = $(this).attr("id");
    var user_name=$(this).attr("data-UserName");
    toastr.warning("Are you sure you want to release "+user_name+" user ?<br /><br /><button type='button' id='confirmationReleaseYes' class='btn btn-secondary'>Yes</button> <button type='button' id='confirmationReleaseNo' class='btn btn-secondary'>No</button>", "Release User",
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
            $("#confirmationReleaseYes").click(function(){

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $('.loader_container').show();
                $.ajax({
                    url: "/release/"+user_id,
                    type: "POST",
                    data: {"userId":user_id},
                    success: function( data ) {
                            $('#toast-container').remove();
                            toastr.options = {
                                "preventDuplicates": true,
                                "timeOut": "1000",
                            }
                            if(data.status==true){
                                toastr['success'](data.message);
                                $('#users-table').DataTable().ajax.reload();
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
            $("#confirmationReleaseNo").click(function(){
                $('.toast-close-button').click();
            });
        }
    });

});

$('body').on('click', '.delete-user', function () {
        var user_id = $(this).attr("id");
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
                data: {"userId":user_id},
                success: function( response ) {
                    $('#toast-container').remove();
                    toastr.options = {
                        "preventDuplicates": true,
                        "timeOut": "1000",
                    }
                    if(response.success==true){
                        toastr['success'](response.message);
                        $('#users-table').DataTable().ajax.reload();
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

$(document).on("click", ".viewDetails" , function() {
    var Id = $(this).attr('data-UserId');
    $('.loader_container').show();
    $.ajax({
        url: "/show-user/"+Id,
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

// check value is exits
$('.checkvalueExists').on('blur',function(){
    var field = $(this).attr('name');
    var value = $(this).val();
    var current_obj = $(this);

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
            $('<span class="invalid-feedback" role="alert"><strong>The Login Username is already been taken</strong></span>').insertAfter( 'input[name='+field+']');
          }
          else{
            $('input[name='+field+']').addClass('is-invalid');
            $('<span class="invalid-feedback" role="alert"><strong>Email address is already assigned</strong></span>').insertAfter( 'input[name='+field+']');
          }
        //   toastr['error'](lable+" already exists");
          current_obj.val('');
        }
      },
      error:function(){
        toastr['error']('something went wrong');
      }

    });

});

$(document).ready(function(){
  $("#popUpClose").click(function(){
    $("#add_new_user, .modal-backdrop.show").hide();
  });
});

$('body').on('click', '.change-password', function () {
    var user_id = $(this).attr("id");
    var user_name=$(this).attr("data-UserName");
    toastr.warning("Are you sure you want to reset password of "+user_name+" user ?<br /><br /><button type='button' id='confirmationResetPassYes' class='btn btn-secondary'>Yes</button> <button type='button' id='confirmationResetPassNo' class='btn btn-secondary '>No</button>", "Reset Password",
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
                    data: {"userId":user_id},
                    success: function( data ) {
                            $('#toast-container').remove();
                            toastr.options = {
                                "preventDuplicates": true,
                                "timeOut": "1000",
                            }
                            if(data.status==true){
                                toastr['success'](data.message);
                                $('#users-table').DataTable().ajax.reload();
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

document.onreadystatechange = function() {
    if (document.readyState !== "complete") {
      setTimeout(function(){
         var elementheight = $('.dataTables_scrollBody').height();
         var devide = 27;
         var result = parseInt(elementheight / devide);
         $('#users-table').DataTable().page.len(result).draw();
      }, 100);
    }
};
