<?php

namespace LaLu\JDR\Exceptions;

use Exception;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use LaLu\JDR\JsonResponse;
use LaLu\JDR\Helpers\Helper;

trait HandlerTrait
{
    public $jsonapiVersion = '1.0';
    public $meta;
    public $headers;

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
        list($status, $error) = $this->getExceptionError($exception);

        return $this->makeResponse($error, $status);
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
        $error = null;
        if ($exception instanceof TokenMismatchException) {
            $status = 406;
            $error = Helper::makeJsonapiObject($this->jsonapiVersion, 'error', [
                'status' => "$status",
                'title' => Helper::trans('lalu-jdr::messages.token_mismatch.title'),
                'detail' => Helper::trans('lalu-jdr::messages.token_mismatch.detail'),
            ]);
        } elseif ($exception instanceof ValidationException) {
            $status = 406;
            $error = [];
            $messages = $exception->validator->messages();
            foreach ($messages->toArray() as $field => $messageArr) {
                foreach ($messageArr as $message) {
                    $error[] = Helper::makeJsonapiObject($this->jsonapiVersion, 'error', [
                        'title' => Helper::trans('lalu-jdr::messages.validation_error.title'),
                        'detail' => $message,
                        'source' => Helper::makeJsonapiObject($this->jsonapiVersion, 'source', [
                            'pointer' => $field,
                        ]),
                    ]);
                }
            }
        } elseif ($exception instanceof AuthorizationException) {
            $status = 401;
            $error = Helper::makeJsonapiObject($this->jsonapiVersion, 'error', [
                'status' => "$status",
                'title' => Helper::trans('lalu-jdr::messages.authorization_error.title'),
                'detail' => Helper::trans('lalu-jdr::messages.authorization_error.detail'),
            ]);
        } else {
            $status = method_exists($exception, 'getStatusCode') ? $exception->getStatusCode() : (method_exists($exception, 'getCode') ? $exception->getCode() : 500);
            if (!in_array($status, JsonResponse::$HTTP_ERROR_STATUS_CODES)) {
                $status = 500;
            }
            $message = $exception->getMessage();
            $error = Helper::makeJsonapiObject($this->jsonapiVersion, 'error', [
                'status' => "$status",
                'title' => Helper::trans("lalu-jdr::messages.$status.title"),
                'detail' => empty($message) ? Helper::trans("lalu-jdr::messages.$status.detail") : $message,
            ]);
        }

        return [$status, $error];
    }

    /**
     * Make response.
     *
     * @param \LaravelSoft\JER\Error $error
     * @param int                    $status
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function makeResponse($error, $status = 500)
    {
        $topLevel = is_array($error) ? Helper::makeJsonapiObject($this->jsonapiVersion, 'toplevel')->set('errors', $error) : Helper::makeJsonapiObject($this->jsonapiVersion, 'toplevel')->add('errors', $error);
        if ($this->meta !== null) {
            $topLevel->set('meta', $this->meta);
        }

        return (new JsonResponse())->generateErrors($topLevel, $status, $this->headers);
    }
}
