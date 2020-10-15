@component('mail::message')
# Hello,{{$user->nickname}}

You initiated a password change,
kindly click on the button below to verify your account

@component('mail::button', ['url' => $url])
    Change Password
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
