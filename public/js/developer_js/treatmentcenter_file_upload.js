$(document).ready(function() {
    getTreatmentCenterName();
    // load the audit list data with datatable
      var treatmentcntre_list_file = $('#treatmentcentre_list_file').DataTable({
        processing: true,
        serverSide: true,
        order:['0','desc'],
        ajax: listtreatmentCentreFiles+"?treatmentCenterId="+getTreatmentCenterId,
        "scrollX": true,
        dom: 'Blfrtip',
        buttons:[
            {
                text: 'Clear Filters',
                attr:  {
                    id: 'clear',
                    'data-attr': 'treatmentcntre_list_file',
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
            {data: 'client_id', name: 'client_id'},
            {data: 'localDate', name: 'localDate'},
            {data: 'localTime', name: 'localTime'},
            {data: 'sessionTime', name: 'sessionTime'},
            {data: 'AquaBSessionTime', name: 'AquaBSessionTime'},
            {data: 'VibroXSessionTime', name: 'VibroXSessionTime'},
            {data: 'MicroTSessionTime', name: 'MicroTSessionTime'},
            {data: 'CollagenSessionTime', name: 'CollagenSessionTime'},
            {data: 'UltraBSessionTime', name: 'UltraBSessionTime'},
            {data: 's1_tick', name: 's1_tick', orderable: false, searchable: false, width: "70px"},
            {data: 's2_tick', name: 's2_tick'},
            {data: 's3_tick', name: 's3_tick'},
            {data: 's4_tick', name: 's4_tick'},
            {data: 'fresh_tick', name: 'fresh_tick'},
            {data: 'bright_tick', name: 'bright_tick'},
            {data: 'action', name: 'action', orderable: false, searchable: false, width: "70px"}
        ],
        initComplete: function () {
            this.api().columns().every(function () {
              var column = this;
                var input = document.createElement('input');
                $(input).attr( 'style', 'text-align: center;width: 100%');
                var columnText = $(column.header()).text();
                input.placeholder = columnText;
                if(columnText!="Action" && columnText!="S1" && columnText!="S2" && columnText!="S3" && columnText!="S4" && columnText!="Fresh" && columnText!="Bright"){
                    $(input).appendTo($(column.header()).empty())
                    .on('keyup', function (e) {
                      // if(e.keyCode == 13) {
                        var val = $.fn.dataTable.util.escapeRegex($(this).val());
                        column.search(val ? val : '', true, false).draw();
                      // }
                    });
                }else{
                    $(input).appendTo('Action');
                }

            });
        }
      }).on('draw.dt', function () {
        var info = treatmentcntre_list_file.page.info();
        if(info.page + 1 === info.pages || info.pages==0) {
          $('.table-responsive').removeClass('datatable_resize');
        } else {
          $('.table-responsive').addClass('datatable_resize');
        }
      });
});


// save media library
// treatment center validation rules
$("#file_upload").submit(function(e) {
  e.preventDefault();
}).validate({
  onkeyup: function(element) {$(element).valid()},
  rules: {
    treatment_file:{
      required:true,
      extension: "json"
    }
  },
  messages: {
    treatment_file: {
      required: "Select treatment file to upload",
      extension: "Invalid file type"
    }
  },
  errorPlacement: function(error, element) {
    if (element.attr("type") == "file") {
       error.appendTo('#error_area');
     }
  },
  submitHandler: function(form) {
    $('.loader_container').show();
    $('#save_treatmentcentre_file').prop( "disabled", true );
    var data = new FormData();

    if(document.getElementById('treatment_file').files[0]){
        data.append('file',document.getElementById('treatment_file').files[0]);
    }
    var center_name = $('#treatmentcenter_name').val();
    data.append('center_name',center_name);
    data.append('center_id',getTreatmentCenterId);

    $.ajax({
      url: saveTreatmentCentreFile,
      type: 'POST',
      processData: false, // important
      contentType: false, // important
      dataType : 'json',
      data: data,
      beforeSend: function(xhr){xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));},
      success:function(data){
        $('#add_new_treatmentcenter_file').modal('hide');
        toastr['success'](data.message);
        $('.loader_container').hide();
      },
      error:function(data){
        $('.loader_container').hide();
        $('#add_new_treatmentcenter_file').modal('hide');
        toastr['error'](data.message);
      }
    });
  }
});


