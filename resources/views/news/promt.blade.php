<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Berita Builder by Sipitchan - 5W 1H</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(135deg, #f3f4f6, #ffffff);
    }
    .card-title {
      font-weight: bold;
      color: #0d6efd;
    }
    .btn:disabled {
      cursor: not-allowed;
    }
  </style>
</head>
<body class="py-4">
<div class="container">
  <h2 class="mb-4 text-center">📰 Form Pembuatan Berita Otomatis by Sipitchan</h2>

  <!-- Info Berita -->
  <div class="card shadow-sm mb-4">
    <div class="card-body">
      <h5 class="card-title">Info Berita</h5>
      <div class="mb-3">
        <label for="kategori" class="form-label">Kategori Berita</label>
        <select class="form-select" id="kategori" required>
          <option selected disabled value="">-- Pilih Kategori --</option>
          <option>Berita Pencapaian dan Prestasi</option>
          <option>Berita Program</option>
          <option>Berita Kegiatan</option>
          <option>Berita Mendalam</option>
        </select>
      </div>
      <div class="mb-3">
        <label for="gaya" class="form-label">Gaya Lead</label>
        <select class="form-select" id="gaya" required>
          <option selected disabled value="">-- Pilih Gaya Lead --</option>
          <option>Lead Ringkasan (Summary Lead)</option>
          <option>Lead Langsung (Straight News Lead)</option>
          <option>Lead Kutipan (Quotation Lead)</option>
          <option>Lead Pertanyaan (Question Lead)</option>
          <option>Lead Deskriptif (Desscripptive Lead)</option>
          <option>Lead Naratif (Narrative Lead)</option>
        </select>
      </div>
    </div>
  </div>

  <!-- Materi Berita -->
  <div class="card shadow-sm mb-4">
    <div class="card-body">
      <h5 class="card-title">Materi Berita (5W + 1H)</h5>
      <div class="row g-3">
        <div class="col-md-6">
          <label class="form-label">What</label>
          <input type="text" class="form-control" id="what" required>
        </div>
        <div class="col-md-6">
          <label class="form-label">Who</label>
          <input type="text" class="form-control" id="who" required>
        </div>
        <div class="col-md-6">
          <label class="form-label">When</label>
          <input type="text" class="form-control" id="when" required>
        </div>
        <div class="col-md-6">
          <label class="form-label">Where</label>
          <input type="text" class="form-control" id="where" required>
        </div>
        <div class="col-md-6">
          <label class="form-label">Why</label>
          <input type="text" class="form-control" id="why" required>
        </div>
        <div class="col-md-6">
          <label class="form-label">How</label>
          <input type="text" class="form-control" id="how" required>
        </div>
      </div>
    </div>
  </div>

  <!-- Narasumber -->
  <div class="card shadow-sm mb-4">
    <div class="card-body">
      <h5 class="card-title">Jumlah Narasumber</h5>
      <div class="mb-3">
        <input type="number" class="form-control" id="jumlahNarsum" placeholder="Masukkan jumlah narasumber">
      </div>
      <div id="formNarsum"></div>
    </div>
  </div>

  <!-- Tombol Generate -->
  <div class="d-grid">
    <button id="submitBtn" class="btn btn-primary" onclick="generateLink()" disabled>Kirim ke ChatGPT</button>
  </div>
</div>

<script>
function createNarsumForms(jumlah) {
  const container = document.getElementById('formNarsum');
  container.innerHTML = '';
  for (let i = 1; i <= jumlah; i++) {
    container.innerHTML += `
      <div class="border rounded p-3 mb-3">
        <h6>Narasumber ${i}</h6>
        <input type="text" class="form-control mb-2" placeholder="Nama" id="narsumNama${i}">
        <input type="text" class="form-control mb-2" placeholder="Jabatan" id="narsumJabatan${i}">
        <textarea class="form-control" placeholder="Kutipan" id="narsumKutipan${i}"></textarea>
      </div>
    `;
  }
}

document.getElementById('jumlahNarsum').addEventListener('input', function() {
  const jumlah = parseInt(this.value);
  if (!isNaN(jumlah) && jumlah > 0) {
    createNarsumForms(jumlah);
  }
});

function encode(val) {
  return encodeURIComponent(val || '');
}

function validateForm() {
  const fields = ['kategori', 'gaya', 'what', 'who', 'when', 'where', 'why', 'how'];
  return fields.every(id => document.getElementById(id).value.trim() !== '');
}

const inputEls = document.querySelectorAll('#kategori, #gaya, #what, #who, #when, #where, #why, #how');
inputEls.forEach(el => el.addEventListener('input', () => {
  document.getElementById('submitBtn').disabled = !validateForm();
}));

function generateLink() {
  const kategori = document.getElementById('kategori').value;
  const gaya = document.getElementById('gaya').value;
  const what = document.getElementById('what').value;
  const who = document.getElementById('who').value;
  const when = document.getElementById('when').value;
  const where = document.getElementById('where').value;
  const why = document.getElementById('why').value;
  const how = document.getElementById('how').value;

  let prompt = `Saya adalah seorang jurnalis profesional media online. Buatkan narasi berita lengkap dan utuh tanpa pembagian bagian. Paragraf pertama wajib mencerminkan kategori berita ${kategori} menggunakan gaya penulisan ${gaya} sesuai kaidah jurnalisme. Gunakan gaya penulisan aktif, informatif, dan langsung ke pokok informasi.\n\nGunakan prinsip 5W + 1H, dengan penekanan pada Why dan How untuk memperluas narasi menjadi deskriptif dan kontekstual. Narasi harus mengalir alami seperti layaknya artikel berita media profesional nasional.\n\nBerikut adalah informasi dasar untuk ditulis:\nWhat: ${what}\nWho: ${who}\nWhen: ${when}\nWhere: ${where}\nWhy: ${why}\nHow: ${how}\n\n(jangan terlihat seperti tulisan AI)`;

  const jumlah = parseInt(document.getElementById('jumlahNarsum').value);
  for (let i = 1; i <= jumlah; i++) {
    const nama = document.getElementById(`narsumNama${i}`).value;
    const jabatan = document.getElementById(`narsumJabatan${i}`).value;
    const kutipan = document.getElementById(`narsumKutipan${i}`).value;
    prompt += `${nama} ${jabatan} menyatakan: \"${kutipan}\"\n`;
  }

  const finalLink = `https://chatgpt.com/?prompt=${encode(prompt)}`;
  window.open(finalLink, '_blank');
}
</script>

</body>
</html>
