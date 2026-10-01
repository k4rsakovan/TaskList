<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Services\TaskService;
use Illuminate\Http\Response;

class MainController extends Controller
{

    public function index()
    {
        return view('main');
    }

    /**
     * Роль пользователя — оставим на всякий случай (мало ли, JS где-то ещё дёргает).
     */
    public function getUserRole()
    {
        $user = auth()->user();
        $roles = $user->roles->pluck('role');
        return response()->json($roles->isEmpty() ? null : $roles, $roles->isEmpty() ? 204 : 200);
    }

    /**
     * @param  int  $taskId
     * @param  int  $fileId
     * @param  TaskService  $service
     * @return Response
     */
    public function downloadFile(int $taskId, int $fileId, TaskService $service): Response
    {
        return $service->downloadFile($taskId, $fileId);
    }
}
