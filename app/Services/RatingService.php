<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\User;

class RatingService
{
    public function store(User $producer, User $worker, int $taskId, int $rating): void
    {
        $worker->rating()->updateOrCreate(
            [
                'producer_id' => $producer->getAuthIdentifier(),
                'task_id' => $taskId,
            ],
            ['rating' => $rating]
        );
    }

    public function workersWithRating(): array
    {
        $result = [];

        User::with('rating')
            ->whereHas('roles', fn($q) => $q->where('role', 'worker'))
            ->each(function ($user, $index) use (&$result) {
                if ($user->rating->isNotEmpty()) {
                    $count = $user->rating->count();
                    $sum = $user->rating->pluck('rating')->sum();

                    $result[$index] = $user->toArray();
                    $result[$index]['rating'] = (int) round($sum / $count);
                }
            });

        return $result;
    }
}
