<?php

namespace App\Console\Commands;

use App\Models\Source;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class AddSources extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'source:add';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Adding sources';

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
        $source_name = $this->ask("Specify the name of the source, for example 'blockchain.com'");
        $source_name_class_service = $this->ask(
            "Specify the name class service of the source, for example 'BlockchainService'"
        );
        if (Str::of($source_name)->endsWith('.com')) {
            $source_name = Str::of($source_name)->after('api.');
            Source::factory()->create(['name' => $source_name, 'sources_class_service' => $source_name_class_service]);
            $this->info("Source $source_name added");
            return 1;
        }
        $this->error('Please enter a valid address');
        return 0;
    }
}
