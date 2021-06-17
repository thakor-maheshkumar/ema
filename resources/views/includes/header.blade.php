<div class="loader_container" style="display:none">
  <div class="w-auto m-auto"><img id="logo_white" src="{{asset('images/logo.png')}}"  />
    <div class="sk-chase mt-5 mx-n3">
      <div class="sk-chase-dot"></div>
      <div class="sk-chase-dot"></div>
      <div class="sk-chase-dot"></div>
      <div class="sk-chase-dot"></div>
      <div class="sk-chase-dot"></div>
      <div class="sk-chase-dot"></div>
    </div>
  </div>
</div>
<nav class="navbar navbar-expand-xl navbar-dark py-0"> <a class="navbar-brand" href="{{ route('home') }}" ><img src="{{asset('images/emalogo-black-new.png')}}" width="100" height="53"  /> </a>
  @if(Auth::user())
  <ul class="navbar-nav search_field ">
    <li class="nav-item"> <a class="nav-link mx-2 mt-lg-1" href="#"> <img src="{{asset("images/search.svg")}}"  width="20" height="20" /> </a> </li>
    <li class="nav-item"> <a class="nav-link mx-2 mt-lg-1" href="{{ route('help') }}"> <img src="{{asset("images/question-circle.svg")}}"  width="20" height="20" /> </a> </li>
    <li class="nav-item dropdown userprofile"> <a class="nav-link dropdown-toggle mx-2" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
      <div class="profile">
        <span>{{getFirstCharacter()}}</span>
      </div></a>
      <div class="dropdown-menu" aria-labelledby="navbarDropdown">

        <a class="dropdown-item nav-link" href="#" data-target="#edit_profile" id="profile_change" data-toggle="modal">My Profile</a>
        <a class="dropdown-item nav-link" href="#" data-toggle="modal" data-target="#change_password">Change Password</a>
        <a class="dropdown-item nav-link" href="{{ route('logout') }}" onclick="event.preventDefault();
        document.getElementById('logout-form').submit();">{{ __('Logout') }}</a>

        @role('system administrator')
        <div class="dropdown-divider"></div>
        <a class="dropdown-item nav-link" id="hi"  href="#" data-toggle="modal" data-target="#setting_info" id="setting_info1" ><i class="fas fa-cog"></i>Settings</a>
        <!-- <a class="dropdown-item nav-link" href="{{ route('emailTemplate') }}" id="emailTemplate" ><i class="fas fa-cog"></i>Email Templates</a> -->
        @endrole
      </div>
    </li>
  </ul>
  @endif
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"> <span class="navbar-toggler-icon"></span> </button>
  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto ">

    @if(Auth::user() && Auth::user()->first_time_login > 0)
        @php
         $getAccessibleMenu = getRoleWiseURLAndMenuAccess()
        @endphp

      @if(isset($getAccessibleMenu['EMA_USERS']) && !empty($getAccessibleMenu['EMA_USERS']))
        <li class="nav-item "> <a class="nav-link mx-2" href="{{ $getAccessibleMenu['EMA_USERS'] }}" ><img src="{{asset("images/ema_users.svg")}}" width="22" height="22" />EMA Users</a> </li>
      @endif

      @if(isset($getAccessibleMenu['DISTRIBUTOR']) && !empty($getAccessibleMenu['DISTRIBUTOR']))
      <li class="nav-item "> <a class="nav-link mx-2" href="{{ $getAccessibleMenu['DISTRIBUTOR'] }}" ><img src="{{asset("images/sitemap.svg")}}"  width="20" height="20" />Distributors</a> </li>
      @endif

      @if(isset($getAccessibleMenu['TREATMENTCENTRE_LIST']) && !empty($getAccessibleMenu['TREATMENTCENTRE_LIST']))
      <li class="nav-item "> <a class="nav-link mx-2" href="{{ $getAccessibleMenu['TREATMENTCENTRE_LIST'] }}" ><img src="{{asset("images/treatment_centre.svg")}}"  width="20" height="20" />Treatment Centres</a> </li>
      @endif

      @if(isset($getAccessibleMenu['DEVICE']) && !empty($getAccessibleMenu['DEVICE']))
      <li class="nav-item "> <a class="nav-link mx-2" href="{{ $getAccessibleMenu['DEVICE'] }}"  ><img src="{{asset("images/device_icon.png")}}"  />Devices</a> </li>
      @endif

      @if(isset($getAccessibleMenu['TREATMENT_CENTRE_FILE_DATA']) && !empty($getAccessibleMenu['TREATMENT_CENTRE_FILE_DATA']))
      <li class="nav-item "> <a class="nav-link mx-2" href="{{ $getAccessibleMenu['TREATMENT_CENTRE_FILE_DATA'] }}"><img src="{{asset("images/treatment_data.svg")}}"  width="20" height="20" /> Treatment Data</a> </li>
      @endif

      @if(isset($getAccessibleMenu['DIAGNOSTIC_DATA']) && !empty($getAccessibleMenu['DIAGNOSTIC_DATA']))
      <li class="nav-item "> <a class="nav-link mx-2" href="{{ $getAccessibleMenu['DIAGNOSTIC_DATA'] }}"><img src="{{asset("images/diagnostic_data.svg")}}"  width="20" height="20" /> Diagnostic Data</a> </li>
      @endif

      @if(isset($getAccessibleMenu['AUDIT_LOG']) && !empty($getAccessibleMenu['AUDIT_LOG']))
      <li class="nav-item "> <a class="nav-link mx-2" href="{{ $getAccessibleMenu['AUDIT_LOG'] }}"><img src="{{asset("images/audit_log_icon.svg")}}"  width="20" height="20" /> Audit Log</a> </li>
      @endif

      @if(isset($getAccessibleMenu['MEDIA_LIBRARY']) && !empty($getAccessibleMenu['MEDIA_LIBRARY']))
      <li class="nav-item"> <a class="nav-link mx-2"  href="{{ $getAccessibleMenu['MEDIA_LIBRARY'] }}" ><img src="{{asset("images/media_library.svg")}}"  width="20" height="20" />Media Library</a> </li>
      @endif

      @if(isset($getAccessibleMenu['REPORTS']) && !empty($getAccessibleMenu['REPORTS']))
      <li class="nav-item"> <a class="nav-link mx-2"  href="javadcript:;" ><img src="{{asset("images/reports.svg")}}"  width="20" height="20" />Reports</a> </li>
      @endif

  @endif
