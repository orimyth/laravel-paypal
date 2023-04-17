NOT ACTIVELY MAINTAINED
# Laravel PayPal
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Tests](https://github.com/shayannosrat/laravel-paypal/actions/workflows/php.yml/badge.svg)](https://github.com/shayannosrat/laravel-paypal/actions/workflows/php.yml)
[![Type Coverage](https://shepherd.dev/github/shayannosrat/laravel-paypal/coverage.svg)](https://shepherd.dev/github/shayannosrat/laravel-paypal)

This package contains some core code from **srmklive/laravel-paypal** but improves and updates this for easier usage especially in e-commerce projects.
- [Installation](#installation)
- [Publishing Assets](#publishing-assets)
- [Configuration](#configuration)
- [Support](#support)
  
<a name="installation"></a>
## Installation (works soon if 1.0 released)
```shell
composer require shayannosrat/laravel-paypal
```

<a name="publishing-assets"></a>
## Publishing Assets
You can publish the assets with the following command

```shell
php artisan vendor:publish --provider "shayannosrat\PayPal\Providers\PayPalServiceProvider"
```

<a name="configuration"></a>
## Configuration
Add the following to your **.env** file

```dotenv
# PayPal API Mode
# values: sandbox or live (default: live)
PAYPAL_MODE=

# PayPal sandbox API Credentials
PAYPAL_SANDBOX_CLIENT_ID=
PAYPAL_SANDBOX_CLIENT_SECRET=

#PayPal live API Credentials
PAYPAL_LIVE_CLIENT_ID=
PAYPAL_LIVE_CLIENT_SECRET= 
```

<a name="support"></a>
## Support

This version supports Laravel 7 or greater.

In case of any issues, kindly create one on the [Issues](https://github.com/shayannosrat/laravel-paypal/issues) section.

 
