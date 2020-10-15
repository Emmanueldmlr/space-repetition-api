<?php
namespace App\Mailing;
use App\Mailing\NotificationContract;
use App\Notifications\SignUpNotification;

class EmailNotification implements NotificationContract
{
    public function sendRegistrationEmail($user){
        $user->notify(new SignUpNotification($user));
    }

    public function sendForgotPasswordEmail($user){
        //$user->notify(new SignUpNotification($user));
    }
}