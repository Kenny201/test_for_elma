<?php

namespace App\Http\Integrations\Blockchain\Requests;

use App\Http\Integrations\Blockchain\BlockchainConnector;
use Sammyjo20\Saloon\Constants\Saloon;
use Sammyjo20\Saloon\Http\SaloonRequest;
use Sammyjo20\Saloon\Traits\Features\AcceptsJson;

class GetBlockchainServerRequest extends SaloonRequest
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
    protected ?string $connector = BlockchainConnector::class;

    /**
     * Define the endpoint for the request.
     *
     * @return string
     */
    public function defineEndpoint(): string
    {
        return "/tickers/$this->symbol";
    }

    public function __construct(
        public string $symbol
    )
    {}
}
