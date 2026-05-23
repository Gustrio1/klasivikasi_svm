<?php

namespace App\Http\Controllers;

use App\Models\DataTraining;
use App\Models\MediaHafalan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DataTrainingController extends Controller
{

    /**
     * Tampilkan semua data training; support filter label_kelas dan is_valid.
     */
    public function index()
    {
        $query = DataTraining::query();

        if (request()->filled('label_kelas')) {
            $query->where('label_kelas', request('label_kelas'));
        }
        if (request()->has('is_valid') && request('is_valid') !== '') {
            $query->where('is_valid', (bool)request('is_valid'));
        }

        $dataTrainings = $query->latest('tanggal_input')->paginate(20);

        return view('admin.data_training.index', compact('dataTrainings'));
    }

    /**
     * Form tambah data training.
     */
    public function create()
    {
        $medias = MediaHafalan::where('is_active', true)->orderBy('nama_media')->get();
        return view('admin.data_training.create', compact('medias'));
    }

    /**
     * Simpan data training baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'fitur_total_surah' => ['required', 'integer', 'min:1', 'max:120'],
            'fitur_usia'        => ['required', 'integer', 'min:1', 'max:100'],
            'id_media'          => ['required', 'integer', 'exists:tb_media_hafalan,id'],
            'label_kelas'       => ['required', 'in:Lulus,Tidak Lulus'],
            'sumber_data'       => ['nullable', 'string', 'max:200'],
            'is_valid'          => ['boolean'],
        ]);

        try {
            DataTraining::create(array_merge(
                $validated,
            ['tanggal_input' => now()]
            ));

            return redirect()->route('admin.data-training.index')
                ->with('success', 'Data training berhasil ditambahkan.');
        }
        catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Gagal menambahkan data training: ' . $e->getMessage());
        }
    }

    /**
     * Form edit data training.
     */
    public function edit(DataTraining $dataTraining)
    {
        $medias = MediaHafalan::where('is_active', true)->orderBy('nama_media')->get();
        return view('admin.data_training.edit', compact('dataTraining', 'medias'));
    }

    /**
     * Update data training.
     */
    public function update(Request $request, DataTraining $dataTraining)
    {
        $request->validate([
            'fitur_total_surah' => ['required', 'integer', 'min:1', 'max:120'],
            'fitur_usia'        => ['required', 'integer', 'min:1', 'max:100'],
            'id_media'          => ['required', 'integer', 'exists:tb_media_hafalan,id'],
            'label_kelas'       => ['required', 'in:Lulus,Tidak Lulus'],
            'sumber_data'       => ['nullable', 'string', 'max:200'],
            'is_valid'          => ['boolean'],
        ]);

        try {
            $dataTraining->update($request->all());

            return redirect()->route('admin.data-training.index')
                ->with('success', 'Data training berhasil diperbarui.');
        }
        catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Gagal memperbarui data training: ' . $e->getMessage());
        }
    }

    /**
     * Hapus permanen data training.
     */
    public function destroy(DataTraining $dataTraining)
    {
        try {
            $dataTraining->delete();

            return redirect()->route('admin.data-training.index')
                ->with('success', 'Data training berhasil dihapus.');
        }
        catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus data training: ' . $e->getMessage());
        }
    }

    /**
     * Import data training dari file CSV menggunakan League\Csv.
     * Pastikan package sudah di-install: composer require league/csv
     */
    public function import(Request $request)
    {
        $request->validate([
            'file_csv' => ['required', 'file', 'mimes:csv,txt', 'max:5120'], // maks 5MB
        ]);

        try {
            $path = $request->file('file_csv')->getRealPath();

            // Gunakan League\Csv jika tersedia, fallback ke fgetcsv
            if (class_exists(\League\Csv\Reader::class)) {
                $csv = \League\Csv\Reader::createFromPath($path, 'r');
                $csv->setHeaderOffset(0);
                $records = $csv->getRecords();
            }
            else {
                $records = $this->readCsvFallback($path);
            }

            $inserted = 0;
            DB::transaction(function () use ($records, &$inserted) {
                foreach ($records as $record) {
                    DataTraining::create([
                        'fitur_total_surah' => $record['fitur_total_surah'] ?? ($record['fitur_jumlah_ayat'] ?? 0),
                        'fitur_usia'        => $record['fitur_usia'] ?? 0,
                        'id_media'          => $record['id_media'] ?? null,
                        'label_kelas'       => $record['label_kelas'] ?? 'Tidak Lulus',
                        'sumber_data'       => $record['sumber_data'] ?? 'Import CSV',
                        'is_valid'          => true,
                        'tanggal_input'     => now(),
                    ]);
                    $inserted++;
                }
            });

            return redirect()->route('admin.data-training.index')
                ->with('success', "{$inserted} data training berhasil diimport dari CSV.");
        }
        catch (\Exception $e) {
            return back()->with('error', 'Import gagal: ' . $e->getMessage());
        }
    }

    /** Fallback CSV reader jika League\Csv tidak tersedia */
    private function readCsvFallback(string $path): array
    {
        $records = [];
        $handle = fopen($path, 'r');
        $headers = fgetcsv($handle);
        while (($row = fgetcsv($handle)) !== false) {
            $records[] = array_combine($headers, $row);
        }
        fclose($handle);
        return $records;
    }
}
