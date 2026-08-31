<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display customer portal dashboard.
     */
    public function index(Request $request): View
    {
        $user = $request->user();

        $stats = [
            'total_orders'   => $user->orders()->count(),
            'active_orders'  => $user->orders()->whereNotIn('status', ['completed', 'cancelled', 'refunded'])->count(),
            'wishlist_count' => $user->wishlists()->count(),
            'saved_builds'   => $user->savedPcBuilds()->count(),
        ];

        $recentOrders = $user->orders()->with('items.productvariant')->take(5)->get();

        return view('customer.dashboard', compact('user', 'stats', 'recentOrders'));
    }
}