</ul>

  @guest

  <ul class="navbar-nav before_login">
    <li class="nav-item">
      <li class="nav-item active"> <a class="nav-link mx-2" href="#" data-toggle="modal" data-target="#aboutema">About EMA </a> </li>
      <li class="nav-item "> <a class="nav-link  mx-2"  href="#" data-toggle="modal" data-target="#contactema">Contact EMA</a> </li>
       <li class="nav-item "> <a class="nav-link mx-2"  href="#" data-toggle="modal" data-target="#login">Login</a> </li>
    </li>
  </ul>

  @else
   <ul class="navbar-nav hide_mobile">
      <li class="nav-item"> <a class="nav-link mx-2 mt-lg-1" href="#"> <img src="{{asset("images/search.svg")}}"  width="20" height="20" /> </a> </a> </li>
      <li class="nav-item"> <a class="nav-link mx-2 mt-lg-1" href="{{ route('help') }}"> <img src="{{asset("images/question-circle.svg")}}"  width="20" height="20" /> </a> </li>
    </ul>
    <ul class="navbar-nav hide_mobile">
      <li class="nav-item dropdown userprofile"> <a class="nav-link dropdown-toggle mx-2" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <div class="profile">
          <span>{{getFirstCharacter()}}</span>
        </div></a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown">

          <a class="dropdown-item nav-link" href="#" data-target="#edit_profile" id="profile_change" data-toggle="modal">My Profile</a>
          <a class="dropdown-item nav-link" href="#" data-toggle="modal" data-target="#change_password">Change Password</a>
          <a class="dropdown-item nav-link" href="{{ route('logout') }}" onclick="event.preventDefault();
          document.getElementById('logout-form').submit();">{{ __('Logout') }}</a>

        @role('system administrator')
        <div class="dropdown-divider"></div>
        <a class="dropdown-item nav-link" id="hi"  href="#" data-toggle="modal" data-target="#setting_info" id="setting_info1" ><i class="fas fa-cog"></i>Settings</a>
        <!-- <a class="dropdown-item nav-link" href="{{ route('emailTemplate') }}" id="emailTemplate" ><i class="fas fa-cog"></i>Email Templates</a> -->
        @endrole
      </div>
    </li>
  </ul>
  <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
  </form>
  @endguest


