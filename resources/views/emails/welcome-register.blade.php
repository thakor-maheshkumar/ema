@component('mail::message')
# Introduction
Hi {{$user->name}} Welcome to EMA Asthetics

Password is {{$user->password}}

@component('mail::button', ['url' => config('app.url') ])
Login
@endcomponent

Thanks,<br>
{{ config('app.url') }}
@endcomponent
