<?php

declare(strict_types=1);

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * @property int id
 * @property string name
 * @property string email
 * @property string|null email_verified_at
 * @property string password
 * @property string|null remember_token
 * @property string|null created_at
 * @property string|null updated_at
 * @property Collection<Task> tasks
 * @property Collection<Role> roles
 * @property Collection<RatingUser> rating
 *
 */
#[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * @return hasMany
     */
    public function tasks(): hasMany
    {
        return $this->hasMany(Task::class, 'user_id', 'id');
    }

    /**
     * @return BelongsToMany
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'user_roles');
    }

    /**
     * @return HasMany
     */
    public function rating(): HasMany
    {
        return $this->hasMany(RatingUser::class, 'user_id', 'id');
    }

    /**
     * @param  string|array  $roles
     * @return bool
     */
    public function hasRole(string|array $roles): bool
    {
        if (!is_array($roles)) {
            $roles = explode(",", $roles);
        }
        foreach ($roles as $role) {
            if ($this->where('id', $this->id)->whereHas('roles', fn($query) => $query->where('role', $role))->exists()) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param  string|array  $permissions
     * @return bool
     */
    public function hasPermission(string|array $permissions): bool
    {
        if (!is_array($permissions)) {
            $permissions = explode(",", $permissions);
        }
        foreach ($permissions as $permission) {
            if ($this->where('id', $this->id)->whereHas('roles.permissions',
                fn($query) => $query->where('permission', $permission))->exists()) {
                return true;
            }
        }
        return false;
    }
}
