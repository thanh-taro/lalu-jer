<?php

namespace LaLu\JER;

class Error extends Object
{
    /**
     * Get jsonapi object version.
     *
     * @return string|null
     */
    public function getJsonStruct()
    {
        if ($this->version === '1.0.0') {
            return ['id', 'links', 'status', 'code', 'title', 'detail', 'source', 'meta'];
        }

        return false;
    }
}
