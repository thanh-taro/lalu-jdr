<?php

namespace LaLu\JDR;

use Exception;
use Faker\Factory;
use Illuminate\Http\JsonResponse as BaseJsonResponse;
use LaLu\JDR\JsonObjects\V1_0\TopLevel;
use LaLu\JDR\JsonObjects\V1_0\Resource;
use LaLu\JDR\JsonObjects\V1_0\Error;
use Art4\JsonApiClient\Utils\Helper;

class JsonResponseTest extends BaseJsonObjectTestCase
{
    public function testAttributes()
    {
        $this->assertClassHasAttribute('status', JsonResponse::class);
        $this->assertClassHasAttribute('headers', JsonResponse::class);
        $this->assertClassHasStaticAttribute('HTTP_ERROR_STATUS_CODES', JsonResponse::class);
        $this->assertClassHasStaticAttribute('HTTP_SUCCESS_STATUS_CODES', JsonResponse::class);

        $object = new JsonResponse();
        $this->assertAttributeEquals(200, 'status', $object);
        $this->assertAttributeEquals([], 'headers', $object);

        $object = new JsonResponse(400);
        $this->assertAttributeEquals(400, 'status', $object);
        $this->assertAttributeEquals([], 'headers', $object);

        $object = new JsonResponse(PHP_INT_MAX);
        $this->assertAttributeEquals(PHP_INT_MAX, 'status', $object);
        $this->assertAttributeEquals([], 'headers', $object);

        $faker = Factory::create();
        for ($i = 0; $i <= static::MAX_LOOP; ++$i) {
            $status = $faker->randomNumber;
            $object = new JsonResponse($status);
            $this->assertAttributeEquals($status, 'status', $object);
            $this->assertAttributeEquals([], 'headers', $object);

            $status = $faker->word;
            $object = new JsonResponse($status);
            $this->assertAttributeEquals(intval($status), 'status', $object);
            $this->assertAttributeEquals([], 'headers', $object);
        }

        $object = new JsonResponse(500, ['Content-Type' => 'application/json']);
        $this->assertAttributeEquals(500, 'status', $object);
        $this->assertAttributeEquals(['Content-Type' => 'application/json'], 'headers', $object);
    }

    public function testSetStatusMethod()
    {
        $object = new JsonResponse();
        $this->assertSame($object, $object->setStatus(200));
        $this->assertAttributeEquals(200, 'status', $object);
        $this->assertSame(200, $object->status);
        $this->assertSame($object, $object->setStatus(PHP_INT_MAX));
        $this->assertAttributeEquals(PHP_INT_MAX, 'status', $object);
        $this->assertSame(PHP_INT_MAX, $object->status);

        $faker = Factory::create();
        for ($i = 0; $i <= static::MAX_LOOP; ++$i) {
            $status = $faker->randomNumber;
            $object = new JsonResponse($status);
            $this->assertAttributeEquals($status, 'status', $object);
            $this->assertSame($status, $object->status);

            $status = $faker->word;
            $object = new JsonResponse($status);
            $this->assertAttributeEquals(intval($status), 'status', $object);
            $this->assertSame(intval($status), $object->status);
        }
    }

    public function testSetAddHeadersMethod()
    {
        $object = new JsonResponse();
        $this->assertSame($object, $object->setHeaders([]));
        $this->assertSame([], $object->headers);
        $this->assertSame($object, $object->setHeaders(['Content-Type' => 'application/json']));
        $this->assertSame(['Content-Type' => 'application/json'], $object->headers);
        $this->assertSame($object, $object->addHeader('Author', 'Thanh Taro'));
        $this->assertSame(['Content-Type' => 'application/json', 'Author' => 'Thanh Taro'], $object->headers);
        $this->assertSame($object, $object->addHeader('Author', 'Thanh Taro <adamnguyen.itdn@gmail.com>'));
        $this->assertSame(['Content-Type' => 'application/json', 'Author' => 'Thanh Taro <adamnguyen.itdn@gmail.com>'], $object->headers);

        $object = new JsonResponse();
        $this->assertSame($object, $object->addHeader('Author', 'Thanh Taro'));
        $this->assertSame(['Author' => 'Thanh Taro'], $object->headers);
    }

