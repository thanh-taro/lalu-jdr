<?php

namespace LaLu\JDR;

use PHPUnit_Framework_TestCase;
use LaLu\JDR\JsonObjects\TopLevel;
use Faker\Factory;

class TopLevelTest extends PHPUnit_Framework_TestCase
{
    const MAX_LOOP = 10000;

    public function testAttributes()
    {
        $object = new TopLevel();

        $this->assertClassHasAttribute('_version', TopLevel::class);
        $this->assertClassHasAttribute('_params', TopLevel::class);
        $this->assertAttributeEquals('1.0', '_version', $object);
        $this->assertAttributeEquals([], '_params', $object);
    }

    public function testGetJsonStruct()
    {
        $faker = Factory::create();

        $object = new TopLevel();
        $this->assertContainsOnly('string', $object->getJsonStruct());
        $this->assertContains('data', $object->getJsonStruct());
        $this->assertContains('errors', $object->getJsonStruct());
        $this->assertContains('meta', $object->getJsonStruct());
        $this->assertContains('jsonapi', $object->getJsonStruct());
        $this->assertContains('links', $object->getJsonStruct());
        $this->assertContains('included', $object->getJsonStruct());
        $this->assertSame(['data', 'errors', 'meta', 'jsonapi', 'links', 'included'], $object->getJsonStruct());
        $object = new TopLevel(['version' => null]);
        $this->assertFalse($object->getJsonStruct());
        $object = new TopLevel(['version' => 1]);
        $this->assertFalse($object->getJsonStruct());
        $object = new TopLevel(['version' => '1']);
        $this->assertFalse($object->getJsonStruct());
        $object = new TopLevel(['version' => 'foo']);
        $this->assertFalse($object->getJsonStruct());
        for ($i = 0; $i <= static::MAX_LOOP; ++$i) {
            $version = $faker->text;
            $object = new TopLevel(['version' => $version]);
            $this->assertFalse($object->getJsonStruct());
            $version = $faker->randomNumber;
            $object = new TopLevel(['version' => $version]);
            $this->assertFalse($object->getJsonStruct());
        }
    }

    public function testLoadOptionsMethod()
    {
        $faker = Factory::create();
        $object = new TopLevel();

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
        $object = new TopLevel();

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
        $object = new TopLevel();
        $this->assertSame([], $object->toArray());
    }

    public function testToJson()
    {
        $object = new TopLevel();
        $this->assertNull($object->toJson());
    }
}
