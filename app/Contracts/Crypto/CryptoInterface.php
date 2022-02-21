<?php
namespace App\Contracts\Crypto;

interface CryptoInterface {
    public static function add(object $source_db, string $crypto_name, string $currency);
    public static function find(string $crypto_name, string $currency);
    public static function delete(string $source_name, string $crypto_name, string $currency);
}
