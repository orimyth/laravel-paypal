<?php


namespace shayannosrat\PayPal\Traits;

use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\ClientException as HttpClientException;
use RuntimeException;

trait PayPalHttpClient
{
    private $client;
    private $httpClientConfiguration;
    private $apiUrl;
    private $apiEndPoint;
    private $notifyUrl;
    private $httpBodyParam;
    private $paymentAction;
    private $locale;
    private $validateSsl;
    protected $verb = 'post';

    protected function setCurlConstants(): void
    {
        $constants = [
            'CURLOPT_SSLVERSION' => 32,
            'CURL_SSLVERSION_TLSv1_2' => 6,
            'CURLOPT_SSL_VERIFYPEER' => 64,
            'CURLOPT_SSLCERT' => 10025,
        ];

        foreach ($constants as $key => $value) {
            if (!defined($key)) {
                define($key, $value);
            }
        }
    }

    public function setClient(HttpClient $client = null): void
    {
        if ($client instanceof HttpClient) {
            $this->client = $client;

            return;
        }

        $this->client = new HttpClient([
            'curl' => $this->httpClientConfig,
        ]);
    }

    protected function setHttpClientConfiguration(): void
    {
        $this->setCurlConstants();

        $this->httpClientConfig = [
            CURLOPT_SSLVERSION => CURL_SSLVERSION_TLSv1_2,
            CURLOPT_SSL_VERIFYPEER => $this->validateSsl,
        ];
        $this->setClient();
        $this->setDefaultValues();
        $this->notifyUrl = $this->config['notify_url'];
    }

    private function setDefaultValues(): void
    {
        $paymentAction = empty($this->paymentAction) ? 'Sale' : $this->paymentAction;
        $this->paymentAction = $paymentAction;

        $locale = empty($this->locale) ? 'en_US' : $this->locale;
        $this->locale = $locale;

        $validateSsl = empty($this->validateSsl) ? true : $this->validateSsl;
        $this->validateSsl = $validateSsl;
    }

    private function makeHttpRequest()
    {
        try {
            return $this->client->{$this->verb}(
                $this->apiUrl,
                $this->options
            )->getBody();
        } catch (HttpClientException $e) {
            throw new RuntimeException($e->getRequest()->getBody() . ' ' . $e->getResponse()->getBody());
        }
    }

    private function doPayPalRequest(bool $decode = true): array
    {
        try {
            $response = $this->makeHttpRequest();

            return ($decode === false) ? $response->getContents() : json_decode($response, true);
        } catch (RuntimeException $t) {
            $message = collect($t->getMessage())->implode('\n');
        }

        return [
            'type' => 'error',
            'message' => $message,
        ];
    }
}
