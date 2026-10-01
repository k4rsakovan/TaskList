<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use App\Services\TaskService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    /**
     * @param  TaskService  $tasks
     */
    public function __construct(
        private readonly TaskService $tasks
    ) {
    }

    /**
     * @param  int|null  $userId
     * @return JsonResponse
     */
    public function index(?int $userId = null): JsonResponse
    {
        return $userId
            ? response()->json(User::query()->findOrFail($userId))
            : response()->json(User::all());
    }

    /**
     * @return View
     */
    public function getTask(): View
    {
        return view('userspace');
    }

    /**
     * @param  int  $taskId
     * @return JsonResponse
     */
    public function linkTask(int $taskId): JsonResponse
    {
        $this->tasks->takeToWork(auth()->user(), Task::query()->findOrFail($taskId));
        return response()->json(null, 204);
    }

    /**
     * @param  int  $taskId
     * @return JsonResponse
     */
    public function completeTask(int $taskId): JsonResponse
    {
        $this->tasks->complete(auth()->user(), Task::query()->findOrFail($taskId));
        return response()->json(null, 204);
    }

    /**
     * @param  int  $taskId
     * @return JsonResponse
     */
    public function cancelTask(int $taskId): JsonResponse
    {
        $this->tasks->cancel(auth()->user(), Task::query()->findOrFail($taskId));
        return response()->json(null, 204);
    }
}
