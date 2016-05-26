# Laravel Json Exception Response #

[![Latest Stable Version](https://poser.pugx.org/laravel-soft/jer/v/stable)](https://packagist.org/packages/laravel-soft/jer)
[![Build Status](https://travis-ci.org/thanh-taro/laravel-jer.svg?branch=master)](https://travis-ci.org/thanh-taro/laravel-jer)
[![Coverage Status](https://coveralls.io/repos/github/thanh-taro/laravel-jer/badge.svg?brand=master)](https://coveralls.io/github/thanh-taro/laravel-jer?brand=master)
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
    LaravelSoft\JER\JERServiceProvider::class,
]
```

Then, publish the localization by running:

``` bash
php artisan vendor:publish
```


## Usage

In the `app\Exceptions\Handler.php`, let the class extends `LaravelSoft\JER\ExceptionHandler`.

``` php
use LaravelSoft\JER\ExceptionHandler;

class Handler extends ExceptionHandler
{
  // ...
}
```


## License

The MIT License (MIT).
