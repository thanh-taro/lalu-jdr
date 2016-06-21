<?php

namespace LaLu\JDR\JsonObjects;

abstract class Object
{
    protected $_params = [];

    /**
     * Get jsonapi struct.
     *
     * @return string[]|false
     */
    abstract public function getJsonStruct();

    /**
     * Constructor.
     *
     * @param array $params
     */
    public function __construct(array $params = [])
    {
        if (!empty($params)) {
            $this->setParams($params);
        }
    }

    /**
     * Convert param into Jsonapi object
     *
     * @param string $field
     * @param mixed  $params
     *
     * @return static
     */
    public function convert($field, $params)
    {
        return $params;
    }

    /**
     * Set params.
     *
     * @param array $params
     *
     * @return $this
     */
    public function setParams(array $params = [])
    {
        $this->_params = [];
        $this->addParams($params);

        return $this;
    }

    /**
     * Add params.
     *
     * @param array $params
     *
     * @return $this
     */
    public function addParams(array $params)
    {
        if (empty($params)) {
            return $this;
        }
        $jsonStruct = $this->getJsonStruct();
        if ($jsonStruct === false || $jsonStruct === null || !is_array($jsonStruct)) {
            return $this;
        }
        foreach ($params as $field => $value) {
            if (empty($jsonStruct) || in_array($field, $jsonStruct)) {
                $this->_params[$field] = $this->convert($field, $value);
            }
        }

        return $this;
    }

    /**
     * Get params.
     *
     * @param array $fields
     *
     * @return array
     */
    public function getParams(array $fields = [])
    {
        if (empty($this->_params)) {
            return [];
        }
        $jsonStruct = $this->getJsonStruct();
        if ($jsonStruct === false || $jsonStruct === null || !is_array($jsonStruct)) {
            return [];
        }
        if (empty($fields)) {
            return $this->_params;
        }
        $result = [];
        foreach ($fields as $field) {
            if (isset($this->_params[$field])) {
                $result[$field] = $this->_params[$field];
            } elseif (empty($jsonStruct) || in_array($field, $jsonStruct)) {
                $result[$field] = null;
            }
        }

        return $result;
    }

    /**
     * Set param.
     *
     * @param string $field
     * @param mixed  $value
     *
     * @return $this
     */
    public function set($field, $value)
    {
        $jsonStruct = $this->getJsonStruct();
        if ($jsonStruct === false || $jsonStruct === null || !is_array($jsonStruct) || empty($field)) {
            return $this;
        }
        if (empty($jsonStruct) || in_array($field, $jsonStruct)) {
            $this->_params[$field] = $this->convert($field, $value);
        }

        return $this;
    }

    /**
     * Add param.
     *
     * @param string      $field
     * @param mixed       $value
     * @param string|null $key
     *
     * @return $this
     */
    public function add($field, $value, $key = null)
    {
        $jsonStruct = $this->getJsonStruct();
        if ($jsonStruct === false || $jsonStruct === null || !is_array($jsonStruct) || empty($field)) {
            return $this;
        }
        if (empty($jsonStruct) || in_array($field, $jsonStruct)) {
            if (empty($this->_params[$field])) {
                $this->_params[$field] = [];
            }
            if ($key === null) {
                $this->_params[$field][] = $this->convert($field, $value);
            } else {
                $this->_params[$field][$key] = $this->convert($field, $value);
            }
        }

        return $this;
    }

    /**
     * Delete param.
     *
     * @return $this
     */
    public function delete($field)
    {
        unset($this->_params[$field]);

        return $this;
    }

    /**
     * Getter.
     *
     * @param string $field
     *
     * @return mixed
     */
    public function __get($field)
    {
        $jsonStruct = $this->getJsonStruct();

        return (!empty($jsonStruct) && !in_array($field, $jsonStruct)) || empty($this->_params) ? null : (isset($this->_params[$field]) ? $this->_params[$field] : null);
    }

    /**
     * Get array data.
     *
     * @return array|null
     */
    public function toArray()
    {
        if (empty($this->_params)) {
            return;
        }
        $result = [];
        foreach ($this->_params as $field => $value) {
            if ($value instanceof self) {
                $result[$field] = $value->toArray();
            } elseif (is_array($value) && !empty($value)) {
                foreach ($value as $key => $val) {
                    $result[$field][$key] = ($val instanceof self) ? $val->toArray() : $val;
                }
            } else {
                $result[$field] = $value;
            }
        }

        return empty($result) ? null : $result;
    }

    /**
     * Get json data.
     *
     * @return string
     */
    public function toJson()
    {
        $arr = $this->toArray();

        return empty($arr) ? null : json_encode($arr);
    }
}
