<?php

namespace App\Http\Integrations\Coingecko\Requests;

use App\Http\Integrations\Coingecko\CoingeckoConnector;
use Sammyjo20\Saloon\Constants\Saloon;
use Sammyjo20\Saloon\Http\SaloonRequest;

class GetCoingeckoPriceServerRequest extends SaloonRequest
{
    /**
     * Define the method that the request will use.
     *
     * @var string|null
     */
    protected ?string $method = Saloon::GET;

    /**
     * The connector.
     *
     * @var string|null
     */
    protected ?string $connector = CoingeckoConnector::class;
    public string $crypto_name = '';

    /**
     * Define the endpoint for the request.
     *
     * @return string
     */
    public function defineEndpoint(): string
    {
        return '/simple/price';
    }

    public function defaultQuery(): array
    {
        return [
            'ids' => $this->crypto_name,
            'vs_currencies' => $this->vs_currencies
        ];
    }

    public function __construct(
        public string $ids,
        public string $vs_currencies
    )
    {
        $this->searchNameInGecko($ids);
    }

    public function searchNameInGecko($crypto_name)
    {
        $coingecko_find = CoingeckoConnector::searchCoingeckoServerRequest($crypto_name);

        $response = $coingecko_find->send();
        $response = $response->json()['coins'];

        foreach ($response as $item) {
            if ($item['symbol'] === $crypto_name) {
                $this->crypto_name = $item['id'];
                return $this->crypto_name;
            }
                $this->crypto_name = false;
        }
    }
}
