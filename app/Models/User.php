<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'current_team_id',
        'avatar',
        'avatar_type',
        'theme_preference',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_secret',
        'two_factor_recovery_codes',
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
            'two_factor_confirmed_at' => 'datetime',
        ];
    }

    // ----------------------------------------------------
    // RBAC Roles & Permissions
    // ----------------------------------------------------

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }

    public function hasRole(string $roleName): bool
    {
        return $this->roles->contains('name', $roleName);
    }

    public function isSuperAdmin(): bool
    {
        return $this->hasRole('super-admin');
    }

    public function hasPermission(string $permissionName): bool
    {
        if ($this->isSuperAdmin()) {
            return true;
        }

        foreach ($this->roles as $role) {
            if ($role->permissions->contains('name', $permissionName)) {
                return true;
            }
        }

        return false;
    }

    public function assignRole(string $roleName): void
    {
        $role = Role::firstOrCreate(['name' => $roleName], ['label' => ucfirst(str_replace('-', ' ', $roleName))]);
        $this->roles()->syncWithoutDetaching([$role->id]);
    }

    // ----------------------------------------------------
    // Teams & Workspaces
    // ----------------------------------------------------

    public function ownedTeams(): HasMany
    {
        return $this->hasMany(Team::class);
    }

    public function teams(): BelongsToMany
    {
        return $this->belongsToMany(Team::class)
            ->withPivot('role')
            ->withTimestamps();
    }

    public function allTeams()
    {
        return $this->ownedTeams->merge($this->teams)->unique('id');
    }

    public function currentTeam(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'current_team_id');
    }

    public function isCurrentTeam(Team $team): bool
    {
        return $this->current_team_id == $team->id;
    }

    public function switchTeam(Team $team): bool
    {
        if (! $this->allTeams()->contains('id', $team->id)) {
            return false;
        }

        $this->forceFill(['current_team_id' => $team->id])->save();

        return true;
    }

    // ----------------------------------------------------
    // Profile Avatar Logic
    // ----------------------------------------------------

    public function getAvatarUrlAttribute(): string
    {
        if ($this->avatar_type === 'custom' && $this->avatar) {
            return asset('storage/' . $this->avatar);
        }

        if ($this->avatar_type === 'gravatar') {
            $hash = md5(strtolower(trim($this->email)));
            return "https://www.gravatar.com/avatar/{$hash}?s=200&d=mp";
        }

        // Default Initials Avatar
        $name = urlencode($this->name);
        return "https://ui-avatars.com/api/?name={$name}&color=7F9CF5&background=EBF4FF";
    }
}