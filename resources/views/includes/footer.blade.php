@guest
<footer class="position-absolute ">© Copyright 2020 EMA Aesthetics</footer>
@else
<footer class=" footer_inner mt-3">© Copyright 2020 EMA Aesthetics</footer>
@endguest
<!-- JS -->

<script>
  var bootstrap_min = '<link href="{{asset("css/bootstrap.min.css")}}" media="all" rel="stylesheet" type="text/css" />';
  var bootstrap_grid = '<link href="{{asset("css/bootstrap-grid.css")}}" media="all" rel="stylesheet" type="text/css" />';
</script>

<script type="text/javascript" src="{{asset('js/select2.min.js')}}"></script>
<script type="text/javascript" src="{{asset('js/jquery.validate.min.js')}}" ></script>
<script type="text/javascript" src="{{asset('js/additional-methods.min.js')}}" ></script>
<script type="text/javascript" src="{{asset('js/jquery.dataTables.min.js')}}"></script>
<script type="text/javascript" src="{{asset('js/fnFilterClear.js')}}"></script>
<script type="text/javascript" src="{{asset('js/dataTables.bootstrap4.min.js')}}"></script>
<script type="text/javascript" src="{{asset('js/dataTables.buttons.min.js')}}"></script>
<script type="text/javascript" src="{{asset('js/jszip.min.js')}}"></script>
<script type="text/javascript" src="{{asset('js/pdfmake.min.js')}}"></script>
<script type="text/javascript" src="{{asset('js/vfs_fonts.js')}}"></script>
<script type="text/javascript" src="{{asset('js/buttons.html5.min.js')}}"></script>
<script type="text/javascript" src="{{asset('js/buttons.print.min.js')}}"></script>

<script type="text/javascript" src="{{ asset('js/toastr.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/developer_js/common.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/developer_js/commonPrint.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/bootstrap-datepicker.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/moment.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/moment-timezone-with-data-2012-2022.min.js') }}"></script>
<script type="text/javascript" src="{{asset('js/jquery.daterangepicker.min.js')}}"></script>
<script type="text/javascript" src="{{asset('js/simplebar.min.js')}}"></script>
<script type="text/javascript" src="{{asset('js/datetime.js')}}"></script>
<script type="text/javascript" src="{{asset('js/dropzone.js')}}"></script>
<script>

$('[data-toggle="tooltip"]').tooltip();

$('th').on("click", function (event) {
  if($(event.target).is("input"))
    event.stopImmediatePropagation();
});
toastr.options = {
  "preventDuplicates": true,
}

@if(!Session::has('user_timezone'))
  var settimezone = "{{ route('settimezone') }}";
    $.ajax({
      type:"get",
      url:settimezone,
      data:{'timezone':moment.tz.guess(),"timezone_value":moment().tz(moment.tz.guess()).format('Z')},
      headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
      success: function() {
       // window.location.reload();
      },
      error: function() {
        //window.location.reload();
      }
    });
@endif

@if(Session::has('message'))
var shortCutFunction ="{{ Session::get('alert-type') }}";
var msg = "{{ Session::get('message') }}";
toastr.options = {
  "preventDuplicates": true,
}
var $toast = toastr[shortCutFunction](msg);
@endif

@auth

@if((Session::get('force_password_change') == 1 && Auth::user()->first_time_login == 0) || $errors->has('new_password'))

$(function() {
  $('#reset_password').modal({
    show: true,
    backdrop: 'static',
    keyboard: false
  });
});
@endif

@if(Session::has('logout_message'))
var shortCutFunction ="{{ Session::get('logout-alert-type') }}";
var msg = "{{ Session::get('logout_message') }}";
if(shortCutFunction == 'success'){
  toastr.options = {
    "preventDuplicates": true,
  }
  var $toast = toastr[shortCutFunction](msg);

  setTimeout(function(){
    var url = "{{ route('logout') }}";
    $.ajax({
      type:"POST",
      url:url,
      headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
      success: function() {
        window.location.reload();
      },
      error: function() {
        window.location.reload();
      }
    });
  }, 4000);
}
@endif
@endauth
</script>

