<?php

namespace App\Console\Commands;

use App\Models\Source;
use App\Services\Crypto\BlockchainService;
use App\Services\Crypto\CoingeckoService;
use Illuminate\Console\Command;

class DeleteCryptoPair extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crypto:delete';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete cryptocurrencies';

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
        $crypto_name = $this->ask('Enter the name of the cryptocurrency?');
        $currency = $this->ask('Enter the name of the currency to be exchanged?');
        $sources = Source::all()->pluck('name')->toArray();
        $source = $this->choice('What is your source?', $sources);

        if ($source === 'coingecko.com') {
            $result = CoingeckoService::delete($source, $crypto_name, $currency);
        }

        if ($source === 'blockchain.com') {
            $result = BlockchainService::delete($source, $crypto_name, $currency);
        }

        if ($result === 1) {
            $info_text = "$crypto_name-$currency pair removed from source '$source'";
        } else {
            $info_text = "No such pair found!";
        }

        $this->info($info_text);
        return 0;
    }
}
