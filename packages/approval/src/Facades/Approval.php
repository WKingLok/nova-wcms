<?php

namespace Packages\Approval\Facades;

use Illuminate\Support\Facades\Facade;

class Approval extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'approval';
    }
}
