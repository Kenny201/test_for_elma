<?php

namespace App\Console\Commands;

use App\Models\Course;
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
    public function handle()
    {
        $crypto_name = $this->ask('What is your crypto name?');
        $currency = $this->ask('What is your currency?');
        $sources = Source::all()->pluck('name')->toArray();
        $source = $this->choice('What is your source?', $sources);
        $source_db = Source::where('name', $source)->first();
        $delete_rate = Course::where([['name', $crypto_name], ['currency', $currency]])->whereRelation('source', 'name', $source)->delete();

        if ($delete_rate === 1) {
            $info_text = "$crypto_name-$currency pair removed from source '$source'";
        } else {
            $info_text = "No such pair found!";
        }

        $this->info($info_text);
        return 0;
    }
}
