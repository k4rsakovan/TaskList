<?php

namespace Database\Seeders\Seeds;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        Role::query()->truncate();
        Schema::enableForeignKeyConstraints();

        Role::query()->upsert([
            ['role' => 'administrator', 'name' => 'Администратор'],
            ['role' => 'employer', 'name' => 'Постановщик'],
            ['role' => 'worker', 'name' => 'Исполнитель'],
        ], ['role', 'name']);

        Role::query()
            ->where('role', 'administrator')
            ->first()
            ->permissions()
            ->attach(
                Permission::all()->pluck('id')
            );

        Role::query()
            ->where('role', 'employer')
            ->first()
            ->permissions()
            ->attach(
                Permission::query()
                    ->whereIn('permission', [
                        'task_create',
                        'task_edit',
                        'file_attach',
                        'archive',
                        'task_attach'
                    ])
                    ->pluck('id')
            );

        Role::query()
            ->where('role', 'worker')
            ->first()
            ->permissions()
            ->attach(
                Permission::query()
                    ->whereIn('permission', ['task_show'])
                    ->pluck('id')
            );
    }
}
