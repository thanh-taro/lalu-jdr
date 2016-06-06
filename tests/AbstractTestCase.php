<?php

namespace LaLu\JDR;

use GrahamCampbell\TestBench\AbstractPackageTestCase;
use LaLu\JDR\Facades\JDRFacade;
use Illuminate\Http\JsonResponse as BaseJsonResponse;
use Art4\JsonApiClient\Utils\Helper;

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

    protected function assertJsonApi($response, $status, $expected = null, $headers = [])
    {
        $this->assertInstanceOf(BaseJsonResponse::class, $response);
        $this->assertSame($status, $response->getStatusCode());
        $headers = array_merge($headers, ['Content-Type' => 'application/vnd.api+json']);
        foreach ($headers as $key => $value) {
            $this->assertSame($value, $response->headers->get($key));
        }
        if ($expected === null) {
            $this->assertTrue($response->isEmpty());
            $this->assertSame(204, $response->getStatusCode());
        } elseif (!empty($response->getContent() !== '{}')) {
            $this->assertTrue(Helper::isValid($response->getContent()));
            $this->assertJsonStringEqualsJsonString($expected, $response->getContent());
        }
    }
}
