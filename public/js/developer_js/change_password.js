$(document).ready(function() {
  $.validator.addMethod("notequal", function(value, element) {
    return $('#current_password').val() != $('#new_password').val()},
    "New password should not same with current password.");

  $("#changepassword").validate({
    rules: {
      current_password: "required",
      new_password: { 
        required: true, 
        pattern: /^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#\$%\^&\*])(?=.{8,})/,
        notequal:true 
      },
      new_confirm_password: { required: true, equalTo: "#new_password"},
    },
    messages: {
      new_password: {         
        pattern: "Minimum password length is 8 character and must contain at least 1 number, 1 uppercase, 1 lowercase." 
      },
    }
  });
});

