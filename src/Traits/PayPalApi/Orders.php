<?php


namespace shayannosrat\PayPal\Traits\PayPalApi;


use Psr\Http\Message\StreamInterface;
use Throwable;

trait Orders
{
    /**
     * creates an order
     *
     * @param array $data
     * @return array|StreamInterface|string
     * @throws Throwable
     * @see https://developer.paypal.com/docs/api/orders/v2/#orders_create
     */
    public function createOrder(array $data): array|StreamInterface|string
    {
        $this->apiEndPoint = 'v2/checkout/orders';
        $this->apiUrl = collect([$this->config['api_url'], $this->apiEndPoint])->implode('/');
        $this->options['json'] = (object)$data;
        $this->verb = 'post';

        return $this->doPayPalRequest();
    }
}
