<?php

namespace LaLu\JDR;

use LaLu\JDR\JsonObjects\Link;
use Faker\Factory;

class LinkTest extends BaseJsonObjectTestCase
{
    public function testAttributes()
    {
        $object = new Link();

        $this->assertClassHasAttribute('_version', Link::class);
        $this->assertClassHasAttribute('_params', Link::class);
        $this->assertAttributeEquals('1.0', '_version', $object);
        $this->assertAttributeEquals([], '_params', $object);
    }

    public function testGetJsonStruct()
    {
        $faker = Factory::create();

        $object = new Link();
        $this->assertContainsOnly('string', $object->getJsonStruct());
        $this->assertContains('href', $object->getJsonStruct());
        $this->assertContains('meta', $object->getJsonStruct());
        $object = new Link(['version' => null]);
        $this->assertFalse($object->getJsonStruct());
        $object = new Link(['version' => 1]);
        $this->assertFalse($object->getJsonStruct());
        $object = new Link(['version' => '1']);
        $this->assertFalse($object->getJsonStruct());
        $object = new Link(['version' => 'foo']);
        $this->assertFalse($object->getJsonStruct());
        for ($i = 0; $i <= static::MAX_LOOP; ++$i) {
            $version = $faker->url;
            $object = new Link(['version' => $version]);
            $this->assertFalse($object->getJsonStruct());
            $version = $faker->randomNumber;
            $object = new Link(['version' => $version]);
            $this->assertFalse($object->getJsonStruct());
        }
    }

    public function testLoadOptionsMethod()
    {
        $faker = Factory::create();
        $object = new Link();

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
        $object = new Link();

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

    public function testAddSetGetDeleteMethod()
    {
        $faker = Factory::create();
        $object = new Link();

        for ($i = 0; $i <= static::MAX_LOOP; ++$i) {
            $field = $faker->word;
            $this->assertNull($object->$field);
            $this->assertSame([], $object->getParams());
        }
        $object->setVersion(null);
        $this->assertSame([], $object->getParams());
        $this->assertSame([], $object->getParams(['meta']));

        $object = new Link();
        $this->assertSame([], $object->getParams());
        $this->assertSame($object, $object->set('href', 'http://www.skilesdonnelly.biz/aut-accusantium-ut-architecto-sit-et.html'));
        $this->assertSame('http://www.skilesdonnelly.biz/aut-accusantium-ut-architecto-sit-et.html', $object->href);
        $this->assertSame(['href' => 'http://www.skilesdonnelly.biz/aut-accusantium-ut-architecto-sit-et.html'], $object->getParams());
        $this->assertSame(['href' => 'http://www.skilesdonnelly.biz/aut-accusantium-ut-architecto-sit-et.html'], $object->getParams(['href']));
        $this->assertSame(['href' => 'http://www.skilesdonnelly.biz/aut-accusantium-ut-architecto-sit-et.html', 'meta' => null], $object->getParams(['href', 'meta']));
        $this->assertSame($object, $object->set('meta', ['author' => 'Thanh Taro <adamnguyen.itdn@gmail.com>']));
        $this->assertSame(['author' => 'Thanh Taro <adamnguyen.itdn@gmail.com>'], $object->meta);
        $this->assertSame(['href' => 'http://www.skilesdonnelly.biz/aut-accusantium-ut-architecto-sit-et.html', 'meta' => ['author' => 'Thanh Taro <adamnguyen.itdn@gmail.com>']], $object->getParams());
        $this->assertSame(['href' => 'http://www.skilesdonnelly.biz/aut-accusantium-ut-architecto-sit-et.html'], $object->getParams(['href']));
        $this->assertSame(['meta' => ['author' => 'Thanh Taro <adamnguyen.itdn@gmail.com>']], $object->getParams(['meta']));
        for ($i = 0; $i <= static::MAX_LOOP; ++$i) {
            $field = $faker->word;
            if ($field !== 'href' && $field !== 'meta') {
                $this->assertSame($object, $object->set($field, $faker->text));
                $this->assertSame('http://www.skilesdonnelly.biz/aut-accusantium-ut-architecto-sit-et.html', $object->href);
                $this->assertSame(['author' => 'Thanh Taro <adamnguyen.itdn@gmail.com>'], $object->meta);
                $this->assertSame(['href' => 'http://www.skilesdonnelly.biz/aut-accusantium-ut-architecto-sit-et.html', 'meta' => ['author' => 'Thanh Taro <adamnguyen.itdn@gmail.com>']], $object->getParams());
                $this->assertSame(['href' => 'http://www.skilesdonnelly.biz/aut-accusantium-ut-architecto-sit-et.html'], $object->getParams(['href']));
                $this->assertSame(['meta' => ['author' => 'Thanh Taro <adamnguyen.itdn@gmail.com>']], $object->getParams(['meta']));
            }
        }
        $this->assertSame($object, $object->delete('meta'));
        $this->assertNull($object->meta);
        $this->assertSame(['href' => 'http://www.skilesdonnelly.biz/aut-accusantium-ut-architecto-sit-et.html'], $object->getParams());
        $this->assertSame(['href' => 'http://www.skilesdonnelly.biz/aut-accusantium-ut-architecto-sit-et.html'], $object->getParams(['href']));
        $this->assertSame(['meta' => null], $object->getParams(['meta']));
    }

    public function testToArray()
    {
        $object = new Link();
        $this->assertSame([], $object->toArray());
        $object = new Link([], ['href' => 'http://www.skilesdonnelly.biz/aut-accusantium-ut-architecto-sit-et.html', 'meta' => ['author' => 'Thanh Taro <adamnguyen.itdn@gmail.com>']]);
        $this->assertSame(['href' => 'http://www.skilesdonnelly.biz/aut-accusantium-ut-architecto-sit-et.html', 'meta' => ['author' => 'Thanh Taro <adamnguyen.itdn@gmail.com>']], $object->toArray());
    }

    public function testToJson()
    {
        $object = new Link();
        $this->assertNull($object->toJson());
        $object = new Link([], ['href' => 'http://www.skilesdonnelly.biz/aut-accusantium-ut-architecto-sit-et.html', 'meta' => ['author' => 'Thanh Taro <adamnguyen.itdn@gmail.com>']]);
        $this->assertSame('{"href":"http:\/\/www.skilesdonnelly.biz\/aut-accusantium-ut-architecto-sit-et.html","meta":{"author":"Thanh Taro <adamnguyen.itdn@gmail.com>"}}', $object->toJson());
    }
}
