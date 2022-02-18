<?php

namespace App\Console\Commands;

use App\Http\Integrations\Blockchain\BlockchainConnector;
use App\Http\Integrations\Coingecko\CoingeckoConnector;
use App\Models\Course;
use App\Models\Source;
use Illuminate\Console\Command;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class AddCryptoPair extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crypto:add';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add cryptocurrency in DB';

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
        $crypto_name = Str::upper($this->ask('Enter the name of the cryptocurrency'));
        $currency = Str::upper($this->ask('Enter the name of the currency to be exchanged'));
        $sources = Source::pluck('name')->toArray();
        $source = $this->choice('What is your source?', $sources);
        $source_db = Source::whereName($source)->first();

        if ($source === 'coingecko.com') {
            $this->addFromCoingecko($source_db, $crypto_name, $currency);
        }

        if ($source === 'blockchain.com') {
            $this->addFromBlockchain($source_db, $crypto_name, $currency);
        }

        return 0;
    }

    public function addFromCoingecko(object $source_db, string $crypto_name, string $currency): void
    {
        $request = CoingeckoConnector::getCoingeckoPriceServerRequest($crypto_name, $currency);
        $coingecko_name = $request->crypto_name;

        if ($coingecko_name === false) {
            $this->error('No such pair found.');
            return;
        }

        $response = $request->send();
        $response = $response->json();
        $currency_lower = Str::lower($currency);

        if (empty($response[$coingecko_name]) && empty($response[$coingecko_name][$currency_lower])) {
            $this->error('No such pair found.');
            return;
        }

        Course::create([
            'name' => $crypto_name,
            'currency' => Str::upper($currency),
            'rate' => $response[$coingecko_name][$currency_lower],
            'rate_including_commission' => ($response[$coingecko_name][$currency_lower] / 100 * 1.5) + $response[$coingecko_name][$currency_lower],
            'source_id' => $source_db->id,
        ]);

        $this->info("Currency $crypto_name-$currency added");
    }

    public function addFromBlockchain(object $source_db, string $crypto_name, string $currency): void
    {
        $symbol = $crypto_name . '-' . $currency;
        $request = BlockchainConnector::getBlockchainServerRequest($symbol);
        $response = $request->send();

        if (!$response->ok()) {
            $this->error('No such pair found.');
            return;
        }

        $response = $response->json();

        Course::create([
            'name' => $crypto_name,
            'currency' => $currency,
            'rate' => $response['last_trade_price'],
            'rate_including_commission' => ($response['last_trade_price'] / 100 * 1.5) + $response['last_trade_price'],
            'source_id' => $source_db->id,
        ]);

        $this->info("Currency $crypto_name-$currency added");
    }
}
