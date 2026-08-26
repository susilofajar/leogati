<?php

namespace App\Policies;

use App\Models\Review;
use App\Models\User;

class ReviewPolicy
{
    public function before(User $user, string $ability): ?bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }
        return null;
    }

    /**
     * Lihat daftar ulasan untuk moderasi — admin.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('manage_reviews')
            || $user->hasPermission('manage_products')
            || $user->hasRole('admin');
    }

    public function view(User $user, Review $review): bool
    {
        return $user->hasPermission('manage_reviews')
            || $user->hasPermission('manage_products')
            || $user->hasRole('admin');
    }

    /**
     * Toggle approval ulasan — admin.
     */
    public function moderate(User $user, Review $review): bool
    {
        return $user->hasPermission('manage_reviews')
            || $user->hasPermission('manage_products')
            || $user->hasRole('admin');
    }

    /**
     * Balas ulasan — admin.
     */
    public function reply(User $user, Review $review): bool
    {
        return $user->hasPermission('manage_reviews')
            || $user->hasPermission('manage_products')
            || $user->hasRole('admin');
    }
}
