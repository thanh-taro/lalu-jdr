<?php

namespace LaLu\JDR;

use Faker\Factory;
use LaLu\JDR\BaseJsonObjectTestCase;
use LaLu\JDR\JsonObjects\V1_0\Resource;
use LaLu\JDR\JsonObjects\V1_0\Meta;

class ResourceTest extends BaseJsonObjectTestCase
{
    public function testAttributes()
    {
        $object = new Resource();

        $this->assertClassHasAttribute('_params', Resource::class);
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
    }

    public function testConstructionSetGetAddParams()
    {
        $faker = Factory::create();

        $id = $faker->uuid;
        $object = new Resource(['id' => $id]);
        $this->assertSame($id, $object->id);
        $this->assertSame(['id' => $id], $object->getParams());
        $this->assertSame(['id' => $id], $object->getParams(['id']));

        $id = $faker->uuid;
        $object = new Resource();
        $object->set('id', $id);
        $this->assertSame($id, $object->id);
        $this->assertSame(['id' => $id], $object->getParams());
        $this->assertSame(['id' => $id], $object->getParams(['id']));

        $id = $faker->uuid;
        $object = new Resource();
        $object->add('id', $id);
        $this->assertSame([$id], $object->id);
        $this->assertSame(['id' => [$id]], $object->getParams());
        $this->assertSame(['id' => [$id]], $object->getParams(['id']));

        $id = $faker->uuid;
        $object = new Resource();
        $object->add('id', $id, 'key');
        $this->assertSame(['key' => $id], $object->id);
        $this->assertSame(['id' => ['key' => $id]], $object->getParams());
        $this->assertSame(['id' => ['key' => $id]], $object->getParams(['id']));

        $meta = new Meta(['pointer' => $faker->word]);
        $object = new Resource(['meta' => $meta]);
        $this->assertSame($meta, $object->meta);
        $this->assertSame(['meta' => $meta], $object->getParams());
        $this->assertSame(['meta' => $meta], $object->getParams(['meta']));

        $meta = new Meta(['pointer' => $faker->word]);
        $object = new Resource();
        $object->set('meta', $meta);
        $this->assertSame($meta, $object->meta);
        $this->assertSame(['meta' => $meta], $object->getParams());
        $this->assertSame(['meta' => $meta], $object->getParams(['meta']));

        $meta = new Meta(['pointer' => $faker->word]);
        $object = new Resource();
        $object->add('meta', $meta);
        $this->assertSame([$meta], $object->meta);
        $this->assertSame(['meta' => [$meta]], $object->getParams());
        $this->assertSame(['meta' => [$meta]], $object->getParams(['meta']));

        $meta = new Meta(['pointer' => $faker->word]);
        $object = new Resource();
        $object->add('meta', $meta, 'key');
        $this->assertSame(['key' => $meta], $object->meta);
        $this->assertSame(['meta' => ['key' => $meta]], $object->getParams());
        $this->assertSame(['meta' => ['key' => $meta]], $object->getParams(['meta']));

        $jsonStruct = $object->getJsonStruct();
        if (is_array($jsonStruct)) {
            if (!empty($jsonStruct)) {
                foreach ($jsonStruct as $field) {
                    for ($i = 0;$i < static::MAX_LOOP;++$i) {
                        $value = $faker->word;
                        $object = new Resource([$field => $value]);
                        $this->assertSame($value, $object->$field);
                    }
                }
                $params = [];
                foreach ($jsonStruct as $field) {
                    $params[$field] = $faker->word;
                }
                $object = new Resource($params);
                foreach ($params as $field => $value) {
                    $this->assertSame($value, $object->$field);
                }
                $this->assertSame($params, $object->getParams());
            } else {
                for ($i = 0;$i < static::MAX_LOOP;++$i) {
                    $field = $faker->word;
                    $value = $faker->word;
                    $object = new Resource([$field => $value]);
                    $this->assertSame($value, $object->$field);
                }
            }
        }
    }

    public function testToArrayToJson()
    {
        $object = new Resource();
        $this->assertSame(null, $object->toArray());
        $this->assertNull($object->toJson());

        $object = new Resource(['id' => 1, 'type' => 'user']);
        $this->assertSame(['id' => 1, 'type' => 'user'], $object->toArray());
        $this->assertSame('{"id":1,"type":"user"}', $object->toJson());
    }
}
