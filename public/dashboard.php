<?php
require_once __DIR__ . '/../config/config.php';
if (!isset($_SESSION['user_id'])) { header('Location: auth/login.php'); exit; }
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>ANOMALI SCAN</title>
  <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
  <script src="https://cdn.jsdelivr.net/npm/tesseract.js@4/dist/tesseract.min.js"></script>
  <style>
    body {
      font-family: Arial, sans-serif;
      padding: 20px;
      background: #f4f4f4;
      text-align: center;
    }

    #reader {
      width: 100%;
      max-width: 400px;
      margin: 20px auto;
    }

    input[type="text"] {
      padding: 10px;
      width: 250px;
      font-size: 16px;
      margin-bottom: 5px;
    }

    button {
      padding: 10px 16px;
      font-size: 16px;
      margin: 5px;
      cursor: pointer;
    }

    #result {
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
      gap: 20px;
      margin-top: 20px;
    }

    .label-container {
      width: 300px;
      background: #fff;
      padding: 16px;
      border: 2px solid #000;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
      text-align: left;
      flex-shrink: 0;
    }

    .label-container h2 {
      font-size: 22px;
      border-bottom: 10px solid black;
      margin-bottom: 4px;
    }

    .label-section {
      border-top: 8px solid black;
      padding-top: 6px;
    }

    .label-section .line {
      display: flex;
      justify-content: space-between;
      padding: 3px 0;
    }

    .label-section .line.italic {
      font-style: italic;
    }

    .label-footer {
      font-size: 11px;
      border-top: 1px solid #000;
      margin-top: 10px;
      padding-top: 4px;
    }

    .highlight {
      font-size: 26px;
      font-weight: bold;
    }

    .product-image {
      display: block;
      margin: 10px auto;
      max-width: 150px;
    }

    .product-name {
      font-size: 18px;
      font-weight: bold;
      margin-top: 10px;
      text-align: center;
    }

    .ideal { color: green; font-weight: bold; }
    .warning { color: orange; font-weight: bold; }
    .danger { color: red; font-weight: bold; }

    @media (max-width: 600px) {
      #result {
        flex-direction: column;
        align-items: center;
      }
    }

    #ocr-video, #ocr-canvas {
      display: none;
    }
  </style>
