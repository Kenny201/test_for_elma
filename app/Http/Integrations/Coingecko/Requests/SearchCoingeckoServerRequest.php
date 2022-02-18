<?php

namespace App\Http\Integrations\Coingecko\Requests;

use App\Http\Integrations\Coingecko\CoingeckoConnector;
use Sammyjo20\Saloon\Constants\Saloon;
use Sammyjo20\Saloon\Http\SaloonRequest;
use Sammyjo20\Saloon\Traits\Features\AcceptsJson;

class SearchCoingeckoServerRequest extends SaloonRequest
{
    use AcceptsJson;

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

    /**
     * Define the endpoint for the request.
     *
     * @return string
     */
    public function defineEndpoint(): string
    {
        return '/search';
    }

    public function defaultQuery(): array
    {
        return [
            'query' => $this->query,
        ];
    }

    public function __construct(
        public string $query,
    )
    {}

}
