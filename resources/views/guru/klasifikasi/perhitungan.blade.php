@extends('layouts.app')

@section('title', 'Detail Perhitungan SVM')

@push('styles')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endpush

@section('content')

<x-page-header
    title="Detail Perhitungan SVM"
    subtitle="Langkah-langkah klasifikasi dari dataset hingga hasil akhir menggunakan metode SVM Linear."
    :links="[
        ['label' => 'Dashboard', 'url' => route('dashboard')],
        ['label' => 'Perhitungan SVM', 'url' => null],
    ]"
/>

{{-- ══ 1. MODEL SVM ════════════════════════════════════════════════════════ --}}
<div class="card mb-6">
    <h2 class="text-base font-bold text-gray-800 mb-4 flex items-center gap-2">
        <span class="w-7 h-7 rounded-lg bg-indigo-100 text-indigo-700 flex items-center justify-center text-sm font-black">1</span>
        Model SVM &amp; Parameter
    </h2>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-indigo-50 rounded-xl p-5 font-mono text-sm text-indigo-900 leading-relaxed">
            <p class="font-bold text-indigo-700 mb-2 text-xs uppercase tracking-widest">Fungsi Keputusan</p>
            <p>f(x) = <span class="font-black text-indigo-800">2</span>·x₁ + <span class="font-black text-indigo-800">1</span>·x₂ + <span class="font-black text-indigo-800">(−1.5)</span>·x₃ + <span class="font-black text-indigo-800">(−0.5)</span></p>
            <div class="mt-3 text-xs text-indigo-700 space-y-1">
                <p>x₁ = Jumlah Ayat <span class="text-indigo-400">(setelah normalisasi)</span></p>
                <p>x₂ = Media <span class="text-indigo-400">(0 = Cetak, 1 = Digital)</span></p>
                <p>x₃ = Usia <span class="text-indigo-400">(setelah normalisasi)</span></p>
            </div>
        </div>
        <div class="bg-gray-50 rounded-xl p-5 text-sm text-gray-700 space-y-2">
            <p class="font-bold text-gray-500 text-xs uppercase tracking-widest mb-2">Aturan Klasifikasi</p>
            <div class="flex items-center gap-3">
                <span class="w-3 h-3 rounded-full bg-emerald-500 shrink-0"></span>
                <span>f(x) <span class="font-bold">&gt; 0</span> → Kelas <span class="font-black text-emerald-700">Lulus (1)</span></span>
            </div>
            <div class="flex items-center gap-3">
                <span class="w-3 h-3 rounded-full bg-red-500 shrink-0"></span>
                <span>f(x) <span class="font-bold">&lt; 0</span> → Kelas <span class="font-black text-red-600">Tidak Lulus (−1)</span></span>
            </div>
            <hr class="border-gray-200 my-2">
            <p class="font-bold text-gray-500 text-xs uppercase tracking-widest mb-2">Normalisasi Min-Max</p>
            <p class="font-mono text-xs">Ayat: (x − 1) / 9 &nbsp;&nbsp; [min=1, max=10]</p>
            <p class="font-mono text-xs">Usia: (x − 18) / 7 &nbsp; [min=18, max=25]</p>
        </div>
    </div>
</div>

