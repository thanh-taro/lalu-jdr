<?php

namespace LaLu\JDR\JsonObjects;

use Illuminate\Database\Eloquent\Model;
use ReflectionClass;

class TopLevel extends Object
{
    /**
     * Get jsonapi struct.
     *
     * @return string[]|false
     */
    public function getJsonStruct()
    {
        if ($this->getVersion() === '1.0') {
            return ['data', 'errors', 'meta', 'jsonapi', 'links', 'included'];
        }

        return false;
    }

    /**
     * Set eloquent data.
     *
     * @param \Illuminate\Database\Eloquent\Model $data
     *
     * @return $this
     */
    public function setEloquentData(Model $data)
    {
        $this->delete('errors');
        $resource = (new Resource())->set('id', $data->id)
                        ->set('type', $this->_getTypeFromInstance($data))
                        ->set('attributes', $data->attributesToArray());

        return $this;
    }

    private function _getTypeFromInstance($instance)
    {
        $reflect = new ReflectionClass($object);

        return strtolower($reflect->getShortName());
    }
}
