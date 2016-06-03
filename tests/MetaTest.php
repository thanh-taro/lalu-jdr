<?php

namespace LaLu\JDR;

use LaLu\JDR\JsonObjects\Meta;
use Faker\Factory;

class MetaTest extends BaseJsonObjectTestCase
{
    public function testAttributes()
    {
        $object = new Meta();

        $this->assertClassHasAttribute('_version', Meta::class);
        $this->assertClassHasAttribute('_params', Meta::class);
        $this->assertAttributeEquals('1.0', '_version', $object);
        $this->assertAttributeEquals([], '_params', $object);
    }

    public function testGetJsonStruct()
    {
        $faker = Factory::create();

        $object = new Meta();
        $this->assertSame([], $object->getJsonStruct());
        $object = new Meta(['version' => null]);
        $this->assertFalse($object->getJsonStruct());
        $object = new Meta(['version' => 1]);
        $this->assertFalse($object->getJsonStruct());
        $object = new Meta(['version' => '1']);
        $this->assertFalse($object->getJsonStruct());
        $object = new Meta(['version' => 'foo']);
        $this->assertFalse($object->getJsonStruct());
        for ($i = 0; $i <= static::MAX_LOOP; ++$i) {
            $version = $faker->text;
            $object = new Meta(['version' => $version]);
            $this->assertFalse($object->getJsonStruct());
            $version = $faker->randomNumber;
            $object = new Meta(['version' => $version]);
            $this->assertFalse($object->getJsonStruct());
        }
    }

    public function testLoadOptionsMethod()
    {
        $faker = Factory::create();
        $object = new Meta();

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
        $object = new Meta();

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

    public function testToArray()
    {
        $object = new Meta();
        $this->assertSame([], $object->toArray());
        $object = new Meta([], ['self' => 'http://www.skilesdonnelly.biz/aut-accusantium-ut-architecto-sit-et.html']);
        $this->assertSame(['self' => 'http://www.skilesdonnelly.biz/aut-accusantium-ut-architecto-sit-et.html'], $object->toArray());
    }

    public function testToJson()
    {
        $object = new Meta();
        $this->assertNull($object->toJson());
        $object = new Meta([], ['self' => 'http://www.skilesdonnelly.biz/aut-accusantium-ut-architecto-sit-et.html']);
        $this->assertSame('{"self":"http:\/\/www.skilesdonnelly.biz\/aut-accusantium-ut-architecto-sit-et.html"}', $object->toJson());
    }
}
