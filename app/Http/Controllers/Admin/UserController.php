<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use App\Services\AuditLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    /**
     * Tampilkan daftar akun pengguna & staf.
     */
    public function index(Request $request)
    {
        $query = User::with('roles');

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($u) use ($q) {
                $u->where('name', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%");
            });
        }

        if ($request->filled('role')) {
            $query->whereHas('roles', function ($r) use ($request) {
                $r->where('name', $request->role);
            });
        }

        $users = $query->latest()->paginate(15)->withQueryString();
        $roles = Role::all();

        return view('admin.pengguna.index', compact('users', 'roles'));
    }

    /**
     * Tampilkan form tambah pengguna / staf baru.
     */
    public function create()
    {
        $roles = Role::all();
        return view('admin.pengguna.create', compact('roles'));
    }

    /**
     * Simpan pengguna baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|max:255|unique:users,email',
            'password' => ['required', Password::min(8)],
            'roles'    => 'required|array|min:1',
            'roles.*'  => 'exists:roles,id',
        ]);

        $user = User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        $user->roles()->sync($validated['roles']);

        $roleNames = $user->roles()->pluck('display_name')->implode(', ');

        AuditLogService::log(
            'create_user',
            'User',
            $user->id,
            ['name' => $user->name, 'email' => $user->email, 'roles' => $validated['roles']]
        );

        return redirect()->route('admin.pengguna.index')
            ->with('success', "Akun pengguna '{$user->name}' berhasil dibuat.");
    }

    /**
     * Tampilkan form edit pengguna.
     */
    public function edit(User $pengguna)
    {
        $pengguna->load('roles');
        $roles = Role::all();
        return view('admin.pengguna.edit', ['user' => $pengguna, 'roles' => $roles]);
    }

    /**
     * Perbarui data pengguna & perannya.
     */
    public function update(Request $request, User $pengguna)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($pengguna->id)],
            'password' => ['nullable', Password::min(8)],
            'roles'    => 'required|array|min:1',
            'roles.*'  => 'exists:roles,id',
        ]);

        $updateData = [
            'name'  => $validated['name'],
            'email' => $validated['email'],
        ];

        if (!empty($validated['password'])) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        $pengguna->update($updateData);
        $pengguna->roles()->sync($validated['roles']);

        $roleNames = $pengguna->roles()->pluck('display_name')->implode(', ');

        AuditLogService::log(
            'update_user',
            'User',
            $pengguna->id,
            ['name' => $pengguna->name, 'email' => $pengguna->email, 'roles' => $validated['roles']]
        );

        return redirect()->route('admin.pengguna.index')
            ->with('success', "Data pengguna '{$pengguna->name}' berhasil diperbarui.");
    }

    /**
     * Hapus akun pengguna.
     */
    public function destroy(User $pengguna)
    {
        if ($pengguna->id === Auth::id()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        // Proteksi: jangan hapus super admin jika hanya ada 1 super admin
        if ($pengguna->isSuperAdmin()) {
            $superAdminCount = User::whereHas('roles', fn($q) => $q->where('name', 'super_admin'))->count();
            if ($superAdminCount <= 1) {
                return back()->with('error', 'Tidak dapat menghapus satu-satunya Super Admin sistem.');
            }
        }

        $id = $pengguna->id;
        $name = $pengguna->name;
        $email = $pengguna->email;
        $pengguna->delete();

        AuditLogService::log(
            'delete_user',
            'User',
            $id,
            ['name' => $name, 'email' => $email]
        );

        return redirect()->route('admin.pengguna.index')
            ->with('success', "Akun pengguna '{$name}' berhasil dihapus.");
    }
}
