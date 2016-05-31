<?php

namespace LaLu\JER;

use GrahamCampbell\TestBench\AbstractPackageTestCase;
use LaLu\JER\Facades\JERFacade;
use Illuminate\Http\JsonResponse;
use Art4\JsonApiClient\Utils\Helper;

abstract class AbstractTestCase extends AbstractPackageTestCase
{
    /**
     * Get the service provider class.
     *
     * @param \Illuminate\Contracts\Foundation\Application $app
     *
     * @return string
     */
    protected function getServiceProviderClass($app)
    {
        return JERServiceProvider::class;
    }

    protected function getPackageAliases($app)
    {
        return [
            'JER' => JERFacade::class,
        ];
    }

    protected function getLanguage($lang = 'en')
    {
        return include __DIR__."/../src/resources/lang/$lang/messages.php";
    }

    protected function getHandler()
    {
        return $this->app->make(ExceptionHandler::class);
    }

    protected function assertJsonApi($response, $status, $exception, $expected, $headers = [])
    {
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertSame($status, $response->getStatusCode());
        $this->assertSame($exception, $response->exception);
        $headers = array_merge($headers, ['Content-Type' => 'application/vnd.api+json']);
        foreach ($headers as $key => $value) {
            $this->assertSame($value, $response->headers->get($key));
        }
        $this->assertTrue(Helper::isValid($response->getContent()));
        $this->assertJsonStringEqualsJsonString($expected, $response->getContent());
    }
}
