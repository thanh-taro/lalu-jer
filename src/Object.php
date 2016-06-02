<?php

namespace LaLu\JER;

abstract class Object
{
    protected $version = '1.0';
    protected $attributes;

    /**
     * Get jsonapi struct.
     *
     * @return array|false
     */
    abstract public function getJsonStruct();

    /**
     * Constructor.
     *
     * @param array $option
     */
    public function __construct(array $option = [], array $attributes = [])
    {
        if (!empty($option)) {
            $this->loadOption($option);
        }
        if (!empty($attributes)) {
            $this->setAttributes($attributes);
        }
    }

    /**
     * Load options.
     *
     * @param array $option
     *
     * @return bool
     */
    public function loadOption(array $option)
    {
        if (empty($option)) {
            return true;
        }
        if (isset($option['version'])) {
            $this->version = $option['version'];
        }
    }

    /**
     * Set jsonapi version.
     *
     * @param string $version
     *
     * @return bool
     */
    public function setVersion($version)
    {
        $this->version = $version;

        return true;
    }

    /**
     * Get jsonapi object version.
     *
     * @return string|null
     */
    public function getVersion()
    {
        return empty($this->version) ? null : $this->version;
    }

    /**
     * Set attributes.
     *
     * @param array $attributes
     *
     * @return bool
     */
    public function setAttributes(array $attributes)
    {
        if (empty($attributes)) {
            return true;
        }
        if (empty($jsonStruct)) {
            $this->attributes = $attributes;
        } else {
            foreach ($attributes as $field => $value) {
                if (in_array($field, $jsonStruct)) {
                    $this->attributes[$field] = $value;
                }
            }
        }

        return true;
    }

    /**
     * Get attributes.
     *
     * @param array $fields
     *
     * @return array
     */
    public function getAttributes(array $fields = [])
    {
        if (empty($this->attributes)) {
            return [];
        }
        if (empty($fields)) {
            return $this->attributes;
        }
        $result = [];
        foreach ($fields as $field) {
            if (isset($this->attributes[$field])) {
                $result[$field] = $this->attributes[$field];
            }
        }

        return $result;
    }

    public function getData()
    {
        if (empty($this->attributes)) {
            return;
        }
        $result = [];
        foreach ($this->attributes as $field => $value) {
            if ($value instanceof self) {
                $result[$field] = $value->getData();
            } elseif (is_array($value)) {
                foreach ($value as $key => $val) {
                    $result[$field][$key] = ($val instanceof self) ? $val->getData() : $val;
                }
            } else {
                $result[$field] = $value;
            }
        }

        return $result;
    }

    /**
     * Getter.
     *
     * @param string $field
     * @param mixed  $value
     */
    public function __set($field, $value)
    {
        $jsonStruct = $this->getJsonStruct();
        if (empty($jsonStruct) || in_array($field, $jsonStruct)) {
            $this->attributes[$field] = $value;
        }
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

        return (!empty($jsonStruct) && !in_array($field, $jsonStruct)) || empty($this->attributes) ? null : (!isset($this->attributes[$field]) ? null : $this->attributes[$field]);
    }
}
