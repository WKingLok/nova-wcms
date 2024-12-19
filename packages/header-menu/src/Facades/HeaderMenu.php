<?php

namespace Packages\HeaderMenu\Facades;

use Illuminate\Support\Facades\Facade;

class HeaderMenu extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'header-menu';
    }
}
