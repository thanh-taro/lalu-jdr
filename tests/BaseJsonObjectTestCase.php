<?php

namespace LaLu\JDR;

use PHPUnit_Framework_TestCase;

abstract class BaseJsonObjectTestCase extends PHPUnit_Framework_TestCase
{
    const MAX_LOOP = 100;

    protected function getLanguage($lang = 'en')
    {
        return include __DIR__."/../src/resources/lang/$lang/messages.php";
    }
}
