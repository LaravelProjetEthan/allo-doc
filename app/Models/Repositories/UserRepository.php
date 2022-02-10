<?php

namespace App\Models\Repositories;

use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use App\Models\User;
use App\Mail\UserRegisterNotification;

class UserRepository
{
    public static function sendEmailInscription(User $user)
    {
        Mail::to($user->email)
            ->send(new UserRegisterNotification($user));
    }
}
