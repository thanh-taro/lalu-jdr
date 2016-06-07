<?php

namespace LaLu\JDR\Exceptions;

use Exception;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use LaLu\JDR\JsonResponse;
use LaLu\JDR\JsonObjects\V1_0\Error;
use LaLu\JDR\JsonObjects\V1_0\Jsonapi;
use LaLu\JDR\JsonObjects\V1_0\Link;
use LaLu\JDR\JsonObjects\V1_0\Links;
use LaLu\JDR\JsonObjects\V1_0\Meta;
use LaLu\JDR\JsonObjects\V1_0\Resource;
use LaLu\JDR\JsonObjects\V1_0\Source;
use LaLu\JDR\JsonObjects\V1_0\TopLevel;

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
            $error = $this->makeJsonapiObject('error', [
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
                    $error[] = $this->makeJsonapiObject('error', [
                        'title' => $this->trans('lalu-jdr::messages.validation_error.title'),
                        'detail' => $message,
                        'source' => $this->makeJsonapiObject('source', [
                            'pointer' => $field,
                        ]),
                    ]);
                }
            }
        } elseif ($exception instanceof AuthorizationException) {
            $status = 401;
            $error = $this->makeJsonapiObject('error', [
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
            $error = $this->makeJsonapiObject('error', [
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
     * @param \LaravelSoft\JER\Error $error
     * @param int                    $status
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function makeResponse($error, $status = 500)
    {
        $topLevel = is_array($error) ? $this->makeJsonapiObject('toplevel')->set('errors', $error) : $this->makeJsonapiObject('toplevel')->add('errors', $error);
        if ($this->meta !== null) {
            $topLevel->set('meta', $this->meta);
        }

        return (new JsonResponse())->generateErrors($topLevel, $status, $this->headers);
    }

    /**
     * Make json object.
     *
     * @param string $name
     * @param array  $params
     *
     * @return null|\LaLu\JDR\JsonObjects\Object
     */
    protected function makeJsonapiObject($name, $params = [])
    {
        if ($this->jsonapiVersion === '1.0') {
            if ($name === 'error') {
                return new Error($params);
            }
            if ($name === 'toplevel') {
                return new TopLevel($params);
            }
            if ($name === 'meta') {
                return new Meta($params);
            }
            if ($name === 'jsonapi') {
                return new Jsonapi($params);
            }
            if ($name === 'link') {
                return new Link($params);
            }
            if ($name === 'links') {
                return new Links($params);
            }
            if ($name === 'resource') {
                return new Resource($params);
            }
            if ($name === 'source') {
                return new Source($params);
            }
        }

        return;
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
