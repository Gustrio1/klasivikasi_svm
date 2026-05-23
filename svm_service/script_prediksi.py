import sys
import json
import math

# ─── Konfigurasi Min-Max Normalisasi ─────────────────────────────────────────
# Berdasarkan domain data: jumlah ayat 1-50, usia 5-30, id_media 1-10
AYAT_MIN, AYAT_MAX = 1, 50
USIA_MIN, USIA_MAX = 5, 30
MEDIA_MIN, MEDIA_MAX = 1, 10

def predict_svm(total_surah, usia, id_media):
    """
    Simulasi SVM Klasifikasi Linear Semester dengan fitur:
      X₁ = Total Surat (Target 30 surat = 1 juz)
      X₂ = Usia (pengaruh sangat kecil)
      X₃ = Media (pengaruh sangat kecil)
    """
    # 1. Bobot (Weights) & Bias (b) untuk Linear SVM
    # Diset sedemikian rupa agar syarat X1 >= 30 mutlak untuk lulus
    W1 = 1.0    # Bobot utama untuk Total Surat
    W2 = -0.01  # Bobot kecil untuk Usia
    W3 = 0.01   # Bobot kecil untuk Media
    b  = -29.5  # Bias batas lulus di antara 29 dan 30

    # 2. Perhitungan Linear SVM: f(x) = (W1 * X1) + (W2 * X2) + (W3 * X3) + b
    fx = (W1 * total_surah) + (W2 * usia) + (W3 * id_media) + b

    # 3. Tentukan kelas berdasarkan hasil f(x)
    kelas_prediksi = 'Lulus' if fx >= 0 else 'Tidak Lulus'

    # 4. Hitung Confidence (skala probabilitas) berdasarkan margin jarak dari 0
    margin = abs(fx)
    # Semakin jauh dari 0, semakin yakin (confidence mendekati 1)
    confidence = min(0.5 + (margin / 10.0), 0.99)
    confidence = round(confidence, 4)

    return kelas_prediksi, confidence, {
        'W1': W1,
        'W2': W2,
        'W3': W3,
        'bias': b,
        'fx': round(fx, 4),
        'X1_surat': total_surah,
        'X2_usia': usia,
        'X3_media': id_media,
        'rumus_fx': f"({W1} * {total_surah}) + ({W2} * {usia}) + ({W3} * {id_media}) + ({b}) = {round(fx, 4)}"
    }


if __name__ == "__main__":
    try:
        # Args: script_prediksi.py <total_surah> <usia> <id_media>
        if len(sys.argv) < 4:
            raise ValueError("Kekurangan argumen. Dibutuhkan: total_surah, usia, id_media")

        total_surah = float(sys.argv[1])
        usia        = float(sys.argv[2])
        id_media    = int(sys.argv[3])

        kelas_prediksi, confidence, debug_info = predict_svm(total_surah, usia, id_media)

        hasil = {
            "prediction": kelas_prediksi,
            "confidence": confidence,
            "status": "success",
            "message": "Klasifikasi SVM berhasil.",
            "fitur": {
                "total_surah_input": total_surah,
                "usia_input": usia,
                "id_media": id_media,
                "W1": debug_info['W1'],
                "W2": debug_info['W2'],
                "W3": debug_info['W3'],
                "bias": debug_info['bias'],
                "fx": debug_info['fx'],
                "rumus_fx": debug_info['rumus_fx']
            },
            "kernel": "Linear"
        }

        print(json.dumps(hasil))
        sys.exit(0)

    except Exception as e:
        error_json = {
            "prediction": "Tidak Lulus",
            "confidence": 0,
            "status": "error",
            "message": str(e)
        }
        print(json.dumps(error_json))
        sys.exit(1)
