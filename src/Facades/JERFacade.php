<?php

namespace LaLu\JER\Facades;

use Illuminate\Support\Facades\Facade;

class JERFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'lalu-jer';
    }
}
