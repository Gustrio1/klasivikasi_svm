@extends('layouts.app')

@section('title', 'Dashboard Siswa')

@section('content')

{{-- ═══════════════════════════════════════════════════════════
     Dashboard Siswa
═══════════════════════════════════════════════════════════ --}}

<x-page-header
    title="Dashboard Siswa"
    subtitle="Selamat datang, {{ auth()->user()->nama_lengkap }} 👋"
    :links="[['label' => 'Dashboard', 'url' => null]]"
/>

{{-- ── Baris 1: Kartu Statistik ─────────────────────────── --}}
<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5 mb-7">

    {{-- Total Hafalan --}}
    <div class="card flex items-center gap-4">
        <div class="flex-shrink-0 w-12 h-12 rounded-xl bg-teal-100 flex items-center justify-center">
            <svg class="w-6 h-6 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
        </div>
        <div>
            <p class="text-xs text-gray-500 font-medium uppercase tracking-wide">Total Hafalan</p>
            <p class="text-3xl font-bold text-gray-800 mt-0.5">{{ $totalHafalan }}</p>
        </div>
    </div>

    {{-- Kelas Terakhir --}}
    <div class="card flex items-center gap-4">
        <div class="flex-shrink-0 w-12 h-12 rounded-xl bg-indigo-100 flex items-center justify-center">
            <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
            </svg>
        </div>
        <div>
            <p class="text-xs text-gray-500 font-medium uppercase tracking-wide">Kelas Terakhir</p>
            @if($kelasTermakhir)
                <span class="mt-1 inline-flex text-2xl font-bold badge-{{ $kelasTermakhir }}">
                    {{ $kelasTermakhir }}
                </span>
            @else
                <p class="text-gray-400 text-sm mt-1">Belum ada</p>
            @endif
        </div>
    </div>

    {{-- Nilai Rata-rata --}}
    <div class="card flex items-center gap-4">
        <div class="flex-shrink-0 w-12 h-12 rounded-xl bg-amber-100 flex items-center justify-center">
            <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
            </svg>
        </div>
        <div>
            <p class="text-xs text-gray-500 font-medium uppercase tracking-wide">Nilai Rata-rata</p>
            <p class="text-3xl font-bold text-gray-800 mt-0.5">
                {{ $nilaiRataRata > 0 ? number_format($nilaiRataRata * 100, 1) : '–' }}
                @if($nilaiRataRata > 0)<span class="text-lg text-gray-400">%</span>@endif
            </p>
        </div>
    </div>



</div>

{{-- ── Baris 2: Tabel Hafalan + Chart ──────────────────── --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-5 mb-7">

    {{-- Hafalan Terakhir (2/3) --}}
    <div class="lg:col-span-2 card">
        <div class="card-header">
            <h3 class="text-base font-semibold text-gray-800">5 Hafalan Terakhir</h3>
            <a href="{{ route('siswa.hafalan.index') }}" class="btn-secondary text-xs py-1.5">
                Lihat Semua →
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="table-base w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="table-th">No</th>
                        <th class="table-th">Nama Surah</th>
                        <th class="table-th">Ayat</th>
                        <th class="table-th">Semester</th>
                        <th class="table-th">Tanggal</th>
                        <th class="table-th">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($hafalanTerakhir as $i => $hf)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="table-td text-gray-400">{{ $i + 1 }}</td>
                            <td class="table-td font-medium text-gray-800">{{ $hf->nama_surah }}</td>
                            <td class="table-td text-gray-500">{{ $hf->jumlah_ayat }} Ayat</td>
                            <td class="table-td text-gray-500 text-sm">{{ $hf->periode_semester ?? '-' }}</td>
                            <td class="table-td text-gray-500 text-sm">
                                {{ \Carbon\Carbon::parse($hf->tanggal_input)->translatedFormat('d M Y') }}
                            </td>
                            <td class="table-td">
                                <a href="{{ route('siswa.hafalan.show', $hf->id) }}" class="btn-secondary py-1 px-3 text-xs">
                                    Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center text-gray-400">
                                <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                          d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                <p class="text-sm">Belum ada data hafalan</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Doughnut Chart (1/3) --}}
    <div class="card flex flex-col">
        <div class="card-header">
            <h3 class="text-base font-semibold text-gray-800">Distribusi Kelas</h3>
        </div>
        <div class="flex-1 flex flex-col items-center justify-center">
            @if($distribusiKelas->isNotEmpty())
                <div class="relative w-48 h-48">
                    <canvas id="distribusiChart"></canvas>
                </div>
                <div class="flex gap-4 mt-4 text-sm">
                    <div class="flex items-center gap-1.5">
                        <span class="w-3 h-3 rounded-full bg-green-500"></span>
                        <span class="text-gray-600">Lulus: {{ $distribusiKelas['Lulus'] ?? 0 }}</span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <span class="w-3 h-3 rounded-full bg-red-400"></span>
                        <span class="text-gray-600">Tidak Lulus: {{ $distribusiKelas['Tidak Lulus'] ?? 0 }}</span>
                    </div>
                </div>
            @else
                <div class="text-center text-gray-400 py-8">
                    <svg class="w-16 h-16 mx-auto mb-2 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/>
                    </svg>
                    <p class="text-sm">Belum ada klasifikasi</p>
                </div>
            @endif
        </div>
    </div>
</div>



@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    @if($distribusiKelas->isNotEmpty())
    const ctx = document.getElementById('distribusiChart');
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Lulus', 'Tidak Lulus'],
            datasets: [{
                data: [
                    {{ $distribusiKelas['Lulus'] ?? 0 }},
                    {{ $distribusiKelas['Tidak Lulus'] ?? 0 }}
                ],
                backgroundColor: ['#22c55e', '#f87171'],
                borderWidth: 0,
                hoverOffset: 6,
            }]
        },
        options: {
            cutout: '70%',
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: (ctx) => ` ${ctx.label}: ${ctx.raw} sesi`
                    }
                }
            }
        }
    });
    @endif
</script>
@endpush
