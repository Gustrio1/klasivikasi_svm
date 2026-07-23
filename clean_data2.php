<?php

$lines = file('Data_siswa_baru_fix.txt', FILE_IGNORE_NEW_LINES);
$students = [];
$current_student = null;
$media_base = [];
$current_student_hafalans = [];

// start from line 2 (index 2)
for ($i = 2; $i < count($lines); $i++) {
    $line = trim($lines[$i]);
    if (empty($line)) continue;
    $cols = explode(',', $line);
    
    // Check if new student (column 0 is not empty)
    if (!empty($cols[0])) {
        if ($current_student !== null) {
            // save previous student's hafalans
            // only save meetings that have surahs
            foreach ($current_student_hafalans as $h) {
                if ($h['ayat'] > 0) {
                    $current_student['hafalan'][] = $h;
                }
            }
            $students[] = $current_student;
        }

        $current_student = [
            'no' => $cols[0],
            'nama' => $cols[1],
            'kelas' => $cols[2],
            'umur' => (int) filter_var($cols[3], FILTER_SANITIZE_NUMBER_INT),
            'jenis_kelamin' => $cols[4] === 'Laki-Laki' ? 'L' : ($cols[4] === 'Perempuan' ? 'P' : $cols[4]),
            'hafalan' => []
        ];
        $media_base = [];
        $current_student_hafalans = [];
        for ($j = 0; $j < 32; $j++) {
            $current_student_hafalans[$j] = [
                'media' => '',
                'surat_list' => [],
                'ayat' => 0
            ];
        }
    }
    
    if ($current_student !== null) {
        $c = 5;
        $group_index = 0;
        while ($c + 2 < count($cols)) {
            $media = trim($cols[$c]);
            $surat = trim($cols[$c+1]);
            $ayat = trim($cols[$c+2]);
            
            // first line sets media
            if (!empty($media) && empty($media_base[$group_index])) {
                $media_base[$group_index] = $media;
            }
            
            $assigned_media = !empty($media) ? $media : ($media_base[$group_index] ?? '');
            if ($assigned_media !== '') {
                $current_student_hafalans[$group_index]['media'] = $assigned_media;
            }

            if (!empty($surat)) {
                $nama_surat = preg_replace('/\s*\(\d+\)$/', '', $surat);
                $current_student_hafalans[$group_index]['surat_list'][] = $nama_surat;
                $current_student_hafalans[$group_index]['ayat'] += (int) $ayat;
            }
            $c += 3;
            $group_index++;
            if ($group_index >= 32) break; // safeguard
        }
    }
}
if ($current_student !== null) {
    foreach ($current_student_hafalans as $h) {
        if ($h['ayat'] > 0) {
            $h['surat'] = implode(', ', $h['surat_list']);
            unset($h['surat_list']);
            $current_student['hafalan'][] = $h;
        }
    }
    $students[] = $current_student;
}

// Convert to output format
$output_students = [];
foreach ($students as $s) {
    $formatted_hafalans = [];
    foreach ($s['hafalan'] as $h) {
        $formatted_hafalans[] = [
            'media' => $h['media'],
            'surat' => is_array($h['surat_list'] ?? null) ? implode(', ', $h['surat_list']) : ($h['surat'] ?? ''),
            'ayat' => $h['ayat']
        ];
    }
    $s['hafalan'] = $formatted_hafalans;
    $output_students[] = $s;
}


file_put_contents('Data_siswa_baru_cleaned.json', json_encode($output_students, JSON_PRETTY_PRINT));
echo "Data parsed! Saved to Data_siswa_baru_cleaned.json. Count of students: " . count($output_students) . "\n";
$first_student_hafalan = $output_students[0]['hafalan'];
echo "Student 1 hafalan count: " . count($first_student_hafalan) . "\n";
