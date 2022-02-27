<?php

namespace App\Contracts\Crypto;

interface CryptoFactoryInterface
{
    public static function make(CryptoInterface $crypto);
}
