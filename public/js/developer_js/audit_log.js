

$(window).scroll(function() {
  $('.date-picker-wrapper').css({"display": "none"})
});


/* load the audit list data with datatable*/
$(document).ready(function() {

    jQuery.fn.DataTable.Api.register( 'buttons.exportData()', function ( options ) {
        var searchData = {};
        $("#audit-log tfoot tr th").map(function() {
            searchData[$(this).attr('dataName')] = $('#'+$(this).attr('dataName')).val();
       }).get();
        if (this.context.length){
            var jsonResult = $.ajax({
                url: audit_list,
                data:{'search_data':searchData},
                async: false
            });
            return {body: jsonResult.responseJSON, header: $("#audit-log tfoot tr th .dataTables_sizing").map(function() { return this.innerHTML; }).get()};
        }
    });

    var auditTable = $('#audit-log').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            "scrollX": true,
            autoWidth: false,
            ajax: audit_list,
            order:[],
            dom: 'Blfrtip',
            buttons: [
                {
                    extend: 'excel',
                    filename: 'Audit log',
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
                    filename: 'Audit log',
                    title:''
                },
                {
                    extend: 'pdf',
                    filename: 'Audit log',
                    title:'Audit Log',
                    orientation : 'landscape',
                    customize: function ( doc ) {
                        var objLayout = {};
                        objLayout['hLineWidth'] = function(i) { return .5; };
                        objLayout['vLineWidth'] = function(i) { return .5; };
                        objLayout['hLineColor'] = function(i) { return '#aaa'; };
                        objLayout['vLineColor'] = function(i) { return '#aaa'; };
                        doc.content[1].layout = objLayout;
                        doc.content.splice( 1, 0, {
                            margin: [ 0, 0, 10, 12 ],
                            alignment: 'right',
                            image: 'data:image/png;base64,'+$('#base64_image').val()
                        } );
                    }
                },
                {
                    extend: 'print',
                    filename: 'Audit log',
                    title:'Audit Log',
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
                        'data-attr': 'audit-log',
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
                {data: 'created_date', name: 'created_at', width: "80px"},
                {data: 'created_at', name: 'audit_timestamp', width: "40px"},
                {data: 'name', name: 'name', width: "100px"},
                {data: 'company_name', name: 'company_name', width: "80px"},
                {data: 'module_activity', name: 'module_activity', width: "200px"},
                {data: 'description', name: 'description', width: "400px"},
                {data: 'action', name: 'action', orderable: false, searchable: false, width: "50px"},
            ],
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
            fnDrawCallback:function () {
                var table = $('#audit-log').DataTable();
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
                    if(columnText!="Action" && columnText!="Time" ){
                        $(input).appendTo($(column.header()).empty())
                        .on('keyup', function (e) {
                            // if(e.keyCode == 13) {
                                var val = $.fn.dataTable.util.escapeRegex($(this).val());
                                column.search(val ? val : '', true, false).draw();
                            // }
                        });
                        if(columnAttrname == 'created_at'){
                            var div = document.createElement('div');
                            input.setAttribute("readonly", true);
                            div.setAttribute("id", 'created_at-container');
                            div.style.width = "25px";
                            $(div).appendTo('body');
                        }
                    }else{
                        $(input).appendTo('Action');
                    }
                });
                $(".apply-btn").prop("value", "Apply");
                $('#created_at').dateRangePicker(
                {
                    inline:true,
                    container: '#created_at-container',
                    singleMonth: true,
                    format: 'DD-MM-YYYY',
                    setValue: function(s)
	                {
                        if($(this).attr('readonly') && !$(this).is(':disabled') && s != $(this).val())
                        {
                            $(this).val(s);
                        }
                    },
                    autoApply:true
                }).bind('datepicker-close',function(){
                    auditTable.columns( 0 ).search(this.value).draw()
                }).bind('datepicker-open',function(){
                    $('.apply-btn').val('Apply');
                });
            }
    }).on('draw.dt', function () {
        var info = auditTable.page.info();
        if(info.page + 1 === info.pages || info.pages == 0) {
          $('.table-responsive').removeClass('datatable_resize');
        } else {
          $('.table-responsive').addClass('datatable_resize');
        }
      });
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
                        console.log(key);
                        if(typeof data=="object"){
                            console.log(typeof data);
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


document.onreadystatechange = function() {
    if (document.readyState !== "complete") {
      setTimeout(function(){
         var elementheight = $('.dataTables_scrollBody').height();
         var devide = 27;
         var result = parseInt(elementheight / devide);
         $('#audit-log').DataTable().page.len(result).draw();
      }, 100);
    }
};
