<?php
namespace App\Mailing;

use App\Mailing\NotificationContract;
use Illuminate\Support\Facades\Mail;

class MackdownNotification implements NotificationContract
{
    public function sendRegistrationEmail($user){
        Mail::to($user->email)->send(new \App\Mail\SignUpEmail($user));
    }

    public function sendForgotPasswordEmail($user)
    {
        Mail::to($user->email)->send(new \App\Mail\ForgotPassword($user));
    }
}