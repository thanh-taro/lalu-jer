<?php

namespace LaLu\JER;

use Exception;
use Illuminate\Http\JsonResponse;
use Art4\JsonApiClient\Utils\Helper;

class JsonExceptionResponseTest extends \PHPUnit_Framework_TestCase
{
    public function testBasic()
    {
        $this->assertClassHasAttribute('version', JsonExceptionResponse::class);
        $this->assertClassHasAttribute('attributes', JsonExceptionResponse::class);
        $this->assertClassHasAttribute('exception', JsonExceptionResponse::class);
        $instance = new JsonExceptionResponse(['version' => '1.0']);
        $this->assertEquals(['meta', 'errors'], $instance->getJsonStruct());
        $instance->setVersion('2.0.0');
        $this->assertEquals(false, $instance->getJsonStruct());
    }

    public function testGetResponse()
    {
        $error = new Error(['version' => '1.0']);
        $error->title = 'Error title';
        $error->detail = 'This is an error';
        $exception = new Exception('Error');
        $option = [
            'version' => '1.0',
            'exception' => $exception,
            'status' => '500',
            'headers' => ['Custom' => 'Header'],
        ];
        $attributes = [
            'errors' => $error,
            'meta' => ['version' => '1.0'],
        ];
        $jer = new JsonExceptionResponse($option, $attributes);
        $response = $jer->getResponse();
        $this->assertJsonApi($response, 500, $exception, '{"jsonapi":{"version":"1.0"},"meta":{"version":"1.0"},"errors":[{"title":"Error title","detail":"This is an error"}]}', ['Custom' => 'Header']);
        $jer = new JsonExceptionResponse($option, $attributes);
        $response = $jer->getResponse(['version' => '2.0.0']);
        $this->assertJsonApi($response, 500, $exception, '{"jsonapi":{"version":"2.0.0"},"meta":{"version":"1.0"},"errors":[{"title":"Error title","detail":"This is an error"}]}', ['Custom' => 'Header']);
        $jer = new JsonExceptionResponse($option, $attributes);
        $response = $jer->getResponse(['status' => 400]);
        $this->assertJsonApi($response, 400, $exception, '{"jsonapi":{"version":"1.0"},"meta":{"version":"1.0"},"errors":[{"title":"Error title","detail":"This is an error"}]}', ['Custom' => 'Header']);
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
