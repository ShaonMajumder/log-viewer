<?php

namespace Shaon\Facades;

use Illuminate\Support\Facades\Facade;

class LogViewer extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'log-viewer'; // This should match the key you use to bind the service in the service provider.
    }
}