</div>
</nav>
<!--Edit Profile -->
@auth
<div class="modal fade" id="edit_profile" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-my-profile homeModel" role="document">
    <div class="modal-content ">
      <div class="modal-header">
      <h2 class="modal-title w-100" id="profile">My Profile</h2>
      <h2 class="modal-title w-100" id="my_profile" style="display: none">Edit Profile</h2>
      <button type="button" class="close text-right" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">X</span> </button>
    </div>
      <div class="modal-body text-center px-3 pb-4 text-center ">
        <div class="row justify-content-xl-center justify-content-lg-center">
          <div class="col-lg-12 col-xl-12">
            <form method="post" action="{{url('/edit/profile')}}" id="chageProfileUser">
              @csrf
              <div class="form-row">
              <div class="form-group col-lg-6 col-md-6 col-sm-6">
                <label  class="text-left w-100 mb-0 font-weight-bold edit_profile_details" id="label_fullname">Full Name:</label>
                <label  class="text-left w-100 mb-0" id="labelfullname" style="display: none">Full Name</label>
                <div class="text-left">
                  <label  id="edit_profile_label_name">{{Auth::user()->name}}</label>
                  <input type="text" id="edit_profile_name" class="form-control"
                  name="edit_profile_name"
                  value="{{Auth::user()->name}}" style="display: none;">
                </div>
              </div>
              <div class="form-group col-lg-6 col-md-6 col-sm-6">
                <label  class="text-left w-100 mb-0 font-weight-bold edit_profile_details" id="label_loginusername">Login Username:</label>
                <label  class="text-left w-100 mb-0" id="labelloginusername" style="display: none">Login Username</label>
                <div class="text-left">
                  <label  id="edit_profile_label_username">{{Auth::user()->username}}</label>
                  <input type="text" id="edit_profile_username" class="form-control" name="edit_profile_username" value="{{Auth::user()->username}}" disabled="" style="display: none;">
                </div>
              </div>
               <div class="form-group col-lg-6 col-md-6 col-sm-6">
                <label  class="text-left w-100 mb-0 font-weight-bold edit_profile_details" id="label_emailaddress">Email Address:</label>
                <label  class="text-left w-100 mb-0" id="labelemailaddress" style="display: none">Email Address</label>
                <div class="text-left">
                  <label  id="edit_profile_label_email">{{Auth::user()->email}}</label>
                   <input type="text" id="edit_profile_email" class="form-control check-remote"
                   name="edit_profile_email" value="{{Auth::user()->email}}"
                   style="display: none;">
                </div>
              </div>
              <div class="form-group col-lg-6 col-md-6 col-sm-6">
                <label  class="text-left w-100 mb-0 font-weight-bold edit_profile_details" id="label_mobilenumber">Mobile Number:</label>
                <label  class="text-left w-100 mb-0" id="labelmobilenumber"style="display: none">Mobile Number</label>
                <div class="text-left">
                  <label  id="edit_profile_label_mobile_telephone_number">{{addSymbol(Auth::user()->mobile_telephone_number)}}</label>
                  <input type="text" id="edit_profile_mobile_telephone_number" class="form-control phoneregx"
                name="edit_profile_mobile_telephone_number" value="{{addSymbol(Auth::user()->mobile_telephone_number)}}" style="display: none">
                </div>
              </div>
             <div class="form-group col-lg-6 col-md-6 col-sm-6">
                <label  class="text-left w-100 mb-0 font-weight-bold edit_profile_details" id="label_telephonenumber">Telephone Number:</label>
                <label  class="text-left w-100 mb-0" id="labeltelephonenumber" style="display: none">Telephone Number</label>
                <div class="text-left">
                  <label  id="edit_profile_label_primary_telephone_number">{{addSymbol(Auth::user()->primary_telephone_number)}}</label>
                  <input type="text" id="edit_profile_primary_telephone_number" class="form-control phoneregx" name="edit_profile_primary_telephone_number" value="{{addSymbol(Auth::user()->primary_telephone_number)}}" style="display: none">
                </div>
              </div>

              <div class="form-group col-lg-6 col-md-6 col-sm-6">
                <label  class="text-left w-100 mb-0 font-weight-bold edit_profile_details" id="label_role">Role:</label>
                <div class="text-left">
                  <label  id="edit_profile_label_primary_telephone_number">{{ getUserRoles(Auth::user()->roles->first()->name) }}</label>
                </div>
              </div>


              </div>
              <div class="row text-center mt-3">
                <div class="col-xl-12 col-lg-12 col-md-12">
                <button class="btn btn-secondary " id="edit_detail" data-toggle="modal"
                 type="button">Edit Profile</button>
                 <button class="btn btn-secondary" id="update_detail" data-toggle="modal"
                 type="submit" style="display: none">Save</button>
              </div>
            </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

