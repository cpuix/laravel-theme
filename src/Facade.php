<?php

namespace Cankod\Theme;

class Facade extends \Illuminate\Support\Facades\Facade
{
    protected static function getFacadeAccessor()
    {
        return 'theme';
    }
}
