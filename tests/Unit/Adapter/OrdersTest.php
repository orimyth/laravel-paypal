<?php


namespace shayannosrat\PayPal\Tests\Unit\Adapter;


use PHPUnit\Framework\TestCase;
use shayannosrat\PayPal\Tests\MockClientClasses;
use shayannosrat\PayPal\Tests\MockRequestPayloads;
use shayannosrat\PayPal\Tests\MockResponsePayloads;

class OrdersTest extends TestCase
{
    use MockClientClasses;
    use MockRequestPayloads;
    use MockResponsePayloads;

    public function testOrderCreation()
    {
        $expectedResponse = $this->mockCreateOrdersResponse();
        $expectedParams = $this->createOrderParams();
        $expectedMethod = 'createOrder';
        $mockClient = $this->mockClient($expectedResponse, $expectedMethod, true);
        $mockClient->setApiCredentials($this->getMockCredentials());
        $mockClient->getAccessToken();

        $this->assertEquals($expectedResponse, $mockClient->{$expectedMethod}($expectedParams));
    }
}
