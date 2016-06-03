<?php

namespace LaLu\JDR;

use PHPUnit_Framework_TestCase;
use LaLu\JDR\JsonObjects\Jsonapi;
use Faker\Factory;

class JsonapiTest extends PHPUnit_Framework_TestCase
{
    const MAX_LOOP = 10000;

    public function testAttributes()
    {
        $object = new Jsonapi();

        $this->assertClassHasAttribute('_version', Jsonapi::class);
        $this->assertClassHasAttribute('_params', Jsonapi::class);
        $this->assertAttributeEquals('1.0', '_version', $object);
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
        $object = new Jsonapi(['version' => null]);
        $this->assertFalse($object->getJsonStruct());
        $object = new Jsonapi(['version' => 1]);
        $this->assertFalse($object->getJsonStruct());
        $object = new Jsonapi(['version' => '1']);
        $this->assertFalse($object->getJsonStruct());
        $object = new Jsonapi(['version' => 'foo']);
        $this->assertFalse($object->getJsonStruct());
        for ($i = 0; $i <= static::MAX_LOOP; ++$i) {
            $version = $faker->text;
            $object = new Jsonapi(['version' => $version]);
            $this->assertFalse($object->getJsonStruct());
            $version = $faker->randomNumber;
            $object = new Jsonapi(['version' => $version]);
            $this->assertFalse($object->getJsonStruct());
        }
    }

    public function testLoadOptionsMethod()
    {
        $faker = Factory::create();
        $object = new Jsonapi();

        $this->assertSame($object, $object->loadOptions([]));
        $this->assertSame('1.0', $object->getVersion());
        $this->assertSame($object, $object->loadOptions([
            'version' => '1.0',
        ]));
        $this->assertSame('1.0', $object->getVersion());
        $this->assertSame($object, $object->loadOptions([
            'version' => '2.0',
        ]));
        $this->assertSame('2.0', $object->getVersion());
        $this->assertSame($object, $object->loadOptions([
            'foo' => 'bar',
        ]));
        $this->assertSame('2.0', $object->getVersion());
        $this->assertSame($object, $object->loadOptions([
            'foo' => null,
        ]));
        $this->assertSame('2.0', $object->getVersion());
        $this->assertSame($object, $object->loadOptions([
            'foo' => PHP_INT_MAX,
        ]));
        $this->assertSame('2.0', $object->getVersion());
        for ($i = 0; $i <= static::MAX_LOOP; ++$i) {
            $this->assertSame($object, $object->loadOptions([
                $faker->word => $faker->text,
            ]));
            $this->assertSame('2.0', $object->getVersion());
        }
    }

    public function testSetGetVersionMethod()
    {
        $faker = Factory::create();
        $object = new Jsonapi();

        $this->assertSame($object, $object->setVersion(null));
        $this->assertSame(null, $object->getVersion());
        $this->assertSame($object, $object->setVersion('1.0'));
        $this->assertSame('1.0', $object->getVersion());
        $this->assertSame($object, $object->setVersion('2.0'));
        $this->assertSame('2.0', $object->getVersion());
        $this->assertSame($object, $object->setVersion(1));
        $this->assertSame('1', $object->getVersion());
        $this->assertSame($object, $object->setVersion('foo'));
        $this->assertSame('foo', $object->getVersion());
        for ($i = 0; $i <= static::MAX_LOOP; ++$i) {
            $version = $faker->text;
            $this->assertSame($object, $object->setVersion($version));
            $this->assertSame($version, $object->getVersion());
            $version = $faker->randomNumber;
            $this->assertSame($object, $object->setVersion($version));
            $this->assertSame(strval($version), $object->getVersion());
        }
        $this->assertSame($object, $object->setVersion(PHP_INT_MAX));
        $this->assertSame(strval(PHP_INT_MAX), $object->getVersion());
    }

    public function testAddSetGetDeleteMethod()
    {
        $faker = Factory::create();
        $object = new Jsonapi();

        for ($i = 0; $i <= static::MAX_LOOP; ++$i) {
            $field = $faker->word;
            $this->assertNull($object->$field);
            $this->assertSame([], $object->getParams());
        }
        $this->assertSame([], $object->getParams());
        $this->assertSame($object, $object->set('version', '1.0'));
        $this->assertSame('1.0', $object->version);
        $this->assertSame(['version' => '1.0'], $object->getParams());
        $this->assertSame(['version' => '1.0'], $object->getParams(['version']));
        $this->assertSame(['version' => '1.0', 'meta' => null], $object->getParams(['version', 'meta']));
        $this->assertSame($object, $object->set('meta', ['author' => 'Thanh Taro <adamnguyen.itdn@gmail.com>']));
        $this->assertSame(['author' => 'Thanh Taro <adamnguyen.itdn@gmail.com>'], $object->meta);
        $this->assertSame(['version' => '1.0', 'meta' => ['author' => 'Thanh Taro <adamnguyen.itdn@gmail.com>']], $object->getParams());
        $this->assertSame(['version' => '1.0'], $object->getParams(['version']));
        $this->assertSame(['meta' => ['author' => 'Thanh Taro <adamnguyen.itdn@gmail.com>']], $object->getParams(['meta']));
        for ($i = 0; $i <= static::MAX_LOOP; ++$i) {
            $field = $faker->word;
            if ($field !== 'version' && $field !== 'meta') {
                $this->assertSame($object, $object->set($field, $faker->text));
                $this->assertSame('1.0', $object->version);
                $this->assertSame(['author' => 'Thanh Taro <adamnguyen.itdn@gmail.com>'], $object->meta);
                $this->assertSame(['version' => '1.0', 'meta' => ['author' => 'Thanh Taro <adamnguyen.itdn@gmail.com>']], $object->getParams());
                $this->assertSame(['version' => '1.0'], $object->getParams(['version']));
                $this->assertSame(['meta' => ['author' => 'Thanh Taro <adamnguyen.itdn@gmail.com>']], $object->getParams(['meta']));
            }
        }
        $this->assertSame($object, $object->delete('meta'));
        $this->assertNull($object->meta);
        $this->assertSame(['version' => '1.0'], $object->getParams());
        $this->assertSame(['version' => '1.0'], $object->getParams(['version']));
        $this->assertSame(['meta' => null], $object->getParams(['meta']));
    }

    public function testToArray()
    {
        $object = new Jsonapi();
        $this->assertSame([], $object->toArray());
        $object = new Jsonapi([], ['version' => '1.0', 'meta' => ['author' => 'Thanh Taro <adamnguyen.itdn@gmail.com>']]);
        $this->assertSame(['version' => '1.0', 'meta' => ['author' => 'Thanh Taro <adamnguyen.itdn@gmail.com>']], $object->toArray());
    }

    public function testToJson()
    {
        $object = new Jsonapi();
        $this->assertNull($object->toJson());
        $object = new Jsonapi([], ['version' => '1.0', 'meta' => ['author' => 'Thanh Taro <adamnguyen.itdn@gmail.com>']]);
        $this->assertSame('{"version":"1.0","meta":{"author":"Thanh Taro <adamnguyen.itdn@gmail.com>"}}', $object->toJson());
    }
}
