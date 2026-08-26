<?php

namespace App\Policies;

use App\Models\Supplier;
use App\Models\User;

class SupplierPolicy
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
        return $user->hasPermission('manage_suppliers') || $user->hasRole('admin');
    }

    public function view(User $user, Supplier $supplier): bool
    {
        return $user->hasPermission('manage_suppliers') || $user->hasRole('admin');
    }

    public function create(User $user): bool
    {
        return $user->hasPermission('manage_suppliers') || $user->hasRole('admin');
    }

    public function update(User $user, Supplier $supplier): bool
    {
        return $user->hasPermission('manage_suppliers') || $user->hasRole('admin');
    }

    public function delete(User $user, Supplier $supplier): bool
    {
        return $user->hasPermission('manage_suppliers') || $user->hasRole('admin');
    }
}
