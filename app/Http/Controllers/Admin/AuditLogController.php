<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AuditLogController extends Controller
{
    /**
     * Tampilkan riwayat jejak audit log keamanan operasional.
     */
    public function index(Request $request): View
    {
        $this->authorize('viewAny', AuditLog::class);

        $query = AuditLog::with('user')->latest('created_at');

        if ($action = $request->query('action')) {
            $query->where('action', $action);
        }

        if ($userId = $request->query('user_id')) {
            $query->where('user_id', $userId);
        }

        if ($search = $request->query('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('user_name', 'like', "%{$search}%")
                  ->orWhere('action', 'like', "%{$search}%")
                  ->orWhere('ip_address', 'like', "%{$search}%")
                  ->orWhere('payload', 'like', "%{$search}%");
            });
        }

        $logs = $query->paginate(25)->withQueryString();
        $actions = AuditLog::select('action')->distinct()->pluck('action');
        $staffUsers = User::whereHas('roles', fn($q) => $q->whereIn('name', ['super_admin', 'admin', 'warehouse_staff', 'sales_staff', 'finance_staff']))->get();

        return view('admin.audit-log.index', compact('logs', 'actions', 'staffUsers'));
    }
}
