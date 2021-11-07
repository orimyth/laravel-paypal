<?php


namespace shayannosrat\PayPal\Tests\Feature;


use PHPUnit\Framework\TestCase;
use shayannosrat\PayPal\Services\PayPalClient;
use shayannosrat\PayPal\Tests\MockClientClasses;
use RuntimeException;

class AdapterConfigTest extends TestCase
{
    use MockClientClasses;

    protected $client;

    protected function setUp(): void
    {
        $this->client = new PayPalClient($this->getApiCredentials());
        parent::setUp();
    }

    /**
     * Tests behaviour when invalid credentials are provided
     * expects \RuntimeException
     * @test
     */
    public function testInvalidCredentials()
    {
        $this->expectException(RuntimeException::class);
        $this->client = new PayPalClient([]);
    }

    /**
     * Tests behaviour when valid credentials are provided
     * expects instance of \PayPal\Services\PayPalClient
     * @test
     */
    public function testValidCredentials()
    {
        $this->assertInstanceOf(PayPalClient::class, $this->client);
    }

    /**
     *
     * @test
     */
    public function testInvalidCredentialsThroughMethod()
    {
        $this->expectException(RuntimeException::class);
        $this->client->setApiCredentials([]);
    }

    /**
     *
     * @test
     */
    public function testValidCredentialsThrougMethod()
    {
        $this->client->setApiCredentials($this->getApiCredentials());
        $this->assertInstanceOf(PayPalClient::class, $this->client);
    }

    /**
     * @test
     */
    public function testInvalidCurrency()
    {
        $this->expectException(RuntimeException::class);
        $this->client->setCurrency('XYZ');
        $this->assertNotEquals('XYZ', $this->client->getCurrency());
    }

    /**
     * @test
     */
    public function testSetOptionsHeader()
    {
        $this->client->setRequestHeader('Prefer', 'return=representation');
        $this->assertNotEmpty($this->client->getRequestHeader('Prefer'));
        $this->assertEquals($this->client->getRequestHeader('Prefer'), 'return=representation');
    }

    /**
     * @test
     */
    public function testOptionsHeaderNotSet()
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionCode('0');
        $this->expectExceptionMessage('Options header is not set.');
        $this->client->getRequestHeader('Prefer');
    }
}
