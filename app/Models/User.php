<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements FilamentUser
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

    public function waiterOrders(): HasMany
    {
        return $this->hasMany(Order::class, 'waiter_id');
    }

    public function canAccessPanel(Panel $panel): bool
    {
        $role = strtolower(trim((string) $this->role));

        return in_array($role, [
            'super_admin',
            'admin',
            'procurement_officer',
            'kitchen_manager',
            'kitchen',
            'general_order_person',
            'steward',
            'barman',
        ], true);
    }

    public function hasRole($role, ?string $guard = null): bool
    {
        $normalizedRole = $this->normalizeRoleName($role);

        if ($normalizedRole === null) {
            return false;
        }

        $storedRole = strtolower(trim((string) $this->role));

        if ($storedRole !== '' && $storedRole === $normalizedRole) {
            return true;
        }

        return $this->spatieHasRole($normalizedRole, $guard);
    }

    public function hasAnyRole(...$roles): bool
    {
        $normalizedRoles = $this->extractNormalizedRoles($roles);

        if ($normalizedRoles === []) {
            return false;
        }

        $storedRole = strtolower(trim((string) $this->role));

        if ($storedRole !== '' && in_array($storedRole, $normalizedRoles, true)) {
            return true;
        }

        return $this->spatieHasAnyRole(...$normalizedRoles);
    }

    private function extractNormalizedRoles(array $roles): array
    {
        $normalizedRoles = [];

        foreach ($roles as $role) {
            if (is_array($role)) {
                $normalizedRoles = [...$normalizedRoles, ...$this->extractNormalizedRoles($role)];
                continue;
            }

            if ($role instanceof \Traversable) {
                $normalizedRoles = [...$normalizedRoles, ...$this->extractNormalizedRoles(iterator_to_array($role, false))];
                continue;
            }

            $normalized = $this->normalizeRoleName($role);

            if ($normalized !== null) {
                $normalizedRoles[] = $normalized;
            }
        }

        return array_values(array_unique($normalizedRoles));
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
