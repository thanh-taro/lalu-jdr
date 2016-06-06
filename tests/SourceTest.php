<?php

namespace LaLu\JDR;

use LaLu\JDR\JsonObjects\Source;
use Faker\Factory;

class SourceTest extends BaseJsonObjectTestCase
{
    public function testAttributes()
    {
        $object = new Source();

        $this->assertClassHasAttribute('_version', Source::class);
        $this->assertClassHasAttribute('_params', Source::class);
        $this->assertAttributeEquals('1.0', '_version', $object);
        $this->assertAttributeEquals([], '_params', $object);
    }

    public function testGetJsonStruct()
    {
        $faker = Factory::create();

        $object = new Source();
        $this->assertContainsOnly('string', $object->getJsonStruct());
        $this->assertContains('pointer', $object->getJsonStruct());
        $this->assertContains('parameter', $object->getJsonStruct());
        $this->assertSame(['pointer', 'parameter'], $object->getJsonStruct());
        $object = new Source(['version' => null]);
        $this->assertFalse($object->getJsonStruct());
        $object = new Source(['version' => 1]);
        $this->assertFalse($object->getJsonStruct());
        $object = new Source(['version' => '1']);
        $this->assertFalse($object->getJsonStruct());
        $object = new Source(['version' => 'foo']);
        $this->assertFalse($object->getJsonStruct());
        for ($i = 0; $i <= static::MAX_LOOP; ++$i) {
            $version = $faker->text;
            $object = new Source(['version' => $version]);
            $this->assertFalse($object->getJsonStruct());
            $version = $faker->randomNumber;
            $object = new Source(['version' => $version]);
            $this->assertFalse($object->getJsonStruct());
        }
    }
}