{{-- ══ 2. DATASET ASAL ═════════════════════════════════════════════════════ --}}
<div class="card mb-6">
    <h2 class="text-base font-bold text-gray-800 mb-4 flex items-center gap-2">
        <span class="w-7 h-7 rounded-lg bg-teal-100 text-teal-700 flex items-center justify-center text-sm font-black">2</span>
        Dataset Asal (Sebelum Normalisasi)
    </h2>
    <div class="overflow-x-auto">
        <table class="table-base w-full">
            <thead class="bg-teal-50">
                <tr>
                    <th class="table-th">Nama</th>
                    <th class="table-th text-center">Jumlah Ayat</th>
                    <th class="table-th text-center">Media</th>
                    <th class="table-th text-center">Usia</th>
                    <th class="table-th text-center">Kelas Asli</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($hasil as $row)
                <tr class="hover:bg-gray-50 transition">
                    <td class="table-td font-bold text-gray-800">{{ $row['nama'] }}</td>
                    <td class="table-td text-center">{{ $row['ayat'] }}</td>
                    <td class="table-td text-center">
                        <span class="px-2 py-0.5 rounded text-xs font-semibold
                            {{ $row['media'] == 1 ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-600' }}">
                            {{ $row['media_label'] }} ({{ $row['media'] }})
                        </span>
                    </td>
                    <td class="table-td text-center">{{ $row['usia'] }}</td>
                    <td class="table-td text-center">
                        <span class="inline-flex items-center justify-center px-3 py-0.5 rounded-full text-xs font-bold
                            {{ $row['kelas_asli'] == 1 ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-600' }}">
                            {{ $row['kelas_asli'] == 1 ? 'Lulus (1)' : 'Tidak Lulus (−1)' }}
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

{{-- ══ 3. PROSES NORMALISASI ═══════════════════════════════════════════════ --}}
<div class="card mb-6">
    <h2 class="text-base font-bold text-gray-800 mb-4 flex items-center gap-2">
        <span class="w-7 h-7 rounded-lg bg-amber-100 text-amber-700 flex items-center justify-center text-sm font-black">3</span>
        Normalisasi Min-Max
    </h2>
    <div class="overflow-x-auto">
        <table class="table-base w-full">
            <thead class="bg-amber-50">
                <tr>
                    <th class="table-th">Nama</th>
                    <th class="table-th text-center">Ayat (x₁ raw)</th>
                    <th class="table-th text-center">x₁ norm</th>
                    <th class="table-th text-center">Media (x₂)</th>
                    <th class="table-th text-center">Usia (x₃ raw)</th>
                    <th class="table-th text-center">x₃ norm</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($hasil as $row)
                <tr class="hover:bg-gray-50 transition">
                    <td class="table-td font-bold text-gray-800">{{ $row['nama'] }}</td>
                    <td class="table-td text-center text-gray-600">{{ $row['ayat'] }}</td>
                    <td class="table-td text-center font-mono font-semibold text-amber-700">
                        ({{ $row['ayat'] }}−1)/9 = <span class="font-black">{{ $row['x1'] }}</span>
                    </td>
                    <td class="table-td text-center font-mono font-semibold text-blue-700">{{ $row['x2'] }}</td>
                    <td class="table-td text-center text-gray-600">{{ $row['usia'] }}</td>
                    <td class="table-td text-center font-mono font-semibold text-amber-700">
                        ({{ $row['usia'] }}−18)/7 = <span class="font-black">{{ $row['x3'] }}</span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

{{-- ══ 4. PERHITUNGAN f(x) ═════════════════════════════════════════════════ --}}
<div class="card mb-6">
    <h2 class="text-base font-bold text-gray-800 mb-4 flex items-center gap-2">
        <span class="w-7 h-7 rounded-lg bg-violet-100 text-violet-700 flex items-center justify-center text-sm font-black">4</span>
        Perhitungan Fungsi Keputusan f(x)
    </h2>
    <div class="space-y-3">
        @foreach($hasil as $row)
        <div class="rounded-xl border {{ $row['benar'] ? 'border-emerald-200 bg-emerald-50' : 'border-red-200 bg-red-50' }} p-4">
            <div class="flex items-start justify-between gap-4 flex-wrap">
                <div>
                    <p class="font-bold text-gray-800 text-sm mb-1">{{ $row['nama'] }}</p>
                    <p class="font-mono text-xs text-gray-600">{{ $row['langkah'] }}</p>
                </div>
                <div class="flex items-center gap-3 shrink-0">
                    <div class="text-center">
                        <p class="text-[10px] text-gray-400 uppercase tracking-widest">f(x)</p>
                        <p class="font-black text-lg {{ $row['fx'] > 0 ? 'text-emerald-700' : 'text-red-600' }}">
                            {{ $row['fx'] > 0 ? '+' : '' }}{{ $row['fx'] }}
                        </p>
                    </div>
                    <div class="text-center">
                        <p class="text-[10px] text-gray-400 uppercase tracking-widest">Prediksi</p>
                        <span class="px-2 py-1 rounded-lg text-xs font-bold
                            {{ $row['kelas_prediksi'] == 1 ? 'bg-emerald-500 text-white' : 'bg-red-500 text-white' }}">
                            {{ $row['label_prediksi'] }}
                        </span>
                    </div>
                    <div class="text-center">
                        <p class="text-[10px] text-gray-400 uppercase tracking-widest">Asli</p>
                        <span class="px-2 py-1 rounded-lg text-xs font-bold
                            {{ $row['kelas_asli'] == 1 ? 'bg-emerald-100 text-emerald-800' : 'bg-red-100 text-red-700' }}">
                            {{ $row['label_asli'] }}
                        </span>
                    </div>
                    <div class="text-center">
                        <p class="text-[10px] text-gray-400 uppercase tracking-widest">Status</p>
                        @if($row['benar'])
                            <span class="text-emerald-600 font-black text-lg">✓</span>
                        @else
                            <span class="text-red-500 font-black text-lg">✗</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

{{-- ══ 5. RINGKASAN & CHART ═════════════════════════════════════════════════ --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    <div class="card">
        <h2 class="text-base font-bold text-gray-800 mb-4 flex items-center gap-2">
            <span class="w-7 h-7 rounded-lg bg-rose-100 text-rose-700 flex items-center justify-center text-sm font-black">5</span>
            Rekapitulasi Hasil Klasifikasi
        </h2>
        <div class="overflow-x-auto">
            <table class="table-base w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="table-th">Nama</th>
                        <th class="table-th text-center">Asli</th>
                        <th class="table-th text-center">Prediksi</th>
                        <th class="table-th text-center">f(x)</th>
                        <th class="table-th text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($hasil as $row)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="table-td font-bold text-gray-800">{{ $row['nama'] }}</td>
                        <td class="table-td text-center"><span class="text-xs font-semibold {{ $row['kelas_asli'] == 1 ? 'text-emerald-700' : 'text-red-600' }}">{{ $row['label_asli'] }}</span></td>
                        <td class="table-td text-center"><span class="text-xs font-semibold {{ $row['kelas_prediksi'] == 1 ? 'text-emerald-700' : 'text-red-600' }}">{{ $row['label_prediksi'] }}</span></td>
                        <td class="table-td text-center font-mono text-xs {{ $row['fx'] > 0 ? 'text-emerald-700' : 'text-red-600' }}">{{ $row['fx'] > 0 ? '+' : '' }}{{ $row['fx'] }}</td>
                        <td class="table-td text-center">
                            @if($row['benar'])
                                <span class="text-xs font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full">Benar</span>
                            @else
                                <span class="text-xs font-bold text-red-500 bg-red-50 px-2 py-0.5 rounded-full">Salah</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="bg-gray-50 font-bold">
                        <td class="table-td" colspan="4">Akurasi Model</td>
                        <td class="table-td text-center">
                            <span class="text-indigo-700 font-black text-base">{{ $akurasi }}%</span>
                            <span class="text-gray-400 text-xs ml-1">({{ $benar }}/{{ $total }})</span>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <div class="space-y-4">
        <div class="card">
            <p class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-3">Akurasi Klasifikasi</p>
            <div class="flex items-center gap-3 mb-2">
                <div class="flex-1 h-5 bg-gray-100 rounded-full overflow-hidden">
                    <div class="h-full rounded-full bg-gradient-to-r from-indigo-500 to-violet-500"
                         style="width: {{ $akurasi }}%"></div>
                </div>
                <span class="font-black text-indigo-700 text-lg w-16 text-right">{{ $akurasi }}%</span>
            </div>
            <div class="flex text-xs text-gray-500 gap-4 mt-2">
                <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>Benar: {{ $benar }}</span>
                <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-red-400"></span>Salah: {{ $salah }}</span>
                <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-gray-300"></span>Total: {{ $total }}</span>
            </div>
        </div>

        <div class="card">
            <p class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-3">Grafik Akurasi</p>
            <div class="flex items-center gap-4">
                <div class="w-44 h-44 shrink-0"><canvas id="chartAkurasi"></canvas></div>
                <div class="space-y-2 text-sm">
                    <div class="flex items-center gap-2"><span class="w-3 h-3 rounded-sm bg-emerald-500 shrink-0"></span><span class="text-gray-600">Prediksi Benar</span><span class="font-black text-gray-800 ml-auto">{{ $benar }}</span></div>
                    <div class="flex items-center gap-2"><span class="w-3 h-3 rounded-sm bg-red-400 shrink-0"></span><span class="text-gray-600">Prediksi Salah</span><span class="font-black text-gray-800 ml-auto">{{ $salah }}</span></div>
                    <hr class="border-gray-100">
                    <div class="flex items-center gap-2"><span class="w-3 h-3 rounded-sm bg-indigo-500 shrink-0"></span><span class="text-gray-600">Akurasi</span><span class="font-black text-indigo-700 ml-auto">{{ $akurasi }}%</span></div>
                </div>
            </div>
        </div>

        <div class="card">
            <p class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-3">Distribusi Kelas Dataset</p>
            <canvas id="chartDistribusi" height="110"></canvas>
        </div>
    </div>
</div>

{{-- ══ 6. CONFUSION MATRIX & METRIK PERFORMA ════════════════════════════════ --}}
<div class="card mb-6">
    <h2 class="text-base font-bold text-gray-800 mb-4 flex items-center gap-2">
        <span class="w-7 h-7 rounded-lg bg-blue-100 text-blue-700 flex items-center justify-center text-sm font-black">6</span>
        Confusion Matrix & Metrik Performa
    </h2>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        
        <!-- Bagian Confusion Matrix -->
        <div>
            <p class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-3">Confusion Matrix</p>
            <div class="grid grid-cols-2 gap-2 text-center text-sm">
                <div class="bg-emerald-50 border border-emerald-200 rounded-lg p-3">
                    <p class="text-emerald-700 font-bold mb-1">True Positive</p>
                    <p class="text-2xl font-black text-emerald-800">{{ $cm['tp'] ?? 0 }}</p>
                </div>
                <div class="bg-rose-50 border border-rose-200 rounded-lg p-3">
                    <p class="text-rose-700 font-bold mb-1">False Positive</p>
                    <p class="text-2xl font-black text-rose-800">{{ $cm['fp'] ?? 0 }}</p>
                </div>
                <div class="bg-rose-50 border border-rose-200 rounded-lg p-3">
                    <p class="text-rose-700 font-bold mb-1">False Negative</p>
                    <p class="text-2xl font-black text-rose-800">{{ $cm['fn'] ?? 0 }}</p>
                </div>
                <div class="bg-emerald-50 border border-emerald-200 rounded-lg p-3">
                    <p class="text-emerald-700 font-bold mb-1">True Negative</p>
                    <p class="text-2xl font-black text-emerald-800">{{ $cm['tn'] ?? 0 }}</p>
                </div>
            </div>
        </div>

        <!-- Bagian Metrik -->
        <div class="space-y-4">
            <p class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-3">Skor Metrik (0 - 1)</p>
            
            <div class="bg-gray-50 rounded-xl p-4 border border-gray-100 flex items-center justify-between">
                <div>
                    <p class="font-bold text-gray-800">Precision</p>
                    <p class="text-xs text-gray-500">Ketepatan prediksi kelas positif</p>
                </div>
                <span class="font-black text-lg text-indigo-700">{{ number_format($cm['precision'] ?? 0, 2) }}</span>
            </div>

            <div class="bg-gray-50 rounded-xl p-4 border border-gray-100 flex items-center justify-between">
                <div>
                    <p class="font-bold text-gray-800">Recall</p>
                    <p class="text-xs text-gray-500">Sensitivitas klasifikasi benar</p>
                </div>
                <span class="font-black text-lg text-indigo-700">{{ number_format($cm['recall'] ?? 0, 2) }}</span>
            </div>

            <div class="bg-gray-50 rounded-xl p-4 border border-gray-100 flex items-center justify-between">
                <div>
                    <p class="font-bold text-gray-800">F1-Score</p>
                    <p class="text-xs text-gray-500">Keseimbangan Precision & Recall</p>
                </div>
                <span class="font-black text-lg text-indigo-700">{{ number_format($cm['f1'] ?? 0, 2) }}</span>
            </div>
        </div>
        
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    new Chart(document.getElementById('chartAkurasi'), {
        type: 'doughnut',
        data: {
            labels: ['Benar', 'Salah'],
            datasets: [{ data: [{{ $benar }}, {{ $salah }}], backgroundColor: ['#10b981', '#f87171'], borderWidth: 0, hoverOffset: 6 }]
        },
        options: { cutout: '65%', plugins: { legend: { display: false } } }
    });

    new Chart(document.getElementById('chartDistribusi'), {
        type: 'bar',
        data: {
            labels: {!! json_encode(array_keys($distribusi)) !!},
            datasets: [{ label: 'Jumlah Data', data: {!! json_encode(array_values($distribusi)) !!}, backgroundColor: ['#6366f1', '#f43f5e'], borderRadius: 8, barThickness: 40 }]
        },
        options: {
            scales: { y: { beginAtZero: true, ticks: { stepSize: 1 }, grid: { color: '#f3f4f6' } }, x: { grid: { display: false } } },
            plugins: { legend: { display: false } }
        }
    });
});
</script>
@endpush

@endsection
