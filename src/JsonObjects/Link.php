<?php

namespace LaLu\JDR\JsonObjects;

class Link extends Object
{
    /**
     * Get jsonapi struct.
     *
     * @return array|false
     */
    public function getJsonStruct()
    {
        if ($this->getVersion() === '1.0') {
            return ['href', 'meta'];
        }

        return false;
    }
}
