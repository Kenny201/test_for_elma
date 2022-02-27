<?php

namespace App\Console\Commands;

use App\Factories\CryptoFactory;
use App\Models\Source;
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
    public function handle(CryptoFactory $crypto_factory): int
    {
        $crypto_name = Str::upper($this->ask('Enter the name of the cryptocurrency?'));
        $currency = Str::upper($this->ask('Enter the name of the currency to be exchanged?'));
        $sources = Source::all();

        foreach ($sources as $request) {
            $source_class_name = config('app.path_service_classes') . $request->sources_class_service;
            $crypto_class = $crypto_factory::make($source_class_name);

            if ($crypto_class === false) {
                $this->error("Class $source_class_name not found");
                return 0;
            }

            $result = $crypto_class::find($crypto_name, $currency);

            $this->info('A source: ' . $result['url']);
            $this->info('Rate: ' . $result['price']);
            $this->newLine();
        }

        return 1;
    }
}
