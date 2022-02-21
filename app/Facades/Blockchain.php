<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class Blockchain extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'BlockchainService';
    }
}
