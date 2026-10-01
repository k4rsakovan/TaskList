<?php

use App\Http\Controllers\MainController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\TasksController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
Route::middleware('auth')->group(function () {
    Route::get('/', [MainController::class, 'index'])->name('main');

    // остаются, потому что используются Livewire-вьюхой и/или внешними клиентами
    Route::get('/userRole', [MainController::class, 'getUserRole']);
    Route::get('/users', [UserController::class, 'index']);
    Route::get('/user/{userId?}', [UserController::class, 'index']);
    Route::get('/getFile/{taskId}/{fileId}', [MainController::class, 'downloadFile'])->name('task.file');

    // рейтинг
    Route::get('/rating', [RatingController::class, 'index'])->name('usersRating');

    // мои задачи
    Route::get('/my-tasks', [UserController::class, 'getTask'])->name('userTask');

    // HTTP-API задач (опционально, если кто-то ещё дёргает)
    Route::post('/task', [TasksController::class, 'store']);
    Route::get('/task/{id}', [TasksController::class, 'show']);
    Route::post('/task/{id}', [TasksController::class, 'update']);
    Route::delete('/task/{id}', [TasksController::class, 'destroy']);

    // task HTTP-операции от юзера
    Route::post('/user/task/{id}/link', [UserController::class, 'linkTask']);
    Route::post('/user/task/{id}/complete', [UserController::class, 'completeTask']);
    Route::post('/user/task/{id}/cancel', [UserController::class, 'cancelTask']);

    // если хотите полностью отказаться от AJAX-DataTables — эти роуты можно удалить:
    // Route::get('/tasks', [TasksController::class, 'index']);
});

Auth::routes();

