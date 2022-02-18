<?php

namespace App\Http\Integrations\Coingecko;

use App\Http\Integrations\Coingecko\Requests\SearchCoingeckoServerRequest;
use App\Http\Integrations\Coingecko\Requests\GetCoingeckoPriceServerRequest;
use Sammyjo20\Saloon\Http\SaloonConnector;
use Sammyjo20\Saloon\Traits\Features\AcceptsJson;

class CoingeckoConnector extends SaloonConnector
{

    /**
     * Register Saloon requests that will become methods on the connector.
     * For example, GetUserRequest would become $this->getUserRequest(...$args)
     *
     * @var array
     */
    protected array $requests = [
        GetCoingeckoPriceServerRequest::class,
        SearchCoingeckoServerRequest::class,
    ];

    /**
     * Define the base url of the api.
     *
     * @return string
     */
    public function defineBaseUrl(): string
    {
        return 'https://api.coingecko.com/api/v3/';
    }

}
