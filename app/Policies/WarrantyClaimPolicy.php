<?php

namespace App\Policies;

use App\Models\User;
use App\Models\WarrantyClaim;

class WarrantyClaimPolicy
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
        return $user->hasPermission('manage_warranties')
            || $user->hasRole(['admin', 'warehouse_staff']);
    }

    public function view(User $user, WarrantyClaim $warrantyClaim): bool
    {
        return $user->hasPermission('manage_warranties')
            || $user->hasRole(['admin', 'warehouse_staff']);
    }

    public function update(User $user, WarrantyClaim $warrantyClaim): bool
    {
        return $user->hasPermission('manage_warranties')
            || $user->hasRole(['admin', 'warehouse_staff']);
    }
}
