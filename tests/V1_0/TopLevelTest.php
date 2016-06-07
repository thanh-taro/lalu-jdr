<?php

namespace LaLu\JDR;

use Faker\Factory;
use LaLu\JDR\BaseJsonObjectTestCase;
use LaLu\JDR\JsonObjects\V1_0\TopLevel;
use LaLu\JDR\JsonObjects\V1_0\Meta;

class TopLevelTest extends BaseJsonObjectTestCase
{
    public function testAttributes()
    {
        $object = new TopLevel();

        $this->assertClassHasAttribute('_params', TopLevel::class);
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
        $this->assertSame(['data', 'errors', 'included', 'links', 'meta', 'jsonapi'], $object->getJsonStruct());
    }
}
