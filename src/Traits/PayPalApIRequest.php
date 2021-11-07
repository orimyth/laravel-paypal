<?php


namespace shayannosrat\PayPal\Traits;


use Illuminate\Support\Arr;
use RuntimeException;

trait PayPalApIRequest
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
        throw_if(
            empty($credentials),
            RuntimeException::class,
            'Empty configuration provided. Please provide valid configurations for Express Checkout API.'
        );

        $this->setApiCredentials($credentials);
        $this->setApiProviderConfiguration($credentials);
        $this->setCurrency(Arr::get($credentials, 'currency'));
        $this->setHttpClientConfiguration();
    }

    public function setCurrency(string $currency = 'USD'): self
    {
        $allowedCurrencies = ['AUD', 'BRL', 'CAD', 'CZK', 'DKK', 'EUR', 'HKD', 'HUF', 'ILS', 'INR', 'JPY', 'MYR', 'MXN', 'NOK', 'NZD', 'PHP', 'PLN', 'GBP', 'SGD', 'SEK', 'CHF', 'TWD', 'THB', 'USD', 'RUB', 'CNY'];
        throw_unless(
            Arr::has($allowedCurrencies, $currency),
            RuntimeException::class,
            'Currency is not supported by PayPal.'
        );
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
        throw_unless(
            Arr::exists($this->options, sprintf('headers.%s', $key)),
            RuntimeException::class,
            'Options header is not set'
        );

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
        $this->mode = Arr::has(['sandbox', 'live'], $mode) ? $mode : 'live';
    }

    private function setApiProviderConfiguration($credentials)
    {
        collect($credentials[$this->mode])->each(function ($value, $key) {
            $this->config[$key] = $value;
        });
        $this->paymentAction = Arr::get($credentials, 'payment_action');
        $this->locale = Arr::get($credentials, 'locale');
        $this->validateSsl = Arr::get($credentials, 'validate_ssl');
        $this->setOptions($credentials);
    }
}
