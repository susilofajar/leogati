<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NotificationController extends Controller
{
    /**
     * Tampilkan seluruh notifikasi milik pelanggan yang sedang login.
     */
    public function index(Request $request): View
    {
        $filter = $request->query('filter', 'all');
        $user = $request->user();

        $query = $user->notifications();

        if ($filter === 'unread') {
            $query->whereNull('read_at');
        }

        $notifications = $query->paginate(15)->withQueryString();
        $unreadCount = $user->unreadNotifications()->count();

        return view('customer.notifications.index', compact('notifications', 'unreadCount', 'filter'));
    }

    /**
     * Tandai sebuah notifikasi telah dibaca lalu arahkan ke tujuan (jika ada).
     */
    public function markAsRead(Request $request, string $id): RedirectResponse
    {
        $notification = $request->user()->notifications()->findOrFail($id);
        $notification->markAsRead();

        $targetUrl = $notification->data['url'] ?? route('customer.notifications.index');

        return redirect($targetUrl);
    }

    /**
     * Tandai semua notifikasi telah dibaca.
     */
    public function markAllAsRead(Request $request): RedirectResponse
    {
        $request->user()->unreadNotifications->markAsRead();

        return redirect()->route('customer.notifications.index')
            ->with('success', 'Semua notifikasi telah ditandai dibaca.');
    }
}
