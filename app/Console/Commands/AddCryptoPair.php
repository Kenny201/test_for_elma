<?php

namespace App\Console\Commands;

use App\Models\Source;
use App\Services\Crypto\BlockchainService;
use App\Services\Crypto\CoingeckoService;
use Illuminate\Console\Command;
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
        $crypto_name = Str::upper($this->ask('Enter the name of the cryptocurrency?'));
        $currency = Str::upper($this->ask('Enter the name of the currency to be exchanged?'));
        $sources = Source::pluck('name')->toArray();
        $source = $this->choice('What is your source?', $sources);
        $source_db = Source::whereName($source)->first();

        if ($source === 'coingecko.com') {
            $result = CoingeckoService::add($source_db, $crypto_name, $currency);
        }

        if ($source === 'blockchain.com') {
            $result = BlockchainService::add($source_db, $crypto_name, $currency);
        }

        if ($result === false) {
            $this->error('No such pair found.');
            return 0;
        }

        $this->info("Currency $crypto_name-$currency added");
        return 1;
    }

}
