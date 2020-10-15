@component('mail::message')
# Hello,{{$user->nickname}}

We are happy to have you on board.
Kindly click on the button below to verify your account

@component('mail::button', ['url' => $url,'color' => 'success'])
Verify Account
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
