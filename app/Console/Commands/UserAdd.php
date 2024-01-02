<?php
//https://stackoverflow.com/a/72515005

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserAdd extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:create {--u|username= : Username of the newly created user.} {--e|email= : E-Mail of the newly created user.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates a new Laravel user.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Enter username, if not present via command line option
        $name = $this->option('username');
        if ($name === null) {
            $name = $this->ask('Please enter username.');
        }

        // Enter email, if not present via command line option
        $email = $this->option('email');
        if ($email === null) {
            $email = $this->ask('Please enter E-Mail.');
        }

        // Always enter password from userinput for more security.
        $password = $this->secret('Please enter a new password.');
        $password_confirmation = $this->secret('Please confirm the password');

        if($password!=$password_confirmation) {
            $this->error('Entered passwords do not match.');
            return;
        }

        try {
            User::create([
                'name' => $name,
                'email' => $email,
                'password' => Hash::make($password),
            ]);
        }
        catch (\Exception $e) {
            $this->error($e->getMessage());
            return;
        }

        // Success message
        $this->info('User created successfully!');
    }
}
