<?php

namespace LaLu\JDR;

use LaLu\JDR\JsonObjects\Resource;
use Faker\Factory;

class ResourceTest extends BaseJsonObjectTestCase
{
    public function testAttributes()
    {
        $object = new Resource();

        $this->assertClassHasAttribute('_version', Resource::class);
        $this->assertClassHasAttribute('_params', Resource::class);
        $this->assertAttributeEquals('1.0', '_version', $object);
        $this->assertAttributeEquals([], '_params', $object);
    }

    public function testGetJsonStruct()
    {
        $faker = Factory::create();

        $object = new Resource();
        $this->assertContainsOnly('string', $object->getJsonStruct());
        $this->assertContains('id', $object->getJsonStruct());
        $this->assertContains('type', $object->getJsonStruct());
        $this->assertContains('attributes', $object->getJsonStruct());
        $this->assertContains('relationships', $object->getJsonStruct());
        $this->assertContains('links', $object->getJsonStruct());
        $this->assertContains('meta', $object->getJsonStruct());
        $this->assertSame(['id', 'type', 'attributes', 'relationships', 'links', 'meta'], $object->getJsonStruct());
        $object = new Resource(['version' => null]);
        $this->assertFalse($object->getJsonStruct());
        $object = new Resource(['version' => 1]);
        $this->assertFalse($object->getJsonStruct());
        $object = new Resource(['version' => '1']);
        $this->assertFalse($object->getJsonStruct());
        $object = new Resource(['version' => 'foo']);
        $this->assertFalse($object->getJsonStruct());
        for ($i = 0; $i <= static::MAX_LOOP; ++$i) {
            $version = $faker->text;
            $object = new Resource(['version' => $version]);
            $this->assertFalse($object->getJsonStruct());
            $version = $faker->randomNumber;
            $object = new Resource(['version' => $version]);
            $this->assertFalse($object->getJsonStruct());
        }
    }

    public function testLoadOptionsMethod()
    {
        $faker = Factory::create();
        $object = new Resource();

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
        $object = new Resource();

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
        $object = new Resource();
        $this->assertSame(null, $object->toArray());
    }

    public function testToJson()
    {
        $object = new Resource();
        $this->assertNull($object->toJson());
    }
}
