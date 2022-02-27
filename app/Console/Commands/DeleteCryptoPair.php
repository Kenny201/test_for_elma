<?php

namespace App\Console\Commands;

use App\Factories\CryptoFactory;
use App\Models\Source;
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
    public function handle(CryptoFactory $crypto_factory)
    {
        $crypto_name = $this->ask('Enter the name of the cryptocurrency?');
        $currency = $this->ask('Enter the name of the currency to be exchanged?');
        $sources = Source::all()->pluck('name')->toArray();
        $source = $this->choice('What is your source?', $sources);
        $source_entry = Source::whereName($source)->first();
        $source_class_name = config('app.path_service_classes') . $source_entry->sources_class_service;
        $crypto_class = $crypto_factory::make($source_class_name);

        if ($crypto_class === false) {
            $this->error("Class $source_class_name not found");
            return 0;
        }

        $result = $crypto_class::delete($source, $crypto_name, $currency);

        if ($result === true) {
            $info_text = "$crypto_name-$currency pair removed from source '$source'";
        } else {
            $info_text = "No such pair found!";
        }

        $this->info($info_text);
        return 1;
    }
}
