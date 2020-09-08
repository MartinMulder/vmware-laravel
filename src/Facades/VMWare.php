<?php

namespace MartinMulder\VMWare\Laravel\Facades;

use Illuminate\Support\Facades\Facade;

class VMWare extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'vmware';
    }
}
