<?php

namespace App\Console\Commands;

use App\Http\Integrations\Blockchain\BlockchainConnector;
use App\Http\Integrations\Coingecko\CoingeckoConnector;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class FindCryptoPair extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crypto:find';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Find pair...';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }


    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $crypto_name = Str::upper($this->ask('Enter the name of the cryptocurrency?'));
        $currency = Str::upper($this->ask('Enter the name of the currency to be exchanged?'));
        $symbol = $crypto_name . '-' . $currency;
        $coins = [];
        $info_text = '';

        $requests = [
            'coingecko' => CoingeckoConnector::getCoingeckoPriceServerRequest($crypto_name, $currency),
            'blockchain' => BlockchainConnector::getBlockchainServerRequest($symbol),
        ];

        foreach ($requests as $k => $request) {
            $coingecko_name = $request->crypto_name ?? '';
            $currency_lower = Str::lower($currency);

            $response = $request->send();
            $response = $response->json();

            $coins[$k]['url'] = Str::of(parse_url($request->getFullRequestUrl(), PHP_URL_HOST))->after('api.');
            $coins[$k]['price'] = $response[$coingecko_name][$currency_lower] ?? $response['last_trade_price'] ?? 'No such pair found.';
            $this->info('A source: ' . $coins[$k]['url']);
            $this->info( 'Rate: ' . $coins[$k]['price']);
            $this->newLine();
        }
        return 0;
    }
}
