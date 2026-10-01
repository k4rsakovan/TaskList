<?php

declare(strict_types=1);


namespace App\Livewire;

use App\Models\Task;
use Livewire\Attributes\On;
use Livewire\Component;

class TaskInfo extends Component
{
    public bool $show = false;
    public ?Task $task = null;

    #[On('open-task-info')]
    public function open(int $taskId): void
    {
        $this->task = Task::with('files')->findOrFail($taskId);
        $this->show = true;
    }

    /**
     * @return void
     */
    public function close(): void
    {
        $this->show = false;
        $this->task = null;
    }

    public function render()
    {
        return view('livewire.task-info');
    }
}
