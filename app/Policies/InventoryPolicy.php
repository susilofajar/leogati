<?php

namespace App\Policies;

use App\Models\Inventory;
use App\Models\User;

class InventoryPolicy
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
        return $user->hasPermission('manage_inventory')
            || $user->hasRole(['admin', 'warehouse_staff']);
    }

    public function view(User $user, Inventory $inventory): bool
    {
        return $user->hasPermission('manage_inventory')
            || $user->hasRole(['admin', 'warehouse_staff']);
    }

    public function adjust(User $user): bool
    {
        return $user->hasPermission('manage_inventory')
            || $user->hasRole(['admin', 'warehouse_staff']);
    }
}
