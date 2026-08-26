<?php

namespace App\Policies;

use App\Models\Category;
use App\Models\User;

class CategoryPolicy
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
        return $user->hasPermission('manage_categories') || $user->hasRole('admin');
    }

    public function view(User $user, Category $category): bool
    {
        return $user->hasPermission('manage_categories') || $user->hasRole('admin');
    }

    public function create(User $user): bool
    {
        return $user->hasPermission('manage_categories') || $user->hasRole('admin');
    }

    public function update(User $user, Category $category): bool
    {
        return $user->hasPermission('manage_categories') || $user->hasRole('admin');
    }

    public function delete(User $user, Category $category): bool
    {
        return $user->hasPermission('manage_categories') || $user->hasRole('admin');
    }
}
