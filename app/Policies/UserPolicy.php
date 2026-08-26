<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
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
        return $user->hasPermission('manage_users') || $user->hasRole('admin');
    }

    public function view(User $user, User $model): bool
    {
        return $user->hasPermission('manage_users') || $user->hasRole('admin');
    }

    public function create(User $user): bool
    {
        return $user->hasPermission('manage_users') || $user->hasRole('admin');
    }

    public function update(User $user, User $model): bool
    {
        return $user->hasPermission('manage_users') || $user->hasRole('admin');
    }

    public function delete(User $user, User $model): bool
    {
        // Tidak boleh hapus akun sendiri (dihandle juga di controller)
        if ($user->id === $model->id) {
            return true; // Let controller handle the custom flash message 'error'
        }
        return $user->hasPermission('manage_users') || $user->hasRole('admin');
    }
}
