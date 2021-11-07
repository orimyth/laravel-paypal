<?php


namespace shayannosrat\PayPal\Traits;


use Illuminate\Support\Arr;
use RuntimeException;

trait PayPalRequest
{
    use PayPalHttpClient;
    use PayPalApi;

    public $mode;
    protected $accessToken;
    private $config;
    protected string $currency;
    protected $options;

    public function setApiCredentials(array $credentials)
    {
        if (empty($credentials)) {
            throw new RuntimeException('Empty configuration provided. Please provide valid configuration for Express Checkout API.');
        }

        $this->setApiEnvironment($credentials);
        $this->setApiProviderConfiguration($credentials);
        $this->setCurrency(Arr::get($credentials, 'currency'));
        $this->setHttpClientConfiguration();
    }

    public function setCurrency(string $currency = 'USD'): self
    {
        $allowedCurrencies = ['AUD', 'BRL', 'CAD', 'CZK', 'DKK', 'EUR', 'HKD', 'HUF', 'ILS', 'INR', 'JPY', 'MYR', 'MXN', 'NOK', 'NZD', 'PHP', 'PLN', 'GBP', 'SGD', 'SEK', 'CHF', 'TWD', 'THB', 'USD', 'RUB', 'CNY'];
        if (!in_array($currency, $allowedCurrencies)) {
            throw new RuntimeException('Currency is not supported by PayPal.');
        }
        $this->currency = $currency;

        return $this;
    }

    public function getCurrency()
    {
        return $this->currency;
    }

    public function setRequestHeader(string $key, $value): self
    {
        Arr::set($this->options, sprintf('headers.%s', $key), $value);

        return $this;
    }

    public function getRequestHeader(string $key)
    {
        if (!Arr::has($this->options, sprintf('headers.%s', $key))) {
            throw new RuntimeException('Options header is not set.');
        }

        return Arr::get($this->options, sprintf('headers.%s', $key));
    }


    private function setConfig(array $config = [])
    {
        $apiConfig = function_exists('config') ? config('paypal') : $config;
        $this->setApiCredentials($apiConfig);
    }

    private function setApiEnvironment($credentials)
    {
        $this->mode = 'live';
        if (!empty($credentials['mode'])) {
            $this->setValidApiEnvironment($credentials['mode']);
        }
    }


    private function setValidApiEnvironment($mode)
    {
        $this->mode = in_array($mode, ['sandbox', 'live']) ? $mode : 'live';
    }

    private function setApiProviderConfiguration($credentials)
    {
        collect($credentials[$this->mode])->map(function ($value, $key) {
            $this->config[$key] = $value;
        });

        $this->paymentAction = $credentials['payment_action'];

        $this->locale = $credentials['locale'];

        $this->validateSSL = $credentials['validate_ssl'];

        $this->setOptions($credentials);
//        collect($credentials[$this->mode])->map(function ($value, $key) {
//            $this->config[$key] = $value;
//        });
//        $this->paymentAction = Arr::get($credentials, 'payment_action');
//        $this->locale = Arr::get($credentials, 'locale');
//        $this->validateSsl = Arr::get($credentials, 'validate_ssl');
//        $this->setOptions($credentials);
    }
}
