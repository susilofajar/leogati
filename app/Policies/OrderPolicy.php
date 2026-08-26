<?php

namespace App\Policies;

use App\Models\Order;
use App\Models\User;

class OrderPolicy
{
    public function before(User $user, string $ability): ?bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }
        return null;
    }

    public function viewAny(User $user): bool
    {
        return $user->hasPermission('manage_orders')
            || $user->hasRole(['admin', 'sales_staff', 'finance_staff', 'warehouse_staff']);
    }

    public function view(User $user, Order $order): bool
    {
        return $user->hasPermission('manage_orders')
            || $user->hasRole(['admin', 'sales_staff', 'finance_staff', 'warehouse_staff']);
    }

    public function update(User $user, Order $order): bool
    {
        return $user->hasPermission('manage_orders')
            || $user->hasRole(['admin', 'sales_staff']);
    }
}
