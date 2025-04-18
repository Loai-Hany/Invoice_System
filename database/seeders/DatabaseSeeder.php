<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\User;
use CreateAdminUserSeeder;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use PermissionTableSeeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        Schema::disableForeignKeyConstraints();
        User::truncate();
        Permission::truncate();
        Role::truncate();
        $this->call([
            PermissionTableSeeder::class,
            CreateAdminUserSeeder::class,

        ]);
    }
}
