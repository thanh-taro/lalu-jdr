<?php

namespace LaLu\JDR\JsonObjects;

class TopLevel extends Object
{
    /**
     * Get jsonapi struct.
     *
     * @return array|false
     */
    public function getJsonStruct()
    {
        if ($this->getVersion() === '1.0') {
            return ['data', 'errors', 'meta', 'jsonapi', 'links', 'included'];
        }

        return false;
    }
}
