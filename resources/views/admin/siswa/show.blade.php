@extends('layouts.app')

@push('styles')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endpush

@section('title', 'Detail Siswa (Admin)')

@section('content')

<x-page-header
    title="Profil Siswa: {{ $siswa->user->nama_lengkap }}"
    subtitle="Pantau perkembangan hafalan, grafik nilai, rekomendasi, dan arsip laporan."
    :links="[
        ['label' => 'Dashboard', 'url' => route('dashboard')],
        ['label' => 'Manajemen Siswa', 'url' => route('admin.siswa.index')],
        ['label' => $siswa->user->nama_lengkap, 'url' => null],
    ]"
>
    <a href="{{ route('admin.siswa.edit', $siswa->id) }}" class="px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white text-sm font-bold rounded-lg transition flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
        Edit Data
    </a>
</x-page-header>

{{-- Header Profil & Statistik --}}
<div class="card mb-6 p-6 md:p-8 flex flex-col lg:flex-row gap-8 items-start relative overflow-hidden border-none shadow-xl">
    <div class="absolute top-0 right-0 w-64 h-64 bg-indigo-50 rounded-full blur-3xl -z-10 -mr-20 -mt-20"></div>
    
    <div class="flex items-center gap-6">
        @php
            $initials = collect(explode(' ', $siswa->user->nama_lengkap ?? 'S'))->take(2)->map(fn($w) => strtoupper($w[0]))->implode('');
        @endphp
        <div class="w-24 h-24 rounded-2xl bg-gradient-to-br from-indigo-500 to-indigo-700 text-white flex items-center justify-center font-bold text-3xl shadow-lg shadow-indigo-500/30 flex-shrink-0">
            {{ $initials }}
        </div>
        <div>
            <h2 class="text-2xl font-bold text-gray-800 tracking-tight">{{ $siswa->user->nama_lengkap }}</h2>
            <div class="flex flex-wrap items-center gap-2 mt-2">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-gray-100 text-gray-600 rounded-lg text-sm font-mono shadow-sm">
                    NISN: {{ $siswa->nisn ?? '-' }}
                </span>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-gray-100 text-gray-600 rounded-lg text-sm font-semibold shadow-sm">
                    Kelas {{ $siswa->kelas ?? 'TBA' }}
                </span>
                @if($siswa->tanggal_lahir)
                    <span class="px-3 py-1 bg-teal-50 text-teal-700 border border-teal-100 rounded-lg text-xs font-bold">
                        Usia: {{ date('Y') - $siswa->tanggal_lahir }} tahun
                    </span>
                @endif
            </div>
            <p class="text-sm text-gray-500 mt-2">
                Pembimbing: <strong class="text-indigo-600">{{ $siswa->guru->user->nama_lengkap ?? 'Belum Ditentukan' }}</strong>
            </p>
        </div>
    </div>

    <div class="w-px h-24 bg-gray-200 hidden lg:block mx-2"></div>

    <div class="flex-1 w-full grid grid-cols-1 md:grid-cols-3 gap-4">
        @php
            $hafalans = $siswa->dataHafalans;
            $totalHafalan = $hafalans->count();
            $totalAyat = $hafalans->sum('jumlah_ayat');
            $kelasTerakhir = $hasilTerakhir ? strtoupper($hasilTerakhir->kelas_prediksi) : '-';
            $laporans = \App\Models\Laporan::where('id_siswa', $siswa->id)->latest('tanggal_cetak')->get();

        @endphp

        <div class="p-4 rounded-xl bg-gray-50 border border-gray-100 flex flex-col justify-center items-center text-center">
            <p class="text-xs text-gray-500 uppercase tracking-wide font-semibold mb-1">Total Hafalan</p>
            <p class="text-2xl font-bold text-gray-800">{{ $totalHafalan }}</p>
        </div>
        
        <div class="p-4 rounded-xl bg-gray-50 border border-gray-100 flex flex-col justify-center items-center text-center">
            <p class="text-xs text-gray-500 uppercase tracking-wide font-semibold mb-1">Total Ayat</p>
            <p class="text-2xl font-bold text-teal-600">{{ $totalAyat }}</p>
        </div>
        
        <div class="p-4 rounded-xl bg-gray-50 border border-gray-100 flex flex-col justify-center items-center text-center">
            <p class="text-xs text-gray-500 uppercase tracking-wide font-semibold mb-1">Kelas Terakhir</p>
            @php $lastKlasifikasi = $siswa->hasilKlasifikasis->first(); @endphp
            @if($lastKlasifikasi)
                @php
                    $kelasPred = strtoupper($lastKlasifikasi->kelas_prediksi);
                    $lulus = ($kelasPred === 'LULUS');
                @endphp
                <span class="text-xs font-black px-3 py-1 rounded-lg mt-0.5 {{ $lulus ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-600' }}">
                    {{ $lulus ? 'Lulus' : 'Tidak Lulus' }}
                </span>
                <span class="text-[10px] font-bold text-gray-500 mt-1">{{ number_format($lastKlasifikasi->confidence_score * 100, 1) }}% Confidence</span>
            @else
                <p class="text-xl font-bold text-gray-400">-</p>
            @endif
        </div>

    </div>
