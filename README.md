# Laravel Json Exception Response #

[![Latest Stable Version](https://poser.pugx.org/laravel-soft/jer/version)](https://packagist.org/packages/laravel-soft/jer)
[![Build Status](https://travis-ci.org/thanh-taro/laravel-jer.svg?branch=master)](https://travis-ci.org/thanh-taro/laravel-jer)
[![Total Downloads](https://poser.pugx.org/laravel-soft/jer/downloads)](https://packagist.org/packages/laravel-soft/jer)
[![License](https://poser.pugx.org/laravel-soft/jer/license)](https://packagist.org/packages/laravel-soft/jer)

A laravel package for structing API exception response in JSON followed http://jsonapi.org/.

## Install


Via Composer

``` bash
$ composer require laravel-soft/jer
```

Once this has finished, you will need to add the service provider to the providers array in your `config/app.php` as follows:

``` php
'providers' => [
    // ...
    Laravel\JER\JERServiceProvider::class,
]
```

Then, publish the localization by running:

``` bash
php artisan vendor:publish
```


## Usage

In the `app\Exceptions\Handler.php`, let the class extends `Laravel\JER\ExceptionHandler`.
use Laravel\JER\ExceptionHandler;

``` php
class Handler extends ExceptionHandler
{
  // ...
}
```


## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
