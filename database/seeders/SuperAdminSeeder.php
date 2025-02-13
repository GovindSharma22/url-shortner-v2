<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Client;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a default client for the Super Admin
        $client = Client::firstOrCreate([
            'name' => 'Super Admin Organization',
        ]);

        // Create the Super Admin user
        User::firstOrCreate(
            ['email' => 'superadmin@example.com'], // Ensure unique email
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'), // Change this in production
                'role' => 'super_admin',
                'client_id' => $client->id,
            ]
        );
    }
}

