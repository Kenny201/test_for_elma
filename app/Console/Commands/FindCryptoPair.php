<?php

namespace App\Console\Commands;

use App\Services\Crypto\BlockchainService;
use App\Services\Crypto\CoingeckoService;
use Illuminate\Console\Command;
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
    public function handle(): int
    {
        $crypto_name = Str::upper($this->ask('Enter the name of the cryptocurrency?'));
        $currency = Str::upper($this->ask('Enter the name of the currency to be exchanged?'));

        $requests = [
            CoingeckoService::find($crypto_name, $currency),
            BlockchainService::find($crypto_name, $currency),
        ];

        foreach ($requests as $request) {
            $this->info('A source: ' . $request['url']);
            $this->info('Rate: ' . $request['price']);
            $this->newLine();
        }

        return 0;
    }
}
