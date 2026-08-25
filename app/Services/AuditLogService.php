<?php

namespace App\Services;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class AuditLogService
{
    /**
     * Catat aktivitas audit secara aman.
     */
    public static function log(
        string $action,
        ?string $targetType = null,
        ?int $targetId = null,
        ?array $payload = null,
        ?int $userId = null,
        ?string $userName = null
    ): AuditLog {
        $currentUser = Auth::user();
        
        return AuditLog::create([
            'user_id' => $userId ?? $currentUser?->id,
            'user_name' => $userName ?? $currentUser?->name ?? 'Sistem',
            'action' => $action,
            'target_type' => $targetType,
            'target_id' => $targetId,
            'ip_address' => Request::ip() ?? '127.0.0.1',
            'user_agent' => Request::userAgent() ?? 'System',
            'payload' => $payload,
            'created_at' => now(),
        ]);
    }
}
