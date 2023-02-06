<?php

namespace App\Console\Commands;

use App\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class UsersCreateNewUserCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:create {--email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new User';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $user = [];
        $user['name'] = $this->ask('Name: ');
        $user['email'] = $this->ask('Email: ');
        $user['password'] = Hash::make($this->ask('Password: '));
        $user['email_verified_at'] = now();
        User::create($user);
    }
}
