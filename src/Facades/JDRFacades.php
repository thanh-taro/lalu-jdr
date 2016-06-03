<?php

namespace LaLu\JDR\Facades;

use Illuminate\Support\Facades\Facade;

class JDRFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'lalu-jdr';
    }
}
