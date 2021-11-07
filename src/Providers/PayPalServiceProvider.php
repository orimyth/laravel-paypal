<?php


namespace shayannosrat\PayPal\Providers;


use Illuminate\Support\ServiceProvider;
use shayannosrat\PayPal\Services\PayPalClient;

class PayPalServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            sprintf('%s/../../config/paypal.php', __DIR__) => config_path('paypal.php')
        ]);
    }

    public function register()
    {
        $this->registerPayPal();
        $this->mergeConfig();
    }

    private function registerPayPal()
    {
        $this->app->singleton('paypal_client', static function () {
            return new PayPalClient();
        });
    }

    private function mergeConfig()
    {
        $this->mergeConfigFrom(
            sprintf('%s/../../config/paypal.php', __DIR__), 'paypal'
        );
    }
}
