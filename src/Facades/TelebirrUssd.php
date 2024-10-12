<?php

namespace Vptrading\TelebirrUssd\Facades;

use Illuminate\Support\Facades\Facade;

class TelebirrUssd extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'telebirr-ussd';
    }
}
