<?php

namespace Database\Seeders\Seeds;

use App\Models\Permission;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        Permission::truncate();
        Schema::enableForeignKeyConstraints();

        Permission::upsert([
            [
                'permission' => 'task_create',
                'name' => 'Создание задачи',
            ], [
                'permission' => 'task_edit',
                'name' => 'Редактирование задачи'
            ], [
                'permission' => 'task_remove',
                'name' => 'Удаление задачи'
            ], [
                'permission' => 'task_show',
                'name' => 'Просмотр задачи'
            ], [
                'permission' => 'file_attach',
                'name' => 'Подключение файлов'
            ], [
                'permission' => 'archive',
                'name' => 'Просмотр архива'
            ], [
                'permission' => 'task_attach',
                'name' => 'Назначение задачи'
            ]
        ], ['permission', 'name']);
    }
}
