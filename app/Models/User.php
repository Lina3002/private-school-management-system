<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = ['first_name', 'last_name', 'email', 'password', 'school_id', 'role_id'];


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

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    // Permissions relationship (many-to-many through controls)
    public function permissions()
    {
        return $this->role ? $this->role->permissions() : collect();
    }

    // Check if user has a permission by title
    public function hasPermission($permissionTitle)
    {
        // Super admin shortcut
        if ($this->role && $this->role->name === 'super_admin') {
            return true;
        }
        $permissions = $this->permissions();
        if (method_exists($permissions, 'get')) {
            $permissions = $permissions->get();
        }
        return $permissions->where('title', $permissionTitle)->count() > 0;
    }

    // Check if user has a specific role
    public function hasRole($roleName)
    {
        if (!$this->role) {
            return false;
        }
        
        if (is_array($roleName)) {
            return in_array($this->role->name, $roleName);
        }
        
        return $this->role->name === $roleName;
    }

    // Get user's full name
    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }
}
