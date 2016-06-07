<?php

namespace LaLu\JDR\V1_0;

use Faker\Factory;
use LaLu\JDR\BaseJsonObjectTestCase;
use LaLu\JDR\JsonObjects\Object;

class ObjectTest extends BaseJsonObjectTestCase
{
    public function testAbstractMethod()
    {
        $faker = Factory::create();
        $stub = $this->getMockForAbstractClass(Object::class);

        for ($i = 0; $i <= static::MAX_LOOP; ++$i) {
            $stub->expects($this->any())
             ->method('getJsonStruct')
             ->will($this->returnValue(false));
        }
        $this->assertSame(false, $stub->getJsonStruct());
    }

    public function testAttributes()
    {
        $stub = $this->getMockForAbstractClass(Object::class);

        $this->assertClassHasAttribute('_params', Object::class);
        $this->assertAttributeEquals([], '_params', $stub);
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

        $this->assertSame($stub, $stub->setParams([]));
        $this->assertSame([], $stub->getParams());
        $this->assertSame([], $stub->getParams(['param1']));
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
        $this->assertSame(null, $stub->toArray());
    }

    public function testToJson()
    {
        $stub = $this->getMockForAbstractClass(Object::class);
        $this->assertNull($stub->toJson());
    }
}
