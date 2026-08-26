<?php

namespace App\Policies;

use App\Models\Coupon;
use App\Models\User;

class CouponPolicy
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
        return $user->hasPermission('manage_promotions') || $user->hasRole(['admin', 'sales_staff']);
    }

    public function view(User $user, Coupon $coupon): bool
    {
        return $user->hasPermission('manage_promotions') || $user->hasRole(['admin', 'sales_staff']);
    }

    public function create(User $user): bool
    {
        return $user->hasPermission('manage_promotions') || $user->hasRole(['admin', 'sales_staff']);
    }

    public function update(User $user, Coupon $coupon): bool
    {
        return $user->hasPermission('manage_promotions') || $user->hasRole(['admin', 'sales_staff']);
    }

    public function delete(User $user, Coupon $coupon): bool
    {
        return $user->hasPermission('manage_promotions') || $user->hasRole(['admin', 'sales_staff']);
    }
}
