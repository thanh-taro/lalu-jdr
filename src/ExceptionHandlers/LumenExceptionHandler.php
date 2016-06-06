<?php

namespace LaLu\JDR\ExceptionHandlers;

use Exception;
use Laravel\Lumen\Exceptions\Handler;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use LaLu\JDR\JsonResponse;
use LaLu\JDR\JsonObjects\Error;
use LaLu\JDR\JsonObjects\Source;
use LaLu\JDR\JsonObjects\TopLevel;

class LumenExceptionHandler extends Handler
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
        list($status, $error, $headers) = $this->getExceptionError($exception);

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
        $headers = [];
        $error = null;
        if ($exception instanceof TokenMismatchException) {
            $status = 406;
            $error = new Error(['version' => $this->jsonapiVersion], [
                'status' => "$status",
                'title' => $this->trans('lalu-jdr::messages.token_mismatch.title'),
                'detail' => $this->trans('lalu-jdr::messages.token_mismatch.detail'),
            ]);
        } elseif ($exception instanceof ValidationException) {
            $status = 406;
            $error = [];
            $messages = $exception->validator->messages();
            foreach ($messages->toArray() as $field => $messageArr) {
                foreach ($messageArr as $message) {
                    $error[] = new Error(['version' => $this->jsonapiVersion], [
                        'title' => $this->trans('lalu-jdr::messages.validation_error.title'),
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
                'title' => $this->trans('lalu-jdr::messages.authorization_error.title'),
                'detail' => $this->trans('lalu-jdr::messages.authorization_error.detail'),
            ]);
        } else {
            $status = method_exists($exception, 'getStatusCode') ? $exception->getStatusCode() : (method_exists($exception, 'getCode') ? $exception->getCode() : 500);
            if (!in_array($status, JsonResponse::$HTTP_ERROR_STATUS_CODES)) {
                $status = 500;
            }
            $message = $exception->getMessage();
            $error = new Error(['version' => $this->jsonapiVersion], [
                'status' => "$status",
                'title' => $this->trans("lalu-jdr::messages.$status.title"),
                'detail' => empty($message) ? $this->trans("lalu-jdr::messages.$status.detail") : $message,
            ]);
        }

        return [$status, $error, $headers];
    }

    /**
     * Make response.
     *
     * @param \LaLu\JDR\JsonObjects\Error|\LaLu\JDR\JsonObjects\Error[] $error
     * @param int                    $status
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function makeResponse($error, $status = 500)
    {
        if (is_array($error)) {
            $topLevel = (new TopLevel(['version' => $this->jsonapiVersion]))->set('errors', $error);
        } else {
            $topLevel = (new TopLevel(['version' => $this->jsonapiVersion]))->add('errors', $error);
        }
        if ($this->meta !== null) {
            $topLevel->set('meta', $this->meta);
        }
        $topLevel->set('jsonapi', ['version' => $this->jsonapiVersion]);

        return (new JsonResponse())->generateErrors($topLevel, $status, $this->headers);
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

        return app('translator')->trans($id, $parameters, $domain, $locale);
    }
}
