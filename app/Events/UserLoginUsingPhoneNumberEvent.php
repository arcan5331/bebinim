<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;

class UserLoginUsingPhoneNumberEvent
{
    use Dispatchable;

    public function __construct(public string $phone_number)
    {
    }
}

