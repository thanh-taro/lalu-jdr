<?php

namespace LaLu\JDR;

use Faker\Factory;
use LaLu\JDR\Helpers\Helper;
use LaLu\JDR\JsonObjects\V1_0\Error;
use LaLu\JDR\JsonObjects\V1_0\Jsonapi;
use LaLu\JDR\JsonObjects\V1_0\Link;
use LaLu\JDR\JsonObjects\V1_0\Links;
use LaLu\JDR\JsonObjects\V1_0\Meta;
use LaLu\JDR\JsonObjects\V1_0\Resource;
use LaLu\JDR\JsonObjects\V1_0\Source;
use LaLu\JDR\JsonObjects\V1_0\TopLevel;

class HelperTest extends BaseJsonObjectTestCase
{
    public function testMakeJsonapiObject()
    {
        $arr = [
            'error' => Error::class,
            'toplevel' => TopLevel::class,
            'meta' => Meta::class,
            'jsonapi' => Jsonapi::class,
            'link' => Link::class,
            'links' => Links::class,
            'resource' => Resource::class,
            'source' => Source::class
        ];
        $rightVersion = '1.0';

        foreach ($arr as $key => $class) {
            $this->assertInstanceOf($class, Helper::makeJsonapiObject($rightVersion, $key));
        }

        $faker = Factory::create();
        for ($i = 0; $i <= static::MAX_LOOP; ++$i) {
            foreach ($arr as $key => $class) {
                $version = $faker->word;
                $this->assertNull(Helper::makeJsonapiObject($version, $key));
            }
        }
    }
}
