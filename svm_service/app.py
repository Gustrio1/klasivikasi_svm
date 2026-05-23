import os
import random
import numpy as np
from flask import Flask, request, jsonify
from flask_cors import CORS
# from sklearn.svm import SVC
# import joblib

app = Flask(__name__)
CORS(app)

# ==========================================
# SIMULASI MODEL SVM (DUMMY)
# ==========================================
def dummy_svm_predict(features):
    """
    Fungsi ini adalah simulasi. Di dunia nyata, Anda akan me-load model .pkl
    hasil training scikit-learn Anda.
    
    Fitur yang masuk: [Siswa_ID, Kode_Surah, Jumlah_Ayat, Media_ID]
    """
    # Contoh logika ngawur untuk sekedar menentukan kelas A, B, atau C
    # Kelas A: Sangat Baik | Kelas B: Cukup | Kelas C: Perlu Bimbingan
    
    # Ambil index-1 yang mana adalah kode_surah, index-2 adalah jumlah_ayat
    kode_surah = features[1]
    jumlah_ayat = features[2]
    media_id = features[3]
    
    # Simulasi perhitungan (hanya sebagai contoh agar API tidak error)
    score = (jumlah_ayat * 0.5) + (media_id * 2) 
    
    # Tentukan prediksi
    if score > 50:
        prediksi = 'A'
        confidence = round(random.uniform(0.80, 0.99), 2)
    elif score > 20:
        prediksi = 'B'
        confidence = round(random.uniform(0.60, 0.79), 2)
    else:
        prediksi = 'C'
        confidence = round(random.uniform(0.40, 0.59), 2)
        
    return prediksi, confidence

@app.route('/', methods=['GET'])
def index():
    return jsonify({
        "status": "online",
        "message": "SVM Classification Service is Running."
    })

@app.route('/predict', methods=['POST'])
def predict():
    try:
        data = request.get_json()
        
        # Validasi payload
        if not data or 'features' not in data:
            return jsonify({"error": "Payload tidak valid. 'features' tidak ditemukan."}), 400
            
        features = data['features']
        
        # Output dari PHP: [Siswa, Surah, Jumlah_Ayat, Media]
        if len(features) != 4:
            return jsonify({"error": f"Dimensi fitur tidak cocok. Diharapkan 4, diterima {len(features)}"}), 400
            
        print(f"Menerima request prediksi dengan fitur: {features}")
        
        # ----------------------------------------------------
        # TODO: Implementasikan Load Model SVM Asli Anda disini
        # model = joblib.load('model_svm.pkl')
        # prediksi = model.predict([features])[0]
        # ----------------------------------------------------
        
        # Untuk saat ini kita gunakan fungsi Dummy Simulator
        kelas_prediksi, confidence = dummy_svm_predict(features)
        
        response = {
            "prediction": kelas_prediksi,
            "confidence": confidence,
            "status": "success",
            "message": "Klasifikasi berhasil."
        }
        
        return jsonify(response), 200
        
    except Exception as e:
        print(f"Error: {str(e)}")
        return jsonify({
            "error": "Terjadi kesalahan pada server",
            "details": str(e)
        }), 500

if __name__ == '__main__':
    # Jalankan server di port 5000 agar web Laravel (Target) bisa menangkapnya
    port = int(os.environ.get("PORT", 5000))
    app.run(host='0.0.0.0', port=port, debug=True)
