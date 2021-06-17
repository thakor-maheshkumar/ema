

jQuery.validator.addMethod("exactlength", function(value, element, param) {
  return this.optional(element) || value.length == param;
 }, $.validator.format("Please enter exactly {0} characters."));
$(document).on('click','#adddistributordisabled',function(){
    $("#group_name").prop("disabled", true);
    $("#is_group").prop("checked", false);
  });

$('#add_form').validate({
  onkeyup: function(element) {$(element).valid()},
    rules:{
        distributor_code:{
            required:true,
            pattern:/^[0-9a-zA-Z/ -]+$/,
            maxlength:10,
            remote:{
              url:uniqueDistributorCode,
              headers:{'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
              type:'post',
            }
        },
        full_company_name:{
            required:true,
            commanRegex:true,
            maxlength: 255
         },
        abbreviated_company_name:{
          required:true,
          commanRegex:true,
        },
        group_name: {
          required: function(elem)
            {
                return $("#is_group:checked").length > 0;
            },
          commanRegex:true,
        },

         building_name:{
          commanRegex:true,
         },
         address1:{
          required:true,
          commanRegex:true,

         },
         address2:{
          commanRegex:true,
         },
         address3:{
          commanRegex:true,
         },
         full_address:{
          commanRegex:true,
         },
         state:{
          required:true,
          commanRegex:true,
         },
         zipcode:{
          required:true,
         },
         position:{
          required:true,
          commanRegex:true,
         },
         country_id:{
          required:true
         },
         fax_number: {

          pattern:/^(?=.*[0-9])[- +()0-9]+$/,
          maxlength:20
        },
         web_site: {
          pattern:/^((ftp|http|https):\/\/)?(www.)?(?!.*(ftp|http|https|www.))[a-zA-Z0-9_-]+(\.[a-zA-Z]+)+((\/)[\w#]+)*(\/\w+\?[a-zA-Z0-9_]+=\w+(&[a-zA-Z0-9_]+=\w+)(^\/{1,2})*)?/
        },
         name_of_primary_contact:{
            required:true,
            maxlength:100,
            commanRegex:true,

         },
         telephone_number_of_primary_contact:{
          required: true,
          telephoneNumber:true,
          maxlength:20
         },
         mobile_number_of_primary_contact:{
          required: true,
          telephoneNumber:true,
          maxlength:20
         },
         email_of_primary_contact:{
          required: true,
          validemail:true,
          maxlength: 50,
         }
    },
    messages: {
            full_company_name: {
            },
            distributor_code:{
              pattern:"Only alphanumeric, space, '-', '/' are allowed",
              remote:"distributor code is already taken !"
            },
            email_of_primary_contact:{
                required:"Please enter valid email address",
                validemail:"Invalid email format",
            },
            telephone_number_of_primary_contact: {
            required: "Please enter telephone number",
          },
          mobile_number_of_primary_contact: {
            required: "Please enter mobile number",
        },
        fax_number:{
          pattern:"Only numbers, space, '+', '-', '()' are allowed",
        },
        country_id: {
          required: "Please Select Country",
        },
        web_site:{
          pattern:"Please enter a valid URL"
        }
        },
        errorPlacement: function(error, element) {
          var elem = $(element);
          if (elem.hasClass("select2-hidden-accessible")) {
              element = $("#select2-" + elem.attr("id") + "-container").parent();
              error.insertAfter(element);
          } else {
              error.insertAfter(element);
          }
        },
        submitHandler: function(form) {var formData = $('#add_form').serializeArray().reduce(function(obj, item) {
          obj[item.name] = item.value;
          return obj;
        }, {});
        $('.loader_container').show();
        $.ajax({
          url:add_distributor,
          type:'POST',
          data:formData,
          headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
          success:function(result){
           toastr['success'](result.message);
           $('#add_distributor').modal('hide');
           $('#add_new_distributor').text('Add New Treatment Centre');
           $('#distributor_list').DataTable().ajax.reload();

           $('#add_form')[0].reset();
           $('.loader_container').hide();
         }
       });
      },

});


$('#update_form').validate({
  onkeyup: function(element) {$(element).valid()},
    rules:{
        distributor_code:{
            required:true,
            pattern:/^[0-9a-zA-Z/ -]+$/,
            maxlength:10,
            remote:{
              url:uniqueDistributorCode,
              data:{id:function() {return $('#id1').val();} },
              headers:{'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
              type:'post',
            }
        },
        full_company_name:{
            required:true,
            maxlength: 255,
            commanRegex:true,
         },
         abbreviated_company_name:{
          required:true,
          commanRegex:true,
        },
        group_name: {
          required: function(elem)
            {
                return $("#is_group1:checked").length > 0;
            },
          commanRegex:true,
        },
          building_name:{
          commanRegex:true,
         },
         address1:{
          required:true,
          commanRegex:true,
         },
         address2:{
          commanRegex:true,
         },
         address3:{
          commanRegex:true,
         },
         full_address:{
          commanRegex:true,
         },
         state:{
          required:true,
          commanRegex:true,
         },
         zipcode:{
          required:true,
         },
         position:{
          required:true,
          commanRegex:true,

         },
         country_id:{
          required:true
         },
         fax_number:{
            pattern:/^(?=.*[0-9])[- +()0-9]+$/,
            maxlength:20
         },
         web_site:{
          pattern:/^((ftp|http|https):\/\/)?(www.)?(?!.*(ftp|http|https|www.))[a-zA-Z0-9_-]+(\.[a-zA-Z]+)+((\/)[\w#]+)*(\/\w+\?[a-zA-Z0-9_]+=\w+(&[a-zA-Z0-9_]+=\w+)(^\/{1,2})*)?/
         },
         name_of_primary_contact:{
            required:true,
            maxlength:100,
            commanRegex:true,

         },
         telephone_number_of_primary_contact:{
            required:true,
            telephoneNumber:true,
            maxlength:20
         },
         mobile_number_of_primary_contact:{
            required:true,
            telephoneNumber:true,
            maxlength:20
         },
         email_of_primary_contact:{
            required:true,
            validemail:true,
         }
    },
    messages: {
            full_company_name: {

            },
            distributor_code:{
              remote:"distributor code is already taken !",
               pattern:"Only alphanumeric, space, '-' and '/' are allowed",

            },
            email_of_primary_contact:{
                required:"Please enter valid email address",
                validemail:"Invalid email format"
            },
            telephone_number_of_primary_contact: {
            required: "Please enter telephone number",
        },
          mobile_number_of_primary_contact: {
          required: "Please enter mobile number",
        },
        fax_number:{
          pattern:"Only numbers, space, '+', '-', '()' are allowed",
        },
        country_id: {
          required: "Please Select Country",
        },
        web_site:{
          pattern:"Please enter a valid URL"
        }
        },
        errorPlacement: function(error, element) {
          var elem = $(element);
          if (elem.hasClass("select2-hidden-accessible")) {
              element = $("#select2-" + elem.attr("id") + "-container").parent();
              error.insertAfter(element);
          } else {
              error.insertAfter(element);
          }
        },
        submitHandler: function(form) {var formData = $('#update_form').serializeArray().reduce(function(obj, item) {
          obj[item.name] = item.value;
          return obj;
        }, {});
        $('.loader_container').show();
        $.ajax({
          url:update_distributor,
          type:'POST',
          data:$('#update_form').serialize(),
          headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
          success:function(result){
            toastr['success'](result.message);
            $('#edit_distributor_detail2').modal('hide');
            $('#edit_distributor_detail').modal('hide');
            $('#distributor_list').DataTable().ajax.reload();
            if(isDetails==1){
              setTimeout(function () {
                location.reload();
              }, 1000);
            }
            $('.loader_container').hide();
          }
        })
      },
    });

$("#addnewdistributoruser").submit(function(e) {
      e.preventDefault();
  }).validate({
    onkeyup: function(element) {$(element).valid()},
    rules:{
      role:{
            required: true
        },
    name:{
      required:true,
      fullnameRegex:true,
    },
    username:{
      required:true,
       usernameRegex:true,
      remote:{
        url:uniqueusername,
        headers:{'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        type:'post'
      }
    },
    email:{
      required:true,
      validemail:true,
      remote:{
        url:uniqueuseremail,
        headers:{'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        type:'post',
      }
    },
    primary_telephone_number:{
      required:true,
      telephoneNumber:true,
      maxlength:20
    },
    mobile_telephone_number:{
      required:true,
      telephoneNumber:true,
      maxlength:20
    }
  },
  messages:{
    role:{
            required: "Please select distributor user role",
        },
    name:{
      required:"Please enter full name",
    },
    email:{
      required:"Please enter valid email address",
      remote:'Email address is already assigned',
      validemail:"Invalid email format",
    },
    username:{
      required:"Please enter Login Username",
      remote:'The Login username is already been taken',
    },
    primary_telephone_number: {
      required: "Please enter telephone number",
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
          $('#save_distributor_user').prop('disabled',true);
          var formData = $('#addnewdistributoruser').serializeArray().reduce(function(obj, item) {
          obj[item.name] = item.value;
          return obj;
        }, {});
          $.ajax({
          url:addDistributoruser,
          type:'POST',
          data:{formData},
          headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
          success:function(result){
                toastr.success('The User has been created successfully');
                $('#distributor_user_list').DataTable().ajax.reload();
                $('#add_new_user').modal('hide');
                $('#addnewdistributoruser')[0].reset();
                $('#save_distributor_user').prop('disabled',false);
                $('.loader_container').hide();

          },
      });
}
  });

$('#update_user_form').submit(function(e){
  e.preventDefault()
}).validate({
  onkeyup: function(element) {$(element).valid()},
  rules:{
    name:{
      required:true,
      fullnameRegex:true,
    },
    email:{
      required:true,
      validemail:true,
      remote:{
        url:uniqueuseremail,
        data:{id: function() { return $('#user_id').val(); } },
        headers:{'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        type:'post',
      }
    },
    primary_telephone_number:{
      required:true,
      telephoneNumber:true,
      maxlength:20
    },
    mobile_telephone_number:{
      required:true,
      telephoneNumber:true,
      maxlength:20
    }
  },
  messages:{
    email:{
      required:"Please enter valid email address",
      remote:'Email address is already assigned',
      validemail:'Invalid email format',
    },
    primary_telephone_number: {
            required: "Please enter telephone number",
        },
          mobile_telephone_number: {
          required: "Please enter mobile number",
        },
  },
    submitHandler:function(form){
      $('.loader_container').show();
      $.ajax({
          url:update_distributor_user,
          type:'POST',
          data:$('#update_user_form').serialize(),
          headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
              success:function(result){
                if(result.success){
                  toastr['success'](result.message);
                  $('#edit_user').modal('hide');
                  $('#distributor_user_list').DataTable().ajax.reload();
                }else{
                  toastr['error'](result.message);
                }
                $('.loader_container').hide();
            }
        })
      },
    });