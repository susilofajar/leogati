<?php

namespace App\Policies;

use App\Models\Brand;
use App\Models\User;

class BrandPolicy
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
        return $user->hasPermission('manage_brands') || $user->hasRole('admin');
    }

    public function view(User $user, Brand $brand): bool
    {
        return $user->hasPermission('manage_brands') || $user->hasRole('admin');
    }

    public function create(User $user): bool
    {
        return $user->hasPermission('manage_brands') || $user->hasRole('admin');
    }

    public function update(User $user, Brand $brand): bool
    {
        return $user->hasPermission('manage_brands') || $user->hasRole('admin');
    }

    public function delete(User $user, Brand $brand): bool
    {
        return $user->hasPermission('manage_brands') || $user->hasRole('admin');
    }
}
