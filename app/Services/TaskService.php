<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\File;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Validation\ValidationException;

class TaskService
{
    /**
     * Создание задачи.
     */
    public function create(User $user, array $data, array $files = []): Task
    {
        if (!$user->hasPermission('task_create')) {
            abort(403);
        }

        $task = Task::create([
            'title' => $data['title'],
            'text' => $data['text'] ?? '',
            'cost' => $data['cost'] ?? 0,
            'creator_id' => $user->id,
            'status' => 'new',
        ]);

        if ($user->hasPermission('file_attach') && !empty($files)) {
            $this->attachFiles($task, $files);
        }

        return $task;
    }

    /**
     * Обновление задачи.
     */
    public function update(User $user, Task $task, array $data, array $files = []): Task
    {
        if (!$user->hasPermission('task_edit')) {
            abort(403);
        }

        if ($task->status === 'complete') {
            throw ValidationException::withMessages([
                'task' => 'Нельзя изменять завершённую задачу',
            ]);
        }

        $task->update([
            'title' => $data['title'],
            'text' => $data['text'] ?? '',
            'cost' => $data['cost'] ?? 0,
        ]);

        if (
            $user->hasPermission('task_attach')
            && !empty($data['worker'])
        ) {
            $task->update([
                'user_id' => $data['worker'],
                'status' => 'in_work',
            ]);
        }

        if ($user->hasPermission('file_attach') && !empty($files)) {
            $this->attachFiles($task, $files);
        }

        return $task;
    }

    /**
     * Взять задачу в работу.
     */
    public function takeToWork(User $user, Task $task): Task
    {
        if ($task->status !== 'new') {
            throw ValidationException::withMessages([
                'task' => 'Задача уже не новая',
            ]);
        }

        $task->update([
            'user_id' => $user->id,
            'status' => 'in_work',
        ]);

        return $task;
    }

    /**
     * Завершить задачу (только исполнитель).
     */
    public function complete(User $user, Task $task): Task
    {
        if ($task->user_id !== $user->id || $task->status !== 'in_work') {
            abort(403);
        }

        $task->update(['status' => 'complete']);

        return $task;
    }

    /**
     * Отказаться от задачи.
     */
    public function cancel(User $user, Task $task): Task
    {
        if ($task->user_id !== $user->id || $task->status !== 'in_work') {
            abort(403);
        }

        $task->update([
            'status' => 'new',
            'user_id' => 0,
        ]);

        return $task;
    }

    /**
     * Удалить задачу.
     */
    public function delete(User $user, Task $task): void
    {
        if (!$user->hasPermission('task_remove')) {
            abort(403);
        }

        $task->delete();
    }

    /**
     * Прикрепление файлов (base64 в БД — как у вас было).
     *
     * @param  UploadedFile[]  $files
     */
    protected function attachFiles(Task $task, array $files): void
    {
        foreach ($files as $file) {
            File::updateOrCreate(
                [
                    'task_id' => $task->id,
                    'name' => $file->getClientOriginalName(),
                ],
                [
                    'file' => base64_encode($file->getContent()),
                    'type' => $file->getClientMimeType(),
                    'size' => $file->getSize(),
                ]
            );
        }
    }

    /**
     * Скачивание файла.
     */
    public function downloadFile(int $taskId, int $fileId): \Illuminate\Http\Response
    {
        $file = Task::where('id', $taskId)
            ->with(['files' => fn($q) => $q->where('id', $fileId)])
            ->first()?->files->first();

        abort_if(!$file, 404);

        return response(base64_decode($file->file))->withHeaders([
            'Content-Type' => $file->type,
            'Content-Disposition' => 'attachment; filename="' . $file->name . '"',
            'Content-Length' => $file->size,
        ]);
    }
}
