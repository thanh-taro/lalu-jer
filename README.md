# Json Exception Response

[![Latest Stable Version](https://img.shields.io/packagist/v/lalu/jer.svg)](https://packagist.org/packages/lalu/jer) [![Build Status](https://travis-ci.org/thanh-taro/lalu-jer.svg?branch=master)](https://travis-ci.org/thanh-taro/lalu-jer) [![Coverage Status](https://coveralls.io/repos/github/thanh-taro/lalu-jer/badge.svg?brand=master)](https://coveralls.io/github/thanh-taro/lalu-jer?brand=master) [![Total Downloads](https://poser.pugx.org/lalu/jer/downloads)](https://packagist.org/packages/lalu/jer) [![License](https://poser.pugx.org/lalu/jer/license)](https://packagist.org/packages/lalu/jer)

A Laravel/Lumen package for structing API exception response in JSON followed <http://jsonapi.org/>.

## Install

Via Composer

```bash
$ composer require lalu/jer
```

### Laravel

Once this has finished, you will need to add the service provider to the providers array in your `config/app.php` as follows:

```php
'providers' => [
    // ...
    LaLu\JER\JERServiceProvider::class,
]
```

If you want to use alias, also in the app.php config file, under the aliases array, you may want to add facades as follows:

```php
'aliases' => [
    // ...
    'JER' => LaLu\JER\Facades\JERFacade::class
]
```

Then, publish the localization by running:

```bash
php artisan vendor:publish
```

### Lumen

Open `bootstrap/app.php` and add this line:

```php
$app->register(LaLu\JER\JERServiceProvider::class);
```

If you want to use alias, also in your bootstrap/app.php, make sure you have uncommented

```php
$app->withFacades();
```

Then, add this line:

```php
class_alias(LaLu\JER\Facades\JERFacade::class, 'JER');
```

For localization, you have to create `messages.php` under `resources\lang\vendor\lalu-jer\en` (default is en - English). Some built-in message ids are in [here](https://github.com/thanh-taro/lalu-jer/blob/master/src/resources/lang/en/messages.php)


## Usage

In the `app\Exceptions\Handler.php`, let the class extends `LaLu\JER\ExceptionHandler`.

```php
use LaLu\JER\ExceptionHandler;

class Handler extends ExceptionHandler
{
    // ...
}
```

Then all of Exceptions will handle by this package.

You can also use `abort` or `throw new \Exception\You\Want()` to raise and response exception.

## Advanced

Add `meta` to response json? It's not a big deal.

```php
use LaLu\JER\ExceptionHandler;

class Handler extends ExceptionHandler
{
    public $meta = [
        'meta_field_1' => 'meta_value_1',
        // ...
    ];

    // ...
}
```


Or


```php
use LaLu\JER\ExceptionHandler;

class Handler extends ExceptionHandler
{
    public function beforeRender($request, Exception $exception)
    {
        $this->meta = [
            'meta_field_1' => 'meta_value_1',
            // ...
        ];
    }

    // ...
}
```

With `beforeRender` which will be raised before the `render` method, you can do more logics to set meta, headers and so on.


If you want to custom the response of some Exception classes, just override the `getExceptionError`.

```php
use LaLu\JER\ExceptionHandler;
use LaLu\JER\Error;

class Handler extends ExceptionHandler
{
    // ...

    /**
     * Get exception jsonapi data.
     *
     * @param \Exception $exception
     *
     * @return array
     */
    protected function getExceptionError(Exception $exception)
    {
        if ($exception instanceof \Exception\You\Want) {
            // status must be an integer and is a HTTP error status code
            $status = 400;
            // headers must be an array of key value
            $headers = [];
            $content = [
                'title' => 'Your exception custom title',
                'detail' => 'Your exception custom detail',
            ];
            // error can be an instance/array items of \LaLu\JER\Error or array of error array
            $error = new Error(['version' => $this->jsonapiVersion], $content);
            $error->status = '400';
            // ...
            return [$status, $error, $headers];
        } elseif ($exception instanceof \Other\Exception) {
            return [400, [['title' => 'Your request is bad request']], []];
        } else {
            return parent::getExceptionError($exception);
        }
    }
}
```

If you want to custom error json response, feel free to use this function:

```php
$option = [
    'version' => '1.0.0', // JSONAPI specification version
    'status' => 400, // HTTP status code
    'headers' => ['My-Custom-Header' => 'Value'], // Response headers,
    'exception' => new \Exception(), // Exception
];
$attributes = [
    'meta' => [
        'apiVersion' => '1.0.0',
    ],
    'errors' => new Error(['version' => '1.0.0'], ['title' => 'My custom error', 'detail' => 'This is an error response']), // Error content
];
$response = \JER::getResponse($option, $attributes);
```

Note that `JER` is an alias, if you didn't config for alias, you may use

```php
(new \LaLu\JER\JsonExceptionResponse())->getResponse($option, $attributes);
```


## License

The MIT License (MIT).
