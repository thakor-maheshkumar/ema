$(document).ready(function(){

  var validator = $("#email_template_form").validate({
    onkeyup: function(element) {$(element).valid()},
    ignore: "",
    rules:{
      email_template_name:{
        required:true
      },
      email_template_slug:{
        required:true,
        remote:{
            url:'checkExistingSlug',
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            type:'post',
          }
      },
      email_template_subject:{
        required:true
      },
      email_template_content:{
        required:true
      }
    },
    errorPlacement: function(label, element) {
        // position error label after generated textarea
        if (element.is("textarea")) {
          label.insertAfter(element.next());
        } else {
          label.insertAfter(element)
        }
      }
    });

  $('textarea#email_template_content').tinymce({
    height: 400,
    menubar: true,
    plugins: [
    'advlist autolink lists link image charmap print preview anchor',
    'searchreplace visualblocks code fullscreen',
    'insertdatetime media table paste code help wordcount'
    ],
    toolbar: 'undo redo | formatselect | bold italic backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | help'
  });

  $(document).on('click','#add_new_emailtemplate',function(){
    $('#add_new_emailtemplate_form').modal('show');
    $('#email_template_slug').prop("disabled", false);
    $('#add_new').text('Add New Email Template');
    $('#hidden_email_template_id').val('');
  });

  $('#email_template_list').DataTable({
    processing: false,
    serverSide: true,
    ajax:emailTemplate_list,
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
    {data: 'subject', name: 'subject'},        
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


// edit EmailTemplate
$(document).on('click','.editEmailTemplate',function(){
  var getEmailTemplatesId = $(this).attr('data-EmailTemplatesId');
  if(getEmailTemplatesId){
    $.ajax({
      url:getEmailTemplatepdetails,
      data:{'EmailTemplatesId':getEmailTemplatesId},
      type:"POST",
      dataType:"JSON",
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      success:function(data){
        if(data.success=='true'){
          $('#hidden_email_template_id').val(getEmailTemplatesId);
          $('#email_template_name').val(data.name);
          $('#email_template_slug').val(data.slug);
          $('#email_template_slug').prop("disabled", true);
          $('#email_template_subject').val(data.subject);
          $('#email_template_content').val(data.content);
          $('#add_new_emailtemplate_form').modal('show');
          $('#add_new').text('Update Email Template');
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

$(document).on('click','.deleteEmailTemplate',function(){
  var getEmailTemplatesId = $(this).attr('data-EmailTemplatesId');
  toastr.warning("Are you sure want to delete this email template?<br /><br /><button type='button' id='confirm_yes' class='btn btn-secondary'>Yes</button>  <button type='button' id='confirm_no' class='btn btn-secondary'>No</button>",'Delete Hydracool SRP',
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
          url:deleteEmailTemplate,
          data:{'EmailTemplatesId':getEmailTemplatesId},
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
              $('#email_template_list').DataTable().ajax.reload();
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