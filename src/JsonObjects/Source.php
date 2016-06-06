<?php

namespace LaLu\JDR\JsonObjects;

class Source extends Object
{
    /**
     * Get jsonapi struct.
     *
     * @return string[]|false
     */
    public function getJsonStruct()
    {
        if ($this->getVersion() === '1.0') {
            return ['pointer', 'parameter'];
        }

        return false;
    }
}
