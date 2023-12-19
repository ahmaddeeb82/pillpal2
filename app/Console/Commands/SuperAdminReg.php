<?php

namespace App\Console\Commands;

use App\Models\Superadmin;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class SuperAdminReg extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'superadmin:register';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make Super Admin Account';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $credentials = [
            'first_name' => 'Ahmad',
            'last_name' => 'Deeb', 
            'phone' => '+963962562729',
            'password' => Hash::make('28022003')
        ];

        $user = Superadmin::create($credentials);

        $user->createToken('testProject')->accessToken;
    }
}
