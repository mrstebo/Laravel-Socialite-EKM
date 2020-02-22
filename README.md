# Laravel-Socialite-EKM
EKM OAuth2 Provider for Laravel Socialite

[![Packagist](https://img.shields.io/packagist/v/mrstebo/laravel-socialite-ekm.svg?maxAge=2592000)](https://packagist.org/packages/mrstebo/laravel-socialite-ekm)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![StyleCI](https://styleci.io/repos/241214788/shield)](https://styleci.io/repos/241214788)

This package allows you to use Laravel Socialite using EKM.

## Installation

You can install the package via composer:

```bash
composer require mrstebo/laravel-socialite-ekm
```

---

**Note:** if you use Laravel 5.5+ you can skip service provider registration, because it should be auto discovered.

Then you should register service provider in your `config/app.php` file:

```php
'providers' => [
    // Other service providers
    
    Mrstebo\LaravelSocialiteEkm\Provider::class,

]
```

You will also need to add credentials for the OAuth application that you can get using your [EKM Partners](http://partners.ekm.net/) account. They should be placed in your `config/services.php` file. You may copy the example configuration below to get started:

```php
'ekm' => [
    'client_id' => env('EKM_CLIENT_ID'),
    'client_secret' => env('EKM_CLIENT_SECRET'),
    'redirect' => env('EKM_REDIRECT'),
],
```

## Basic usage

So now, you are ready to authenticate users! You will need two routes: one for redirecting the user to the OAuth provider, and another for receiving the callback from the provider after authentication. We will access Socialite using the Socialite facade:

```php
<?php

namespace App\Http\Controllers\Auth;

use Socialite;

class AuthController extends Controller
{
    /**
     * Redirect the user to the EKM authentication page.
     *
     * @return Response
     */
    public function redirectToProvider()
    {
        return Socialite::driver('ekm')->redirect();
    }

    /**
     * Obtain the user information from EKM.
     *
     * @return Response
     */
    public function handleProviderCallback()
    {
        $user = Socialite::driver('ekm')->user();

        // $user->token;
    }
}
```

Of course, you will need to define routes to your controller methods:

```php
Route::get('auth/ekm', 'Auth\AuthController@redirectToProvider');
Route::get('auth/ekm/callback', 'Auth\AuthController@handleProviderCallback');
```

The redirect method takes care of sending the user to the OAuth provider, while the user method will read the incoming request and retrieve the user's information from the provider.

EKM OAuth2 does not support scopes on request, all scopes are configured in OAuth application settings.

## Retrieving user details

Once you have a user instance, you can grab a few more details about the user:

```php
$user = Socialite::driver('ekm')->user();

// OAuth Two Providers
$token = $user->token;
$refreshToken = $user->refreshToken; // may not always be provided
$expiresIn = $user->expiresIn;

// EKM Specific Providers
$username = $user->user['sub'];
$serverId = $user->user['server_id'];
```
