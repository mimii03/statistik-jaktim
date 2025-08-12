<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Pilih Kategori Data</title>
  <link rel="stylesheet" href="style.css">
  <style>
    * {
      box-sizing: border-box;
      font-family: Arial, sans-serif;
    }

    body {
      margin: 0;
      padding: 0;
      background-color: #f4f4f4;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      height: 100vh;
    }

    h1 {
      margin-bottom: 20px;
    }

    .button {
      background-color: #2980b9;
      color: white;
      padding: 15px 30px;
      text-decoration: none;
      border-radius: 5px;
      margin: 10px;
      transition: background-color 0.3s;
    }

    .button:hover {
      background-color: #1c598a;
    }

    select {
      padding: 10px;
      margin: 10px;
    }
  </style>
</head>
<body>

  <h1>Pilih Kategori Data</h1>

  <label for="kategori">Pilih Kategori Data:</label>
  <select id="kategori">
    <option value="">-- Pilih Kategori --</option>
    <option value="pendidikan">📚 Pendidikan</option>
    <option value="kependudukan">🧑‍🤝‍🧑 Kependudukan</option>
    <option value="ekonomi">💼 Ekonomi</option>
    <option value="kesehatan">🏥 Kesehatan</option>
  </select>

  <button onclick="redirectToData()" class="button">Tampilkan Data</button>

  <script>
    function redirectToData() {
      const kelurahan = new URLSearchParams(window.location.search).get('kelurahan');
      const kategori = document.getElementById("kategori").value;

      if (kelurahan && kategori) {
        window.location.href = `${kategori}.php?kelurahan=${kelurahan}`;
      } else {
        alert("Silakan pilih kategori data.");
      }
    }
  </script>

</body>
</html>