</head>
<body>

  <h2>📷 Scan Barcode Produk</h2>
  <div id="reader"></div>
  <video id="ocr-video" width="300" autoplay muted playsinline></video>
  <canvas id="ocr-canvas"></canvas>

  <h3>🔎 Cari Manual</h3>
  <p>Masukkan <strong>Barcode</strong>:</p>
  <input type="text" id="barcode-input" placeholder="Contoh: 8993175568173">
  <button onclick="searchByBarcode()">🔍 Cari Barcode</button>

  <p>Masukkan <strong>Nama Produk</strong>:</p>
  <input type="text" id="name-input" placeholder="Contoh: Indomie Goreng">
  <button onclick="searchByName()">🔍 Cari Nama</button>

  <div id="result"></div>
  <button id="scan-again" onclick="restartScan()" style="display:none;">🔄 Scan Ulang</button>

  <script>
    const resultDiv = document.getElementById('result');
    const scanAgainBtn = document.getElementById('scan-again');
    let html5QrCode;
    let isScanning = false;
    let currentCameraId = null;

    function playBeep() {
      const beep = new Audio("https://www.soundjay.com/buttons/sounds/beep-07.mp3");
      beep.play();
    }

    function checkLevel(nutrient, value) {
      if (nutrient === 'protein') {
        return value >= 10 ? { text: '✅ Ideal', class: 'ideal' } : { text: '⚠️ Kurang', class: 'warning' };
      } else if (nutrient === 'carbohydrates') {
        return value <= 25 ? { text: '✅ Ideal', class: 'ideal' } : { text: '❌ Berlebih', class: 'danger' };
      } else if (nutrient === 'sugar' || nutrient === 'sugars') {
        return value <= 5 ? { text: '✅ Ideal', class: 'ideal' } : { text: '❌ Berlebih', class: 'danger' };
      }
      return { text: '', class: '' };
    }

    function displayNutrition(data) {
      if (!data || data.error) {
        resultDiv.innerHTML = `<p>❌ ${data?.error || "Gagal memuat data"}</p>`;
        return;
      }

      let products = Array.isArray(data) ? data : [data];

      const allHtml = products.map(prod => {
        const n = prod.nutrients || {};
        const productName = prod.product_name || "Produk";
        const imageUrl = prod.image_url || "";
        const source = prod.fallback || "Unknown";
        const serving = prod.serving_size || n["Serving Size"] || "-";
        const per100g = prod.per_100g || "As sold for 100 g / 100 ml";

        let nutrientLines = "";
        for (const key in n) {
          const value = n[key];
          if (typeof value === "string" || typeof value === "number") {
            let comparison = "";
            let compClass = "";
            const keyLower = key.toLowerCase();

            if (['protein', 'carbohydrates', 'sugar', 'sugars'].includes(keyLower)) {
              const numVal = parseFloat(value);
              if (!isNaN(numVal)) {
                const result = checkLevel(keyLower, numVal);
                comparison = result.text;
                compClass = result.class;
              }
            }

            nutrientLines += `
              <div class="line">
                <strong>${key}</strong>: ${value} ${comparison ? `<span class="${compClass}">(${comparison})</span>` : ""}
              </div>
            `;
          }
        }

        return `
          <div class="label-container">
            ${imageUrl ? `<img src="${imageUrl}" class="product-image">` : ""}
            <div class="product-name">${productName}</div>
            <h2>Nutrition Facts</h2>
            <div><strong>${per100g}</strong></div>
            <div><strong>Serving Size:</strong> ${serving}</div>
            <div class="label-section">
              ${nutrientLines || "<div class='line italic'>Tidak ada data nutrisi tersedia</div>"}
            </div>
            <div class="label-footer">
              * Sumber data: ${source}
            </div>
          </div>
        `;
      }).join('');

      resultDiv.innerHTML = allHtml;
    }

    function fetchNutrition(param, value) {
      resultDiv.innerHTML = `<p>🔍 Mencari data untuk <b>${value}</b>...</p>`;
      scanAgainBtn.style.display = "block";

      fetch(`api.php?${param}=` + encodeURIComponent(value))
        .then(res => res.json())
        .then(json => displayNutrition(json))
        .catch(err => {
          resultDiv.innerHTML = `<p>❌ Gagal memuat data: ${err.message}</p>`;
        });
    }

    function searchByBarcode() {
      const code = document.getElementById("barcode-input").value.trim();
      if (!code) {
        alert("Masukkan kode barcode terlebih dahulu.");
        return;
      }
      fetchNutrition('barcode', code);
    }

    function searchByName() {
      const name = document.getElementById("name-input").value.trim();
      if (!name) {
        alert("Masukkan nama produk terlebih dahulu.");
        return;
      }
      fetchNutrition('nama', name);
    }

    async function tryOCR() {
      const video = document.getElementById('ocr-video');
      const canvas = document.getElementById('ocr-canvas');
      const context = canvas.getContext('2d');

      canvas.width = video.videoWidth;
      canvas.height = video.videoHeight;
      context.drawImage(video, 0, 0, canvas.width, canvas.height);
      const imageData = canvas.toDataURL("image/png");

      const result = await Tesseract.recognize(
        imageData,
        'eng',
        { logger: m => console.log(m) }
      );

      const text = result.data.text;
      const match = text.match(/\d{8,13}/);
      if (match) {
        fetchNutrition('barcode', match[0]);
      } else {
        resultDiv.innerHTML = "<p>❌ Tidak dapat mengenali angka barcode melalui OCR.</p>";
      }
    }

    function startScan() {
  if (isScanning) return;
  isScanning = true;
  html5QrCode = new Html5Qrcode("reader");

  Html5Qrcode.getCameras().then(cameras => {
    if (cameras && cameras.length) {
      currentCameraId = cameras[0].id;

      // Mulai barcode scanner
      html5QrCode.start(
        { deviceId: { exact: currentCameraId } },
        {
          fps: 30,
          qrbox: { width: 300, height: 150 },
          formatsToSupport: [
            Html5QrcodeSupportedFormats.QR_CODE,
            Html5QrcodeSupportedFormats.EAN_13,
            Html5QrcodeSupportedFormats.EAN_8,
            Html5QrcodeSupportedFormats.UPC_A,
            Html5QrcodeSupportedFormats.UPC_E,
            Html5QrcodeSupportedFormats.CODE_128,
            Html5QrcodeSupportedFormats.CODE_39,
            Html5QrcodeSupportedFormats.ITF,
            Html5QrcodeSupportedFormats.CODABAR
          ]
        },
        (decodedText, decodedResult) => {
          if (!isScanning) return;
          isScanning = false;
          html5QrCode.stop().then(() => {
            playBeep();
            fetchNutrition('barcode', decodedText);
          });
        },
        (error) => {
          // silent error
        }
      );

      // Jalankan OCR paralel dari video
      navigator.mediaDevices.getUserMedia({ video: { deviceId: currentCameraId } })
        .then(stream => {
          const videoElement = document.getElementById("ocr-video");
          videoElement.srcObject = stream;
          videoElement.play();

          const canvas = document.getElementById('ocr-canvas');
          const ctx = canvas.getContext('2d');

          function runOCRLoop() {
            if (!isScanning) return;

            canvas.width = videoElement.videoWidth;
            canvas.height = videoElement.videoHeight;
            ctx.drawImage(videoElement, 0, 0, canvas.width, canvas.height);
            const imageData = canvas.toDataURL("image/png");

            Tesseract.recognize(imageData, 'eng')
              .then(result => {
                const text = result.data.text;
                const match = text.match(/\d{8,13}/);
                if (match && isScanning) {
                  isScanning = false;
                  html5QrCode.stop().then(() => {
                    fetchNutrition('barcode', match[0]);
                  });
                } else {
                  setTimeout(runOCRLoop, 1500);  
                }
              })
              .catch(err => {
                console.error("OCR error:", err);
                setTimeout(runOCRLoop, 1500);
              });
          }

          runOCRLoop(); // mulai OCR loop
        });
    } else {
      resultDiv.innerHTML = "<p>❌ Kamera tidak tersedia.</p>";
    }
  }).catch(err => {
    resultDiv.innerHTML = `<p>❌ Gagal mengakses kamera: ${err.message}</p>`;
  });
}


    function restartScan() {
      resultDiv.innerHTML = "";
      scanAgainBtn.style.display = "none";
      if (html5QrCode) {
        html5QrCode.clear().then(startScan).catch(startScan);
      } else {
        startScan();
      }
    }

    window.onload = () => {
      startScan();
    };
  </script>
</body>
</html>