// get treatment center name
function  getTreatmentCenterName(){
  $.ajax({
    url:treatmentcenterdetails,
    data:{"treatment_center_id":getTreatmentCenterId},
    type:"POST",
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    success:function(data){
      if(data.success=='true'){
        details = data.center_details;
        $('#treatmentcenter_name').val(details.full_company_name);
      }
    }
  });
}

$('.custom-file-input').on('change',function(){
  var fileName = document.getElementById("treatment_file").files[0].name;
  $(this).next('.custom-file-label').addClass("selected").html(fileName);
});


// document.onreadystatechange = function() {
//   if (document.readyState !== "complete") {
//     setTimeout(function(){
//        var elementheight = $('.dataTables_scrollBody').height();
//        var devide = 27;
//        var result = parseInt(elementheight / devide);
//        $('#treatmentcentre_list_file').DataTable().page.len(result).draw();
//     }, 100);
//   }
// };

$('#add_new_treatmentcenter_file').on('hidden.bs.modal', function (){
    
    $(".error").css("display", "none");
    $('#imageUpload')[0].dropzone.files.forEach(function(file) { 
    file.previewElement.remove(); 
});
$('.dz-message').show();

});
$('#imageUpload').click(function(){
  $(".error").css("display", "none");
});


if ($('#imageUpload').length) {

Dropzone.autoDiscover = false;
   myDropzone = new Dropzone('div#imageUpload', {
    addRemoveLinks: true,
    autoProcessQueue: false,
    uploadMultiple: true,
    parallelUploads: 100,
    paramName: 'file',
    clickable: true,
    acceptedFiles: '.json',
    maxFilesize: 1024.06,
    url: saveTreatmentCentreFile,
    init: function () {
      $('.dropzone').removeClass('dz-started');
        var myDropzone = this;

        
        // Update selector to match your button
        $("#save_treatmentcentre_file").click(function (e) {
            e.preventDefault();
            e.stopPropagation();

            

            if(myDropzone.files.length == 0){
              $("#imageUpload").removeClass('error');
              $('#file-error').show();
              return false;
            }

            if ( $("#file_upload").valid() ) {

                myDropzone.processQueue();
            }
            return false;
        });

        this.on('sending', function (file, xhr, formData) {
            var data = $('#file_upload').serializeArray();
            $.each(data, function (key, el) {
                formData.append(el.name, el.value);
            });
            formData.append('center_id',getTreatmentCenterId);
            var center_name = $('#treatmentcenter_name').val();
            formData.append('center_name',center_name);
            console.log(formData);

        });
        this.on("removedfile", function (file) {
          if(myDropzone.files.length == 0){
              $('.dz-message').show();
              return false;
            }
        });
        this.on("addedfile", function (file) {
          if(myDropzone.files.length >0){
              $('.dz-message').hide();
            }
        });
            myDropzone.on("complete",function(file) {
            myDropzone.removeFile(file);
        });
    },
    error: function (file, response){
        if ($.type(response) === "string")
            var message = response;
        else
            var message = response.message;
          console.log(message);
          if(message || 0 !== message.length){
            $('#file-extenstion').show();
            $('#file-extenstion').text(message);
            $('#file-error').hide();
          }else{
            console.log("no message");
            $('#file-extenstion').hide();
            $('#file-extenstion').text('');
          }

        /*file.previewElement.classList.add("dz-error");
        _ref = file.previewElement.querySelectorAll("[data-dz-errormessage]");
        _results = [];
        for (_i = 0, _len = _ref.length; _i < _len; _i++) {
            node = _ref[_i];
            _results.push(node.textContent = message);
        }*/
        return _results;
    },
    successmultiple: function (file, response) {
         if(response.success=='true'){
          toastr['success'](response.message);
          $('#add_new_treatmentcenter_file').modal('hide');
          $('#file_upload')[0].reset();
          $('#save_treatmentcentre_file').removeAttr('disabled');
          $('#media_library_list').DataTable().ajax.reload();

        }else{
          toastr['error'](data.message);
          $('#add_new_treatmentcenter_file').modal('hide');
          $('#file_upload')[0].reset();
          $('#save_treatmentcentre_file').removeAttr('disabled');
        }
        $('.loader_container').hide();
    },

    reset: function () {
        console.log("resetFiles");
        this.removeAllFiles(true);
    },
});
}


