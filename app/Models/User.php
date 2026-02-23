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
        if (is_array($role)) {
            return $this->hasAnyRole($role);
        }

        if ($role instanceof \Traversable) {
            return $this->hasAnyRole(iterator_to_array($role));
        }

        $normalizedRole = $this->normalizeRoleName($role);
        if ($normalizedRole === null) {
            return $this->spatieHasRole($role, $guard);
        }

        $storedRole = strtolower(trim((string) $this->role));

        if ($storedRole !== '' && $storedRole === $normalizedRole) {
            return true;
        }

        return $this->spatieHasRole($normalizedRole, $guard)
            || $this->spatieHasRole($role, $guard);
    }

    public function hasAnyRole(...$roles): bool
    {
        $normalizedRoles = collect($roles)
            ->flatten()
            ->map(fn ($value): ?string => $this->normalizeRoleName($value))
            ->filter(fn (?string $value): bool => $value !== null)
            ->values()
            ->all();

        if ($normalizedRoles === []) {
            return $this->spatieHasAnyRole(...$roles);
        }

        $storedRole = strtolower(trim((string) $this->role));
        if ($storedRole !== '' && in_array($storedRole, $normalizedRoles, true)) {
            return true;
        }

        return $this->spatieHasAnyRole(...$roles)
            || $this->spatieHasAnyRole(...$normalizedRoles);
    }

    private function normalizeRoleName(mixed $role): ?string
    {
        if ($role instanceof \BackedEnum) {
            $role = $role->value;
        } elseif (is_object($role) && isset($role->name)) {
            $role = $role->name;
        }

        if (!is_scalar($role) && !(is_object($role) && method_exists($role, '__toString'))) {
            return null;
        }

        $normalized = strtolower(trim((string) $role));

        return $normalized === '' ? null : $normalized;
    }
}
