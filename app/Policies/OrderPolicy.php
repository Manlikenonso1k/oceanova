<?php

namespace App\Policies;

use App\Models\Order;
use App\Models\User;

class OrderPolicy
{
    public function before(User $user, string $ability): bool|null
    {
        if ($user->hasAnyRole(['admin', 'super_admin'])) {
            return true;
        }

        return null;
    }

    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['steward', 'kitchen', 'general_order_person', 'kitchen_manager']);
    }

    public function view(User $user, Order $order): bool
    {
        if ($user->hasAnyRole(['kitchen', 'kitchen_manager'])) {
            return in_array(strtolower((string) $order->status), ['pending', 'cooking'], true);
        }

        if ($user->hasAnyRole(['steward', 'general_order_person'])) {
            return (int) $order->waiter_id === (int) $user->id;
        }

        return false;
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole(['steward', 'general_order_person']);
    }

    public function update(User $user, Order $order): bool
    {
        if ($user->hasAnyRole(['kitchen', 'kitchen_manager'])) {
            return in_array(strtolower((string) $order->status), ['pending', 'cooking'], true);
        }

        if ($user->hasAnyRole(['steward', 'general_order_person'])) {
            return (int) $order->waiter_id === (int) $user->id;
        }

        return false;
    }

    public function delete(User $user, Order $order): bool
    {
        return false;
    }
}
