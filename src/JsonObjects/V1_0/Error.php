<?php

namespace LaLu\JDR\JsonObjects\V1_0;

use LaLu\JDR\JsonObjects\Object;

class Error extends Object
{
    /**
     * Get jsonapi struct.
     *
     * @return string[]
     */
    public function getJsonStruct()
    {
        return ['id', 'status', 'code', 'title', 'detail', 'source', 'links', 'meta'];
    }
}
