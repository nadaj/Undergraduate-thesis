<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class VotingSuccess extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'voting:success';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will check voting statuses.';

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
     * @return mixed
     */
    public function handle()
    {
        
    }
}
