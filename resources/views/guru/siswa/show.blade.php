@extends('layouts.app')

@push('styles')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endpush

@section('title', 'Detail Siswa')

@section('content')

    <x-page-header title="Detail Siswa: {{ $siswa->user->nama_lengkap }}"
        subtitle="Pantau grafik hafalan, riwayat evaluasi, dan rekap rekomendasi" :links="[
            ['label' => 'Dashboard', 'url' => route('dashboard')],
            ['label' => 'Siswa', 'url' => route('guru.siswa.index')],
            ['label' => 'Detail', 'url' => null],
        ]" />

    {{-- Header Profil & Statistik --}}
    <div class="card mb-6 p-6 md:p-8 flex flex-col lg:flex-row gap-8 items-start relative overflow-hidden">
        <div class="absolute top-0 right-0 w-64 h-64 bg-teal-50 rounded-full blur-3xl -z-10 -mr-20 -mt-20"></div>

        <div class="flex items-center gap-6">
            @php
                $initials = collect(explode(' ', $siswa->user->nama_lengkap ?? 'S'))->take(2)->map(fn($w) => strtoupper($w[0]))->implode('');
            @endphp
            <div
                class="w-24 h-24 rounded-2xl bg-gradient-to-br from-teal-500 to-teal-700 text-white flex items-center justify-center font-bold text-3xl shadow-lg shadow-teal-500/30 flex-shrink-0">
                {{ $initials }}
            </div>
            <div>
                <h2 class="text-2xl font-bold text-gray-800 tracking-tight">{{ $siswa->user->nama_lengkap }}</h2>
                <div class="flex flex-wrap items-center gap-2 mt-2">
                    <span
                        class="inline-flex items-center gap-1.5 px-3 py-1 bg-gray-100 text-gray-600 rounded-lg text-sm font-mono shadow-sm">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2">
                            </path>
                        </svg>
                        {{ $siswa->nisn ?? 'NISN Kosong' }}
                    </span>
                    <span
                        class="inline-flex items-center gap-1.5 px-3 py-1 bg-gray-100 text-gray-600 rounded-lg text-sm font-semibold shadow-sm">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                            </path>
                        </svg>
                        Kelas {{ $siswa->kelas ?? 'TBA' }}
                    </span>
                </div>
                <p class="text-sm text-gray-500 mt-2 flex items-center gap-1">
                    Akun aktif sejak {{ \Carbon\Carbon::parse($siswa->user->created_at)->translatedFormat('d M Y') }}
                </p>
            </div>
        </div>

        <div class="w-px h-24 bg-gray-200 hidden lg:block mx-2"></div>

        <div class="flex-1 w-full grid grid-cols-1 md:grid-cols-3 gap-4">
            @php
                $hafalans = $siswa->dataHafalans;
                $totalHafalan = $hafalans->count();

                // Hitung rata-rata nilai dari relasi nilaiEvaluasi
                $totalMakhraj = 0;
                $totalFashohah = 0;
                $evaluatedCount = 0;
                foreach ($hafalans as $h) {
                    if ($h->nilaiEvaluasi) {
                        $totalMakhraj += $h->nilaiEvaluasi->nilai_makhraj;
                        $totalFashohah += $h->nilaiEvaluasi->nilai_fashohah;
                        $evaluatedCount++;
                    }
                }
                $avgMakhraj = $evaluatedCount > 0 ? $totalMakhraj / $evaluatedCount : 0;
                $avgFashohah = $evaluatedCount > 0 ? $totalFashohah / $evaluatedCount : 0;
                $avgTotal = ($avgMakhraj + $avgFashohah) / 2;

                $lastKlasifikasi = $siswa->hasilKlasifikasis->first();
                $kelasTerakhir = $lastKlasifikasi ? strtoupper($lastKlasifikasi->kelas_prediksi) : '-';


                $laporans = \App\Models\Laporan::where('id_siswa', $siswa->id)->latest('tanggal_cetak')->get();
            @endphp

            <div
                class="p-4 rounded-xl bg-gray-50 border border-gray-100 flex flex-col justify-center items-center text-center">
                <p class="text-xs text-gray-500 uppercase tracking-wide font-semibold mb-1">Total Hafalan</p>
                <p class="text-2xl font-bold text-gray-800">{{ $totalHafalan }}</p>
            </div>

            <div
                class="p-4 rounded-xl bg-gray-50 border border-gray-100 flex flex-col justify-center items-center text-center">
                <p class="text-xs text-gray-500 uppercase tracking-wide font-semibold mb-1">Kelas Terakhir</p>
                @if($lastKlasifikasi)
                    @php
                        $kelasPred = strtoupper($lastKlasifikasi->kelas_prediksi);
                        $lulus = ($kelasPred === 'LULUS');
                    @endphp
                    <span
                        class="text-xs font-black px-3 py-1 rounded-lg mt-0.5 {{ $lulus ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-600' }}">
                        {{ $lulus ? 'Lulus' : 'Tidak Lulus' }}
                    </span>
                    <span
                        class="text-[10px] font-bold text-gray-500 mt-1">{{ number_format($lastKlasifikasi->confidence_score * 100, 1) }}%
                        Confidence</span>
                @else
                    <p class="text-xl font-bold text-gray-400">-</p>
                @endif
            </div>

            <div
                class="p-4 rounded-xl bg-gray-50 border border-gray-100 flex flex-col justify-center items-center text-center">
                <p class="text-xs text-gray-500 uppercase tracking-wide font-semibold mb-1">Rata-rata Nilai</p>
                <p class="text-2xl font-bold {{ $avgTotal >= 70 ? 'text-teal-600' : 'text-amber-600' }}">
                    {{ number_format($avgTotal, 1) }}
                </p>
            </div>

        </div>
    </div>

    {{-- Tombol Tindakan Cepat --}}
    <div class="mb-6 flex flex-wrap gap-3">
        <a href="{{ route('guru.hafalan.create', ['id_siswa' => $siswa->id]) }}"
            class="btn-primary shadow-md flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Input Hafalan Baru
        </a>

        <button type="button" onclick="document.getElementById('formLaporan').submit()"
            class="btn-secondary shadow-sm flex items-center gap-2 bg-white">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                </path>
            </svg>
            Buat Laporan Periode Ini
        </button>

        <form id="formLaporan" action="{{ route('guru.laporan.store') }}" method="POST" class="hidden">
            @csrf
            <input type="hidden" name="id_siswa" value="{{ $siswa->id }}">
            <input type="hidden" name="judul_laporan"
                value="Laporan Perkembangan Tahfidz - {{ \Carbon\Carbon::now()->translatedFormat('F Y') }}">
            <input type="hidden" name="periode" value="{{ \Carbon\Carbon::now()->translatedFormat('F Y') }}">
        </form>

        {{-- Tombol Hapus Siswa --}}
        <form action="{{ route('guru.siswa.destroy', $siswa->id) }}" method="POST" class="inline-block ml-auto"
            onsubmit="return confirm('Hapus siswa {{ $siswa->user->nama_lengkap }} beserta SELURUH data hafalan, nilai evaluasi, laporan, dan hasil klasifikasinya secara PERMANEN?\n\nTindakan ini tidak dapat dibatalkan!');">
            @csrf
            @method('DELETE')
            <button type="submit"
                class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-red-50 text-red-600 hover:bg-red-600 hover:text-white border border-red-200 hover:border-red-600 transition font-semibold text-sm shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
                Hapus Siswa
            </button>
        </form>
    </div>

    {{-- Main Tab System (Alpine.js) --}}
    <div x-data="{ activeTab: 'riwayat' }" class="mb-10">

        {{-- Container Tab Navigation --}}
        <div class="border-b border-gray-200 mb-6 flex overflow-x-auto scroolbar-hide">
            <button @click="activeTab = 'riwayat'"
                :class="activeTab === 'riwayat' ? 'border-b-2 border-teal-600 text-teal-700 font-bold' : 'text-gray-500 hover:text-gray-700 font-medium'"
                class="px-6 py-3 text-sm transition whitespace-nowrap">
                Riwayat Hafalan
            </button>
            <button @click="activeTab = 'evaluasi'"
                :class="activeTab === 'evaluasi' ? 'border-b-2 border-teal-600 text-teal-700 font-bold' : 'text-gray-500 hover:text-gray-700 font-medium'"
                class="px-6 py-3 text-sm transition whitespace-nowrap">
                Evaluasi Semester (SVM)
            </button>
            <button @click="activeTab = 'laporan'"
                :class="activeTab === 'laporan' ? 'border-b-2 border-teal-600 text-teal-700 font-bold' : 'text-gray-500 hover:text-gray-700 font-medium'"
                class="px-6 py-3 text-sm transition whitespace-nowrap">
                Arsip Laporan
            </button>
        </div>

        {{-- KONTEN TAB: RIWAYAT HAFALAN --}}
        <div x-show="activeTab === 'riwayat'" x-transition class="card overflow-hidden">
            <div class="overflow-x-auto">
                <table class="table-base w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="table-th w-10 text-center">No</th>
                            <th class="table-th">Surah & Ayat</th>
                            <th class="table-th text-center">Status Evaluasi</th>
                            <th class="table-th">Waktu Setor</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($hafalans->sortByDesc('tanggal_input') as $idx => $haf)
                            <tr class="hover:bg-gray-50">
                                <td class="table-td text-center text-gray-400 text-sm">{{ $loop->iteration }}</td>
                                <td class="table-td">
                                    <p class="font-bold text-gray-800">{{ $haf->nama_surah }}</p>
                                    <p class="text-xs text-gray-500 mt-0.5">Total {{ $haf->jumlah_ayat }} Ayat</p>
                                </td>
                                <td class="table-td text-center">
                                    @if($haf->nilaiEvaluasi)
                                        <span class="inline-flex items-center gap-1 text-[10px] font-bold uppercase tracking-wider text-green-700 bg-green-100 px-2 py-1 rounded-full">
                                            Sudah Dievaluasi
                                        </span>
                                    @else
                                        <a href="{{ route('guru.hafalan.show', $haf->id) }}"
                                            class="text-xs text-amber-600 hover:underline">Evaluasi Sekarang -></a>
                                    @endif
                                </td>
                                <td class="table-td text-sm text-gray-500">
                                    {{ \Carbon\Carbon::parse($haf->tanggal_input)->translatedFormat('d M Y, H:i') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="p-8 text-center text-gray-400">Belum ada riwayat hafalan untuk siswa ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- KONTEN TAB: EVALUASI SEMESTER (SVM) --}}
        <div x-show="activeTab === 'evaluasi'" x-transition class="space-y-6" style="display: none;">

            <div class="card p-6 border-l-4 border-l-indigo-500 bg-indigo-50/50">
                <h3 class="font-bold text-indigo-900 mb-2">Proses Evaluasi Akhir Semester</h3>
                <p class="text-sm text-indigo-700 mb-4">Sistem akan mengambil akumulasi hafalan siswa pada semester yang
                    dipilih, menghitung Total Surat, dan memprediksi status kelulusan (Syarat Mutlak: 30 Surat = Lulus).</p>

                <form action="{{ route('guru.hasil-klasifikasi.evaluasi-semester') }}" method="POST"
                    class="flex gap-4 items-end">
                    @csrf
                    <input type="hidden" name="id_siswa" value="{{ $siswa->id }}">
                    <div class="flex-1 max-w-sm">
                        <label for="periode_semester" class="form-label text-indigo-900">Periode Semester</label>
                        <select name="periode_semester" id="periode_semester" class="form-input bg-white" required>
                            <option value="">-- Pilih Periode Semester --</option>
                            @php
                                // Ambil semester unik dari data hafalan siswa ini
                                $availableSemesters = $hafalans->pluck('periode_semester')->filter()->unique();
                            @endphp
                            @foreach($availableSemesters as $sem)
                                <option value="{{ $sem }}">{{ $sem }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit"
                        class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2.5 px-6 rounded-lg transition shadow-md shadow-indigo-500/30 flex items-center gap-2 h-[42px]">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                        Proses SVM
                    </button>
                </form>
            </div>

            <div class="card overflow-hidden">
                <div class="p-4 bg-gray-50 border-b border-gray-100 flex justify-between items-center">
                    <h4 class="font-bold text-gray-800">Riwayat Evaluasi Semester</h4>
                </div>
                <div class="overflow-x-auto">
                    <table class="table-base w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="table-th w-10 text-center">No</th>
                                <th class="table-th">Periode Semester</th>
                                <th class="table-th text-center">Total Surat</th>
                                <th class="table-th">Hasil Prediksi (SVM)</th>
                                <th class="table-th text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($siswa->hasilKlasifikasis()->latest('tanggal_klasifikasi')->get() as $idx => $hk)
                                <tr class="hover:bg-gray-50">
                                    <td class="table-td text-center text-gray-400 text-sm">{{ $idx + 1 }}</td>
                                    <td class="table-td font-bold text-gray-800">{{ $hk->periode_semester }}</td>
                                    <td class="table-td text-center font-mono">{{ $hk->total_surah }} Surat</td>
                                    <td class="table-td">
                                        @php
                                            $kelasPred = strtoupper($hk->kelas_prediksi);
                                            $lulus = ($kelasPred === 'LULUS');
                                        @endphp
                                        <div class="flex flex-col items-start gap-1">
                                            <span
                                                class="text-xs font-bold px-2 py-0.5 rounded-full {{ $lulus ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-600' }}">
                                                {{ $lulus ? 'Lulus' : 'Tidak Lulus' }}
                                            </span>
                                            <span class="text-[10px] font-bold text-gray-500 ml-1">Score:
                                                {{ number_format($hk->confidence_score * 100, 1) }}%</span>
                                        </div>
                                    </td>
                                    <td class="table-td text-right">
                                        <a href="{{ route('guru.hasil-klasifikasi.show', $hk->id) }}"
                                            class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">Detail</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="p-8 text-center text-gray-400">Belum ada hasil klasifikasi semester
                                        untuk siswa ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>



        {{-- KONTEN TAB: ARSIP LAPORAN --}}
        <div x-show="activeTab === 'laporan'" x-transition class="card overflow-hidden" style="display: none;">
            <div class="overflow-x-auto">
                <table class="table-base w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="table-th w-10 text-center">No</th>
                            <th class="table-th">Judul Laporan</th>
                            <th class="table-th">Periode</th>
                            <th class="table-th">Tanggal Cetak</th>
                            <th class="table-th text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($laporans as $i => $lap)
                            <tr class="hover:bg-gray-50">
                                <td class="table-td text-center text-gray-400 text-sm">{{ $i + 1 }}</td>
                                <td class="table-td font-medium text-gray-800">{{ $lap->judul_laporan }}</td>
                                <td class="table-td text-gray-600">{{ $lap->periode }}</td>
                                <td class="table-td text-gray-500">
                                    {{ \Carbon\Carbon::parse($lap->tanggal_cetak)->translatedFormat('d F Y, H:i') }}
                                </td>
                                <td class="table-td text-right">
                                    <a href="{{ route('guru.laporan.download', $lap->id) }}"
                                        class="btn-primary py-1.5 px-3 text-xs inline-flex items-center gap-1.5">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                            </path>
                                        </svg>
                                        Unduh PDF
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="p-8 text-center text-gray-400">Belum ada arsip laporan untuk siswa ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

@endsection