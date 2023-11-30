<?php

namespace App\Console\Commands;

use App\Models\Medicine;
use Illuminate\Console\Command;

class CheckExpiration extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:expire';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check if the medicine is expired or not';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Medicine::where('expiration_date','<',now())->update(['expired'=>1]);
    }
}
