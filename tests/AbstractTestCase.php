<?php

namespace LaLu\JDR;

use GrahamCampbell\TestBench\AbstractPackageTestCase;
use LaLu\JDR\Facades\JDRFacade;
use Illuminate\Http\JsonResponse as BaseJsonResponse;
use Art4\JsonApiClient\Utils\Helper;
use LaLu\JDR\Exceptions\Handler;
use LaLu\JDR\Exceptions\LumenHandler;

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
        return JDRServiceProvider::class;
    }

    /**
     * Get package aliases.
     *
     * @param \Illuminate\Contracts\Foundation\Application $app
     *
     * @return array
     */
    protected function getPackageAliases($app)
    {
        return [
            'JDR' => JDRFacade::class,
        ];
    }

    protected function getLanguage($lang = 'en')
    {
        return include __DIR__."/../src/resources/lang/$lang/messages.php";
    }

    protected function getHandler()
    {
        return $this->app->make(Handler::class);
    }

    protected function getLumenHandler()
    {
        return $this->app->make(LumenHandler::class);
    }

    protected function assertJsonApi($response, $status, $expected = null, $headers = [])
    {
        $this->assertInstanceOf(BaseJsonResponse::class, $response);
        $this->assertSame($status, $response->getStatusCode());
        $headers = array_merge($headers, ['Content-Type' => 'application/vnd.api+json']);
        foreach ($headers as $key => $value) {
            $this->assertSame($value, $response->headers->get($key));
        }
        if ($expected !== '{}') {
            if ($expected === null) {
                $this->assertTrue($response->isEmpty());
                $this->assertSame(204, $response->getStatusCode());
            } else {
                $this->assertTrue(Helper::isValid($response->getContent()));
                $this->assertJsonStringEqualsJsonString($expected, $response->getContent());
            }
        }
    }
}
