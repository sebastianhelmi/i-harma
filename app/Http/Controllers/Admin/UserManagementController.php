<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UserManagementController extends Controller
{
    /**
     * Display a listing of users
     */
    public function index(Request $request)
    {
        $query = User::with('role');

        // Apply search filter
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                    ->orWhere('email', 'like', "%{$request->search}%");
            });
        }

        // Apply role filter
        if ($request->filled('role')) {
            $query->where('role_id', $request->role);
        }

        // Apply status filter
        if ($request->filled('status')) {
            $query->where('is_active', $request->boolean('status'));
        }

        // Get statistics
        $stats = [
            'total' => User::count(),
            'active' => User::where('is_active', true)->count(),
            'inactive' => User::where('is_active', false)->count()
        ];

        // Get roles with ID and name
        $roles = Role::pluck('name', 'id');
        $users = $query->latest()->paginate(10)->withQueryString();

        return view('admin.users.index', compact('users', 'stats', 'roles'));
    }

    public function show(User $user)
    {
        return response()->json($user->load('role'));
    }


    /**
     * Store a newly created user
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'password' => ['required', 'confirmed', Password::min(8)],
                'role_id' => ['required', 'exists:roles,id'],
                'is_active' => ['boolean']
            ], [
                'name.required' => 'Nama lengkap harus diisi',
                'email.required' => 'Email harus diisi',
                'email.email' => 'Format email tidak valid',
                'email.unique' => 'Email sudah digunakan',
                'password.required' => 'Password harus diisi',
                'password.min' => 'Password minimal 8 karakter',
                'password.confirmed' => 'Konfirmasi password tidak sesuai',
                'role_id.required' => 'Role harus dipilih',
                'role_id.exists' => 'Role tidak valid'
            ]);

            DB::beginTransaction();

            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role_id' => $validated['role_id'],
                'is_active' => $validated['is_active'] ?? true
            ]);

            DB::commit();

            return response()->json([
                'message' => 'User berhasil ditambahkan',
                'user' => $user->load('role')
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Terjadi kesalahan saat menambahkan user',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    /**
     * Update the specified user
     */
    public function update(Request $request, User $user)
    {
        try {
            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'email', 'unique:users,email,' . $user->id],
                'password' => $request->filled('password') ? ['confirmed', Password::min(8)] : [],
                'role_id' => ['required', 'exists:roles,id'],
                'is_active' => ['boolean']
            ]);

            DB::beginTransaction();

            $updateData = [
                'name' => $validated['name'],
                'email' => $validated['email'],
                'role_id' => $validated['role_id'],
                'is_active' => $validated['is_active'] ?? false
            ];

            if ($request->filled('password')) {
                $updateData['password'] = Hash::make($validated['password']);
            }

            $user->update($updateData);

            DB::commit();

            return response()->json([
                'message' => 'User berhasil diperbarui',
                'user' => $user->fresh()->load('role')
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Remove the specified user
     */
    public function destroy(User $user)
    {
        try {
            // Prevent self-deletion
            if ($user->id === Auth::id()) {
                return response()->json([
                    'message' => 'Anda tidak dapat menghapus akun sendiri'
                ], 403);
            }

            DB::beginTransaction();

            $user->delete();

            DB::commit();

            return response()->json([
                'message' => 'User berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting user: ' . $e->getMessage());

            return response()->json([
                'message' => 'Terjadi kesalahan saat menghapus user',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Bulk delete users
     */
    public function bulkDestroy(Request $request)
    {
        $validated = $request->validate([
            'ids' => ['required', 'array'],
            'ids.*' => ['exists:users,id']
        ]);

        try {
            // Prevent self-deletion
            if (in_array(Auth::id(), $validated['ids'])) {
                return response()->json([
                    'message' => 'Anda tidak dapat menghapus akun sendiri'
                ], 403);
            }

            DB::beginTransaction();

            User::whereIn('id', $validated['ids'])->delete();

            DB::commit();

            return response()->json([
                'message' => count($validated['ids']) . ' user berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Terjadi kesalahan saat menghapus user',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Toggle user active status
     */
    public function toggleStatus(User $user)
    {
        try {
            // Prevent self-deactivation
            if ($user->id === Auth::id()) {
                return response()->json([
                    'message' => 'Anda tidak dapat menonaktifkan akun sendiri'
                ], 403);
            }

            $user->update(['is_active' => !$user->is_active]);

            return response()->json([
                'message' => 'Status user berhasil diperbarui',
                'is_active' => $user->is_active
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan saat mengubah status user',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
