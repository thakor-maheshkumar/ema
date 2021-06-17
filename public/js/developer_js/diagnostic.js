$(document).ready(function() {
  $(".js-example-basic-single").select2();
  var diagnostic_list = $('#diagnostic_list').DataTable({
    processing: true,
    serverSide: true,
    responsive: true,
    "scrollX": true,
    autoWidth: false,
    order:['0','desc'],
    ajax: diagnostic_data_list,
    dom: 'Blfrtip',
    buttons:[
      {
        text: 'Clear Filters',
        attr:  {
          id: 'clear',
          'data-attr': 'diagnostic_list',
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
      {data: 'UTI_date', name: 'UTI', width: "50px"},
      {data: 'UTI_time', name: 'UTI', width: "50px"},
      {data: 'treatment_center_name', name: 'treatment_center_name', width: "150px"},
      {data: 'DSN', name: 'DSN', width: "100px"},
      {data: 'error_type', name: 'STA', width: "100px"},
      {data: 'description', name: 'description', width: "100px"},
      {data: 'comment', name: 'comment', width: "100px"},
      {data: 'status', name: 'status', width: "100px"},
      {data: 'action', name: 'action', width: "50px", orderable: false, searchable: false,},
    ],
    initComplete: function () {
      this.api().columns().every(function () {
        var column = this;
        var columnAttrname = $(column.footer()).attr('dataName');
        var input = document.createElement('input');
        input.setAttribute("id", columnAttrname);
        $(input).attr( 'style', 'text-align: center;width: 100%');
        var columnText = $(column.header()).text();
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
  }).on('draw.dt', function () {
    var info = diagnostic_list.page.info();
    if(info.page + 1 === info.pages || info.pages==0) {
      $('.table-responsive').removeClass('datatable_resize');
    } else {
      $('.table-responsive').addClass('datatable_resize');
    }
  });




  $('#diagnostic_list_dashboard').DataTable({
    processing: false,
    serverSide: true,
    responsive: true,
    "scrollX": true,
    autoWidth: false,
    order: [],
    ajax: diagnostic_details_dashboards,
    paging:false,
    "info":false,
    columns: [
      {data: 'UTI_date', name: 'UTI_date', width: "40px"},
      {data: 'UTI_time', name: 'UTI_time', width: "40px"},
      {data:'DSN',name:'DSN',width:"40px"},
      {data: 'STA', name: 'STA', width: "40px"},
      {data: 'Traffic Light', name: 'Traffic Light', width: "40px"},
      {data: 'action', name: 'action', orderable: false, searchable: false,width:"40px"},
    ],
    initComplete: function () {
      this.api().columns().every(function () {
        var column = this;
        var columnAttrname = $(column.footer()).attr('dataName');
        var input = document.createElement('input');
        input.setAttribute("id", columnAttrname);
        $(input).attr( 'style', 'text-align: center;width: 100%');
        var columnText = $(column.header()).text();
        input.placeholder = columnText;
        if(columnText!="Actions"){
          $(input).appendTo($(column.header()).empty())
          .on('keyup', function (e) {
            var val = $.fn.dataTable.util.escapeRegex($(this).val());
            column.search(val ? val : '', true, false).draw();
          });
        }else{
          $(input).appendTo('Actions');
        }

      });
    }
  });

  /* get data for view the boot details */
  $(document).on("click", ".viewBootDetails" , function() {
    var pid = $(this).attr('data-pid');
    $('.loader_container').show();
    $.ajax({
      url:diagnostic_details,
      type:'post',
      data:{"pid":pid},
      dataType:"JSON",
      success:function(data){
        if(data.success == 1){
          $(".viewdatajson").empty();
          var html = "";
          $.each(data.getTreatmentcentreData, function (key, data) {
            if(key != '_token' && key !="is_logged_in" && key !="remember_token" && key !="created_at" && key !="updated_at" && key !="deleted_at" && key !="last_login_activity" && key !="first_time_login" && key !="email_verified_at" && key !="password"){
              html +='<div class="form-group col-lg-6 col-md-6 col-sm-6 col-12 mb-2">';
              html +='<div class="row">';
              html +='<label for="module_name" class="col-12 pr-0 text-left font-weight-bold mb-0">'+key+':</label>';
              html +='<label id="module_name_details" class="col-12 text-left modulename">'+data+'</label>';
              html +='</div>';
              html +='</div>';
            }
          });
          $(".viewdatajson").append(html);
          $('#view_log_details').modal('show');
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


$(document).on("click", ".viewDiagnosticData" , function() {
  var diagnosticData = $(this).attr('data-getTreatmentcentreData');
  $('.loader_container').show();
  $.ajax({
    url: "/show-diagnostic-data/"+diagnosticData,
    type: "GET",
    dataType:"JSON",
    success:function(response){
      if(response.success == true){
        $('#diagnostic_date').text(response.diagnostic_data.uti_date);
        $('#diagnostic_time').text(response.diagnostic_data.uti_time);
        $('#diagnostic_dsn').text(response.diagnostic_data.dsn);
        $('#diagnostic_treatment_centre_name').text(response.diagnostic_data.treatment_centre_name);
        $('#diagnostic_distributor_name').text(response.diagnostic_data.distributor_name);
        $('#diagnostic_alarm').text(response.diagnostic_data.alarm);
        $('#json_id').val(diagnosticData);
        $('#diagnostic_status').val(response.diagnostic_data.status).trigger("change");
        appendCommentList(response.diagnostic_data.diagnostic_comment_list);
        $('#view_diagnostic_data').modal('show');
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


/* Save diagnostic comment */
$(document).on('click','#save_diagnostic_comment',function(){

  var diagnostic_status = $('#diagnostic_status').val();
  var diagnostic_comment = $('#diagnostic_comment').val();
  var json_id = $('#json_id').val();

  if(diagnostic_comment){
    $('#save_diagnostic_comment').prop( "disabled", true );
    $('.loader_container').show();
  $.ajax({
    url: add_diagnostic_comment,
    type: "POST",
    dataType:"JSON",
    data:{"diagnostic_status":diagnostic_status,"diagnostic_comment":diagnostic_comment,"json_id":json_id},
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    success:function(response){
      if(response.success == true){
        $('#save_diagnostic_comment').removeAttr('disabled');
        appendCommentList(response.diagnostic_comment_list);
      }else{
        toastr['error'](response.message);
      }
      $('.loader_container').hide();
    },
    error: function(data) {
      $('.loader_container').hide();
      $('#save_diagnostic_comment').removeAttr('disabled');
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
  });
}else{
  toastr['error']('Please Add Comment');
}
});
/* Save diagnostic comment */


document.onreadystatechange = function() {
  if (document.readyState !== "complete") {
    setTimeout(function(){
      var elementheight = $('.dataTables_scrollBody').height();
      var devide = 27;
      var result = parseInt(elementheight / devide);
      $('#diagnostic_list').DataTable().page.len(result).draw();
    }, 100);
  }
};

function appendCommentList(commentData){
  var innerHtml ="";
  if(commentData){
    $.each(commentData,function(i,val){
      innerHtml = innerHtml+"<tr>";
      innerHtml = innerHtml+"<td>"+val.comment+"</td>";
      innerHtml = innerHtml+"<td>"+val.name+"</td>";
      innerHtml = innerHtml+"<td>"+val.added_date+"</td>";
      innerHtml = innerHtml+"</tr>";
    });
    $('#comment_list').html(innerHtml);
  }
}