    /**
     * @expectedException Exception
     * @expectedExceptionMessage Wrong parameter for generator
     * @expectedExceptionCode 500
     */
    public function testGenerateDataWithExceptionMethod()
    {
        $object = new JsonResponse();
        $object->generateData(['meta' => ['author' => 'Thanh Taro']]);
    }

    /**
     * @expectedException Exception
     * @expectedExceptionMessage Wrong parameter for generator
     * @expectedExceptionCode 500
     */
    public function testGenerateErrorsWithExceptionMethod()
    {
        $object = new JsonResponse();
        $object->generateErrors(['meta' => ['author' => 'Thanh Taro']]);
    }

    public function testGenerateDataMethod()
    {
        $object = new JsonResponse();
        $topLevel = (new TopLevel())->set('data', null)->set('meta', ['author' => 'Thanh Taro']);
        $this->assertJsonApi($object->generateData($topLevel), 200, '{"data":null,"meta":{"author":"Thanh Taro"},"jsonapi":{"version":"1.0"}}', []);
        $topLevel = (new TopLevel())->set('data', (new Resource()))->set('meta', ['author' => 'Thanh Taro']);
        $this->assertJsonApi($object->generateData($topLevel), 200, '{"data":null,"meta":{"author":"Thanh Taro"},"jsonapi":{"version":"1.0"}}', []);
        $data = new Resource();
        $topLevel = (new TopLevel())->set('data', $data)->set('meta', ['author' => 'Thanh Taro']);
        $this->assertJsonApi($object->generateData($topLevel), 200, '{"data":null,"meta":{"author":"Thanh Taro"},"jsonapi":{"version":"1.0"}}', []);

        $this->assertJsonApi($object->generateData(null), 204);
        $topLevel = (new TopLevel());
        $this->assertJsonApi($object->generateData($topLevel), 204);
        $topLevel = (new TopLevel())->set('data', null)->set('meta', ['author' => 'Thanh Taro']);
        $this->assertJsonApi($object->generateData($topLevel), 204);
        $data = new Resource();
        $topLevel = (new TopLevel())->set('data', $data)->set('meta', ['author' => 'Thanh Taro']);
        $this->assertJsonApi($object->generateData($topLevel, 204), 204);

        $this->assertJsonApi($object->generateData(null, 200), 200, '{}');
        $topLevel = (new TopLevel());
        $this->assertJsonApi($object->generateData($topLevel, 200), 200, '{}');

        $this->assertJsonApi($object->generateData(null, null, ['Author' => 'Thanh Taro']), 204, null, ['Author' => 'Thanh Taro']);

        $this->assertJsonApi($object->generateData(null, 500), 200, '{}');
    }

    public function testGenerateErrorsMethod()
    {
        $object = new JsonResponse();
        $topLevel = (new TopLevel())->add('errors', (new Error())->set('title', 'Error'))->set('meta', ['author' => 'Thanh Taro']);
        $this->assertJsonApi($object->generateErrors($topLevel), 500, '{"errors":[{"title":"Error"}],"meta":{"author":"Thanh Taro"},"jsonapi":{"version":"1.0"}}', []);

        $this->assertJsonApi($object->generateErrors(null), 500, '{}', []);
        $this->assertJsonApi($object->generateErrors(null, 400), 400, '{}', []);
        $this->assertJsonApi($object->generateErrors(null, 200), 500, '{}', []);
        $this->assertJsonApi($object->generateErrors(null, null, ['Author' => 'Thanh Taro']), 500, '{}', ['Author' => 'Thanh Taro']);
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
