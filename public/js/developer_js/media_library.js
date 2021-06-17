$(document).ready(function() {

 var media_library_list =  $('#media_library_list').DataTable({
    processing: true,
    serverSide: true,
    responsive: true,
    "scrollX": true,
    autoWidth: false,
    order:['4','desc'],
    ajax: listMedialLibrary,
    dom: 'Blfrtip',
    buttons:[
        {
            text: 'Clear Filters',
            attr:  {
                id: 'clear',
                'data-attr': 'media_library_list',
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
        {data: 'document_name', name: 'document_name', width: "150px"},
        {data: 'category_name', name: 'category_name', width: "100px"},
        {data: 'serial_number', name: 'serial_number', width: "150px"},
        {data: 'status', name: 'status', width: "50px"},
        {data:'created_at',name:'created_at', width: "80px"},
        {data: 'action', name: 'action', orderable: false, searchable: false, width: "50px"}
    ],
    initComplete: function () {
        this.api().columns().every(function () {
            var column = this;
            var columnText = $(column.header()).text();
            var columnAttrname = $(column.footer()).attr('dataName');
              var input = document.createElement('input');
              $(input).attr( 'style', 'text-align: center;width: 100%');
              input.setAttribute("id", columnAttrname);
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
    var info = media_library_list.page.info();
    if(info.page + 1 === info.pages || info.pages == 0) {
      $('.table-responsive').removeClass('datatable_resize');
    } else {
      $('.table-responsive').addClass('datatable_resize');
    }
  });
});
$('.select_dropdown').select2();
$('.select_dropdownHydra').select2();

// save media library
 $('#support_file').on('change', function() {
        $(this).valid();
    });


// media library form validation rules
$("#media_library_form").submit(function(e) {
  e.preventDefault();
}).validate({
  onkeyup: function(element) {$(element).valid()},
  rules: {
    document_name: {
      required: true,
    },
    category_id: "required",
    fk_hydracool_srp_id: "required",
    description: "required",
    support_file:{
      required:true,
      extension: "docx|pdf|png|JPEG|jpeg|PNG|mp4|JPG|jpg|zip|bin",
      filesize: 0,
    }
  },

  messages: {
    support_file: {
      required: "Please select the file",
      extension: "Please upload the file with valid extenstion",
    },
  },
  errorPlacement: function(error, element) {

    if (element.attr("type") == "file") {
       error.appendTo('#error_area');
    }else{
      error.insertAfter(element);
    }
  },
  submitHandler: function(form) {
    $('.loader_container').show();
    $('#save_media_library').prop( "disabled", true );
    var data = new FormData();

    var document_name = $('#document_name').val();
    var description=$('#description').val();
    var category_id = $('#category_id').val();
    var fk_hydracool_srp_id  = $('#fk_hydracool_srp_id').val();


    if(document.getElementById('support_file').files[0]){
        data.append('file',document.getElementById('support_file').files[0]);
    }
      data.append('document_name',document_name);
      data.append('category_id',category_id);
      data.append('fk_hydracool_srp_id',fk_hydracool_srp_id);
      data.append('description',description);

    $.ajax({
      url: saveMedialLibrary,
      type: 'POST',
      processData: false, // important
      contentType: false, // important
      dataType : 'json',
      data: data,
      beforeSend: function(xhr){xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));},
      success:function(data){
        $('.custom-file-label').removeClass("selected").html('Choose file');
        if(data.success=='true'){
          toastr['success'](data.message);
          $('#add_new_media_library').modal('hide');
          $('#media_library_form')[0].reset();
          $('#save_media_library').removeAttr('disabled');
          $('#media_library_list').DataTable().ajax.reload();

        }else{
          toastr['error'](data.message);
          $('#add_new_media_library').modal('hide');
          $('#media_library_form')[0].reset();
          $('#save_media_library').removeAttr('disabled');
        }
        $('.loader_container').hide();
      },
      error:function(data){
        $('.loader_container').hide();
        $('#save_media_library').removeAttr('disabled');
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
        $('.loader_container').hide();
      }
    });
  }
});


// delete document
$(document).on('click','.deletemediafile',function(){
  var getMediaFileId = $(this).attr('data-mediaFileId');
  var getFileName = $(this).attr('data-FileName');
  var getFileDocumentName=$(this).attr('data-mediaFileDocumentName');

toastr.warning("Are you sure want to delete "+getFileDocumentName+" document ?<br /><br /><button type='button' id='confirm_yes' class='btn btn-secondary'>Yes</button>  <button type='button' id='confirm_no' class='btn btn-secondary'>No</button>",'Delete Support Document',
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
              url:deleteMedialLibrary,
              data:{'media_library_id':getMediaFileId,'filename':getFileName},
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
                  $('#media_library_list').DataTable().ajax.reload();
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
$('.custom-file-input').on('change',function(){
  var fileName = document.getElementById("support_file").files[0].name;
  $(this).next('.custom-file-label').addClass("selected").html(fileName);
})
$(document).on('hidden.bs.modal', '#add_new_media_library', function () {
  $('.custom-file-label').addClass("selected").html('Choose file');
});

let timer, currSeconds = 0;

function resetTimer() {
    clearInterval(timer);
    currSeconds = 0;
    timer = setInterval(startIdleTimer, 1000);
}


window.onload = resetTimer;
window.onmousemove = resetTimer;
window.onmousedown = resetTimer;
window.ontouchstart = resetTimer;
window.onclick = resetTimer;
window.onkeypress = resetTimer;

function startIdleTimer() {
  var getSessonValue =  login_activity*60;
  var getRemainingTime = getSessonValue - currSeconds;
  if(getRemainingTime == '60'){
      $('#event_click').click();
  }
    currSeconds++;
}

document.onreadystatechange = function() {
  if (document.readyState !== "complete") {
    setTimeout(function(){
       var elementheight = $('.dataTables_scrollBody').height();
       var devide = 27;
       var result = parseInt(elementheight / devide);
       $('#media_library_list').DataTable().page.len(result).draw();
    }, 100);
  }
};