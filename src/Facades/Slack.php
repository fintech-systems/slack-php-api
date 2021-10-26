<?php

namespace FintechSystems\Slack\Facades;

use Illuminate\Support\Facades\Facade;

class Slack extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'slack';
    }
}
