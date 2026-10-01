<?php

declare(strict_types=1);


namespace App\Livewire;

use App\Models\Task;
use App\Models\User;
use App\Services\TaskService;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithFileUploads;

class TaskForm extends Component
{
    use WithFileUploads;

    public bool $showCreate = false;
    public bool $showEdit = false;
    public ?int $taskId = null;
    public string $title = '';
    public string $text = '';
    public $cost = null;
    public ?int $worker = null;
    public array $files = [];

    protected function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'text' => 'nullable|string',
            'cost' => 'required|numeric|min:0',
            'worker' => 'nullable|exists:users,id',
            'files.*' => 'nullable|file|max:20480',
        ];
    }

    #[On('open-create-task')]
    public function openCreate(): void
    {
        $this->reset(['taskId', 'title', 'text', 'cost', 'worker', 'files']);
        $this->resetErrorBag();
        $this->showCreate = true;
    }

    #[On('open-edit-task')]
    public function openEdit(int $taskId): void
    {
        $task = Task::query()->findOrFail($taskId);
        $this->taskId = $task->id;
        $this->title = $task->title;
        $this->text = $task->text;
        $this->cost = $task->cost;
        $this->worker = $task->user_id ?: null;
        $this->files = [];
        $this->resetErrorBag();
        $this->showEdit = true;
    }

    /**
     * @param  TaskService  $service
     * @return void
     */
    public function save(TaskService $service): void
    {
        $this->validate();
        $user = Auth::user();

        if ($this->taskId) {
            $task = Task::findOrFail($this->taskId);
            $service->update($user, $task, [
                'title' => $this->title,
                'text' => $this->text,
                'cost' => $this->cost,
                'worker' => $this->worker,
            ], $this->files);
            $message = "Заявка № {$this->taskId} успешно обновлена";
        } else {
            $service->create($user, [
                'title' => $this->title,
                'text' => $this->text,
                'cost' => $this->cost,
            ], $this->files);
            $message = 'Заявка создана успешно';
        }

        $this->close();
        $this->dispatch('task-saved');
        $this->dispatch('notify', type: 'success', message: $message);
    }

    /**
     * @return void
     */
    public function close(): void
    {
        $this->showCreate = false;
        $this->showEdit = false;
        $this->reset(['taskId', 'title', 'text', 'cost', 'worker', 'files']);
    }

    public function render()
    {
        return view('livewire.task-form', [
            'users' => User::query()->orderBy('name')->get(),
        ]);
    }
}
