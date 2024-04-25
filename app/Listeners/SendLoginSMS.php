<?php

namespace App\Listeners;

use App\Events\UserLoginUsingPhoneNumberEvent;
use App\Models\TempPassword;
use Carbon\Carbon;

//use IPPanel\Client;

class SendLoginSMS
{
    public function __construct()
    {
    }

    public function handle(UserLoginUsingPhoneNumberEvent $event): void
    {
//        $code = str_pad(rand(0,9999),4,'0',STR_PAD_LEFT);
        TempPassword::create([
            'phone_number' => $event->phone_number,
            'code' => '1234',
            'expire_at' => Carbon::now()->addMinutes(5)
        ]);
//        $client = new Client(env('IPPANEL_SMS_API_KEY'));
//        $client->sendPattern(
//            env('IPPANEL_LOGIN_SMS_PATTERN_ID'),
//            env('IPPANEL_SMS_NUMBER'),
//            $event->phone_number,
//            ['verification-code' => $code]
//        );

    }
}
