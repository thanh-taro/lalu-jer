<?php

namespace LaLu\JER;

class Meta extends Object
{
    /**
     * Get jsonapi object version.
     *
     * @return string|null
     */
    public function getJsonStruct()
    {
        if ($this->version === '1.0') {
            return [];
        }

        return false;
    }
}
