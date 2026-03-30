<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Role;
use App\Models\Permission;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'phone',
        'image',
        'password',
    ];

    /**
     * Roles relationship
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    /**
     * Direct Permissions relationship
     */
    public function permissions()
    {
        return $this->belongsToMany(Permission::class);
    }

    /**
     * Get user by username with permissions
     */
    public static function findByUsername($username)
    {
        return static::where('username', $username)->first();
    }

    /**
     * Check if a specific username has a specific permission
     */
    public static function hasPermissionByUsername($username, $permissionSlug)
    {
        $user = static::where('username', $username)->first();
        return $user ? $user->hasPermission($permissionSlug) : false;
    }

    /**
     * Check if user has a specific permission
     */
    public function hasPermission($permissionSlug)
    {
        // Grant full permission if the user is 'setec'
        if ($this->username === 'setec') {
            return true;
        }

        // Check direct permissions
        if ($this->permissions()->where('slug', $permissionSlug)->exists()) {
            return true;
        }

        // Check permissions via roles
        return $this->roles()->whereHas('permissions', function ($query) use ($permissionSlug) {
            $query->where('slug', $permissionSlug);
        })->exists();
    }

    /**
     * Check if user has a specific role
     */
    public function hasRole($roleSlug)
    {
        return $this->roles()->where('slug', $roleSlug)->exists();
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

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
}
