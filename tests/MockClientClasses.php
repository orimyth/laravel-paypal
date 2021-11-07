<?php


namespace shayannosrat\PayPal\Tests;


use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use shayannosrat\PayPal\Services\PayPalClient;

trait MockClientClasses
{
    private function mockHttpClient($response)
    {
        return new Client([
            'handler' => HandlerStack::create(new MockHandler([
                new Response(200, [], ($response === false ? '' : json_encode($response)))
            ]))
        ]);
    }

    private function mockHttpRequest($expectedResponse, $expectedEndpoint, $expectedParams, $expectedMethod = 'post')
    {
        $setMethodName = 'setMethods';
        if (function_exists('onlyMethods')) {
            $setMethodName = 'onlyMethods';
        }

        $mockResponse = $this->getMockBuilder(ResponseInterface::class)
            ->getMock();
        $mockResponse->expects($this->exactly(1))
            ->method('getBody')
            ->willReturn($expectedResponse);

        $mockHttpClient = $this->getMockBuilder(Client::class)
            ->{$setMethodName}([$expectedMethod])
            ->getMock();
        $mockHttpClient->expects($this->once())
            ->method($expectedMethod)
            ->with($expectedEndpoint, $expectedParams)
            ->willReturn($mockResponse);

        return $mockHttpClient;
    }

    private function mockClient($expectedResponse, $expectedMethod, $token = false)
    {
        $setMethodName = 'setMethods';
        if (function_exists('onlyMethods')) {
            $setMethodName = 'onlyMethods';
        }

        $methods = [$expectedMethod, 'setApiCredentials'];
        if ($token) {
            $methods[] = 'getAccessToken';
        }

        $mockClient = $this->getMockBuilder(PayPalClient::class)
            ->{$setMethodName}($methods)
            ->getMock();

        if ($token) {
            $mockClient->expects($this->exactly(1))
                ->method('getAccessToken');
        }

        $mockClient->expects($this->exactly(1))
            ->method('setApiCredentials');

        $mockClient->expects($this->exactly(1))
            ->method($expectedMethod)
            ->willReturn($expectedResponse);

        return $mockClient;
    }

    private function getMockCredentials(): array
    {
        return [
            'mode' => 'sandbox',
            'sandbox' => [
                'client_id' => 'some-client-id',
                'client_secret' => 'some-access-token',
                'app_id' => '',
            ],
            'payment_action' => 'Sale',
            'currency' => 'USD',
            'notify_url' => '',
            'locale' => 'en_US',
            'validate_ssl' => true,
        ];
    }

    private function getApiCredentials(): array
    {
        return [
            'mode' => 'sandbox',
            'sandbox' => [
                'client_id' => 'AbJgVQM6g57qPrXimGkBz1UaBOXn1dKLSdUj7BgiB3JhzJRCapzCnkPq6ycOOmgXHtnDZcjwLMJ2IdAI',
                'client_secret' => 'EPd_XBNkfhU3-MlSw6gpa6EJj9x8QBdsC3o77jZZWjcFy_hrjR4kzBP8QN3MPPH4g52U_acG4-ogWUxI',
                'app_id' => '',
            ],
            'payment_action' => 'Sale',
            'currency' => 'USD',
            'notify_url' => '',
            'locale' => 'en_US',
            'validate_ssl' => true,
        ];
    }
}
