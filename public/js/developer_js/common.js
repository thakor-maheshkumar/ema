// document.onreadystatechange = function() {
//     if (document.readyState !== "complete") {
//        $('.loader_container').show();
//     } else {
//       $('.loader_container').hide();
//     }
// };

$.validator.addMethod("notequal", function(value, element) {
  return $('#current_password').val() != $('#new_password_change').val()},
  "New password should not same with current password.");

jQuery.validator.addMethod("validUrl", function(value, element, param) {
  var urlReg = /^((ftp|http|https):\/\/)?(www.)?(?!.*(ftp|http|https|www.))[a-zA-Z0-9_-]+(\.[a-zA-Z]+)+((\/)[\w#]+)*(\/\w+\?[a-zA-Z0-9_]+=\w+(&[a-zA-Z0-9_]+=\w+)*)?$/;
  return urlReg.test( value );
}, $.validator.format("Please enter a valid URL"));

$(document).ready(function(){

$.fn.modal.prototype.constructor.Constructor.Default.backdrop = 'static';
$.fn.modal.prototype.constructor.Constructor.Default.keyboard =  false;

  if($( '#login_form' ).length){
    $('#login_form #username').on('change keyup', function() {
      if($('#login_form #username').val() != '' ) {
        $('#login_form #username').removeClass('is-invalid');
        $('#login_form .invalid-feedback').html('');
      }
    });
  }

  if($( '#form_reset_password' ).length){
    $("#form_reset_password").validate({
      onkeyup: function(element) {$(element).valid()},
      rules: {
        new_password: {
          required: true,
          pattern: /^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[-_!@#\$%\^&\*])(?=.{8,})/,
          remote:{
            url:checkCurrentPassword,
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            type:'post',
          }
        },
        password_confirmation: { required: true, equalTo: "#new_password"}
      },
      messages: {
        new_password: {
          required : "Please enter new password",
          pattern: "Your Password should have at least 8 characters and contain at least 1 numeric, 1 uppercase letter, 1    lowercase letter and 1 special character.",
          remote : "Do not use current password."
        },
        password_confirmation: {
          required: "Please enter confirmation password",
          equalTo : "Passwords do not match",

        }
      }
    });
  }

  if($('#changepassword').length){
    $("#changepassword").validate({
      onkeyup: function(element) {$(element).valid()},
      rules: {
        current_password: {
          required: true,
          remote:{
            url:checkExistingPassword,
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            type:'post',
          }
        },
        new_password_change: {
          required: true,
          pattern: /^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[-_!@#\$%\^&\*])(?=.{8,})/,
          notequal:true
        },
        new_confirm_password: { required: true, equalTo: "#new_password_change"},
      },
      messages: {
        new_password_change: {
          required:"Please enter new password",
          pattern: "Your Password should have at least 8 characters and contain at least 1 numeric, 1 uppercase letter, 1 lowercase letter and 1 special character.",
        },
        current_password:{
          required:"Please enter current password",
          remote : "Current password incorrect"
        },
        new_confirm_password:{
          required:"Please enter confirmation password",
          equalTo : "Password do not match"
        }
      }
    });
  }
  if($('#setting_form').length){
    $('#setting_form').validate({
      onkeyup: function(element) {$(element).valid()},
      rules:{
        inactivity_session_time:{
          required:true,
          number:true,
        },
        login_attempt:{
          required:true,
          number:true,
        },
        inactive_user_suspension:{
          required:true,
          number:true
        },
        delete_suspended_user:{
          required:true,
          number:true
        },
        contact_forward_email:{
          required:true,
          email:true
        }
      },
      messages:{
        inactivity_session_time:{
          required:"Please enter maximum allowed inactive session time in minutes"
        },
        login_attempt:{
          required:"Please enter maximum allowed login failure attempts",
        },
        inactive_user_suspension:{
          required:"Please enter maximum allowed inactive user suspension",
        },
        delete_suspended_user:{
          required:"Please enter maximum allowed delete suspended user",
        },
        contact_forward_email:{
          required:"Please enter contact forward email",
        },
      }
    });
  }

  if($('#chageProfileUser').length){

    $('#chageProfileUser').validate({
      onkeyup: function(element) {$(element).valid()},
      rules:{
        edit_profile_email:{
          required:true,
          validemail:true,
          remote:{
            url:uniqueuseremailData,
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            type:'post',
          },
        },
        edit_profile_name:{
          required:true,
        },
        edit_profile_primary_telephone_number:{
          required:true,
          telephoneNumber:true,
          maxlength:20
        },
        edit_profile_mobile_telephone_number:{
          required:true,
          telephoneNumber:true,
          maxlength:20
        }
      },
      messages:{
        edit_profile_name:{
          required:"Please enter full name"
        },
        edit_profile_email:{
          required:'Please enter valid email address',
          remote:"Email address is already assigned",
          validemail:'Invalid email format',
        },
        edit_profile_primary_telephone_number:{
          required:'Please enter telephone number',
        },
        edit_profile_mobile_telephone_number:{
          required:'Please enter mobile number',
        }
      }
    });
  }
  if($('#addContactUser').length){
    $('.select_dropdown').select2();
    $('#addContactUser').validate({
      onkeyup: function(element) {$(element).valid()},
      rules:{
        first_name:{
          required:true
        },
        last_name:{
          required:true,
        },
        email_address:{
          validemail:true,
        },
        contact_telephone_number:{
          pattern:/^(?=.*[0-9])[- +()0-9]+$/,
          maxlength:20
        },
        mobile_number:{
          pattern:/^(?=.*[0-9])[- +()0-9]+$/,
          maxlength:20
        },
        message:{
          required:true,
        }
      },
      messages:{
        first_name:{
          required:'Please enter first name',
        },
        last_name:{
          required:'Please enter last name',
        },
        email_address:{
          validemail:'Invalid email format',
        },
        contact_telephone_number:{
          pattern:"Only numbers, space, '+', '-', '()' are allowed",
        },
        mobile_number:{
          pattern:"Only numbers, space, '+', '-', '()' are allowed",
        },
      }
    });
  }

  $('#edit_detail').click(function(){
    $('#edit_profile_label_name').hide();
    $('#profile').hide();
    $('#my_profile').show();
    $('#edit_profile_name').show();
    $('#edit_profile_label_username').hide();
    $('#edit_profile_username').show();
    $('#edit_profile_label_primary_telephone_number').hide();
    $('#edit_profile_primary_telephone_number').show();
    $('#edit_profile_label_mobile_telephone_number').hide();
    $('#edit_profile_mobile_telephone_number').show();
    $('#edit_profile_label_email').hide();
    $('#edit_profile_email').show();
    $('#edit_detail').hide();
    $('#update_detail').show();
    $('.edit_profile_details').removeClass('font-weight-bold');
    $('#label_fullname').hide();
    $('#labelfullname').show();
    $('#label_loginusername').hide();
    $('#labelloginusername').show();
    $('#label_emailaddress').hide();
    $('#labelemailaddress').show();
    $('#label_mobilenumber').hide();
    $('#labelmobilenumber').show();
    $('#label_telephonenumber').hide();
    $('#labeltelephonenumber').show();

  });

});

$(document).on('hidden.bs.modal', '#edit_profile', function () {
  $('#edit_profile_label_name').show();
  $('#profile').show();
  $('#my_profile').hide();
  $('#edit_profile_name').hide();
  $('#edit_profile_label_username').show();
  $('#edit_profile_username').hide();
  $('#edit_profile_label_primary_telephone_number').show();
  $('#edit_profile_primary_telephone_number').hide();
  $('#edit_profile_label_mobile_telephone_number').show();
  $('#edit_profile_mobile_telephone_number').hide();
  $('#edit_profile_label_email').show();
  $('#edit_profile_email').hide();
  $('#edit_detail').show();
  $('#update_detail').hide();
  $('.edit_profile_details').addClass('font-weight-bold');
  $('#label_fullname').show();
  $('#labelfullname').hide();
  $('#label_loginusername').show();
  $('#labelloginusername').hide();
  $('#label_emailaddress').show();
  $('#labelemailaddress').hide();
  $('#label_mobilenumber').show();
  $('#labelmobilenumber').hide();
  $('#label_telephonenumber').show();
  $('#labeltelephonenumber').hide();
});


$(document).on('hidden.bs.modal', '.modal', function () {
  if($(this).find('form').length){
    $(this).find('form').trigger('reset');
    $(this).find('form').validate().resetForm();
    var form_id = $(this).find('form').attr('id');
    $("#"+form_id+" input, textarea").removeClass("error");
    $("#"+form_id+" input").removeClass("is-invalid");
    $(".select_dropdown").val('').trigger('change') ;
  }
});

$('.select_dropdown').change(function(){
  $(this).valid()
});


// addeed validation rules  for email id validation
jQuery.validator.addMethod("validemail", function(value, element, param) {
  var emailReg = /(?!^[.+&'_-]*@.*$)(^[_\w\d+&'-]+(\.[_\w\d+&'-]*)*@[\w\d-]+(\.[\w\d-]+)*\.(([\d]{1,3})|([\w]{2,3}))$)/;
  return emailReg.test( value );
}, $.validator.format("Please enter exactly {0} characters."));


jQuery.validator.addMethod("exactlength", function(value, element, param) {
  return this.optional(element) || value.length == param;
}, $.validator.format("Please enter exactly {0} characters."));

jQuery.validator.addMethod('filesize', function (value, element, param) {
    return this.optional(element) || (element.files[0].size)
}, 'File size must be greater than {0}');

jQuery.validator.addMethod("telephoneNumber", function(value, element, param) {
  var telephone = /^(?=.*[0-9])[- +()0-9]+$/;
  return telephone.test( value );
}, $.validator.format("Only numbers, space, '+', '-', '()' are allowed"));

////////////Pritn for all///////////

////////Print for Hydracool///////////

$.validator.addMethod("usernameRegex", function(value, element) {
    return this.optional(element) || /^[\w.-]+$/i.test(value);
}, "Only alphanumeric characters or '_', ' . ', '-' allowed");

$.validator.addMethod("fullnameRegex", function(value, element) {
    return this.optional(element) || /^[0-9a-zA-Z -]+$/.test(value);
},  "Only alphanumeric characters,space,'-' are allowed.");


$.validator.addMethod("commanRegex", function(value, element) {
    return this.optional(element) || /^[0-9a-zA-Z/ -]+$/.test(value);
}, "Only alphanumeric characters,space,'-', '/' are allowed");

$(document).on('click', '#clear', function(){
    var tableid = $(this).attr('data-attr');
    $('#'+tableid).find("input:text").each(function() {
        var id = $(this).attr('id');
        $('#'+id).val('');
    });

    var table = $('#'+tableid).DataTable();
    table.search( '' ).columns().search( '' );
    if(tableid=="users-table"){
        table.draw().order(['7','desc']);
    }else if(tableid=="cosmetic_list"){
      table.draw().order(['10','desc']);
    }else if(tableid=="hydracool_srp_list"){
      table.draw().order(['9','desc']);
    }else if(tableid=="distributor_list"){
      table.draw().order(['9','desc']);
    }else if(tableid=="distributor_user_list"){
      table.draw().order(['7','desc']);
    }else if(tableid=="distributor_treatment"){
      table.draw().order(['8','desc']);
    }else if(tableid=="media_library_list"){
      table.draw().order(['4','desc']);
    }else if(tableid=="treatmentcntre_list_file"){
      table.draw().order(['0','desc']);
    }else if(tableid=="hydracool_srp_list"){
      table.draw().order(['8','desc']);
    }else if(tableid=="treatment_center_Principal_list"){
      table.draw().order(['7','desc']);
    }else if(tableid=="treatmence_center_list"){
      table.draw().order(['8','desc']);
    }else{
      table.draw().order([]);
    }

    table.ajax.reload();

});


/*function printDiv(divID) {
  var domClone = divID.cloneNode(true);
  var $printSection = document.getElementById("printSection");

  if (!$printSection) {
    var $printSection = document.createElement("div");
    $printSection.id = "printSection";
    document.body.appendChild($printSection);
  }

  $printSection.innerHTML = "";
  $printSection.appendChild(domClone);

  window.print();
 }*/


 function updateSerialNumberList(){
  $.ajax({
    url:getAllHydraCoolSrp,
    type:"POST",
    dataType:"JSON",
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    success:function(data){
      var insightsarray = data.data;
      $('#srp_serial_number_non_ema')
      .find('option')
      .remove()
      .end();
      $('#srp_serial_number_non_ema').val('');
      $('#srp_serial_number_non_ema').val(null).trigger('change');
      $('#srp_serial_number_non_ema').empty().trigger("change")

      var selectBox = document.getElementById('srp_serial_number_non_ema');
      selectBox.options.add( new Option('Select OR Enter Serial number','') );
      $.each(insightsarray,function(i,val){
        selectBox.options.add( new Option(val,val) );
      });
      $('#srp_serial_number_non_ema').trigger("change")
    },
  });
}

$.validator.addMethod("uniqueHandset", function(value, element) {
    var parentForm = $(element).closest('form');
    var timeRepeated = 0;
    if (value != '') {
        $(parentForm.find(':text')).each(function () {
            if ($(this).val() === value) {
                timeRepeated++;
            }
        });
    }
    return timeRepeated === 1 || timeRepeated === 0;

}, "Handset number already exist");
// $('.unit_name').on('blur',function(){
//   var currentValue = $(this).val();
//   var currentAttrId = $(this).attr('id');
//   $('#'+currentAttrId+'-error').remove();
//   if($.inArray(currentValue, getAllHandsetValue) != -1){
//     $('<label id="'+currentAttrId+'-error" class="error" for="'+currentAttrId+'">Handset number already exist</label>').insertAfter('#'+currentAttrId);
//     $(this).val('');
//   }else{
//     if(currentValue){
//       getAllHandsetValue.push(currentValue);
//     }
//   }
// });



$('.phoneregx').on('keyup',function(){
  var getPhone = $(this).val();
  getPhone = getPhone.replace('+', "")
  $(this).val('+'+getPhone);
});