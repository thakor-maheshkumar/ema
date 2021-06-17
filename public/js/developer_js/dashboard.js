$(document).ready(function(){

    $("#popUpClose").click(function(){
        $("#add_new_tcp_users, .modal-backdrop.show").hide();
    });
    // $('#example, .example').DataTable();
    $('#audit-log').DataTable({
        processing: true,
        serverSide: true,
        responsive:true,
        "scrollX": true,
        autoWidth: false,
        ajax: system_activity_list,
        "paging": false,
        "info":    false,
        scroller: {
            loadingIndicator: true
        },
        order: [],
        columns: [
            {data: 'created_date', name: 'created_at', width: "80px"},
            {data: 'created_at', name: 'audit_timestamp', width: "40px"},
            {data: 'name', name: 'name', width: "100px"},
            {data: 'company_name', name: 'company_name', width: "80px"},
            {data: 'module_activity', name: 'module_activity', width: "200px"},
            {data: 'description', name: 'description', width: "400px"},
            {data: 'action', name: 'action', orderable: false, searchable: false, width: "35px"},
        ],
        language: {

            'processing': '<div class="datatable-loader"><img src="'+$('#logo_white').attr('src')+'" /><div class="sk-chase mt-3 mx-n3"><div class="sk-chase-dot"></div> <div class="sk-chase-dot"></div> <div class="sk-chase-dot"></div> <div class="sk-chase-dot"></div> <div class="sk-chase-dot"></div> <div class="sk-chase-dot"></div></div></div>'
        },
        columnDefs: [
            {
                "targets": 1,
                "data": "created_at",
                "render": function ( data, type, row, meta ) {
                    var format = 'HH:mm:ss';
                    return moment.utc(data).local().format(format);
                }
            }
        ],
        initComplete: function () {
            this.api().columns().every(function () {
                var column = this;
                var columnText = $(column.header()).text();
                var columnAttrname = $(column.footer()).attr('dataName');
                var input = document.createElement('input');
                input.setAttribute("id", columnAttrname);
                $(input).attr( 'style', 'text-align: center;width: 100%');
                input.placeholder = columnText;
                if(columnText!="Action" && columnText!="Time" ){
                    $(input).appendTo($(column.header()).empty())
                    .on('keyup', function (e) {
                        var val = $.fn.dataTable.util.escapeRegex($(this).val());
                        column.search(val ? val : '', true, false).draw();
                    });
                }else{
                    $(input).appendTo('Action');
                }
            });
        }
    });

    /* get data for view the audit log details */
    $(document).on("click", ".viewDetails" , function() {
        var audtiLogId = $(this).attr('data-audtiLogId');
        $('.loader_container').show();
        $.ajax({
            url:audit_details,
            data:{"id":audtiLogId},
            dataType:"JSON",
            success:function(data){
                if(data.success==1){
                    var response=jQuery.parseJSON(data.audit_log_detail.request_data);
                    $("#requested_data_details table.table tr").remove();
                    var obj = JSON.parse(data.audit_log_detail.request_data);
                    var html = "";
                    $.each(obj, function (key, data) {
                        if(key != '_token' && key !="is_logged_in" && key !="remember_token" && key !="created_at" && key !="updated_at" && key !="deleted_at" && key !="last_login_activity" && key !="first_time_login" && key !="email_verified_at" && key !="password"){
                            if(typeof data=="object"){
                                $.each(data, function (key1, data1) {
                                    $("#requested_data_details table.table").append("<tr><td>" + key1 + "</td><td>" + data1 + "</td></tr>");
                                });
                            }else{
                                if(data){
                                    $("#requested_data_details table.table").append("<tr><td>" + key + "</td><td>" + data + "</td></tr>");
                                }else{
                                    $("#requested_data_details table.table").append("<tr><td>" + key + "</td><td>" + '--' + "</td></tr>");
                                }
                            }
                        }
                    });
                    $('#view_log_details').modal('show');
                    $('#username_details').text(data.audit_log_detail.username);
                    $('#module_name_details').text(data.audit_log_detail.module_name);
                    $('#activity_details').text(data.audit_log_detail.module_activity);
                    $('#description_details').text(data.audit_log_detail.description);
                    $('#ip_address_details').text(data.audit_log_detail.ip_address);
                }else{
                    toastr['error'](data.message);
                }
                $('.loader_container').hide();
            },
            error:function(){
                $('.loader_container').hide();
                toastr['error']('Something wrong,please try again');
            }
        });
    });
});

