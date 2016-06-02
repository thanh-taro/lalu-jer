<?php

namespace LaLu\JER;

use Exception;
use Illuminate\Http\JsonResponse;

class JsonExceptionResponse extends Object
{
    public static $HTTP_STATUS_CODES = [
        400, 401, 402, 403, 404, 405, 406, 407, 408, 409, 410, 411, 412, 413, 414, 415, 416, 417,
        418, 422, 423, 424, 425, 426, 449, 450, 500, 501, 502, 503, 504, 505, 506, 507, 509, 510,
    ];

    public $exception;
    public $headers;
    public $status;

    /**
     * Get jsonapi object version.
     *
     * @return string|null
     */
    public function getJsonStruct()
    {
        if ($this->version === '1.0') {
            return ['meta', 'errors'];
        }

        return false;
    }

    /**
     * Load options.
     *
     * @param array $option
     *
     * @return bool
     */
    public function loadOption(array $option)
    {
        parent::loadOption($option);
        if (isset($option['exception'])) {
            $this->exception = $option['exception'];
        }
        if (isset($option['headers'])) {
            $this->headers = $option['headers'];
        }
        if (isset($option['status'])) {
            $this->status = $option['status'];
        }

        return true;
    }

    /**
     * Set attributes.
     *
     * @param array $attributes
     *
     * @return bool
     */
    public function setAttributes(array $attributes)
    {
        if (isset($attributes['errors']) && $attributes['errors'] instanceof Error) {
            $attributes['errors'] = [$attributes['errors']];
        }
        return parent::setAttributes($attributes);
    }

    /**
     * Get response.
     *
     * @param array|null $option
     * @param array|null $attributes
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getResponse($option = [], $attributes = [])
    {
        // lazy load
        if (!empty($option)) {
            $this->loadOption($option);
        }
        if (!empty($attributes)) {
            $this->setAttributes($attributes);
        }

        // processes response
        $this->status = in_array($this->status, static::$HTTP_STATUS_CODES) ? $this->status : 500;
        $content = array_merge(['jsonapi' => ['version' => $this->version]], $this->getData());
        if ($this->headers === null) {
            $this->headers = [];
        }

        // makes response
        $response = new JsonResponse($content, $this->status, array_merge($this->headers, ['Content-Type' => 'application/vnd.api+json']));
        $response->exception = $this->exception;

        return $response;
    }
}
