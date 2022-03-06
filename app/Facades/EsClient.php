<?php

namespace App\Facades;

/**
 * @method static bool is_available($entrySubmission)
 */

use Illuminate\Support\Facades\Facade;

class EsClient extends Facade
{
//    protected static function resolveFacade($name)
//    {
//        return app()[$name];
//    }
//
//    public static function __callStatic($method, $arguments)
//    {
//        return self::resolveFacade('LoyaltyProgram')
//            ->$method(...$arguments);
//    }
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'EsClient';
    }
}
