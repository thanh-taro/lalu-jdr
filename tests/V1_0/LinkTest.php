<?php

namespace LaLu\JDR;

use Faker\Factory;
use LaLu\JDR\BaseJsonObjectTestCase;
use LaLu\JDR\JsonObjects\V1_0\Link;
use LaLu\JDR\JsonObjects\V1_0\Meta;

class LinkTest extends BaseJsonObjectTestCase
{
    public function testAttributes()
    {
        $object = new Link();

        $this->assertClassHasAttribute('_params', Link::class);
        $this->assertAttributeEquals([], '_params', $object);
    }

    public function testGetJsonStruct()
    {
        $faker = Factory::create();

        $object = new Link();
        $this->assertContainsOnly('string', $object->getJsonStruct());
        $this->assertContains('href', $object->getJsonStruct());
        $this->assertContains('meta', $object->getJsonStruct());
        $this->assertSame(['href', 'meta'], $object->getJsonStruct());
    }

    public function testConstructionSetGetAddParams()
    {
        $faker = Factory::create();

        $href = $faker->url;
        $object = new Link(['href' => $href]);
        $this->assertSame($href, $object->href);
        $this->assertSame(['href' => $href], $object->getParams());
        $this->assertSame(['href' => $href], $object->getParams(['href']));

        $href = $faker->url;
        $object = new Link();
        $object->set('href', $href);
        $this->assertSame($href, $object->href);
        $this->assertSame(['href' => $href], $object->getParams());
        $this->assertSame(['href' => $href], $object->getParams(['href']));

        $href = $faker->url;
        $object = new Link();
        $object->add('href', $href);
        $this->assertSame([$href], $object->href);
        $this->assertSame(['href' => [$href]], $object->getParams());
        $this->assertSame(['href' => [$href]], $object->getParams(['href']));

        $href = $faker->url;
        $object = new Link();
        $object->add('href', $href, 'key');
        $this->assertSame(['key' => $href], $object->href);
        $this->assertSame(['href' => ['key' => $href]], $object->getParams());
        $this->assertSame(['href' => ['key' => $href]], $object->getParams(['href']));

        $meta = new Meta(['pointer' => $faker->word]);
        $object = new Link(['meta' => $meta]);
        $this->assertSame($meta, $object->meta);
        $this->assertSame(['meta' => $meta], $object->getParams());
        $this->assertSame(['meta' => $meta], $object->getParams(['meta']));

        $meta = new Meta(['pointer' => $faker->word]);
        $object = new Link();
        $object->set('meta', $meta);
        $this->assertSame($meta, $object->meta);
        $this->assertSame(['meta' => $meta], $object->getParams());
        $this->assertSame(['meta' => $meta], $object->getParams(['meta']));

        $meta = new Meta(['pointer' => $faker->word]);
        $object = new Link();
        $object->add('meta', $meta);
        $this->assertSame([$meta], $object->meta);
        $this->assertSame(['meta' => [$meta]], $object->getParams());
        $this->assertSame(['meta' => [$meta]], $object->getParams(['meta']));

        $meta = new Meta(['pointer' => $faker->word]);
        $object = new Link();
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
                        $object = new Link([$field => $value]);
                        $this->assertSame($value, $object->$field);
                    }
                }
                $params = [];
                foreach ($jsonStruct as $field) {
                    $params[$field] = $faker->word;
                }
                $object = new Link($params);
                foreach ($params as $field => $value) {
                    $this->assertSame($value, $object->$field);
                }
                $this->assertSame($params, $object->getParams());
            } else {
                for ($i = 0;$i < static::MAX_LOOP;++$i) {
                    $field = $faker->word;
                    $value = $faker->word;
                    $object = new Link([$field => $value]);
                    $this->assertSame($value, $object->$field);
                }
            }
        }
    }

    public function testToArrayToJson()
    {
        $object = new Link();
        $this->assertSame(null, $object->toArray());
        $this->assertNull($object->toJson());

        $object = new Link(['href' => 'http://www.skilesdonnelly.biz/aut-accusantium-ut-architecto-sit-et.html', 'meta' => ['author' => 'Thanh Taro <adamnguyen.itdn@gmail.com>']]);
        $this->assertSame(['href' => 'http://www.skilesdonnelly.biz/aut-accusantium-ut-architecto-sit-et.html', 'meta' => ['author' => 'Thanh Taro <adamnguyen.itdn@gmail.com>']], $object->toArray());
        $this->assertSame('{"href":"http:\/\/www.skilesdonnelly.biz\/aut-accusantium-ut-architecto-sit-et.html","meta":{"author":"Thanh Taro <adamnguyen.itdn@gmail.com>"}}', $object->toJson());
    }
}
