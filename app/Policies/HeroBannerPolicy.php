<?php

namespace App\Policies;

use App\Models\HeroBanner;
use App\Models\User;

class HeroBannerPolicy
{
    /**
     * Intercept all checks for Super Admin.
     */
    public function before(User $user, string $ability): ?bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        return null;
    }

    /**
     * Determine whether the user can view any hero banners in admin panel.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRole(['super_admin', 'admin']);
    }

    /**
     * Determine whether the user can create hero banners.
     */
    public function create(User $user): bool
    {
        return $user->hasRole(['super_admin', 'admin']);
    }

    /**
     * Determine whether the user can update the hero banner.
     */
    public function update(User $user, HeroBanner $heroBanner): bool
    {
        return $user->hasRole(['super_admin', 'admin']);
    }

    /**
     * Determine whether the user can delete the hero banner.
     */
    public function delete(User $user, HeroBanner $heroBanner): bool
    {
        return $user->hasRole(['super_admin', 'admin']);
    }
}
