<?php

namespace LaLu\JER;

use Exception;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Console\Application as ConsoleApplication;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;

trait ExceptionHandlerTrait
{
    protected static $HTTP_STATUS_CODES = [
        400, 401, 402, 403, 404, 405, 406, 407, 408, 409, 410, 411, 412, 413, 414, 415, 416, 417,
        418, 422, 423, 424, 425, 426, 449, 450, 500, 501, 502, 503, 504, 505, 506, 507, 509, 510,
    ];

    public $jsonapiVersion = '1.0.0';
    public $meta;
    public $headers = [];

    /**
     * The log implementation.
     *
     * @var \Psr\Log\LoggerInterface
     */
    protected $log;

    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [];

    /**
     * Get log instance.
     *
     * @return \Psr\Log\LoggerInterface
     */
    protected function getLog()
    {
        if ($this->log === null) {
            $this->log = app('Psr\Log\LoggerInterface');
        }

        return $this->log;
    }

    /**
     * Determine if the exception should be reported.
     *
     * @param \Exception $e
     *
     * @return bool
     */
    public function shouldReport(Exception $e)
    {
        return !$this->shouldntReport($e);
    }

    /**
     * Determine if the exception is in the "do not report" list.
     *
     * @param \Exception $e
     *
     * @return bool
     */
    protected function shouldntReport(Exception $e)
    {
        foreach ($this->dontReport as $type) {
            if ($e instanceof $type) {
                return true;
            }
        }

        return false;
    }

    /**
     * Before rendering the exception into a response.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Exception               $exception
     */
    public function beforeRender($request, Exception $exception)
    {
        //
    }

    /**
     * Render an exception into a response.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Exception               $exception
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function render($request, Exception $exception)
    {
        // raises before render
        $this->beforeRender($request, $exception);
        // get exception response data
        list($status, $error, $headers) = $this->getExceptionError($exception);

        return $this->makeResponse($exception, $error, $status);
    }

    /**
     * Report or log an exception.
     *
     * @param \Exception $e
     */
    public function report(Exception $e)
    {
        if ($this->shouldReport($e)) {
            $this->getLog()->error($e);
        }
    }

    /**
     * Render an exception to the console.
     *
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @param \Exception                                        $e
     */
    public function renderForConsole($output, Exception $e)
    {
        (new ConsoleApplication())->renderException($e, $output);
    }

    /**
     * Get exception jsonapi data.
     *
     * @param \Exception $exception
     *
     * @return array
     */
    protected function getExceptionError(Exception $exception)
    {
        $status = 500;
        $headers = [];
        $error = null;
        if ($exception instanceof TokenMismatchException) {
            $status = 406;
            $error = new Error(['version' => $this->jsonapiVersion], [
                'status' => "$status",
                'title' => $this->trans('lalu-jer::messages.token_mismatch.title'),
                'detail' => $this->trans('lalu-jer::messages.token_mismatch.detail'),
            ]);
        } elseif ($exception instanceof ValidationException) {
            $status = 406;
            $error = [];
            $messages = $exception->validator->messages();
            foreach ($messages->toArray() as $field => $messageArr) {
                foreach ($messageArr as $message) {
                    $error[] = new Error(['version' => $this->jsonapiVersion], [
                        'title' => $this->trans('lalu-jer::messages.validation_error.title'),
                        'detail' => $message,
                        'source' => new Source(['version' => $this->jsonapiVersion], [
                            'pointer' => $field,
                        ]),
                    ]);
                }
            }
        } elseif ($exception instanceof AuthorizationException) {
            $status = 401;
            $error = new Error(['version' => $this->jsonapiVersion], [
                'status' => "$status",
                'title' => $this->trans('lalu-jer::messages.authorization_error.title'),
                'detail' => $this->trans('lalu-jer::messages.authorization_error.detail'),
            ]);
        } else {
            $status = method_exists($exception, 'getStatusCode') ? $exception->getStatusCode() : (method_exists($exception, 'getCode') ? $exception->getCode() : 500);
            if (!in_array($status, JsonExceptionResponse::$HTTP_STATUS_CODES)) {
                $status = 500;
            }
            $message = $exception->getMessage();
            $error = new Error(['version' => $this->jsonapiVersion], [
                'status' => "$status",
                'title' => $this->trans("lalu-jer::messages.$status.title"),
                'detail' => empty($message) ? $this->trans("lalu-jer::messages.$status.detail") : $message,
            ]);
        }

        return [$status, $error, $headers];
    }

    /**
     * Make response.
     *
     * @param \Exception             $exception
     * @param \LaravelSoft\JER\Error $error
     * @param int                    $status
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function makeResponse(Exception $exception, $error, $status = 500)
    {
        $option = [
            'exception' => $exception,
            'version' => $this->jsonapiVersion,
            'status' => $status,
            'headers' => empty($this->headers) ? [] : $this->headers,
        ];
        $attributes = [];
        if ($this->meta != null) {
            $attributes['meta'] = $this->meta;
        };
        $attributes['errors'] = $error;

        return (new JsonExceptionResponse($option, $attributes))->getResponse();
    }

    /**
     * Lumen compatibility for trans().
     *
     * @param string $id
     * @param array  $parameters
     * @param string $domain
     * @param string $locale
     *
     * @return \Symfony\Component\Translation\TranslatorInterface|string
     */
    protected function trans($id = null, $parameters = [], $domain = 'messages', $locale = null)
    {
        if (function_exists('trans')) {
            return trans($id, $parameters, $domain, $locale);
        }
        if (is_null($id)) {
            return app('translator');
        }

        return app('translator')->$this->trans($id, $parameters, $domain, $locale);
    }
}
