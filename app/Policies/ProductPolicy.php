<?php

namespace App\Policies;

use App\Models\Product;
use App\Models\User;

class ProductPolicy
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
        return $user->hasPermission('manage_products')
            || $user->hasRole(['admin', 'warehouse_staff', 'sales_staff']);
    }

    public function view(User $user, Product $product): bool
    {
        return $user->hasPermission('manage_products')
            || $user->hasRole(['admin', 'warehouse_staff', 'sales_staff']);
    }

    public function create(User $user): bool
    {
        return $user->hasPermission('manage_products')
            || $user->hasRole('admin');
    }

    public function update(User $user, Product $product): bool
    {
        return $user->hasPermission('manage_products')
            || $user->hasRole('admin');
    }

    public function delete(User $user, Product $product): bool
    {
        return $user->hasPermission('manage_products')
            || $user->hasRole('admin');
    }
}
