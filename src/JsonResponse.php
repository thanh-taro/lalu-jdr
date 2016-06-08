<?php

namespace LaLu\JDR;

use Exception;
use LaLu\JDR\JsonObjects\V1_0\TopLevel;
use Illuminate\Http\JsonResponse as BaseJsonResponse;

class JsonResponse
{
    public static $HTTP_ERROR_STATUS_CODES = [
        400, 401, 402, 403, 404, 405, 406, 407, 408, 409, 410, 411, 412, 413, 414, 415, 416, 417,
        418, 422, 423, 424, 425, 426, 449, 450, 500, 501, 502, 503, 504, 505, 506, 507, 509, 510,
    ];

    public static $HTTP_SUCCESS_STATUS_CODES = [
        200, 201, 202, 203, 204, 205, 206, 207, 208, 226,
    ];

    public $headers;
    public $status;

    /**
     * Constructor.
     *
     * @param int|string     $status
     * @param array          $headers
     * @param Exception|null $exception
     */
    public function __construct($status = 200, $headers = [])
    {
        $this->setStatus($status);
        $this->setHeaders($headers);
    }

    /**
     * Set headers.
     *
     * @param array|null $headers
     *
     * @return $this
     */
    public function setHeaders($headers)
    {
        $this->headers = $headers;

        return $this;
    }

    /**
     * Add header.
     *
     * @param string $key
     * @param mixed  $value
     *
     * @return $this
     */
    public function addHeader($key, $value)
    {
        if (empty($this->headers)) {
            $this->headers = [];
        }
        $this->headers[$key] = $value;

        return $this;
    }

    /**
     * Set status.
     *
     * @param int|string $status
     *
     * @return $this
     */
    public function setStatus($status)
    {
        $this->status = intval($status);

        return $this;
    }

    /**
     * Generate data response.
     *
     * @param \LaLu\JDR\JsonObjects\TopLevel|null $topLevel
     * @param int|null                            $status
     * @param array|null                          $headers
     *
     * @return Illuminate\Http\JsonResponse
     */
    public function generateData($topLevel = null, $status = null, $headers = null)
    {
        if ($topLevel !== null && !($topLevel instanceof TopLevel)) {
            throw new Exception('Wrong parameter for generator', 500);
        }
        if ($topLevel instanceof TopLevel) {
            $topLevel->delete('errors');
            $content = $topLevel->toArray();
        } else {
            $content = null;
        }
        if ($status !== null) {
            $this->setStatus($status);
        } elseif ($content === null) {
            $this->setStatus(204);
        }
        if (!in_array($this->status, static::$HTTP_SUCCESS_STATUS_CODES)) {
            $this->setStatus(200);
        }
        if ($headers !== null) {
            $this->setHeaders($headers);
        }
        if ($this->status === 204) {
            return new BaseJsonResponse(null, $this->status, array_merge($this->headers, ['Content-Type' => 'application/vnd.api+json']));
        }

        return new BaseJsonResponse($content, $this->status, array_merge($this->headers, ['Content-Type' => 'application/vnd.api+json']));
    }

    /**
     * Generate errors response.
     *
     * @param \LaLu\JDR\JsonObjects\TopLevel|null $topLevel
     * @param int|null                            $status
     * @param array|null                          $headers
     *
     * @return Illuminate\Http\JsonResponse
     */
    public function generateErrors($topLevel = null, $status = null, $headers = null)
    {
        if ($topLevel !== null && !($topLevel instanceof TopLevel)) {
            throw new Exception('Wrong parameter for generator', 500);
        }
        if ($topLevel instanceof TopLevel) {
            $topLevel->delete('data');
            $content = $topLevel->toArray();
        } else {
            $content = null;
        }
        if ($status !== null) {
            $this->setStatus($status);
        }
        if (!in_array($this->status, static::$HTTP_ERROR_STATUS_CODES)) {
            $this->setStatus(500);
        }
        if ($headers !== null) {
            $this->setHeaders($headers);
        }

        return new BaseJsonResponse($content, $this->status, array_merge($this->headers, ['Content-Type' => 'application/vnd.api+json']));
    }
}
