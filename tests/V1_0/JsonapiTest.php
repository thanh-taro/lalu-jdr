<?php

namespace LaLu\JDR\V1_0;

use Faker\Factory;
use LaLu\JDR\BaseJsonObjectTestCase;
use LaLu\JDR\JsonObjects\V1_0\Jsonapi;
use LaLu\JDR\JsonObjects\V1_0\Meta;

class JsonapiTest extends BaseJsonObjectTestCase
{
    public function testAttributes()
    {
        $object = new Jsonapi();

        $this->assertClassHasAttribute('_params', Jsonapi::class);
        $this->assertAttributeEquals([], '_params', $object);
    }

    public function testGetJsonStruct()
    {
        $faker = Factory::create();

        $object = new Jsonapi();
        $this->assertContainsOnly('string', $object->getJsonStruct());
        $this->assertContains('version', $object->getJsonStruct());
        $this->assertContains('meta', $object->getJsonStruct());
        $this->assertSame(['version', 'meta'], $object->getJsonStruct());
    }

    public function testToArrayToJson()
    {
        $object = new Jsonapi();
        $this->assertSame(null, $object->toArray());
        $this->assertNull($object->toJson());

        $object = new Jsonapi(['version' => '1.0', 'meta' => ['author' => 'Thanh Taro <adamnguyen.itdn@gmail.com>']]);
        $this->assertSame(['version' => '1.0', 'meta' => ['author' => 'Thanh Taro <adamnguyen.itdn@gmail.com>']], $object->toArray());
        $this->assertSame('{"version":"1.0","meta":{"author":"Thanh Taro <adamnguyen.itdn@gmail.com>"}}', $object->toJson());

        $object = new Jsonapi(['version' => '1.0', 'meta' => (new Meta())->set('author', 'Thanh Taro')]);
        $this->assertSame(['version' => '1.0', 'meta' => ['author' => 'Thanh Taro']], $object->toArray());
        $this->assertSame('{"version":"1.0","meta":{"author":"Thanh Taro"}}', $object->toJson());
    }
}
