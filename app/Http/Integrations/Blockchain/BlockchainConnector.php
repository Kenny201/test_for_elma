<?php

namespace App\Http\Integrations\Blockchain;

use App\Http\Integrations\Blockchain\Requests\GetBlockchainServerRequest;
use Sammyjo20\Saloon\Http\SaloonConnector;

class BlockchainConnector extends SaloonConnector
{

    /**
     * Register Saloon requests that will become methods on the connector.
     * For example, GetUserRequest would become $this->getUserRequest(...$args)
     *
     * @var array
     */
    protected array $requests = [
        GetBlockchainServerRequest::class
    ];

    /**
     * Define the base url of the api.
     *
     * @return string
     */
    public function defineBaseUrl(): string
    {
        return 'https://api.blockchain.com/v3/exchange/';
    }

}
