<?php

namespace App\Factories;

use App\Contracts\Crypto\CryptoFactoryInterface;
use App\Contracts\Crypto\CryptoInterface;
use Throwable;


class CryptoFactory implements CryptoFactoryInterface
{
    public static function make(string|CryptoInterface $crypto): CryptoInterface|bool
    {
        try {
            return new $crypto();
        } catch (Throwable $e) {
            return false;
        }
    }

}
