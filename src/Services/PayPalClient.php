<?php

namespace shayannosrat\PayPal\Services;

use Exception;
use shayannosrat\PayPal\Traits\PayPalRequest;

class PayPalClient
{
    use PayPalRequest;

    public function __construct($config = '')
    {
        // Setting PayPal API Credentials
        if (is_array($config)) {
            $this->setConfig($config);
        }

        $this->httpBodyParam = 'form_params';

        $this->options = [];
        $this->options['headers'] = [
            'Accept'            => 'application/json',
            'Accept-Language'   => $this->locale,
        ];
    }

    protected function setOptions($credentials)
    {
        $this->config['api_url'] = 'https://api.paypal.com';
        $this->config['gateway_url'] = 'https://www.paypal.com';
        $this->config['ipn_url'] = 'https://ipnpb.paypal.com/cgi-bin/wbscr';

        if ($this->mode === 'sandbox') {
            $this->config['api_url'] = 'https://api.sandbox.paypal.com';
            $this->config['gateway_url'] = 'https://www.sandbox.paypal.com';
            $this->config['ipn_url'] = 'https://ipnpb.sandbox.paypal.com/cgi-bin/wbscr';
        }
        $this->config['payment_action'] = $credentials['payment_action'];
        $this->config['notify_url'] = $credentials['notify_url'];
        $this->config['locale'] = $credentials['locale'];
    }
}
