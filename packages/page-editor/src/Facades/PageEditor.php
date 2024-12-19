<?php

namespace Packages\PageEditor\Facades;

use Illuminate\Support\Facades\Facade;

class PageEditor extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'page-editor';
    }
}
