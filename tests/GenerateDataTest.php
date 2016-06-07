<?php

namespace LaLu\JDR;

use JDR;
use LaLu\JDR\JsonObjects\V1_0\TopLevel;
use LaLu\JDR\JsonObjects\V1_0\Resource;

class GenerateDataTest extends AbstractTestCase
{
    public function testBasic()
    {
        $topLevel = (new TopLevel())->set('data', null)->set('meta', ['author' => 'Thanh Taro']);
        $this->assertJsonApi(JDR::generateData($topLevel), 200, '{"data":null,"meta":{"author":"Thanh Taro"},"jsonapi":{"version":"1.0"}}', []);
        $topLevel = (new TopLevel())->set('data', (new Resource()))->set('meta', ['author' => 'Thanh Taro']);
        $this->assertJsonApi(JDR::generateData($topLevel), 200, '{"data":null,"meta":{"author":"Thanh Taro"},"jsonapi":{"version":"1.0"}}', []);
        $data = new Resource();
        $topLevel = (new TopLevel())->set('data', $data)->set('meta', ['author' => 'Thanh Taro']);
        $this->assertJsonApi(JDR::generateData($topLevel), 200, '{"data":null,"meta":{"author":"Thanh Taro"},"jsonapi":{"version":"1.0"}}', []);

        $this->assertJsonApi(JDR::generateData(null), 204);
        $topLevel = (new TopLevel());
        $this->assertJsonApi(JDR::generateData($topLevel), 204);
        $topLevel = (new TopLevel())->set('data', null)->set('meta', ['author' => 'Thanh Taro']);
        $this->assertJsonApi(JDR::generateData($topLevel), 204);
        $data = new Resource();
        $topLevel = (new TopLevel())->set('data', $data)->set('meta', ['author' => 'Thanh Taro']);
        $this->assertJsonApi(JDR::generateData($topLevel, 204), 204);

        $this->assertJsonApi(JDR::generateData(null, 200), 200, '{}');
        $topLevel = (new TopLevel());
        $this->assertJsonApi(JDR::generateData($topLevel, 200), 200, '{}');

        $this->assertJsonApi(JDR::generateData(null, null, ['Author' => 'Thanh Taro']), 204, null, ['Author' => 'Thanh Taro']);

        $this->assertJsonApi(JDR::generateData(null, 500), 200, '{}');
    }
}
