<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;

class Install extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'install {password} {fullname=admin} {email=admin@site.local}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate the databases and create a new admin user with the username: admin.';

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
        Artisan::call('migrate');
         // Check if user already exist
         if(User::where('username', 'admin')->first()){
            $this->error('User with the username: admin already exist!');
        }else{
        // If user not already exit, CREATE IT!
            User::create([
                'fullname' => $this->argument('fullname'),
                'username' => 'admin',
                'email' => $this->argument('email'),
                'password' => Hash::make($this->argument('password')),
                'role' => 'superadmin',
            ]);
            $this->info('A user with the username:admin was successfully created!');
        }
        $this->info('System was successfully installed!');
    }
}
