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
