<?php


namespace shayannosrat\PayPal\Providers;


use Illuminate\Support\ServiceProvider;
use shayannosrat\PayPal\Services\PayPalClient;

class PayPalServiceProvider extends ServiceProvider
{
    protected bool $defer = false;

    /**
     * @psalm-suppress UndefinedFunction
     */
    public function boot(): void
    {
        $this->publishes([
            sprintf('%s/../../config/paypal.php', __DIR__) => config_path('paypal.php')
        ]);
    }

    public function register(): void
    {
        $this->registerPayPal();
        $this->mergeConfig();
    }

    private function registerPayPal(): void
    {
        $this->app->singleton('paypal_client', static function () {
            return new PayPalClient();
        });
    }

    private function mergeConfig(): void
    {
        $this->mergeConfigFrom(
            sprintf('%s/../../config/paypal.php', __DIR__), 'paypal'
        );
    }
}
