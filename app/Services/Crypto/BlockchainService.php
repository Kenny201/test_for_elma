<?php

namespace App\Services\Crypto;

use App\Contracts\Crypto\CryptoInterface;
use App\Http\Integrations\Blockchain\BlockchainConnector;
use App\Models\Course;
use Illuminate\Support\Str;

class BlockchainService implements CryptoInterface
{

    public static function add(object $source_db, string $crypto_name, string $currency): bool
    {
        $symbol = $crypto_name . '-' . $currency;
        $request = BlockchainConnector::getBlockchainServerRequest($symbol);
        $response = $request->send();

        if (!$response->ok()) {
            return false;
        }

        $response = $response->json();

        Course::create([
            'name' => $crypto_name,
            'currency' => $currency,
            'rate' => $response['last_trade_price'],
            'rate_including_commission' => ($response['last_trade_price'] / 100 * 1.5) + $response['last_trade_price'],
            'source_id' => $source_db->id,
        ]);

        return true;

    }

    public static function find(string $crypto_name, string $currency): array
    {
        $symbol = $crypto_name . '-' . $currency;
        $request = BlockchainConnector::getBlockchainServerRequest($symbol);
        $response = $request->send();
        $response = $response->json();
        $coins['url'] = Str::of(parse_url($request->getFullRequestUrl(), PHP_URL_HOST))->after('api.');
        $coins['price'] = $response['last_trade_price'] ?? 'No such pair found.';
        return $coins;
    }

    public static function delete(string $source_name, string $crypto_name, string $currency)
    {
        return Course::where([['name', $crypto_name], ['currency', $currency]])->whereRelation('source', 'name', $source_name)->delete();
    }
}
