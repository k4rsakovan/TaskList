<?php

declare(strict_types=1);


namespace App\Livewire;

use App\Models\Task;
use App\Services\TaskService;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;

class MyTasks extends Component
{
    public array $myTasks = [];

    public function mount(): void
    {
        $this->reload();
    }

    #[On('task-changed')]
    public function reload(): void
    {
        $this->myTasks = Task::query()->where('user_id', Auth::id())
            ->orderByDesc('id')
            ->get()
            ->map(fn(Task $task) => [
                'id' => $task->id,
                'title' => $task->title,
                'cost' => $task->cost,
                'created_at' => $task->created_at?->format('d.m.Y H:i'),
                'updated_at' => $task->updated_at?->format('d.m.Y H:i'),
                'status' => match ($task->status) {
                    'in_work' => 'В работе',
                    'complete' => 'Завершена',
                    default => $task->status,
                },
                'raw_status' => $task->status,
            ])
            ->toArray();
    }

    /**
     * @param  int  $taskId
     * @return void
     */
    public function openInfo(int $taskId): void
    {
        $this->dispatch('open-task-info', taskId: $taskId);
    }

    /**
     * @param  int  $taskId
     * @param  TaskService  $service
     * @return void
     */
    public function complete(int $taskId, TaskService $service): void
    {
        $task = Task::query()->findOrFail($taskId);
        $service->complete(Auth::user(), $task);
        $this->reload();
        $this->dispatch('notify', type: 'success', message: 'Успех!');
    }

    /**
     * @param  int  $taskId
     * @param  TaskService  $service
     * @return void
     */
    public function cancel(int $taskId, TaskService $service): void
    {
        $task = Task::query()->findOrFail($taskId);

        $service->cancel(Auth::user(), $task);
        $this->reload();

        $this->dispatch('notify', type: 'success', message: 'Успех!');
    }

    public function render()
    {
        return view('livewire.my-tasks');
    }
}
