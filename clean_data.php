<?php

$lines = file('Data_siswa_baru_fix.txt', FILE_IGNORE_NEW_LINES);
$students = [];
$current_student = null;
$student_lines_count = 0;
$media_base = [];

// start from line 2 (index 2)
for ($i = 2; $i < count($lines); $i++) {
    $line = trim($lines[$i]);
    if (empty($line)) continue;
    $cols = explode(',', $line);
    
    // Check if new student (column 0 is not empty)
    if (!empty($cols[0])) {
        // Unset reference to avoid overriding
        unset($current_student);
        $current_student = [
            'no' => $cols[0],
            'nama' => $cols[1],
            'kelas' => $cols[2],
            'umur' => (int) filter_var($cols[3], FILTER_SANITIZE_NUMBER_INT),
            'jenis_kelamin' => $cols[4] === 'Laki-Laki' ? 'L' : ($cols[4] === 'Perempuan' ? 'P' : $cols[4]),
            'hafalan' => []
        ];
        $students[] = &$current_student;
        $student_lines_count = 0;
        $media_base = [];
    }
    
    if ($current_student !== null) {
        $c = 5;
        $group_index = 0;
        while ($c + 2 < count($cols)) {
            $media = trim($cols[$c]);
            $surat = trim($cols[$c+1]);
            $ayat = trim($cols[$c+2]);
            
            if ($student_lines_count == 0) {
                $media_base[$group_index] = $media;
            } else {
                if (empty($media) && isset($media_base[$group_index])) {
                    $media = $media_base[$group_index];
                }
            }
            
            if (!empty($surat)) {
                $nama_surat = preg_replace('/\s*\(\d+\)$/', '', $surat);
                $current_student['hafalan'][] = [
                    'media' => $media,
                    'surat' => $nama_surat,
                    'ayat' => (int) $ayat
                ];
            }
            $c += 3;
            $group_index++;
        }
        $student_lines_count++;
    }
}

file_put_contents('Data_siswa_baru_cleaned.json', json_encode($students, JSON_PRETTY_PRINT));
echo "Data parsed! Saved to Data_siswa_baru_cleaned.json\n";
