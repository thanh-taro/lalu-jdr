<?php

namespace LaLu\JDR\JsonObjects;

class Resource extends Object
{
    /**
     * Get jsonapi struct.
     *
     * @return array|false
     */
    public function getJsonStruct()
    {
        if ($this->getVersion() === '1.0') {
            return ['id', 'type', 'attributes', 'relationships', 'links', 'meta'];
        }

        return false;
    }
}
