<?php


namespace shayannosrat\PayPal\Tests;


trait MockResponsePayloads
{
    use Mocks\Responses\Orders;

    private function mockAccessTokenResponse()
    {
        return [
            'scope'         => 'some_scope',
            'access_token'  => 'some_access_token',
            'token_type'    => 'Bearer',
            'app_id'        => 'APP-80W284485P519543T',
            'expires_in'    => 32400,
            'nonce'         => 'some_nonce',
        ];
    }
}
