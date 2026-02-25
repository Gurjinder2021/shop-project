<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(AdminUserSeeder::class);
<<<<<<< HEAD
        $this->call(DbAdminUserSeeder::class);
=======
>>>>>>> 7309b0a16334b18e8b07a9eb7691666b1ed2f8ab

    }
}
