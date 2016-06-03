<?php

namespace LaLu\JDR;

use PHPUnit_Framework_TestCase;
use LaLu\JDR\JsonObjects\Object;
use Faker\Factory;

class ObjectTest extends PHPUnit_Framework_TestCase
{
    const MAX_LOOP = 10000;

    public function testAbstractMethod()
    {
        $faker = Factory::create();
        $stub = $this->getMockForAbstractClass(Object::class);

        for ($i = 0; $i <= static::MAX_LOOP; ++$i) {
            $stub->setVersion($faker->text);
            $stub->expects($this->any())
             ->method('getJsonStruct')
             ->will($this->returnValue(false));
        }
        $this->assertSame(false, $stub->getJsonStruct());
    }

    public function testAttributes()
    {
        $stub = $this->getMockForAbstractClass(Object::class);

        $this->assertClassHasAttribute('_version', Object::class);
        $this->assertClassHasAttribute('_params', Object::class);
        $this->assertAttributeEquals('1.0', '_version', $stub);
        $this->assertAttributeEquals([], '_params', $stub);
    }

    public function testLoadOptionsMethod()
    {
        $faker = Factory::create();
        $stub = $this->getMockForAbstractClass(Object::class);

        $this->assertSame($stub, $stub->loadOptions([]));
        $this->assertSame('1.0', $stub->getVersion());
        $this->assertSame($stub, $stub->loadOptions([
            'version' => '1.0',
        ]));
        $this->assertSame('1.0', $stub->getVersion());
        $this->assertSame($stub, $stub->loadOptions([
            'version' => '2.0',
        ]));
        $this->assertSame('2.0', $stub->getVersion());
        $this->assertSame($stub, $stub->loadOptions([
            'foo' => 'bar',
        ]));
        $this->assertSame('2.0', $stub->getVersion());
        $this->assertSame($stub, $stub->loadOptions([
            'foo' => null,
        ]));
        $this->assertSame('2.0', $stub->getVersion());
        $this->assertSame($stub, $stub->loadOptions([
            'foo' => PHP_INT_MAX,
        ]));
        $this->assertSame('2.0', $stub->getVersion());
        for ($i = 0; $i <= static::MAX_LOOP; ++$i) {
            $this->assertSame($stub, $stub->loadOptions([
                $faker->word => $faker->text,
            ]));
            $this->assertSame('2.0', $stub->getVersion());
        }
    }

    public function testSetGetVersionMethod()
    {
        $faker = Factory::create();
        $stub = $this->getMockForAbstractClass(Object::class);

        $this->assertSame($stub, $stub->setVersion(null));
        $this->assertSame(null, $stub->getVersion());
        $this->assertSame($stub, $stub->setVersion('1.0'));
        $this->assertSame('1.0', $stub->getVersion());
        $this->assertSame($stub, $stub->setVersion('2.0'));
        $this->assertSame('2.0', $stub->getVersion());
        $this->assertSame($stub, $stub->setVersion(1));
        $this->assertSame('1', $stub->getVersion());
        $this->assertSame($stub, $stub->setVersion('foo'));
        $this->assertSame('foo', $stub->getVersion());
        for ($i = 0; $i <= static::MAX_LOOP; ++$i) {
            $version = $faker->text;
            $this->assertSame($stub, $stub->setVersion($version));
            $this->assertSame($version, $stub->getVersion());
            $version = $faker->randomNumber;
            $this->assertSame($stub, $stub->setVersion($version));
            $this->assertSame(strval($version), $stub->getVersion());
        }
        $this->assertSame($stub, $stub->setVersion(PHP_INT_MAX));
        $this->assertSame(strval(PHP_INT_MAX), $stub->getVersion());
    }

    public function testGetMagicMethod()
    {
        $faker = Factory::create();
        $stub = $this->getMockForAbstractClass(Object::class);

        for ($i = 0; $i <= static::MAX_LOOP; ++$i) {
            $field = $faker->word;
            $this->assertNull($stub->$field);
        }
    }

    public function testSetAddGetParams()
    {
        $faker = Factory::create();
        $stub = $this->getMockForAbstractClass(Object::class);

        $this->assertSame($stub, $stub->setParams(['param1' => 'value1']));
        $this->assertSame([], $stub->getParams());
        $this->assertSame([], $stub->getParams(['param1']));
        for ($i = 0; $i <= static::MAX_LOOP; ++$i) {
            $field = $faker->word;
            $this->assertSame($stub, $stub->setParams([$field => $faker->text]));
            $this->assertSame([], $stub->getParams());
            $this->assertSame([], $stub->getParams([$field]));
        }
    }

    public function testSetAddDeleteMethod()
    {
        $faker = Factory::create();
        $stub = $this->getMockForAbstractClass(Object::class);

        $this->assertSame($stub, $stub->set('param1', 'value1'));
        $this->assertNull($stub->param1);
        $this->assertSame([], $stub->getParams());
        $this->assertSame([], $stub->getParams(['param1']));
        for ($i = 0; $i <= static::MAX_LOOP; ++$i) {
            $field = $faker->word;
            $this->assertSame($stub, $stub->set($field, 'value1'));
            $this->assertSame([], $stub->getParams());
            $this->assertSame([], $stub->getParams([$field]));
        }
        $this->assertSame($stub, $stub->add('param1', 'value1'));
        $this->assertNull($stub->param1);
        $this->assertSame([], $stub->getParams());
        $this->assertSame([], $stub->getParams(['param1']));
        for ($i = 0; $i <= static::MAX_LOOP; ++$i) {
            $field = $faker->word;
            $this->assertSame($stub, $stub->add($field, 'value1'));
            $this->assertSame([], $stub->getParams());
            $this->assertSame([], $stub->getParams([$field]));
        }

        $this->assertSame($stub, $stub->delete('param1'));
        $this->assertNull($stub->param1);
        $this->assertSame([], $stub->getParams());
        $this->assertSame([], $stub->getParams(['param1']));
        for ($i = 0; $i <= static::MAX_LOOP; ++$i) {
            $field = $faker->word;
            $this->assertSame($stub, $stub->delete($field));
            $this->assertSame([], $stub->getParams());
            $this->assertSame([], $stub->getParams([$field]));
        }
    }

    public function testToArray()
    {
        $stub = $this->getMockForAbstractClass(Object::class);
        $this->assertSame([], $stub->toArray());
    }

    public function testToJson()
    {
        $stub = $this->getMockForAbstractClass(Object::class);
        $this->assertNull($stub->toJson());
    }
}
