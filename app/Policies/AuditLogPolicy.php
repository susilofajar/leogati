<?php

namespace App\Policies;

use App\Models\AuditLog;
use App\Models\User;

class AuditLogPolicy
{
    public function before(User $user, string $ability): ?bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }
        return null;
    }

    /**
     * Lihat audit log — hanya super_admin dan admin.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRole(['super_admin', 'admin']);
    }
}
