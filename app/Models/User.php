<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles {
        HasRoles::hasRole as private spatieHasRole;
        HasRoles::hasAnyRole as private spatieHasAnyRole;
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

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

    public function inventoryLogs(): HasMany
    {
        return $this->hasMany(InventoryLog::class);
    }

    public function hasRole($role, ?string $guard = null): bool
    {
        $normalizedRole = strtolower(trim((string) $role));
        $storedRole = strtolower(trim((string) $this->role));

        if ($storedRole !== '' && $storedRole === $normalizedRole) {
            return true;
        }

        return $this->spatieHasRole($normalizedRole, $guard)
            || $this->spatieHasRole($role, $guard);
    }

    public function hasAnyRole(...$roles): bool
    {
        $flattenedRoles = collect($roles)
            ->flatten()
            ->map(fn ($value): string => trim((string) $value))
            ->filter(fn (string $value): bool => $value !== '')
            ->values();

        if ($flattenedRoles->isEmpty()) {
            return false;
        }

        $normalizedRoles = $flattenedRoles
            ->map(fn (string $value): string => strtolower($value))
            ->values()
            ->all();

        $storedRole = strtolower(trim((string) $this->role));
        if ($storedRole !== '' && in_array($storedRole, $normalizedRoles, true)) {
            return true;
        }

        return $this->spatieHasAnyRole(...$flattenedRoles->all())
            || $this->spatieHasAnyRole(...$normalizedRoles);
    }
}
