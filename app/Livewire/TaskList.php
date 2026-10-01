<?php

declare(strict_types=1);


namespace App\Livewire;

use App\Models\Task;
use App\Services\TaskQueryService;
use App\Services\TaskService;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;

class TaskList extends Component
{
    public array $tasks = [];

    public function mount(TaskQueryService $query): void
    {
        $this->reload($query);
    }

    #[On('task-saved')]
    #[On('task-changed')]
    public function reload(TaskQueryService $query): void
    {
        $user = Auth::user();
        $collection = $query->forMainPage($user);
        $userRole = $user->hasRole('worker') ? 'worker' : 'manager';

        $this->tasks = $collection->map(function (Task $task) use ($query, $userRole) {
            return [
                'id' => $task->id,
                'title' => $task->title,
                'cost' => $task->cost,
                'created_at' => $task->created_at?->format('d.m.Y H:i'),
                'updated_at' => $task->updated_at?->format('d.m.Y H:i'),
                'status' => $query::statusLabel(
                    $task->status,
                    $task->user?->name
                ),
                'raw_status' => $task->status,
                'user_id' => $task->user_id,
                'worker_name' => $task->user?->name,
                'role' => $userRole,
            ];
        })->toArray();
    }

    public function openCreate(): void
    {
        $this->dispatch('open-create-task');
    }

    public function openEdit(int $taskId): void
    {
        $task = Task::query()->findOrFail($taskId);
        if ($task->status === 'complete') {
            $this->dispatch('notify', type: 'warning', message: 'Задача завершена. Изменить невозможно');
            return;
        }
        $this->dispatch('open-edit-task', taskId: $taskId);
    }

    public function openInfo(int $taskId): void
    {
        $this->dispatch('open-task-info', taskId: $taskId);
    }

    public function openRating(int $taskId, int $userId): void
    {
        $this->dispatch('open-rating', taskId: $taskId, userId: $userId);
    }

    /**
     * @param  int  $taskId
     * @param  TaskService  $service
     * @return void
     */
    public function takeToWork(int $taskId, TaskService $service): void
    {
        $service->takeToWork(Auth::user(), Task::query()->findOrFail($taskId));
        $this->reload(app(TaskQueryService::class));
        $this->dispatch('notify', type: 'success', message: 'Успех!');
    }

    /**
     * @param  int  $taskId
     * @return void
     */
    public function payForWork(int $taskId): void
    {
        // логика оплаты — заведёте позже
        $this->dispatch('notify', type: 'success', message: 'Платёж инициирован');
    }

    public function render()
    {
        return view('livewire.task-list');
    }
}
