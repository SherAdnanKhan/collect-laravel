<?php

namespace App\Console\Commands;

use DB;
use App\Models\CreditRole;
use Illuminate\Console\Command;

class ResetCreditRole extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reset:creditrole';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Credit Role Table will be turcated after this command';

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
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        CreditRole::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
