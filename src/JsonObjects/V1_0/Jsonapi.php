<?php

namespace LaLu\JDR\JsonObjects\V1_0;

use LaLu\JDR\JsonObjects\Object;

class Jsonapi extends Object
{
    /**
     * Get jsonapi struct.
     *
     * @return string[]
     */
    public function getJsonStruct()
    {
        return ['version', 'meta'];
    }

    /**
     * Convert param into Jsonapi object.
     *
     * @param string $field
     * @param mixed  $params
     *
     * @return static
     */
    public function convert($field, $params)
    {
        switch ($field) {
            case 'meta':
                if ($params instanceof Meta) {
                    return $params;
                } elseif (is_array($params)) {
                    return new Meta($params);
                }

                return;
            default:
                return $params;
        }
    }
}
