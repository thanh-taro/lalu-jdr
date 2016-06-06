<?php

namespace LaLu\JDR\JsonObjects;

class Error extends Object
{
    /**
     * Get jsonapi struct.
     *
     * @return string[]|false
     */
    public function getJsonStruct()
    {
        if ($this->getVersion() === '1.0') {
            return ['id', 'status', 'code', 'title', 'detail', 'source', 'links', 'meta'];
        }

        return false;
    }
}