@endauth
<!--Change Password -->
<div class="modal fade" id="change_password" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
      <div class="modal-header">
      <h2 class="modal-title w-100 ">Change Password</h2>
            <button type="button" class="close text-right" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">X</span> </button>
          </div>
      <div class="modal-body px-3 pb-4">
        <div class="row justify-content-xl-center justify-content-lg-center">
          <div class="col-lg-12 col-xl-12">

            <form method="post" action="{{route('changepassword')}}" id="changepassword">
              @csrf
              <div class="form-group">
                <label for="exampleInputEmail1" class="mb-1">Enter Current Password</label>

                <input type="password" id="current_password" class="form-control check-remote" name="current_password">
              </div>
              <div class="form-group">
                <label for="exampleInputEmail1" class="mb-1">Enter New Password</label>
                <input type="password" id="new_password_change" class="form-control" name="new_password_change">
              </div>
              <div class="form-group">
                <label for="exampleInputEmail1" class="mb-1">Confirm Password</label>
                <input type="password" id="new_confirm_password" class="form-control" name="new_confirm_password">
              </div>
              <div class="row text-center">
                <div class="col-xl-12 col-lg-12">
                  <button data-toggle="modal" class="btn btn-secondary text-right font-weight-bold">Save</button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!--Change Password -->
<!-- Setting Information -->
<div class="modal fade" id="setting_info" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
         <h2 class="modal-title w-100">Setting Information</h2>
      <button type="button" class="close text-right" data-dismiss="modal" aria-label="Close" > <span aria-hidden="true">X</span> </button>
    </div>
      <div class="modal-body px-3 pb-4 ">
        <div class="row justify-content-xl-center justify-content-lg-center">
          <div class="col-lg-12 col-xl-12 text-center "> <img src="{{asset('images/emalogo-black-new.png')}}" id="logo_url" class="img-fluid mb-3"/>


            <form method="post" action="{{route('updatecoresetting')}}" id="setting_form">
            @csrf

            <div class="form-group text-left">
              <label for="exampleInputEmail1" class="mb-1 ">Max allowed inactive session time (Minutes)</label>
              <input type="text" id="inactivity_session_time" class="form-control"
              name="inactivity_session_time" value="{{ App\CoreSetting::where('name','inactivity_session_time')->first()->value  }}">
            </div>
            <div class="form-group text-left">
              <label for="exampleInputEmail12" class="mb-1 text-left">Max allowed login failure attempts
              </label><a href="#" data-toggle="tooltip" title="Tooltip" class="ml-1"><i class="fas fa-question-circle"></i></a>
              <input type="text" id="login_attempt" class="form-control" name="login_attempt"
              value="{{ App\CoreSetting::where('name','login_attempt')->first()->value  }}">
              </div>
              <div class="form-group text-left">
              <label for="exampleInputEmail12" class="mb-1 text-left">Max allowed inactive user suspension (Days)</label><a href="#" data-toggle="tooltip" title="Tooltip" class="ml-1"><i class="fas fa-question-circle"></i></a>
              <input type="text" id="inactive_user_suspension" class="form-control" name="inactive_user_suspension"
              value="{{ App\CoreSetting::where('name','inactive_user_suspension')->first()->value  }}">
              </div>
              <div class="form-group text-left">
              <label for="exampleInputEmail12" class="mb-1 text-left">Max allowed delete suspended user (Days)</label>
              <input type="text" id="delete_suspended_user" class="form-control" name="delete_suspended_user"
              value="{{ App\CoreSetting::where('name','delete_suspended_user')->first()->value  }}">
              </div>
              <div class="form-group text-left">
              <label for="exampleInputEmail12" class="mb-1 text-left">Contact Forward Email</label>
              <input type="email" id="contact_forward_email" class="form-control" name="contact_forward_email"
              value="{{ App\CoreSetting::where('name','contact_forward_email')->first()->value  }}">
              </div>
            <div class="form-group row mx-0">
              <div class="o-switch align-items-end w-100">
                <label for="exampleInputEmail1" class="mb-1 float-left mt-2">Force password change after one time password entered</label>
                <input class="checkbox success sr-only" name="force_password_change" id="checkbox4" type="checkbox" {{ App\CoreSetting::where('name','force_password_change')->first()->value ==1 ? 'checked' : '' }}>
                <label for="checkbox4" class="toggle rounded form-control-label float-right " aria-hidden="true" style="width: 78px;">
                  <span class="on">Yes
                  </span>
                  <span class="off">No</span>
                </label>
              </div>
            </div>
              <div class="row">
            <div class="col-xl-12 col-lg-12">
              <button data-toggle="modal" class="btn btn-secondary text-right font-weight-bold">Save</button>
            </div>
          </div>

          </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

@php
$path = public_path('images/emalogo-black-new.png');
$image = base64_encode(file_get_contents($path));

@endphp
<script>
   var checkExistingPassword = '{{ url("checkExistingPassword") }}';
   var checkCurrentPassword = '{{ url("checkCurrentPassword") }}';
   var uniqueuseremailData = '{{ url("uniqueuseremailData") }}';

</script>
<input type="hidden" id="base64_image" value="{{ $image }}">
