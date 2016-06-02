<?php

namespace LaLu\JER;

use Exception;
use JER;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Auth\Access\AuthorizationException;

class ExceptionHandlerTest extends AbstractTestCase
{
    public function testBasicRender()
    {
        $lang = $this->getLanguage('en');
        $handler = $this->getHandler();

        $response = $handler->render($this->app->request, $exception = new Exception('Foo'));
        $this->assertJsonApi($response, 500, $exception, '{"jsonapi":{"version":"1.0"},"errors":[{"status":"500","title":"'.$lang[$response->getStatusCode().'.title'].'","detail":"Foo"}]}');

        $response = $handler->render($this->app->request, $exception = new Exception('Bar', 403));
        $this->assertJsonApi($response, 403, $exception, '{"jsonapi":{"version":"1.0"},"errors":[{"status":"403","title":"'.$lang[$response->getStatusCode().'.title'].'","detail":"Bar"}]}');

        $response = $handler->render($this->app->request, $exception = new TokenMismatchException());
        $this->assertJsonApi($response, 406, $exception, '{"jsonapi":{"version":"1.0"},"errors":[{"status":"406","title":"'.$lang['token_mismatch.title'].'","detail":"'.$lang['token_mismatch.detail'].'"}]}');

        $response = $handler->render($this->app->request, $exception = new AuthorizationException());
        $this->assertJsonApi($response, 401, $exception, '{"jsonapi":{"version":"1.0"},"errors":[{"status":"401","title":"'.$lang['authorization_error.title'].'","detail":"'.$lang['authorization_error.detail'].'"}]}');
    }

    public function testRenderWithMeta()
    {
        $lang = $this->getLanguage('en');
        $handler = $this->getHandler();
        $handler->meta = ['version' => '1.0'];
        $response = $handler->render($this->app->request, $exception = new Exception('Foo'));
        $this->assertJsonApi($response, 500, $exception, '{"jsonapi":{"version":"1.0"},"meta":{"version": "1.0"},"errors":[{"status":"500","title":"'.$lang[$response->getStatusCode().'.title'].'","detail":"Foo"}]}');
    }

    public function testRenderWithHeaders()
    {
        $lang = $this->getLanguage('en');
        $handler = $this->getHandler();
        $handler->headers = ['Application' => 'phpunit'];
        $response = $handler->render($this->app->request, $exception = new Exception('Foo'));
        $this->assertJsonApi($response, 500, $exception, '{"jsonapi":{"version":"1.0"},"errors":[{"status":"500","title":"'.$lang[$response->getStatusCode().'.title'].'","detail":"Foo"}]}', ['Application' => 'phpunit']);
    }

    public function testCustomResponse()
    {
        $error = new Error(['version' => '1.0']);
        $error->title = 'Error';
        $exception = new Exception('Foo');
        $response = JER::getResponse(['exception' => $exception], ['errors' => $error]);
        $this->assertJsonApi($response, 500, $exception, '{"jsonapi":{"version":"1.0"},"errors":[{"title":"Error"}]}');
    }

    public function testCustomResponseWithArray()
    {
        $error = [
            [
                'title' => 'Error',
            ],
        ];
        $exception = new Exception('Foo');
        $response = JER::getResponse(['exception' => $exception], ['errors' => $error]);
        $this->assertJsonApi($response, 500, $exception, '{"jsonapi":{"version":"1.0"},"errors":[{"title":"Error"}]}');
    }

    public function testCustomResponseWithArrayObject()
    {
        $errors = [];
        foreach (range(0, 2) as $index) {
            $errors[] = new Error(['version' => '1.0'], ['title' => 'Error '.$index]);
        }
        $response = JER::getResponse([], ['errors' => $errors]);
        $this->assertJsonApi($response, 500, null, '{"jsonapi":{"version":"1.0"},"errors":[{"title":"Error 0"},{"title":"Error 1"},{"title":"Error 2"}]}');
    }
}
