<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\WithoutTimestamps;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int id
 * @property int producer_id
 * @property int user_id
 * @property int task_id
 * @property int rating
 * @property User producer
 * @property User user
 * @property Task task
 */
#[Fillable([
    'producer_id',
    'user_id',
    'task_id',
    'rating'
])]
#[WithoutTimestamps]
class RatingUser extends Model
{
    /**
     * @return BelongsTo
     */
    public function producer(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo
     */
    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }
}

