<?php


namespace App\Services\Crypto;

use App\Contracts\Crypto\CryptoInterface;
use App\Http\Integrations\Blockchain\BlockchainConnector;
use App\Http\Integrations\Coingecko\CoingeckoConnector;
use App\Models\Course;
use Illuminate\Support\Str;

class CoingeckoService implements CryptoInterface
{
    public static function add(object $source_db, string $crypto_name, string $currency):bool
    {
        $request = CoingeckoConnector::getCoingeckoPriceServerRequest($crypto_name, $currency);
        $coingecko_name = $request->crypto_name;

        if ($coingecko_name === false) {
            return false;
        }

        $response = $request->send();
        $response = $response->json();
        $currency_lower = Str::lower($currency);

        if (empty($response[$coingecko_name]) && empty($response[$coingecko_name][$currency_lower])) {
            return false;
        }

        Course::create([
            'name' => $crypto_name,
            'currency' => Str::upper($currency),
            'rate' => $response[$coingecko_name][$currency_lower],
            'rate_including_commission' => ($response[$coingecko_name][$currency_lower] / 100 * 1.5) + $response[$coingecko_name][$currency_lower],
            'source_id' => $source_db->id,
        ]);

        return true;
    }

    public static function find(string $crypto_name, string $currency): array
    {
        $request = CoingeckoConnector::getCoingeckoPriceServerRequest($crypto_name, $currency);
        $coingecko_name = $request->crypto_name ?? '';
        $currency_lower = Str::lower($currency);
        $response = $request->send();
        $response = $response->json();
        $coins['url'] = Str::of(parse_url($request->getFullRequestUrl(), PHP_URL_HOST))->after('api.');
        $coins['price'] = $response[$coingecko_name][$currency_lower] ?? 'No such pair found.';
        return $coins;
    }

    public static function delete(string $source_name, string $crypto_name, string $currency)
    {
        return Course::where([['name', $crypto_name], ['currency', $currency]])->whereRelation('source', 'name', $source_name)->delete();
    }
}
