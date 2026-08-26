<?php

namespace App\Policies;

use App\Models\PurchaseOrder;
use App\Models\User;

class PurchaseOrderPolicy
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
        return $user->hasPermission('manage_purchases')
            || $user->hasRole(['admin', 'warehouse_staff']);
    }

    public function view(User $user, PurchaseOrder $purchaseOrder): bool
    {
        return $user->hasPermission('manage_purchases')
            || $user->hasRole(['admin', 'warehouse_staff']);
    }

    public function create(User $user): bool
    {
        return $user->hasPermission('manage_purchases')
            || $user->hasRole('admin');
    }

    public function send(User $user, PurchaseOrder $purchaseOrder): bool
    {
        return $user->hasPermission('manage_purchases')
            || $user->hasRole('admin');
    }

    public function receive(User $user, PurchaseOrder $purchaseOrder): bool
    {
        return $user->hasPermission('manage_purchases')
            || $user->hasPermission('manage_inventory')
            || $user->hasRole(['admin', 'warehouse_staff']);
    }

    public function cancel(User $user, PurchaseOrder $purchaseOrder): bool
    {
        return $user->hasPermission('manage_purchases')
            || $user->hasRole('admin');
    }
}
