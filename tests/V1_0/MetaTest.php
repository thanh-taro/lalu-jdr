<?php

namespace LaLu\JDR;

use Faker\Factory;
use LaLu\JDR\BaseJsonObjectTestCase;
use LaLu\JDR\JsonObjects\V1_0\Meta;
use LaLu\JDR\JsonObjects\V1_0\Source;

class MetaTest extends BaseJsonObjectTestCase
{
    public function testAttributes()
    {
        $object = new Meta();

        $this->assertClassHasAttribute('_params', Meta::class);
        $this->assertAttributeEquals([], '_params', $object);
    }

    public function testGetJsonStruct()
    {
        $object = new Meta();
        $this->assertSame([], $object->getJsonStruct());
    }

    public function testConstructionSetGetAddParams()
    {
        $faker = Factory::create();

        $id = $faker->uuid;
        $object = new Meta(['id' => $id]);
        $this->assertSame($id, $object->id);
        $this->assertSame(['id' => $id], $object->getParams());
        $this->assertSame(['id' => $id], $object->getParams(['id']));

        $id = $faker->uuid;
        $object = new Meta();
        $object->set('id', $id);
        $this->assertSame($id, $object->id);
        $this->assertSame(['id' => $id], $object->getParams());
        $this->assertSame(['id' => $id], $object->getParams(['id']));

        $id = $faker->uuid;
        $object = new Meta();
        $object->add('id', $id);
        $this->assertSame([$id], $object->id);
        $this->assertSame(['id' => [$id]], $object->getParams());
        $this->assertSame(['id' => [$id]], $object->getParams(['id']));

        $id = $faker->uuid;
        $object = new Meta();
        $object->add('id', $id, 'key');
        $this->assertSame(['key' => $id], $object->id);
        $this->assertSame(['id' => ['key' => $id]], $object->getParams());
        $this->assertSame(['id' => ['key' => $id]], $object->getParams(['id']));

        $source = new Source(['pointer' => $faker->word]);
        $object = new Meta(['source' => $source]);
        $this->assertSame($source, $object->source);
        $this->assertSame(['source' => $source], $object->getParams());
        $this->assertSame(['source' => $source], $object->getParams(['source']));

        $source = new Source(['pointer' => $faker->word]);
        $object = new Meta();
        $object->set('source', $source);
        $this->assertSame($source, $object->source);
        $this->assertSame(['source' => $source], $object->getParams());
        $this->assertSame(['source' => $source], $object->getParams(['source']));

        $source = new Source(['pointer' => $faker->word]);
        $object = new Meta();
        $object->add('source', $source);
        $this->assertSame([$source], $object->source);
        $this->assertSame(['source' => [$source]], $object->getParams());
        $this->assertSame(['source' => [$source]], $object->getParams(['source']));

        $source = new Source(['pointer' => $faker->word]);
        $object = new Meta();
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
                        $object = new Meta([$field => $value]);
                        $this->assertSame($value, $object->$field);
                    }
                }
                $params = [];
                foreach ($jsonStruct as $field) {
                    $params[$field] = $faker->word;
                }
                $object = new Meta($params);
                foreach ($params as $field => $value) {
                    $this->assertSame($value, $object->$field);
                }
                $this->assertSame($params, $object->getParams());
            } else {
                for ($i = 0;$i < static::MAX_LOOP;++$i) {
                    $field = $faker->word;
                    $value = $faker->word;
                    $object = new Meta([$field => $value]);
                    $this->assertSame($value, $object->$field);
                }
            }
        }
    }

    public function testToArrayToJson()
    {
        $object = new Meta();
        $this->assertSame(null, $object->toArray());
        $this->assertNull($object->toJson());

        $object = new Meta(['self' => 'http://www.skilesdonnelly.biz/aut-accusantium-ut-architecto-sit-et.html']);
        $this->assertSame(['self' => 'http://www.skilesdonnelly.biz/aut-accusantium-ut-architecto-sit-et.html'], $object->toArray());
        $this->assertSame('{"self":"http:\/\/www.skilesdonnelly.biz\/aut-accusantium-ut-architecto-sit-et.html"}', $object->toJson());
    }
}
