<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="format-detection" content="telephone=no">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'EMA Aesthetics') }} - @yield('title')</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <!-- Styles -->

    @include('includes.head')
    @yield('cssdependencies')
</head>
<body>
    <div id="app">
        @auth
        <auto-logout></auto-logout>
        @endauth

        <!-- Page Header -->
		@include('includes.header')

        <main>
            @yield('content')
        </main>
    </div>
    <script>
        window.login_activity = "{!! Session::get('inactivity_session_time') !!}"
    </script>
    <script src="{{ asset('js/app.js') }}"></script>
    @include('includes.footer')
     <!-- Scripts -->
    @yield('jsdependencies')
    <script>
        // $(document).ajaxStart(function(){
        //     $('.loader_container').show();
        // });

        // $(document).ajaxComplete(function(){
        //     $('.loader_container').hide();
        // });

        $(document).ajaxError(function myErrorHandler(event, xhr, ajaxOptions, thrownError) {
            if(xhr.responseJSON !== 'Internal Server Error' && xhr.responseJSON){
                var data = xhr.responseJSON.data;
                var message = xhr.responseJSON.message;
                if(data=="Unauthenticated." || data=="CSRF token mismatch."){
                    toastr['error'](message);
                    setTimeout(function(){ window.location.reload(); }, 2000);
                }
            }
        });
        /* $(document).ready(function() {
            $(".check-remote").on("mouseenter", function() {
                $(".loader_container").css("visibility" , "hidden");
            });
        });*/
     </script>
</body>
</html>
