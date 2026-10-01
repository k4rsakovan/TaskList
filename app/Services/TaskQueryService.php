<?php

declare(strict_types=1);


namespace App\Services;

use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Collection;

class TaskQueryService
{
    /**
     * Список задач для главной страницы с учётом ролей.
     */
    public function forMainPage(User $user): Collection
    {
        if ($user->hasRole('administrator')) {
            return Task::with('user')->get();
        }

        if ($user->hasRole('employer')) {
            return Task::where('creator_id', $user->id)->with('user')->get();
        }

        // worker — только новые, никем не взятые
        return Task::where('user_id', 0)->where('status', 'new')->get();
    }

    /**
     * Русские ярлыки для статусов.
     */
    public static function statusLabel(string $status, ?string $workerName = null): string
    {
        return match ($status) {
            'new' => 'Новая',
            'in_work' => $workerName ? 'Исполняет: ' . $workerName : 'В работе',
            'complete' => $workerName ? 'Завершена: ' . $workerName : 'Завершена',
            default => '',
        };
    }
}
