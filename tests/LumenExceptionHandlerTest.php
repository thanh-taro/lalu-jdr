<?php

namespace LaLu\JDR;

use LaLu\JDR\ExceptionHandlers\LumenExceptionHandler;
use Exception;
use JDR;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Auth\Access\AuthorizationException;

class LumenExceptionHandlerTest extends AbstractTestCase
{
    public function testAttributes()
    {
        $this->assertClassHasAttribute('jsonapiVersion', LumenExceptionHandler::class);
        $this->assertClassHasAttribute('meta', LumenExceptionHandler::class);
        $this->assertClassHasAttribute('headers', LumenExceptionHandler::class);
    }

    public function testBasicRender()
    {
        $lang = $this->getLanguage('en');
        $handler = $this->getLumenHandler();
        $response = $handler->render($this->app->request, new Exception('Foo'));
        $this->assertJsonApi($response, 500, '{"jsonapi":{"version":"1.0"},"errors":[{"status":"500","title":"'.$lang[$response->getStatusCode().'.title'].'","detail":"Foo"}]}');
        $response = $handler->render($this->app->request, new Exception('Bar', 403));
        $this->assertJsonApi($response, 403, '{"jsonapi":{"version":"1.0"},"errors":[{"status":"403","title":"'.$lang[$response->getStatusCode().'.title'].'","detail":"Bar"}]}');
        $response = $handler->render($this->app->request, new TokenMismatchException());
        $this->assertJsonApi($response, 406, '{"jsonapi":{"version":"1.0"},"errors":[{"status":"406","title":"'.$lang['token_mismatch.title'].'","detail":"'.$lang['token_mismatch.detail'].'"}]}');
        $response = $handler->render($this->app->request, new AuthorizationException());
        $this->assertJsonApi($response, 401, '{"jsonapi":{"version":"1.0"},"errors":[{"status":"401","title":"'.$lang['authorization_error.title'].'","detail":"'.$lang['authorization_error.detail'].'"}]}');
    }

    public function testRenderWithMeta()
    {
        $lang = $this->getLanguage('en');
        $handler = $this->getLumenHandler();
        $handler->meta = ['version' => '1.0'];
        $response = $handler->render($this->app->request, new Exception('Foo'));
        $this->assertJsonApi($response, 500, '{"jsonapi":{"version":"1.0"},"meta":{"version": "1.0"},"errors":[{"status":"500","title":"'.$lang[$response->getStatusCode().'.title'].'","detail":"Foo"}]}');
    }

    public function testRenderWithHeaders()
    {
        $lang = $this->getLanguage('en');
        $handler = $this->getLumenHandler();
        $handler->headers = ['Application' => 'phpunit'];
        $response = $handler->render($this->app->request, new Exception('Foo'));
        $this->assertJsonApi($response, 500, '{"jsonapi":{"version":"1.0"},"errors":[{"status":"500","title":"'.$lang[$response->getStatusCode().'.title'].'","detail":"Foo"}]}', ['Application' => 'phpunit']);
    }
}
