<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MasterSurah;

class MasterSurahSeeder extends Seeder
{
    public function run(): void
    {
        $surahs = [
            ['nomor_surah' => 78,  'nama_surah' => "An-Naba'",       'jumlah_ayat' => 40],
            ['nomor_surah' => 79,  'nama_surah' => "An-Nazi'at",     'jumlah_ayat' => 46],
            ['nomor_surah' => 80,  'nama_surah' => "Abasa",          'jumlah_ayat' => 42],
            ['nomor_surah' => 81,  'nama_surah' => "At-Takwir",      'jumlah_ayat' => 29],
            ['nomor_surah' => 82,  'nama_surah' => "Al-Infithar",    'jumlah_ayat' => 19],
            ['nomor_surah' => 83,  'nama_surah' => "Al-Muthaffifin", 'jumlah_ayat' => 36],
            ['nomor_surah' => 84,  'nama_surah' => "Al-Insyiqaq",    'jumlah_ayat' => 25],
            ['nomor_surah' => 85,  'nama_surah' => "Al-Buruj",       'jumlah_ayat' => 22],
            ['nomor_surah' => 86,  'nama_surah' => "Ath-Thariq",     'jumlah_ayat' => 17],
            ['nomor_surah' => 87,  'nama_surah' => "Al-A'la",        'jumlah_ayat' => 19],
            ['nomor_surah' => 88,  'nama_surah' => "Al-Ghasyiyah",   'jumlah_ayat' => 26],
            ['nomor_surah' => 89,  'nama_surah' => "Al-Fajr",        'jumlah_ayat' => 30],
            ['nomor_surah' => 90,  'nama_surah' => "Al-Balad",       'jumlah_ayat' => 20],
            ['nomor_surah' => 91,  'nama_surah' => "Asy-Syams",      'jumlah_ayat' => 15],
            ['nomor_surah' => 92,  'nama_surah' => "Al-Lail",        'jumlah_ayat' => 21],
            ['nomor_surah' => 93,  'nama_surah' => "Adh-Dhuha",      'jumlah_ayat' => 11],
            ['nomor_surah' => 94,  'nama_surah' => "Asy-Syarh",      'jumlah_ayat' => 8],
            ['nomor_surah' => 95,  'nama_surah' => "At-Tin",         'jumlah_ayat' => 8],
            ['nomor_surah' => 96,  'nama_surah' => "Al-'Alaq",       'jumlah_ayat' => 19],
            ['nomor_surah' => 97,  'nama_surah' => "Al-Qadr",        'jumlah_ayat' => 5],
            ['nomor_surah' => 98,  'nama_surah' => "Al-Bayyinah",    'jumlah_ayat' => 8],
            ['nomor_surah' => 99,  'nama_surah' => "Az-Zalzalah",    'jumlah_ayat' => 8],
            ['nomor_surah' => 100, 'nama_surah' => "Al-'Adiyat",     'jumlah_ayat' => 11],
            ['nomor_surah' => 101, 'nama_surah' => "Al-Qari'ah",     'jumlah_ayat' => 11],
            ['nomor_surah' => 102, 'nama_surah' => "At-Takatsur",    'jumlah_ayat' => 8],
            ['nomor_surah' => 103, 'nama_surah' => "Al-'Ashr",       'jumlah_ayat' => 3],
            ['nomor_surah' => 104, 'nama_surah' => "Al-Humazah",     'jumlah_ayat' => 9],
            ['nomor_surah' => 105, 'nama_surah' => "Al-Fil",         'jumlah_ayat' => 5],
            ['nomor_surah' => 106, 'nama_surah' => "Quraisy",        'jumlah_ayat' => 4],
            ['nomor_surah' => 107, 'nama_surah' => "Al-Ma'un",       'jumlah_ayat' => 7],
            ['nomor_surah' => 108, 'nama_surah' => "Al-Kautsar",     'jumlah_ayat' => 3],
            ['nomor_surah' => 109, 'nama_surah' => "Al-Kafirun",     'jumlah_ayat' => 6],
            ['nomor_surah' => 110, 'nama_surah' => "An-Nashr",       'jumlah_ayat' => 3],
            ['nomor_surah' => 111, 'nama_surah' => "Al-Lahab",       'jumlah_ayat' => 5],
            ['nomor_surah' => 112, 'nama_surah' => "Al-Ikhlas",      'jumlah_ayat' => 4],
            ['nomor_surah' => 113, 'nama_surah' => "Al-Falaq",       'jumlah_ayat' => 5],
            ['nomor_surah' => 114, 'nama_surah' => "An-Nas",         'jumlah_ayat' => 6],
        ];

        foreach ($surahs as $surah) {
            MasterSurah::updateOrCreate(
                ['nomor_surah' => $surah['nomor_surah']],
                array_merge($surah, ['is_active' => true])
            );
        }

        $this->command->info('✅ MasterSurahSeeder: ' . count($surahs) . ' surat Juz 30 berhasil di-seed.');
    }
}
