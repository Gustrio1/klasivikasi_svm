<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SiswaController extends Controller
{

    /**
     * Admin: tampil semua siswa. Guru: hanya siswa yang diampu.
     */
    public function index()
    {
        try {
            $user = auth()->user();

            if ($user->role === 'admin') {
                $siswas = Siswa::with(['user', 'guru.user'])
                    ->paginate(15);
            }
            else {
                // Guru hanya melihat siswa dengan id_guru miliknya
                $guru = $user->guru;
                $siswas = Siswa::with(['user'])
                    ->where('id_guru', $guru->id)
                    ->paginate(15);
            }

            $view = match ($user->role) {
                    'admin' => 'admin.siswa.index',
                    'guru' => 'guru.siswa.index',
                    default => 'siswa.index',
                };
            return view($view, compact('siswas'));
        }
        catch (\Exception $e) {
            return back()->with('error', 'Gagal memuat daftar siswa: ' . $e->getMessage());
        }
    }

    /**
     * Form tambah siswa (admin/guru).
     */
    public function create()
    {
        try {
            $gurus = Guru::with('user')->where('is_active', true)->get();
            $kelas = ['A', 'B', 'C']; // Daftar kelas default

            $view = match (auth()->user()->role) {
                    'admin' => 'admin.siswa.create',
                    'guru' => 'guru.siswa.create',
                    default => 'siswa.create',
                };
            return view($view, compact('gurus', 'kelas'));
        }
        catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat memuat form: ' . $e->getMessage());
        }
    }

    /**
     * Buat user (role siswa) lalu buat record siswa; guru auto-assign ke guru yang login.
     */
    public function store(Request $request)
    {
        // Authorization handled via route middleware (auth + role)
        $user = auth()->user();

        $request->validate([
            'username'     => ['required', 'string', 'max:100', 'unique:tb_users,username'],
            'password'     => ['required', 'string', 'min:8', 'confirmed'],
            'nama_lengkap' => ['required', 'string', 'max:150'],
            'nisn'         => ['nullable', 'string', 'max:20', 'unique:tb_siswa,nisn'],
            'kelas'        => ['nullable', 'string', 'max:20'],
            'jenis_kelamin'=> ['required', 'in:L,P'],
            'tanggal_lahir'=> ['nullable', 'integer', 'digits:4', 'min:1900', 'max:' . date('Y')],
            'id_guru'      => [$user->role === 'admin' ? 'required' : 'nullable', 'exists:tb_guru,id'],
        ]);

        try {
            DB::transaction(function () use ($request, $user) {
                $newUser = User::create([
                    'username'     => $request->username,
                    'password'     => bcrypt($request->password),
                    'role'         => 'siswa',
                    'nama_lengkap' => $request->nama_lengkap,
                    'email'        => null, // Siswa tidak menggunakan email
                    'is_active'    => true,
                ]);

                // Jika yang login guru, assign ke guru tersebut otomatis
                $idGuru = ($user->role === 'guru')
                    ? $user->guru->id
                    : $request->id_guru;

                Siswa::create([
                    'id_user' => $newUser->id,
                    'id_guru' => $idGuru,
                    'nisn' => $request->nisn,
                    'kelas' => $request->kelas,
                    'jenis_kelamin' => $request->jenis_kelamin,
                    'tanggal_lahir' => $request->tanggal_lahir,
                ]);
            });

            $route = match (auth()->user()->role) {
                    'admin' => 'admin.siswa.index',
                    'guru' => 'guru.siswa.index',
                    default => 'siswa.index',
                };

            return redirect()->route($route)
                ->with('success', 'Data siswa berhasil ditambahkan.');
        }
        catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Gagal menambahkan siswa: ' . $e->getMessage());
        }
    }

    /**
     * Detail siswa: riwayat hafalan dan hasil klasifikasi terakhir.
     */
    public function show(Siswa $siswa)
    {
        $this->authorizeShowAccess($siswa);

        $siswa->load([
            'user',
            'guru.user',
            'dataHafalans.nilaiEvaluasi',
            'hasilKlasifikasis' => fn($q) => $q->latest('tanggal_klasifikasi')->with('modelSvm'),
        ]);

        $hasilTerakhir = $siswa->hasilKlasifikasis->first();

        $view = match (auth()->user()->role) {
                'admin' => 'admin.siswa.show',
                'guru' => 'guru.siswa.show',
                default => 'siswa.show',
            };
        return view($view, compact('siswa', 'hasilTerakhir'));
    }

    /**
     * Form edit siswa (admin/guru).
     */
    public function edit(Siswa $siswa)
    {
        $gurus = Guru::with('user')->where('is_active', true)->get();

        $view = auth()->user()->role === 'admin' ? 'admin.siswa.edit' : 'siswa.edit';
        return view($view, compact('siswa', 'gurus'));
    }

    /**
     * Update data siswa dalam satu transaksi dengan data user terkait.
     */
    public function update(Request $request, Siswa $siswa)
    {
        $request->validate([
            'nama_lengkap'  => ['sometimes', 'required', 'string', 'max:150'],
            'nisn'          => ['nullable', 'string', 'max:20'],
            'kelas'         => ['nullable', 'string', 'max:20'],
            'jenis_kelamin' => ['sometimes', 'required', 'in:L,P'],
            'tanggal_lahir' => ['nullable', 'integer', 'digits:4', 'min:1900', 'max:' . date('Y')],
            'id_guru'       => ['nullable', 'exists:tb_guru,id'],
            'password'      => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        try {
            DB::transaction(function () use ($request, $siswa) {
                $userData = $request->only(['nama_lengkap']);
                if ($request->filled('password')) {
                    $userData['password'] = bcrypt($request->password);
                }
                $siswa->user->update($userData);

                $siswa->update(
                    $request->only(['nisn', 'kelas', 'jenis_kelamin', 'tanggal_lahir', 'id_guru'])
                );
            });

            $route = match (auth()->user()->role) {
                    'admin' => 'admin.siswa.index',
                    'guru' => 'guru.siswa.index',
                    default => 'siswa.index',
                };

            return redirect()->route($route)
                ->with('success', 'Data siswa berhasil diperbarui.');
        }
        catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Gagal memperbarui siswa: ' . $e->getMessage());
        }
    }

    /**
     * Hapus siswa secara permanen beserta data terkait dan akun user-nya.
     */
    public function destroy(Siswa $siswa)
    {
        try {
            DB::transaction(function () use ($siswa) {
                $user = $siswa->user;

                // Hapus klasifikasi
                foreach ($siswa->hasilKlasifikasis as $hasil) {
                    $hasil->delete();
                }

                // Hapus data hafalan beserta nilai evaluasinya
                foreach ($siswa->dataHafalans as $hafalan) {
                    $hafalan->nilaiEvaluasi?->delete();
                    $hafalan->delete();
                }

                $siswa->delete();
                $user?->delete();
            });

            $route = auth()->user()->role === 'admin' ? 'admin.siswa.index' : 'guru.siswa.index';

            return redirect()->route($route)
                ->with('success', 'Data siswa berhasil dihapus secara permanen.');
        }
        catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus siswa: ' . $e->getMessage());
        }
    }

    /**
     * Helper: pastikan siswa hanya bisa melihat data dirinya sendiri.
     */
    private function authorizeShowAccess(Siswa $siswa): void
    {
        $user = auth()->user();
        if ($user->role === 'siswa' && $user->siswa->id !== $siswa->id) {
            abort(403);
        }
        if ($user->role === 'guru' && $siswa->id_guru !== $user->guru->id) {
            abort(403);
        }
    }
}
