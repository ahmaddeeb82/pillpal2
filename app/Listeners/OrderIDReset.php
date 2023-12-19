<?php

namespace App\Listeners;

use App\Events\OrderReset;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\DB;

class OrderIDReset
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(OrderReset $event): void
    {
        $max = DB::table('orders')->max('id') + 1; 
        DB::statement("ALTER TABLE orders AUTO_INCREMENT =  $max");
    }
}
