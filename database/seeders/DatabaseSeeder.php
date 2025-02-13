<?php

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder {
    public function run(): void
    {
     $this->call(SuperAdminSeeder::class);
    }
}


