<?php

namespace Database\Seeders\Seeds;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        User::query()->truncate();
        Schema::enableForeignKeyConstraints();

        $admin = User::query()->create([
            'name' => 'Admin Example',
            'email' => 'admin@example.ru',
            'password' => Hash::make('12345678')
        ]);

        $admin->roles()->attach(1);

        $employer = User::query()->create([
            'name' => 'Постановщик',
            'email' => 'employer@example.ru',
            'password' => Hash::make('12345678')
        ]);

        $employer->roles()->attach(2);

        $worker = User::query()->create([
            'name' => 'Работник',
            'email' => 'worker@example.ru',
            'password' => Hash::make('12345678')
        ]);

        $worker->roles()->attach(3);
    }
}
