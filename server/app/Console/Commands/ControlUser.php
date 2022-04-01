<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ControlUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:control {action} {--role=} {--provider=} {--id=} {--name=} {--email=} {--password=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command to control users manually';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        // to run this command, you can check the below example command:
        // php artisan user:control create --email=abc@deg.com --name=test --password=123456

        // to update a user, you can check the below example command:
        // php artisan user:control update --id=1 --name=test --password=123456 --email=abc@deg.com

        // to delete a user, you can check the below example command:
        // php artisan user:control delete --id=1

        // to list all users, you can check the below example command:
        // php artisan user:control list --provider=manual

        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $action = $this->argument('action');
        $id = $this->option('id');
        $role = $this->option('role', 'user');
        $name = $this->option('name');
        $email = $this->option('email');
        $password = $this->option('password');
        $provider = $this->option('provider', 'manual');

        // hash the password
        $password = bcrypt($password);

        if ($action == 'create') {
            $user = \App\Models\User::create([
                'name' => $name,
                'email' => $email,
                'password' => $password,
                'social_provider' => $provider,
                'role' => $role,
            ]);
            $this->info('User created successfully');
            
        } elseif ($action == 'update') {
            $user = \App\Models\User::find($id);
            if(!$user){
                $this->error('User not found');
                return 1;
            }

            $user->name = $name;
            $user->email = $email;
            $user->password = $password;
            $user->social_provider = $provider;
            $user->role = $role;

            $user->save();
            $this->info('User updated successfully');

        } elseif ($action == 'delete') {
            $user = \App\Models\User::find($id);
            if(!$user){
                $this->error('User not found');
                return 1;
            }

            $user->delete();
            $this->info('User deleted successfully');
        } elseif ($action == 'list-all') {
            $users = \App\Models\User::where('social_provider', $provider)->get();
            foreach ($users as $user) {
                $this->info($user->id . ': ' . $user->name .
                            ' (' . $user->email . ')'. ' - ' .
                             $user->social_provider. ' - ' . $user->role);
            }
        }
        else {
            $this->error('Invalid action');
        }
    }
}
