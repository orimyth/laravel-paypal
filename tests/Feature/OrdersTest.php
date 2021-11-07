<?php


namespace shayannosrat\PayPal\Tests\Feature;


use PHPUnit\Framework\TestCase;
use shayannosrat\PayPal\Services\PayPalClient;
use shayannosrat\PayPal\Tests\MockClientClasses;
use shayannosrat\PayPal\Tests\MockRequestPayloads;
use shayannosrat\PayPal\Tests\MockResponsePayloads;

class OrdersTest extends TestCase
{
    use MockClientClasses;
    use MockRequestPayloads;
    use MockResponsePayloads;

    protected static $accessToken = '';
    protected $client;

    public function setUp(): void
    {
        $this->client = new PayPalClient($this->getApiCredentials());

        parent::setUp();
    }

    public function testOrderCreation(){
        $this->client->setAccessToken([
            'access_token'  => self::$accessToken,
            'token_type'    => 'Bearer',
        ]);

        $this->client->setClient(
            $this->mockHttpClient(
                $this->mockCreateOrdersResponse()
            )
        );

        $filters = $this->createOrderParams();

        $response = $this->client->createOrder($filters);

        $this->assertArrayHasKey('status', $response);
        $this->assertArrayHasKey('id', $response);
        $this->assertArrayHasKey('links', $response);
    }
}
