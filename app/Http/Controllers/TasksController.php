<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\TaskRequest;
use App\Models\Task;
use App\Services\TaskService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;

class TasksController extends Controller
{
    public function __construct(
        private readonly TaskService $tasks
    ) {
    }

    public function store(TaskRequest $request): JsonResponse|RedirectResponse
    {
        if (!$request->ajax()) {
            return redirect('/');
        }

        $this->tasks->create(
            $request->user,
            $request->only('task_add_title', 'task_add_text', 'task_add_cost'),
            $request->file('task_add_files') ?? []
        );

        return response()->json(null, 201);
    }

    /**
     * @param  TaskRequest  $request
     * @param  int  $taskId
     * @return JsonResponse
     */
    public function show(TaskRequest $request, int $taskId): JsonResponse
    {
        if (!$request->ajax()) {
            return redirect('/');
        }

        return response()->json(
            Task::query()
                ->where('id', $taskId)
                ->with('files')
                ->first()
        );
    }

    /**
     * @param  TaskRequest  $request
     * @param  int  $taskId
     * @return JsonResponse|RedirectResponse
     */
    public function update(TaskRequest $request, int $taskId): JsonResponse|RedirectResponse
    {
        if (!$request->ajax()) {
            return redirect('/');
        }

        $task = Task::query()->findOrFail($taskId);

        $this->tasks->update(
            $request->user,
            $task, [
            'title' => $request->input('task_edit_title'),
            'text' => $request->input('task_edit_text'),
            'cost' => $request->input('task_edit_cost'),
            'worker' => $request->input('task_edit_worker'),
        ],
            $request->file('task_edit_files') ?? []
        );

        return response()->json(null, 204);
    }

    /**
     * @param  TaskRequest  $request
     * @param  int  $taskId
     * @return JsonResponse|RedirectResponse
     */
    public function destroy(TaskRequest $request, int $taskId): JsonResponse|RedirectResponse
    {
        if (!$request->ajax()) {
            return redirect('/');
        }

        $this->tasks->delete(
            $request->user, Task::query()->findOrFail($taskId)
        );

        return response()->json(null, 204);
    }
}
