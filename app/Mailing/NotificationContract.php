<?php
namespace App\Mailing;

interface NotificationContract
{
    public function sendRegistrationEmail($user);
}