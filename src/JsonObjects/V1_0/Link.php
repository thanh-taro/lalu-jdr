<?php

namespace LaLu\JDR\JsonObjects\V1_0;

use LaLu\JDR\JsonObjects\Object;

class Link extends Object
{
    /**
     * Get jsonapi struct.
     *
     * @return string[]
     */
    public function getJsonStruct()
    {
        return ['href', 'meta'];
    }
}
