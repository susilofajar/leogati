<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreHeroBannerRequest;
use App\Http\Requests\Admin\UpdateHeroBannerRequest;
use App\Models\HeroBanner;
use App\Services\AuditLogService;
use App\Services\CacheService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class HeroBannerController extends Controller
{
    public function __construct(
        protected CacheService $cacheService
    ) {}

    /**
     * Tampilkan daftar slide background hero.
     */
    public function index(Request $request): View
    {
        $this->authorize('viewAny', HeroBanner::class);

        $query = HeroBanner::query();

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($b) use ($q) {
                $b->where('title', 'like', "%{$q}%")
                  ->orWhere('subtitle', 'like', "%{$q}%")
                  ->orWhere('badge_text', 'like', "%{$q}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $banners = $query->ordered()->paginate(10)->withQueryString();

        return view('admin.hero_banners.index', compact('banners'));
    }

    /**
     * Tampilkan form pembuatan slide hero baru.
     */
    public function create(): View
    {
        $this->authorize('create', HeroBanner::class);

        return view('admin.hero_banners.create');
    }

    /**
     * Simpan slide hero baru.
     */
    public function store(StoreHeroBannerRequest $request): RedirectResponse
    {
        $this->authorize('create', HeroBanner::class);

        $validated = $request->validated();

        $imagePath = null;
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('hero_banners', 'public');
            $imagePath = 'storage/' . $path;
        }

        $banner = HeroBanner::create([
            'title'       => $validated['title'] ?? null,
            'subtitle'    => $validated['subtitle'] ?? null,
            'badge_text'  => $validated['badge_text'] ?? null,
            'image_path'  => $imagePath,
            'button_text' => $validated['button_text'] ?? null,
            'button_url'  => $validated['button_url'] ?? null,
            'sort_order'  => $validated['sort_order'] ?? 0,
            'is_active'   => $request->has('is_active'),
        ]);

        $this->cacheService->flushHeroBannerCache();

        AuditLogService::log(
            'create_hero_banner',
            'HeroBanner',
            $banner->id,
            $banner->toArray()
        );

        return redirect()->route('admin.banner-hero.index')
            ->with('success', 'Slide background hero berhasil ditambahkan.');
    }

    /**
     * Tampilkan form edit slide hero.
     */
    public function edit(HeroBanner $banner_hero): View
    {
        $this->authorize('update', $banner_hero);

        return view('admin.hero_banners.edit', ['banner' => $banner_hero]);
    }

    /**
     * Perbarui slide hero.
     */
    public function update(UpdateHeroBannerRequest $request, HeroBanner $banner_hero): RedirectResponse
    {
        $this->authorize('update', $banner_hero);

        $validated = $request->validated();

        $imagePath = $banner_hero->image_path;
        if ($request->hasFile('image')) {
            // Hapus gambar lama jika ada di storage publik
            if ($banner_hero->image_path && str_contains($banner_hero->image_path, 'storage/hero_banners/')) {
                $oldRelative = str_replace('storage/', '', $banner_hero->image_path);
                Storage::disk('public')->delete($oldRelative);
            }

            $path = $request->file('image')->store('hero_banners', 'public');
            $imagePath = 'storage/' . $path;
        }

        $banner_hero->update([
            'title'       => $validated['title'] ?? null,
            'subtitle'    => $validated['subtitle'] ?? null,
            'badge_text'  => $validated['badge_text'] ?? null,
            'image_path'  => $imagePath,
            'button_text' => $validated['button_text'] ?? null,
            'button_url'  => $validated['button_url'] ?? null,
            'sort_order'  => $validated['sort_order'] ?? 0,
            'is_active'   => $request->has('is_active'),
        ]);

        $this->cacheService->flushHeroBannerCache();

        AuditLogService::log(
            'update_hero_banner',
            'HeroBanner',
            $banner_hero->id,
            $banner_hero->toArray()
        );

        return redirect()->route('admin.banner-hero.index')
            ->with('success', 'Slide background hero berhasil diperbarui.');
    }

    /**
     * Hapus slide hero.
     */
    public function destroy(HeroBanner $banner_hero): RedirectResponse
    {
        $this->authorize('delete', $banner_hero);

        $id = $banner_hero->id;
        $title = $banner_hero->title ?? "Slide #{$id}";

        if ($banner_hero->image_path && str_contains($banner_hero->image_path, 'storage/hero_banners/')) {
            $oldRelative = str_replace('storage/', '', $banner_hero->image_path);
            Storage::disk('public')->delete($oldRelative);
        }

        $banner_hero->delete();

        $this->cacheService->flushHeroBannerCache();

        AuditLogService::log(
            'delete_hero_banner',
            'HeroBanner',
            $id,
            ['title' => $title]
        );

        return redirect()->route('admin.banner-hero.index')
            ->with('success', "Slide '{$title}' berhasil dihapus.");
    }

    /**
     * Toggle status aktif slide hero.
     */
    public function toggle(HeroBanner $banner_hero): RedirectResponse
    {
        $this->authorize('update', $banner_hero);

        $banner_hero->update([
            'is_active' => !$banner_hero->is_active,
        ]);

        $this->cacheService->flushHeroBannerCache();

        $status = $banner_hero->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return redirect()->back()
            ->with('success', "Status slide '{$banner_hero->title}' berhasil {$status}.");
    }
}
