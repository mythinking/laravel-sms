<?php

namespace Mythinking\LaravelSms\Facades;

use Illuminate\Support\Facades\Facade;

class Sms extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'sms';
    }
}