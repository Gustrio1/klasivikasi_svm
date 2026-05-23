<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{

    /**
     * Tampilkan daftar semua user.
     */
    public function index()
    {
        $users = User::orderBy('created_at', 'desc')->paginate(15);

        return view('admin.users.index', compact('users'));
    }

    /**
     * Tampilkan form tambah user baru.
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Simpan user baru dengan password ter-hash.
     */
    public function store(StoreUserRequest $request)
    {
        try {
            User::create([
                'username' => $request->username,
                'password' => bcrypt($request->password),
                'role' => $request->role,
                'nama_lengkap' => $request->nama_lengkap,
                'email' => $request->email,
                'is_active' => $request->boolean('is_active', true),
            ]);

            return redirect()->route('admin.users.index')
                ->with('success', 'User berhasil ditambahkan.');
        }
        catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Gagal menambahkan user: ' . $e->getMessage());
        }
    }

    /**
     * Tampilkan detail user.
     */
    public function show(User $user)
    {
        $user->load(['guru', 'siswa']);

        return view('admin.users.show', compact('user'));
    }

    /**
     * Tampilkan form edit user.
     */
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update data user; hash ulang password jika diisi.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        try {
            $data = $request->except('password', 'password_confirmation');

            if ($request->filled('password')) {
                $data['password'] = bcrypt($request->password);
            }

            $user->update($data);

            return redirect()->route('admin.users.index')
                ->with('success', 'User berhasil diperbarui.');
        }
        catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Gagal memperbarui user: ' . $e->getMessage());
        }
    }

    /**
     * Hapus user secara permanen.
     */
    public function destroy(User $user)
    {
        try {
            $user->delete();

            return redirect()->route('admin.users.index')
                ->with('success', 'User berhasil dihapus secara permanen.');
        }
        catch (\Illuminate\Database\QueryException $e) {
            // Check for foreign key constraint violation
            if ($e->getCode() == '23000') {
                return back()->with('error', 'Gagal menghapus user: User ini terikat dengan data lain (misal: Guru yang masih memiliki Siswa atau data Hafalan).');
            }
            return back()->with('error', 'Gagal menghapus user: ' . $e->getMessage());
        }
        catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus user: ' . $e->getMessage());
        }
    }
}
