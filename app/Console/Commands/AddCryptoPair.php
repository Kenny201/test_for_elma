<?php

namespace App\Console\Commands;

use App\Factories\CryptoFactory;
use App\Models\Source;
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
    public function handle(CryptoFactory $crypto_factory)
    {
        $crypto_name = Str::upper($this->ask('Enter the name of the cryptocurrency?'));
        $currency = Str::upper($this->ask('Enter the name of the currency to be exchanged?'));
        $sources = Source::pluck('name')->toArray();
        $source = $this->choice('What is your source?', $sources);
        $source_entry = Source::whereName($source)->first();
        $source_id = $source_entry->id;
        $source_class_name = config('app.path_service_classes') . $source_entry->sources_class_service;
        $crypto_class = $crypto_factory::make($source_class_name);

        if ($crypto_class === false) {
            $this->error("Class $source_class_name not found");
            return 0;
        }

        $result = $crypto_class::add($source_id, $crypto_name, $currency);

        if ($result === false) {
            $this->error('No such pair found.');
            return 0;
        }

        $this->info("Currency $crypto_name-$currency added");
        return 1;
    }

}
