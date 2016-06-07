<?php

namespace LaLu\JDR\V1_0;

use Faker\Factory;
use LaLu\JDR\BaseJsonObjectTestCase;
use LaLu\JDR\JsonObjects\V1_0\Error;
use LaLu\JDR\JsonObjects\V1_0\Source;

class ErrorTest extends BaseJsonObjectTestCase
{
    public function testAttributes()
    {
        $object = new Error();

        $this->assertClassHasAttribute('_params', Error::class);
        $this->assertAttributeEquals([], '_params', $object);
    }

    public function testGetJsonStruct()
    {
        $faker = Factory::create();

        $object = new Error();
        $this->assertContainsOnly('string', $object->getJsonStruct());
        $this->assertContains('id', $object->getJsonStruct());
        $this->assertContains('status', $object->getJsonStruct());
        $this->assertContains('code', $object->getJsonStruct());
        $this->assertContains('title', $object->getJsonStruct());
        $this->assertContains('detail', $object->getJsonStruct());
        $this->assertContains('source', $object->getJsonStruct());
        $this->assertContains('links', $object->getJsonStruct());
        $this->assertContains('meta', $object->getJsonStruct());
        $this->assertSame(['id', 'status', 'code', 'title', 'detail', 'source', 'links', 'meta'], $object->getJsonStruct());
    }

    public function testConstructionSetGetAddParams()
    {
        $faker = Factory::create();

        $id = $faker->uuid;
        $object = new Error(['id' => $id]);
        $this->assertSame($id, $object->id);
        $this->assertSame(['id' => $id], $object->getParams());
        $this->assertSame(['id' => $id], $object->getParams(['id']));

        $id = $faker->uuid;
        $object = new Error();
        $object->set('id', $id);
        $this->assertSame($id, $object->id);
        $this->assertSame(['id' => $id], $object->getParams());
        $this->assertSame(['id' => $id], $object->getParams(['id']));

        $id = $faker->uuid;
        $object = new Error();
        $object->add('id', $id);
        $this->assertSame([$id], $object->id);
        $this->assertSame(['id' => [$id]], $object->getParams());
        $this->assertSame(['id' => [$id]], $object->getParams(['id']));

        $id = $faker->uuid;
        $object = new Error();
        $object->add('id', $id, 'key');
        $this->assertSame(['key' => $id], $object->id);
        $this->assertSame(['id' => ['key' => $id]], $object->getParams());
        $this->assertSame(['id' => ['key' => $id]], $object->getParams(['id']));

        $source = new Source(['pointer' => $faker->word]);
        $object = new Error(['source' => $source]);
        $this->assertSame($source, $object->source);
        $this->assertSame(['source' => $source], $object->getParams());
        $this->assertSame(['source' => $source], $object->getParams(['source']));

        $source = new Source(['pointer' => $faker->word]);
        $object = new Error();
        $object->set('source', $source);
        $this->assertSame($source, $object->source);
        $this->assertSame(['source' => $source], $object->getParams());
        $this->assertSame(['source' => $source], $object->getParams(['source']));

        $source = new Source(['pointer' => $faker->word]);
        $object = new Error();
        $object->add('source', $source);
        $this->assertSame([$source], $object->source);
        $this->assertSame(['source' => [$source]], $object->getParams());
        $this->assertSame(['source' => [$source]], $object->getParams(['source']));

        $source = new Source(['pointer' => $faker->word]);
        $object = new Error();
        $object->add('source', $source, 'key');
        $this->assertSame(['key' => $source], $object->source);
        $this->assertSame(['source' => ['key' => $source]], $object->getParams());
        $this->assertSame(['source' => ['key' => $source]], $object->getParams(['source']));

        $jsonStruct = $object->getJsonStruct();
        if (is_array($jsonStruct)) {
            if (!empty($jsonStruct)) {
                foreach ($jsonStruct as $field) {
                    for ($i = 0;$i < static::MAX_LOOP;++$i) {
                        $value = $faker->word;
                        $object = new Error([$field => $value]);
                        $this->assertSame($value, $object->$field);
                    }
                }
                $params = [];
                foreach ($jsonStruct as $field) {
                    $params[$field] = $faker->word;
                }
                $object = new Error($params);
                foreach ($params as $field => $value) {
                    $this->assertSame($value, $object->$field);
                }
                $this->assertSame($params, $object->getParams());
            } else {
                for ($i = 0;$i < static::MAX_LOOP;++$i) {
                    $field = $faker->word;
                    $value = $faker->word;
                    $object = new Error([$field => $value]);
                    $this->assertSame($value, $object->$field);
                }
            }
        }
    }

    public function testToArrayToJson()
    {
        $object = new Error();
        $this->assertSame(null, $object->toArray());
        $this->assertNull($object->toJson());

        $object = new Error(['id' => '1234', 'title' => 'error']);
        $this->assertSame(['id' => '1234', 'title' => 'error'], $object->toArray());
        $this->assertSame('{"id":"1234","title":"error"}', $object->toJson());
    }
}
