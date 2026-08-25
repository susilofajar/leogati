<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\SavedPcBuild;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SavedBuildController extends Controller
{
    /**
     * Tampilkan daftar racikan PC tersimpan pelanggan.
     */
    public function index()
    {
        $builds = Auth::user()
            ->savedPcBuilds()
            ->paginate(10);

        return view('customer.builds.index', compact('builds'));
    }

    /**
     * Tampilkan detail racikan PC.
     */
    public function show($token)
    {
        $build = SavedPcBuild::where('share_token', $token)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return view('customer.builds.show', compact('build'));
    }

    /**
     * Hapus racikan PC tersimpan.
     */
    public function destroy($token)
    {
        $build = SavedPcBuild::where('share_token', $token)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $build->delete();

        return back()->with('success', 'Racikan PC berhasil dihapus.');
    }
}
