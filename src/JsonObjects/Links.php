<?php

namespace LaLu\JDR\JsonObjects;

class Links extends Object
{
    /**
     * Get jsonapi struct.
     *
     * @return array|false
     */
    public function getJsonStruct()
    {
        if ($this->getVersion() === '1.0') {
            return [];
        }

        return false;
    }
}
