<?php


namespace shayannosrat\PayPal\Traits;


use Illuminate\Support\Arr;
use RuntimeException;

trait PayPalRequest
{
    use PayPalHttpClient;
    use PayPalApi;

    public string $mode;
    protected string $accessToken;
    private array $config;
    protected string $currency;
    protected array $options;

    public function setApiCredentials(array $credentials): void
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

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function setRequestHeader(string $key, string $value): self
    {
        Arr::set($this->options, sprintf('headers.%s', $key), $value);

        return $this;
    }

    public function getRequestHeader(string $key): string
    {
        if (!Arr::has($this->options, sprintf('headers.%s', $key))) {
            throw new RuntimeException('Options header is not set.');
        }

        return Arr::get($this->options, sprintf('headers.%s', $key));
    }


    private function setConfig(array $_config = []): void
    {
        $apiConfig = function_exists('config') ? config('paypal') : $_config;
        $this->setApiCredentials($apiConfig);
    }

    private function setApiEnvironment(array $credentials): void
    {
        $this->mode = 'live';
        if (!empty($credentials['mode'])) {
            $this->setValidApiEnvironment($credentials['mode']);
        }
    }


    private function setValidApiEnvironment(string $mode): void
    {
        $this->mode = in_array($mode, ['sandbox', 'live']) ? $mode : 'live';
    }

    private function setApiProviderConfiguration(array $credentials): void
    {
        collect($credentials[$this->mode])->each(function (string $value, string $key) {
            $this->config[$key] = $value;
        });
        $this->paymentAction = Arr::get($credentials, 'payment_action');
        $this->locale = Arr::get($credentials, 'locale');
        $this->validateSsl = Arr::get($credentials, 'validate_ssl');
        $this->setOptions($credentials);
    }
}
