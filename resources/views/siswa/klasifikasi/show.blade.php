@extends('layouts.app')

@section('title', 'Detail Klasifikasi')

@section('content')

<x-page-header
    title="Detail Hasil Klasifikasi"
    :links="[
        ['label' => 'Dashboard', 'url' => route('dashboard')],
        ['label' => 'Hasil Klasifikasi', 'url' => route('siswa.hasil-klasifikasi.index')],
        ['label' => 'Detail', 'url' => null],
    ]"
>
    <a href="{{ route('siswa.hasil-klasifikasi.index') }}" class="btn-secondary">← Kembali</a>
</x-page-header>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-5">

    {{-- ── Kartu Evaluasi Semester ──────────────────────────────── --}}
    <div class="card">
        <h3 class="text-base font-semibold text-gray-800 mb-5">Data Evaluasi Semester</h3>

        <dl class="space-y-3">
            <div class="flex justify-between py-2 border-b border-gray-50">
                <dt class="text-xs text-gray-400 uppercase tracking-wide">Periode Semester</dt>
                <dd class="text-sm font-semibold text-gray-800">{{ $hasil->periode_semester ?? '-' }}</dd>
            </div>
            <div class="flex justify-between py-2 border-b border-gray-50">
                <dt class="text-xs text-gray-400 uppercase tracking-wide">Total Surat</dt>
                <dd class="text-sm font-bold text-teal-700">{{ $hasil->total_surah ?? '-' }} Surat</dd>
            </div>
            <div class="flex justify-between py-2 border-b border-gray-50">
                <dt class="text-xs text-gray-400 uppercase tracking-wide">Usia Siswa (X₁)</dt>
                <dd class="text-sm font-semibold text-purple-700">
                    @php
                        $usiaSiswa = $hasil->siswa?->tanggal_lahir
                            ? date('Y') - $hasil->siswa->tanggal_lahir
                            : null;
                    @endphp
                    {{ $usiaSiswa ? $usiaSiswa . ' Tahun' : '-' }}
                </dd>
            </div>
            <div class="flex justify-between py-2 border-b border-gray-50">
                <dt class="text-xs text-gray-400 uppercase tracking-wide">Modus Media Belajar</dt>
                <dd class="text-sm font-semibold text-gray-800">
                    @php
                        $vector = is_array($hasil->vector_svm) ? $hasil->vector_svm : json_decode($hasil->vector_svm, true);
                        $id_media = $vector['id_media'] ?? null;
                        $media = $id_media ? \App\Models\MediaHafalan::find($id_media) : null;
                    @endphp
                    {{ $media ? $media->nama_media : '-' }}
                </dd>
            </div>
            <div class="flex justify-between py-2 border-b border-gray-50">
                <dt class="text-xs text-gray-400 uppercase tracking-wide">Tanggal Klasifikasi</dt>
                <dd class="text-sm text-gray-700">
                    {{ \Carbon\Carbon::parse($hasil->tanggal_klasifikasi)->translatedFormat('d F Y, H:i') }}
                </dd>
            </div>
            <div class="flex justify-between py-2">
                <dt class="text-xs text-gray-400 uppercase tracking-wide">Status Notifikasi</dt>
                <dd class="text-sm">
                    @if($hasil->notifikasi_terkirim)
                        <span class="text-green-600 font-medium">✓ Terkirim</span>
                    @else
                        <span class="text-yellow-600 font-medium">⏳ Pending</span>
                    @endif
                </dd>
            </div>
        </dl>
    </div>

    {{-- ── Hasil Prediksi ───────────────────────────────── --}}
    <div class="card p-0 overflow-hidden border-none shadow-sm h-fit">
        <div class="p-6 bg-gradient-to-br {{ $hasil->kelas_prediksi == 'Lulus' ? 'from-emerald-500 to-emerald-700' : 'from-red-500 to-red-700' }} text-white">
            <h3 class="text-sm font-bold text-white/80 uppercase tracking-widest mb-4">Hasil Evaluasi SVM</h3>
            <div class="flex items-center gap-5">
                <div class="w-auto px-5 py-2 h-16 bg-white/20 rounded-2xl flex items-center justify-center text-xl font-black shadow-inner">
                    {{ strtoupper($hasil->kelas_prediksi) }}
                </div>
                <div>
                    <h4 class="text-xl font-black">Status: {{ $hasil->kelas_prediksi }}</h4>
                </div>
            </div>
            <p class="text-white/80 mt-4 text-sm">
                @if($hasil->kelas_prediksi == 'Lulus')
                    Selamat! Hafalan Anda dinilai sudah memenuhi standar dengan sangat baik.
                @else
                    Hafalan Anda masih perlu perbaikan dan bimbingan lebih intensif.
                @endif
            </p>
        </div>
        <div class="p-6 bg-white">
            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mb-1">Confidence Score (Tingkat Keyakinan Model)</p>
            <div class="flex items-center gap-3">
                <div class="flex-1 h-2 bg-gray-200 rounded-full overflow-hidden">
                    <div class="h-full bg-indigo-500" style="width: {{ $hasil->confidence_score * 100 }}%"></div>
                </div>
                <span class="text-lg font-black text-gray-800">{{ number_format($hasil->confidence_score * 100, 1) }}%</span>
            </div>
        </div>
    </div>

</div>

@endsection