</div>

{{-- Main Tab System --}}
<div x-data="{ activeTab: 'riwayat' }" class="mb-10">
    
    <div class="border-b border-gray-200 mb-6 flex overflow-x-auto">
        <button @click="activeTab = 'riwayat'" :class="activeTab === 'riwayat' ? 'border-b-2 border-indigo-600 text-indigo-700 font-bold' : 'text-gray-500 hover:text-gray-700 font-medium'" class="px-6 py-3 text-sm transition whitespace-nowrap">
            Riwayat Hafalan
        </button>
        <button @click="activeTab = 'grafik'" :class="activeTab === 'grafik' ? 'border-b-2 border-indigo-600 text-indigo-700 font-bold' : 'text-gray-500 hover:text-gray-700 font-medium'" class="px-6 py-3 text-sm transition whitespace-nowrap">
            Grafik Perkembangan
        </button>
        <button @click="activeTab = 'klasifikasi'" :class="activeTab === 'klasifikasi' ? 'border-b-2 border-indigo-600 text-indigo-700 font-bold' : 'text-gray-500 hover:text-gray-700 font-medium'" class="px-6 py-3 text-sm transition whitespace-nowrap">
            Histori SVM
        </button>

        <button @click="activeTab = 'laporan'" :class="activeTab === 'laporan' ? 'border-b-2 border-indigo-600 text-indigo-700 font-bold' : 'text-gray-500 hover:text-gray-700 font-medium'" class="px-6 py-3 text-sm transition whitespace-nowrap">
            Arsip Laporan
        </button>
    </div>

    {{-- TAB: RIWAYAT HAFALAN --}}
    <div x-show="activeTab === 'riwayat'" x-transition class="card overflow-hidden border-none shadow-xl">
        <div class="overflow-x-auto">
            <table class="table-base w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="table-th w-10 text-center">No</th>
                        <th class="table-th">Surah & Ayat</th>
                        <th class="table-th text-center">Nilai Input</th>
                        <th class="table-th text-center">Nilai Evaluasi</th>
                        <th class="table-th text-center">Prediksi SVM</th>
                        <th class="table-th">Waktu Setor</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($hafalans->sortByDesc('tanggal_input') as $idx => $haf)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="table-td text-center text-gray-400 text-sm">{{ $idx + 1 }}</td>
                            <td class="table-td">
                                <p class="font-bold text-gray-800">{{ $haf->nama_surah }}</p>
                                <p class="text-xs text-gray-500 mt-0.5">{{ $haf->jumlah_ayat }} Ayat</p>
                            </td>
                            <td class="table-td text-center">
                                <div class="text-xs inline-flex gap-2 text-gray-600 bg-gray-50 px-2.5 py-1 rounded shadow-inner">
                                    <span title="Kelancaran">🗣️ {{ $haf->nilai_kelancaran * 100 }}</span> | 
                                    <span title="Makhraj">🔊 {{ $haf->nilai_makhraj * 100 }}</span>
                                </div>
                            </td>
                            <td class="table-td text-center">
                                @if($haf->nilaiEvaluasi)
                                    <span class="font-bold text-teal-600">{{ number_format($haf->nilaiEvaluasi->nilai_total, 1) }}</span>
                                @else
                                    <span class="text-xs text-gray-400 italic">Belum dinilai</span>
                                @endif
                            </td>
                            <td class="table-td">
                                @php $klasifikasi = $siswa->hasilKlasifikasis->where('periode_semester', $haf->periode_semester)->last(); @endphp
                                @if($klasifikasi)
                                    @php
                                        $kelasPred = strtoupper($klasifikasi->kelas_prediksi);
                                        $lulus = ($kelasPred === 'LULUS');
                                    @endphp
                                    <div class="flex flex-col items-start gap-1">
                                        <span class="text-xs font-bold px-2 py-0.5 rounded-full {{ $lulus ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-600' }}">
                                            {{ $lulus ? 'Lulus' : 'Tidak Lulus' }}
                                        </span>
                                        <span class="text-[10px] font-bold text-gray-500 ml-1">Score: {{ number_format($klasifikasi->confidence_score * 100, 1) }}%</span>
                                    </div>
                                @else
                                    <span class="text-xs text-gray-400 italic">Belum</span>
                                @endif
                            </td>
                            <td class="table-td text-sm text-gray-500">
                                {{ \Carbon\Carbon::parse($haf->tanggal_input)->translatedFormat('d M Y, H:i') }}
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="p-8 text-center text-gray-400">Belum ada riwayat hafalan untuk siswa ini.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- TAB: GRAFIK --}}
    <div x-show="activeTab === 'grafik'" x-transition class="card p-6 border-none shadow-xl" style="display: none;">
        @if($hafalans->count() >= 2)
            <div class="h-80 w-full relative">
                <canvas id="chartAdmin"></canvas>
            </div>
            @push('scripts')
                @php
                    $grafikData = $hafalans->sortBy('tanggal_input')->take(-15);
                    $labels = $grafikData->map(fn($h) => \Carbon\Carbon::parse($h->tanggal_input)->format('d/m'))->values();
                    $dataKelancaran = $grafikData->map(fn($h) => $h->nilai_kelancaran * 100)->values();
                    $dataMakhraj = $grafikData->map(fn($h) => $h->nilai_makhraj * 100)->values();
                    $dataFashohah = $grafikData->map(fn($h) => $h->nilaiEvaluasi ? $h->nilaiEvaluasi->nilai_fashohah : 0)->values();
                @endphp
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        new Chart(document.getElementById('chartAdmin').getContext('2d'), {
                            type: 'line',
                            data: {
                                labels: {!! json_encode($labels) !!},
                                datasets: [
                                    { label: 'Kelancaran (%)', data: {!! json_encode($dataKelancaran) !!}, borderColor: '#6366f1', backgroundColor: 'rgba(99,102,241,0.1)', borderWidth: 2, tension: 0.3, fill: true },
                                    { label: 'Makhraj (%)', data: {!! json_encode($dataMakhraj) !!}, borderColor: '#0d9488', backgroundColor: 'transparent', borderWidth: 2, borderDash: [5, 5], tension: 0.3 },
                                    { label: 'Fashohah / Evaluasi', data: {!! json_encode($dataFashohah) !!}, borderColor: '#d97706', backgroundColor: 'transparent', borderWidth: 2, tension: 0.3 }
                                ]
                            },
                            options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'top', labels: { usePointStyle: true, boxWidth: 6 } } }, scales: { y: { min: 0, max: 100, ticks: { stepSize: 20 } } } }
                        });
                    });
                </script>
            @endpush
        @else
            <div class="py-12 text-center text-gray-400">
                <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"/></svg>
                Grafik belum tersedia. Minimal 2 data hafalan diperlukan.
            </div>
        @endif
    </div>

    {{-- TAB: HISTORI SVM --}}
    <div x-show="activeTab === 'klasifikasi'" x-transition class="card overflow-hidden border-none shadow-xl" style="display: none;">
        <div class="overflow-x-auto">
            <table class="table-base w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="table-th w-10">No</th>
                        <th class="table-th text-center">Kelas Prediksi</th>
                        <th class="table-th text-center">Confidence Score</th>
                        <th class="table-th">Tanggal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($siswa->hasilKlasifikasis as $i => $hk)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="table-td text-center text-gray-400">{{ $i + 1 }}</td>
                            <td class="table-td text-center">
                                <span class="badge-{{ strtoupper($hk->kelas_prediksi) }} font-black text-sm px-3 py-1 rounded-lg">Kelas {{ strtoupper($hk->kelas_prediksi) }}</span>
                            </td>
                            <td class="table-td text-center font-mono font-bold text-indigo-600">
                                {{ $hk->confidence_score ? number_format($hk->confidence_score * 100, 2) . '%' : '-' }}
                            </td>
                            <td class="table-td text-sm text-gray-500">{{ \Carbon\Carbon::parse($hk->tanggal_klasifikasi)->translatedFormat('d M Y, H:i') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="p-8 text-center text-gray-400 italic">Belum ada histori klasifikasi SVM.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>



    {{-- TAB: ARSIP LAPORAN --}}
    <div x-show="activeTab === 'laporan'" x-transition class="card overflow-hidden border-none shadow-xl" style="display: none;">
        <div class="overflow-x-auto">
            <table class="table-base w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="table-th w-10 text-center">No</th>
                        <th class="table-th">Judul Laporan</th>
                        <th class="table-th text-center">Periode</th>
                        <th class="table-th text-center">Tanggal Cetak</th>
                        <th class="table-th text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($laporans as $i => $lap)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="table-td text-center text-gray-400">{{ $i + 1 }}</td>
                            <td class="table-td font-medium text-gray-800">{{ $lap->judul_laporan }}</td>
                            <td class="table-td text-center text-gray-600">{{ $lap->periode }}</td>
                            <td class="table-td text-center text-gray-500 text-xs">{{ \Carbon\Carbon::parse($lap->tanggal_cetak)->translatedFormat('d F Y, H:i') }}</td>
                            <td class="table-td text-right">
                                <a href="{{ route('admin.laporan.download', $lap->id) }}" class="btn-primary py-1.5 px-3 text-xs inline-flex items-center gap-1.5" target="_blank">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    Unduh PDF
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="p-8 text-center text-gray-400 italic">Belum ada arsip laporan untuk siswa ini.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
