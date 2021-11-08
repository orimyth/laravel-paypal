<?php

namespace shayannosrat\PayPal;

use shayannosrat\PayPal\Services\PayPalClient;

class PayPalFacadeAccessor
{
    public static PayPalFacadeAccessor|PayPalClient $provider;

    public static function getProvider(): PayPalFacadeAccessor|PayPalClient
    {
        return self::$provider;
    }

    public static function setProvider(): PayPalFacadeAccessor|PayPalClient
    {
        self::$provider = new PayPalClient();

        return self::getProvider();
    }
}
