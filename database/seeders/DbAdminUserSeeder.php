<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DbAdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'dbadmin@dbadmimn.com'],
            [
                'name' => 'Database Admin',
                'password' => Hash::make('password'),
                'user_type' => 'dbadmin',
            ]
        );

        $this->command?->info('DB admin user ensured: dbadmin@dbadmimn.com');
    }
}
