$(document).ready(function(){

  var validator = $("#sms_template_form").validate({
    onkeyup: function(element) {$(element).valid()},
    ignore: "",
    rules:{
      sms_template_name:{
        required:true
      },
      sms_template_slug:{
        required:true,
        remote:{
            url:'checkExistingSMSSlug',
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            type:'post',
          }
      },      
      sms_template_content:{
        required:true
      }
    }    
    });

  $(document).on('click','#add_new_smstemplate',function(){
    $('#add_new_smstemplate_form').modal('show');
    $('#sms_template_slug').prop("disabled", false);
    $('#add_new').text('Add New SMS Template');
    $('#hidden_sms_template_id').val('');
  });

  $('#sms_template_list').DataTable({
    processing: false,
    serverSide: true,
    ajax:smsTemplate_list,
    language: {
      "lengthMenu": 'Show <select class="custom-select custom-select-sm form-control form-control-sm">'+
      '<option value="10">10</option>'+
      '<option value="20">20</option>'+
      '<option value="30">30</option>'+
      '<option value="40">40</option>'+
      '<option value="50">50</option>'+
      '</select>'
    },
    columns: [
    {data: 'id', name: 'id  '},
    {data: 'name', name: 'name  '},
    {data: 'slug', name: 'slug  '},
    {data: 'content', name: 'content'},        
    {data: 'action', name: 'action', orderable: false, searchable: false}
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
        input.id = columnText;
        if(columnText!="Action"){
          $(input).appendTo($(column.header()).empty())
          .on('keyup', function (e) {
            if(e.keyCode == 13) {
              var val = $.fn.dataTable.util.escapeRegex($(this).val());
              column.search(val ? val : '', true, false).draw();  
            }            
          });
        }else{
          $(input).appendTo('Action');
        }
      });
    }
  });
});


// edit SMSTemplate
$(document).on('click','.editSMSTemplate',function(){
  var getSMSTemplatesId = $(this).attr('data-SMSTemplatesId');
  if(getSMSTemplatesId){
    $.ajax({
      url:getSMSTemplatepdetails,
      data:{'SMSTemplatesId':getSMSTemplatesId},
      type:"POST",
      dataType:"JSON",
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      success:function(data){
        if(data.success=='true'){
          $('#hidden_sms_template_id').val(getSMSTemplatesId);
          $('#sms_template_name').val(data.name);
          $('#sms_template_slug').val(data.slug);
          $('#sms_template_slug').prop("disabled", true);
          $('#sms_template_content').val(data.content);
          $('#add_new_smstemplate_form').modal('show');
          $('#add_new').text('Update SMS Template');
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

$(document).on('click','.deleteSMSTemplate',function(){
  var getSMSTemplatesId = $(this).attr('data-SMSTemplatesId');
  toastr.warning("Are you sure want to delete this sms template?<br /><br /><button type='button' id='confirm_yes' class='btn btn-secondary'>Yes</button>  <button type='button' id='confirm_no' class='btn btn-secondary'>No</button>",'Delete Hydracool SRP',
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
          url:deleteSMSTemplate,
          data:{'SMSTemplatesId':getSMSTemplatesId},
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
              $('#sms_template_list').DataTable().ajax.reload();
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