<?php

namespace LaLu\JDR;

use Faker\Factory;
use LaLu\JDR\BaseJsonObjectTestCase;
use LaLu\JDR\JsonObjects\V1_0\Source;

class SourceTest extends BaseJsonObjectTestCase
{
    public function testAttributes()
    {
        $object = new Source();

        $this->assertClassHasAttribute('_params', Source::class);
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
    }

    public function testConstructionSetGetAddParams()
    {
        $faker = Factory::create();

        $pointer = $faker->url;
        $object = new Source(['pointer' => $pointer]);
        $this->assertSame($pointer, $object->pointer);
        $this->assertSame(['pointer' => $pointer], $object->getParams());
        $this->assertSame(['pointer' => $pointer], $object->getParams(['pointer']));

        $pointer = $faker->url;
        $object = new Source();
        $object->set('pointer', $pointer);
        $this->assertSame($pointer, $object->pointer);
        $this->assertSame(['pointer' => $pointer], $object->getParams());
        $this->assertSame(['pointer' => $pointer], $object->getParams(['pointer']));

        $pointer = $faker->url;
        $object = new Source();
        $object->add('pointer', $pointer);
        $this->assertSame([$pointer], $object->pointer);
        $this->assertSame(['pointer' => [$pointer]], $object->getParams());
        $this->assertSame(['pointer' => [$pointer]], $object->getParams(['pointer']));

        $pointer = $faker->url;
        $object = new Source();
        $object->add('pointer', $pointer, 'key');
        $this->assertSame(['key' => $pointer], $object->pointer);
        $this->assertSame(['pointer' => ['key' => $pointer]], $object->getParams());
        $this->assertSame(['pointer' => ['key' => $pointer]], $object->getParams(['pointer']));

        $jsonStruct = $object->getJsonStruct();
        if (is_array($jsonStruct)) {
            if (!empty($jsonStruct)) {
                foreach ($jsonStruct as $field) {
                    for ($i = 0;$i < static::MAX_LOOP;++$i) {
                        $value = $faker->word;
                        $object = new Source([$field => $value]);
                        $this->assertSame($value, $object->$field);
                    }
                }
                $params = [];
                foreach ($jsonStruct as $field) {
                    $params[$field] = $faker->word;
                }
                $object = new Source($params);
                foreach ($params as $field => $value) {
                    $this->assertSame($value, $object->$field);
                }
                $this->assertSame($params, $object->getParams());
            } else {
                for ($i = 0;$i < static::MAX_LOOP;++$i) {
                    $field = $faker->word;
                    $value = $faker->word;
                    $object = new Source([$field => $value]);
                    $this->assertSame($value, $object->$field);
                }
            }
        }
    }

    public function testToArrayToJson()
    {
        $object = new Source();
        $this->assertSame(null, $object->toArray());
        $this->assertNull($object->toJson());

        $object = new Source(['pointer' => 'email']);
        $this->assertSame(['pointer' => 'email'], $object->toArray());
        $this->assertSame('{"pointer":"email"}', $object->toJson());
    }
}
