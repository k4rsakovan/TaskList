<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int id
 * @property int task_id
 * @property string file
 * @property string name
 * @property string type
 * @property string size
 * @property string|null created_at
 * @property string|null updated_at
 */
#[Fillable([
    'task_id',
    'file',
    'name',
    'type',
    'size'
])]
#[Hidden(['file', 'task_id'])]
class File extends Model
{
}
