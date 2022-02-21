<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class Coingecko extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'CoingeckoService';
    }
}
