@extends('layouts.app')

@section('title', 'Tambah Guru Baru')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
    body { font-family: 'Plus Jakarta Sans', sans-serif; }
    .field-input:focus {
        outline: none;
        border-color: #0d9488;
        box-shadow: 0 0 0 3px rgba(13,148,136,.15);
    }
    #strengthFill { transition: width .35s ease, background-color .35s ease; }
</style>
@endpush

@section('content')

<x-page-header
    title="Registrasi Guru Pembimbing"
    subtitle="Buat akun untuk guru baru yang akan mengelola dan membimbing hafalan siswa."
    :links="[
        ['label' => 'Dashboard', 'url' => route('dashboard')],
        ['label' => 'Manajemen Guru', 'url' => route('admin.guru.index')],
        ['label' => 'Tambah Guru', 'url' => null],
    ]"
/>

@if ($errors->any())
<div class="flex gap-3 bg-red-50 border border-red-200 rounded-xl p-4 mb-6">
    <svg class="w-5 h-5 text-red-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
    <div>
        <p class="text-sm font-bold text-red-700 mb-1">Terdapat kesalahan pada input:</p>
        <ul class="text-sm text-red-600 list-disc list-inside space-y-0.5">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
</div>
@endif

<div class="max-w-3xl mx-auto pb-12">
    <form action="{{ route('admin.guru.store') }}" method="POST" class="space-y-6">
        @csrf

        {{-- ── Seksi 1: Kredensial Login ── --}}
        <div class="bg-white rounded-2xl border border-gray-200 shadow-xl shadow-gray-100/60 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/70 flex items-center gap-3">
                <div class="w-8 h-8 bg-teal-600 text-white rounded-full flex items-center justify-center text-xs font-black">1</div>
                <div>
                    <p class="text-[10px] font-black uppercase tracking-widest text-gray-400">Langkah 1</p>
                    <p class="text-sm font-bold text-gray-800">Informasi Login</p>
                </div>
            </div>

            <div class="p-6 space-y-5">
                {{-- Username --}}
                <div class="space-y-1.5">
                    <label class="block text-sm font-semibold text-gray-700">Username <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        </span>
                        <input type="text" name="username" value="{{ old('username') }}" required
                               placeholder="ustadz_akhmad"
                               class="field-input w-full pl-10 pr-4 py-2.5 text-sm font-medium text-gray-800 bg-gray-50 border @error('username') border-red-400 bg-red-50 @else border-gray-200 @enderror rounded-xl placeholder-gray-400 transition-colors hover:border-gray-300">
                    </div>
                    <p class="text-[11px] text-gray-400 italic">Huruf kecil, angka, dan garis bawah. Tanpa spasi.</p>
                    @error('username') <p class="text-xs text-red-500 font-bold">{{ $message }}</p> @enderror
                </div>

                {{-- Email --}}
                <div class="space-y-1.5">
                    <label class="block text-sm font-semibold text-gray-700">Alamat Email <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        </span>
                        <input type="email" name="email" value="{{ old('email') }}" required
                               placeholder="email@sekolah.sch.id"
                               class="field-input w-full pl-10 pr-4 py-2.5 text-sm font-medium text-gray-800 bg-gray-50 border @error('email') border-red-400 bg-red-50 @else border-gray-200 @enderror rounded-xl placeholder-gray-400 transition-colors hover:border-gray-300">
                    </div>
                    @error('email') <p class="text-xs text-red-500 font-bold">{{ $message }}</p> @enderror
                </div>

                {{-- Password Row --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    {{-- Password --}}
                    <div class="space-y-1.5">
                        <label class="block text-sm font-semibold text-gray-700">Password <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                            </span>
                            <input type="password" name="password" id="f_password" required
                                   placeholder="Min. 8 karakter"
                                   oninput="checkStrength(this.value)"
                                   class="field-input w-full pl-10 pr-10 py-2.5 text-sm font-medium text-gray-800 bg-gray-50 border border-gray-200 rounded-xl placeholder-gray-400 transition-colors hover:border-gray-300">
                            <button type="button" onclick="togglePwd('f_password', this)"
                                    class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-gray-400 hover:text-gray-600 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            </button>
                        </div>
                        {{-- Strength meter --}}
                        <div class="h-1 bg-gray-200 rounded-full overflow-hidden">
                            <div id="strengthFill" class="h-full rounded-full w-0"></div>
                        </div>
                        <p id="strengthText" class="text-[11px] text-gray-400">Masukkan password</p>
                    </div>

                    {{-- Confirm Password --}}
                    <div class="space-y-1.5">
                        <label class="block text-sm font-semibold text-gray-700">Konfirmasi <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                            </span>
                            <input type="password" name="password_confirmation" id="f_confirm" required
                                   placeholder="Ulangi password"
                                   oninput="checkMatch()"
                                   class="field-input w-full pl-10 pr-10 py-2.5 text-sm font-medium text-gray-800 bg-gray-50 border border-gray-200 rounded-xl placeholder-gray-400 transition-colors hover:border-gray-300">
                            <button type="button" onclick="togglePwd('f_confirm', this)"
                                    class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-gray-400 hover:text-gray-600 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            </button>
                        </div>
                        <p id="matchHint" class="hidden text-[11px] text-red-500 font-semibold">Password tidak cocok.</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── Seksi 2: Data Kepegawaian ── --}}
        <div class="bg-white rounded-2xl border border-gray-200 shadow-xl shadow-gray-100/60 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/70 flex items-center gap-3">
                <div class="w-8 h-8 bg-teal-600 text-white rounded-full flex items-center justify-center text-xs font-black">2</div>
                <div>
                    <p class="text-[10px] font-black uppercase tracking-widest text-gray-400">Langkah 2</p>
                    <p class="text-sm font-bold text-gray-800">Profil & Data Kepegawaian</p>
                </div>
            </div>

            <div class="p-6 space-y-5">
                {{-- Nama Lengkap --}}
                <div class="space-y-1.5">
                    <label class="block text-sm font-semibold text-gray-700">Nama Lengkap & Gelar <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </span>
                        <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap') }}" required
                               placeholder="Ustadz Ahmad Fauzi, S.Pd.I"
                               class="field-input w-full pl-10 pr-4 py-2.5 text-sm font-medium text-gray-800 bg-gray-50 border @error('nama_lengkap') border-red-400 bg-red-50 @else border-gray-200 @enderror rounded-xl placeholder-gray-400 transition-colors hover:border-gray-300">
                    </div>
                    @error('nama_lengkap') <p class="text-xs text-red-500 font-bold">{{ $message }}</p> @enderror
                </div>

                {{-- NIP & No. WA --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div class="space-y-1.5">
                        <label class="block text-sm font-semibold text-gray-700">NIP / ID Kepegawaian</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"/></svg>
                            </span>
                            <input type="text" name="nip" value="{{ old('nip') }}"
                                   placeholder="198703xx xxxx xxxx"
                                   class="field-input w-full pl-10 pr-4 py-2.5 text-sm font-medium text-gray-800 bg-gray-50 border @error('nip') border-red-400 bg-red-50 @else border-gray-200 @enderror rounded-xl placeholder-gray-400 transition-colors hover:border-gray-300">
                        </div>
                        <p class="text-[11px] text-gray-400 italic">Opsional — kosongkan jika belum ada.</p>
                        @error('nip') <p class="text-xs text-red-500 font-bold">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-1.5">
                        <label class="block text-sm font-semibold text-gray-700">No. WhatsApp Aktif</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                            </span>
                            <input type="text" name="no_telp" value="{{ old('no_telp') }}"
                                   placeholder="0812-xxxx-xxxx"
                                   class="field-input w-full pl-10 pr-4 py-2.5 text-sm font-medium text-gray-800 bg-gray-50 border border-gray-200 rounded-xl placeholder-gray-400 transition-colors hover:border-gray-300">
                        </div>
                    </div>
                </div>

                {{-- Mata Pelajaran --}}
                <div class="space-y-1.5">
                    <label class="block text-sm font-semibold text-gray-700">Spesialisasi / Mata Pelajaran</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                        </span>
                        <input type="text" name="mata_pelajaran" id="f_mapel"
                               value="{{ old('mata_pelajaran', "Tahfidz Al-Qur'an") }}"
                               placeholder="Contoh: Tahfidz Al-Qur'an"
                               list="mapelSuggestions"
                               class="field-input w-full pl-10 pr-4 py-2.5 text-sm font-medium text-gray-800 bg-gray-50 border border-gray-200 rounded-xl placeholder-gray-400 transition-colors hover:border-gray-300">
                        <datalist id="mapelSuggestions">
                            <option value="Tahfidz Al-Qur'an">
                            <option value="Tahsin & Tajwid">
                            <option value="Fiqih & Akidah">
                            <option value="Bahasa Arab">
                            <option value="Ilmu Tafsir">
                            <option value="Hadits">
                        </datalist>
                    </div>
                    <p class="text-[11px] text-gray-400 mb-2">Ketik bebas atau pilih dari daftar.</p>
                    <div class="flex flex-wrap gap-2">
                        @foreach(["Tahfidz Al-Qur'an", "Tahsin & Tajwid", "Bahasa Arab", "Fiqih & Akidah"] as $mp)
                            <button type="button"
                                    onclick="document.getElementById('f_mapel').value = '{{ $mp }}'"
                                    class="text-[10px] font-bold text-gray-500 bg-gray-100 hover:bg-teal-50 hover:text-teal-700 border border-gray-200 hover:border-teal-200 px-2.5 py-1 rounded-lg transition-all">
                                {{ $mp }}
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        {{-- Notice --}}
        <div class="flex gap-3 items-start bg-indigo-50 border border-indigo-200/60 rounded-xl p-4">
            <svg class="w-4 h-4 text-indigo-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <p class="text-xs text-indigo-700 leading-relaxed">Akun guru akan <strong>langsung aktif</strong> setelah disimpan. Pastikan kredensial ini telah dikomunikasikan kepada guru yang bersangkutan.</p>
        </div>

        {{-- Tombol Aksi --}}
        <div class="flex items-center justify-between pt-2">
            <a href="{{ route('admin.guru.index') }}"
               class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-bold text-gray-600 bg-white border border-gray-200 rounded-xl hover:bg-gray-50 hover:border-gray-300 transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Batal
            </a>
            <button type="submit"
                    class="inline-flex items-center gap-2 px-8 py-3 text-sm font-bold text-white rounded-xl bg-gradient-to-r from-teal-600 to-indigo-600 shadow-lg shadow-indigo-500/25 hover:shadow-indigo-500/40 hover:-translate-y-0.5 transition-all active:translate-y-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                Simpan & Aktifkan Akun
            </button>
        </div>

        <p class="text-center text-xs text-gray-400">Bidang bertanda <span class="text-red-400 font-semibold">*</span> wajib diisi.</p>
    </form>
</div>

@push('scripts')
<script>
    function togglePwd(id, btn) {
        const inp = document.getElementById(id);
        inp.type = inp.type === 'password' ? 'text' : 'password';
        btn.classList.toggle('text-teal-600');
    }

    function checkStrength(val) {
        const fill = document.getElementById('strengthFill');
        const text = document.getElementById('strengthText');
        if (!val) { fill.style.width = '0%'; text.textContent = 'Masukkan password'; text.className = 'text-[11px] text-gray-400'; return; }
        let score = 0;
        if (val.length >= 8)           score++;
        if (/[A-Z]/.test(val))         score++;
        if (/[0-9]/.test(val))         score++;
        if (/[^A-Za-z0-9]/.test(val))  score++;
        const map = [
            { w: '20%', bg: '#ef4444', label: 'Sangat lemah',   cls: 'text-[11px] font-semibold text-red-500' },
            { w: '40%', bg: '#f97316', label: 'Lemah',          cls: 'text-[11px] font-semibold text-orange-500' },
            { w: '70%', bg: '#eab308', label: 'Cukup kuat',     cls: 'text-[11px] font-semibold text-yellow-600' },
            { w: '100%',bg: '#22c55e', label: 'Sangat kuat 💪', cls: 'text-[11px] font-semibold text-emerald-600' },
        ];
        const s = Math.max(0, score - 1);
        fill.style.width = map[s].w;
        fill.style.backgroundColor = map[s].bg;
        text.textContent = map[s].label;
        text.className = map[s].cls;
    }

    function checkMatch() {
        const p = document.getElementById('f_password').value;
        const c = document.getElementById('f_confirm').value;
        const hint = document.getElementById('matchHint');
        if (c && p !== c) hint.classList.remove('hidden');
        else hint.classList.add('hidden');
    }
</script>
@endpush

@endsection