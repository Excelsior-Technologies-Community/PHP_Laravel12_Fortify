<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Role extends Model
{
    protected $fillable = [
        'name',
        'label',
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class);
    }

    public function givePermissionTo(Permission|string $permission): void
    {
        if (is_string($permission)) {
            $permission = Permission::firstOrCreate(['name' => $permission], ['label' => ucfirst(str_replace('-', ' ', $permission))]);
        }

        $this->permissions()->syncWithoutDetaching([$permission->id]);
    }
}
