<?php
namespace App\Contracts\Crypto;

interface CryptoInterface {
    public static function add(int $source_id, string $crypto_name, string $currency);
    public static function find(string $crypto_name, string $currency);
    public static function delete(string $source_name, string $crypto_name, string $currency);
}
