<?php


namespace shayannosrat\PayPal\Traits;


trait PayPalApi
{

    public function getAccessToken()
    {
        $this->apiEndPoint = 'v1/oauth2/token';
        $this->apiUrl = collect([$this->config['api_url'], $this->apiEndPoint])->implode('/');

        $this->options['auth'] = [$this->config['client_id'], $this->config['client_secret']];
        $this->options[$this->httpBodyParam] = [
            'grant_type' => 'client_credentials',
        ];

        $response = $this->doPayPalRequest();

        if (isset($response['access_token'])) {
            $this->setAccessToken($response);

            $this->setPayPalAppId($response);
        }

        return $response;
    }

    public function setAccessToken($response)
    {
        $this->accessToken = $response['access_token'];

        $this->options['headers']['Authorization'] = "{$response['token_type']} {$this->accessToken}";
    }

    private function setPayPalAppId($response)
    {
        if (empty($this->config['app_id'])) {
            $this->config['app_id'] = $response['app_id'];
        }
    }
}
