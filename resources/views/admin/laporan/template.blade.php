<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Perkembangan Hafalan - {{ $siswa->user->nama_lengkap }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            color: #333;
            margin: 0;
            padding: 20px;
        }

        .header-container {
            text-align: center;
            border-bottom: 2px solid #1D9E75;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }

        .header-title {
            font-size: 16px;
            font-weight: bold;
            color: #1D9E75;
            margin: 0 0 5px 0;
            text-transform: uppercase;
        }

        .header-subtitle {
            font-size: 12px;
            color: #555;
            margin: 0;
        }

        .info-table {
            width: 100%;
            margin-bottom: 20px;
        }

        .info-table td {
            vertical-align: top;
            padding: 3px 0;
        }

        .info-label {
            width: 120px;
            font-weight: bold;
        }

        .info-colon {
            width: 10px;
        }

        .section-title {
            background-color: #1D9E75;
            color: white;
            font-weight: bold;
            padding: 6px 10px;
            margin-top: 20px;
            margin-bottom: 10px;
        }

        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table.data-table th, table.data-table td {
            border: 1px solid #ccc;
            padding: 6px;
        }

        table.data-table th {
            background-color: #f0f0f0;
            font-weight: bold;
            text-align: center;
        }

        table.data-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        table.data-table tr:nth-child(odd) {
            background-color: #ffffff;
        }

        .text-center {
            text-align: center;
        }

        .footer {
            margin-top: 50px;
            table-layout: fixed;
            width: 100%;
        }

        .signature-box {
            text-align: center;
            width: 30%;
            float: right;
        }

        .signature-line {
            margin-top: 60px;
            border-bottom: 1px solid #333;
            width: 100%;
            display: inline-block;
        }
        
        .clear {
            clear: both;
        }
    </style>
</head>
<body>

    @php
        // Perhitungan Bagian 1 Ringkasan
        $totalSesi = $hafalan->count();
        $kelasA = 0; $kelasB = 0;

        foreach($hafalan as $h) {
            $klasifikasi = $siswa->hasilKlasifikasis->where('periode_semester', $h->periode_semester)->last();
            if ($klasifikasi) {
                $k = strtoupper($klasifikasi->kelas_prediksi);
                if ($k == 'LULUS') $kelasA++;
                elseif ($k == 'TIDAK LULUS') $kelasB++;
            }
        }
    @endphp

    <div class="header-container">
        <h1 class="header-title">LEMBAGA PENDIDIKAN AL-QUR'AN</h1>
        <h2 class="header-subtitle">Laporan Perkembangan Hafalan Al-Qur'an</h2>
    </div>

    <table class="info-table">
        <tr>
            <td class="info-label">Nama Siswa</td>
            <td class="info-colon">:</td>
            <td><strong>{{ $siswa->user->nama_lengkap }}</strong></td>
            <td class="info-label">Nama Guru</td>
            <td class="info-colon">:</td>
            <td>{{ $guru->user->nama_lengkap ?? '-' }}</td>
        </tr>
        <tr>
            <td class="info-label">NISN</td>
            <td class="info-colon">:</td>
            <td>{{ $siswa->nisn ?? '-' }}</td>
            <td class="info-label">Periode Laporan</td>
            <td class="info-colon">:</td>
            <td>{{ $periode }}</td>
        </tr>
        <tr>
            <td class="info-label">Kelas</td>
            <td class="info-colon">:</td>
            <td>{{ $siswa->kelas ?? '-' }}</td>
            <td class="info-label">Judul File</td>
            <td class="info-colon">:</td>
            <td>{{ $judul }}</td>
        </tr>
    </table>

    <div class="section-title">Bagian 1 — Ringkasan Pencapaian</div>
    <table class="data-table">
        <tr>
            <th>Total Sesi Setoran</th>
            <th>Jumlah Prediksi Lulus</th>
            <th>Jumlah Prediksi Tidak Lulus</th>
        </tr>
        <tr>
            <td class="text-center">{{ $totalSesi }} Sesi</td>
            <td class="text-center">{{ $kelasA }}</td>
            <td class="text-center">{{ $kelasB }}</td>
        </tr>
    </table>

    <div class="section-title">Bagian 2 — Riwayat Hafalan & Evaluasi</div>
    <table class="data-table">
        <tr>
            <th width="5%">No</th>
            <th width="20%">Surah</th>
            <th width="12%">Ayat</th>
            <th width="18%">Tanggal</th>
            <th width="15%">Semester</th>
            <th width="20%">Kelas (SVM)</th>
            <th width="30%">Catatan Evaluasi</th>
        </tr>
        @forelse($hafalan->sortBy('tanggal_input') as $idx => $h)
            <tr>
                <td class="text-center">{{ $loop->iteration }}</td>
                <td>{{ $h->nama_surah }}</td>
                <td class="text-center">{{ $h->jumlah_ayat }} Ayat</td>
                <td class="text-center">{{ \Carbon\Carbon::parse($h->tanggal_input)->format('d/m/Y H:i') }}</td>
                <td class="text-center">{{ $h->periode_semester ?? '-' }}</td>
                <td class="text-center">
                    @php 
                        $klas = $siswa->hasilKlasifikasis->where('periode_semester', $h->periode_semester)->last();    
                    @endphp
                    {{ $klas ? strtoupper($klas->kelas_prediksi) : '-' }}
                </td>
                <td>
                    {{ $h->nilaiEvaluasi?->catatan_guru ?? '-' }}
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="7" class="text-center">Belum ada riwayat hafalan untuk periode ini.</td>
            </tr>
        @endforelse
    </table>

    <div class="section-title">Bagian 3 — Catatan Guru</div>
    <table class="data-table">
        <tr>
            <th width="30%">Surah & Setoran</th>
            <th width="70%">Catatan Evaluasi</th>
        </tr>
        @php $hasCatatan = false; @endphp
        @foreach($hafalan->sortBy('tanggal_input') as $h)
            @if($h->nilaiEvaluasi && !empty($h->nilaiEvaluasi->catatan_guru))
                @php $hasCatatan = true; @endphp
                <tr>
                    <td>{{ $h->nama_surah }} ({{ $h->jumlah_ayat }} Ayat)</td>
                    <td>{{ $h->nilaiEvaluasi->catatan_guru }}</td>
                </tr>
            @endif
        @endforeach
        
        @if(!$hasCatatan)
            <tr>
                <td colspan="2" class="text-center">Tidak ada catatan khusus dari Guru.</td>
            </tr>
        @endif
    </table>



    <div class="footer">
        <div class="signature-box">
            <p style="margin-bottom: 5px;">Dicetak pada tanggal: <br><strong>{{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</strong></p>
            <p>Mengetahui, Guru Pembimbing</p>
            
            <span class="signature-line"></span>
            
            <p style="margin-top: 5px;"><strong>{{ $guru->user->nama_lengkap ?? '_______________________' }}</strong></p>
        </div>
        <div class="clear"></div>
    </div>

</body>
</html>
