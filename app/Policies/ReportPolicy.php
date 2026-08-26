<?php

namespace App\Policies;

use App\Models\User;

class ReportPolicy
{
    public function before(User $user, string $ability): ?bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }
        return null;
    }

    /**
     * Lihat laporan — admin, finance_staff.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('view_reports') || $user->hasRole(['admin', 'finance_staff']);
    }

    /**
     * Laporan penjualan — admin, finance_staff.
     */
    public function viewSales(User $user): bool
    {
        return $user->hasPermission('view_reports') || $user->hasRole(['admin', 'finance_staff']);
    }

    /**
     * Laporan inventaris — admin (bukan finance_staff).
     */
    public function viewInventory(User $user): bool
    {
        if ($user->hasRole('finance_staff') && !$user->hasRole(['super_admin', 'admin'])) {
            return false;
        }
        return $user->hasPermission('view_reports') || $user->hasRole('admin');
    }

    /**
     * Laporan pembelian — admin (bukan finance_staff).
     */
    public function viewPurchasing(User $user): bool
    {
        if ($user->hasRole('finance_staff') && !$user->hasRole(['super_admin', 'admin'])) {
            return false;
        }
        return $user->hasPermission('view_reports') || $user->hasRole('admin');
    }

    /**
     * Laporan pelanggan — admin, finance_staff.
     */
    public function viewCustomers(User $user): bool
    {
        return $user->hasPermission('view_reports') || $user->hasRole(['admin', 'finance_staff']);
    }
}
