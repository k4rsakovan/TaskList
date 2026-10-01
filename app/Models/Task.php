<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int id
 * @property string title
 * @property string|null text
 * @property double|null cost
 * @property string|null status
 * @property int|null user_id
 * @property int creator_id
 * @property string|null created_at
 * @property string|null updated_at
 * @property Collection<File> files
 * @property User user
 * @property User creator
 */
#[Fillable([
    'title',
    'text',
    'cost',
    'status',
    'user_id',
    'creator_id'
])]
class Task extends Model
{
    protected $casts = [
        'created_at' => "datetime:Y-m-d H:i",
        'updated_at' => "datetime:Y-m-d H:i",
    ];

    /**
     * @return HasMany
     */
    public function files(): HasMany
    {
        return $this->hasMany(File::class);
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
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id', 'id');
    }
}
