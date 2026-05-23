<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GuruController extends Controller
{

    /**
     * Tampilkan semua guru beserta data user terkait.
     */
    public function index()
    {
        $gurus = Guru::with('user')
            ->orderBy('id', 'desc')
            ->paginate(15);

        $view = auth()->user()->role === 'admin' ? 'admin.guru.index' : 'guru.index';
        return view($view, compact('gurus'));
    }

    /**
     * Tampilkan form tambah guru.
     */
    public function create()
    {
        $view = auth()->user()->role === 'admin' ? 'admin.guru.create' : 'guru.create';
        return view($view);
    }

    /**
     * Buat akun user (role guru) lalu buat record guru dalam satu transaksi.
     */
    public function store(Request $request)
    {
        $request->validate([
            'username' => ['required', 'string', 'max:100', 'unique:tb_users,username'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'nama_lengkap' => ['required', 'string', 'max:150'],
            'email' => ['required', 'email', 'unique:tb_users,email'],
            'nip' => ['nullable', 'string', 'max:50', 'unique:tb_guru,nip'],
            'mata_pelajaran' => ['nullable', 'string', 'max:100'],
            'no_telp' => ['nullable', 'string', 'max:20'],
        ]);

        try {
            DB::transaction(function () use ($request) {
                $user = User::create([
                    'username' => $request->username,
                    'password' => bcrypt($request->password),
                    'role' => 'guru',
                    'nama_lengkap' => $request->nama_lengkap,
                    'email' => $request->email,
                    'is_active' => true,
                ]);

                Guru::create([
                    'id_user' => $user->id,
                    'nip' => $request->nip,
                    'mata_pelajaran' => $request->mata_pelajaran,
                    'no_telp' => $request->no_telp,
                    'is_active' => true,
                ]);
            });

            return redirect()->route('admin.guru.index')
                ->with('success', 'Data guru berhasil ditambahkan.');
        }
        catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Gagal menambahkan guru: ' . $e->getMessage());
        }
    }

    /**
     * Tampilkan detail guru.
     */
    public function show(Guru $guru)
    {
        $guru->load(['user', 'siswas', 'dataHafalans']);

        $view = auth()->user()->role === 'admin' ? 'admin.guru.show' : 'guru.show';
        return view($view, compact('guru'));
    }

    /**
     * Tampilkan form edit guru.
     */
    public function edit(Guru $guru)
    {
        $guru->load('user');

        $view = auth()->user()->role === 'admin' ? 'admin.guru.edit' : 'guru.edit';
        return view($view, compact('guru'));
    }

    /**
     * Update data guru dan user terkait dalam satu transaksi.
     */
    public function update(Request $request, Guru $guru)
    {
        $request->validate([
            'nama_lengkap' => ['sometimes', 'required', 'string', 'max:150'],
            'email' => ['sometimes', 'required', 'email'],
            'nip' => ['nullable', 'string', 'max:50'],
            'mata_pelajaran' => ['nullable', 'string', 'max:100'],
            'no_telp' => ['nullable', 'string', 'max:20'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        try {
            DB::transaction(function () use ($request, $guru) {
                // Update tabel users
                $userData = $request->only(['nama_lengkap', 'email']);
                if ($request->filled('password')) {
                    $userData['password'] = bcrypt($request->password);
                }
                $guru->user->update($userData);

                // Update tabel guru
                $guru->update($request->only(['nip', 'mata_pelajaran', 'no_telp']));
            });

            return redirect()->route('admin.guru.index')
                ->with('success', 'Data guru berhasil diperbarui.');
        }
        catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Gagal memperbarui guru: ' . $e->getMessage());
        }
    }

    /**
     * Hapus guru secara permanen beserta akun user-nya.
     */
    public function destroy(Guru $guru)
    {
        try {
            DB::transaction(function () use ($guru) {
                $user = $guru->user;
                $guru->delete();
                $user?->delete();
            });

            return redirect()->route('admin.guru.index')
                ->with('success', 'Data guru berhasil dihapus secara permanen.');
        }
        catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() == '23000') {
                return back()->with('error', 'Gagal menghapus: Guru ini masih memiliki siswa atau data hafalan terkait. Hapus data tersebut terlebih dahulu.');
            }
            return back()->with('error', 'Gagal menghapus guru: ' . $e->getMessage());
        }
        catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus guru: ' . $e->getMessage());
        }
    }
}
