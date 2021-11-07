<?php

namespace shayannosrat\PayPal;

use shayannosrat\PayPal\Services\PayPalClient;

class PayPalFacadeAccessor
{
    public static $provider;

    public static function getProvider(): self
    {
        return self::$provider;
    }

    public static function setProvider(): self
    {
        self::$provider = new PayPalClient();

        return self::getProvider();
    }
}
