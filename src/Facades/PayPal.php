<?php

namespace shayannosrat\PayPal\Facades;

use Illuminate\Support\Facades\Facade;

class PayPal extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'shayannosrat\PayPalFacadeAccessor';
    }
}
