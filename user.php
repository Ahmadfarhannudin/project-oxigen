<!DOCTYPE html>
<html lang="pt">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>PROGRAM GYM PROJECT OXIGEN</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #ffffffff;
      color: white;
      font-family: Arial, sans-serif;
    }
    .navbar-custom {
  background-color: #ffffffff;
  padding: 10px 20px;
}

.navbar-custom img {
  height: 40px;
  margin-right: 10px;
  border-radius: 5px;
}

.navbar-custom .nav-link {
  color: black !important;
  font-weight: bold;
}

.navbar-custom .nav-link:hover {
  color: red !important;
}

    .hero {
      background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0,0,0,0.7)),
                  url('public/member/foto/Gym2.jpg') no-repeat center center/cover;
      padding: 80px 0;
      text-align: center;
    }
    .hero h1 {
      color: white;
      font-weight: bold;
    }
    .stat-box {
      background-color: #000000ff;
      border-radius: 10px;
      padding: 15px;
      text-align: center;
      color: white;
      font-size: 1.5rem;
      font-weight: bold;
    }
    .program-section {
        background: black;
      padding: 80px 0;
      text-align: center;
      font-weight: bold;
    }
  .card-custom {
  display: flex;
  flex-direction: column;
  height: 100%;
  border-radius: 10px;
  overflow: hidden;
  box-shadow: 0 4px 8px rgba(0,0,0,0.2);
  transition: transform 0.2s ease-in-out;
  background: #fff;
  text-align: center;
}

.card-custom img {
  width: 100%;
  height: 180px; /* Sesuaikan tinggi gambar */
  object-fit: cover; /* Potong gambar agar proporsional */
}

.card-custom .card-text {
  flex-grow: 1;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 15px;
  font-weight: bold;
  font-size: 1.2rem;
  background-color: #ff0000ff;
  cursor: pointer;
}

.card-custom:hover {
  transform: translateY(-5px);
}

    .processo {
      background-color: #000000ff;
      padding: 40px;
      border-radius: 15px;
      margin-top: 50px;
    }
  </style>
</head>
<body>

  <!-- NAVBAR -->
  <nav class="navbar navbar-dark navbar-custom">
    <div class="container-fluid d-flex align-items-center">
      <!-- 4 Logo di pojok kiri -->
      <div class="d-flex align-items-center">
        <img src="public/member/foto/utb.png" alt="Logo 1">
        <img src="public/member/foto/Bem.png" alt="Logo 2">
        <img src="public/member/foto/Oxigen.png" alt="Logo 3">
        <img src="public/member/foto/Cuanki.png" alt="Logo 4">
      </div>
      <!-- Menu navigasi -->
      <ul class="navbar-nav ms-auto flex-row">
        <li class="nav-item me-3"><a class="nav-link text-white" href="#">Home</a></li>
        <li class="nav-item me-3"><a class="nav-link text-white" href="index.php">Scan Barcode</a></li>
        <li class="nav-item me-3"><a class="nav-link text-white" href="#">Instruktur</a></li>
        <li class="nav-item"><a class="nav-link text-white" href="#">Kontak</a></li>
      </ul>
    </div>
  </nav>

  <!-- HERO SECTION -->
  <section class="hero">
    <div class="container">
      <h1>GymMaster</h1>
      <p>Sistem manajemen gym lengkap untuk mengatur member, instruktur, jadwal kelas, dan pembayaran.</p>
      <form class="mt-4">
        <div class="row justify-content-center">
          <div class="col-md-3 mb-2">
            <input type="text" class="form-control" placeholder="Seu Nome">
          </div>
          <div class="col-md-3 mb-2">
            <input type="email" class="form-control" placeholder="Seu Email">
          </div>
          <div class="col-md-2 mb-2">
            <button class="btn btn-danger w-100">Enviar</button>
          </div>
        </div>
      </form>
    </div>
  </section>

  <!-- STATS -->
  <section class="container text-center my-5">
    <div class="row g-3">
      <div class="col-md-4">
        <div class="stat-box">+100</div>
      </div>
      <div class="col-md-4">
        <div class="stat-box">+10</div>
      </div>
      <div class="col-md-4">
        <div class="stat-box">+3</div>
      </div>
    </div>
  </section>

  <!-- PROGRAM -->
<section class="program-section">
  <div class="container">
    <h2 class="text-center mb-5">
      <span style="color: red; font-weight: bold;">DAFTAR LATIHAN</span> BAGIAN-BAGIAN TUBUH
    </h2>
    <div class="row g-4">

      <!-- Card 1 -->
      <div class="col-md-4">
        <div class="card-custom">
          <img src="public/member/foto/ototperut.jpg" alt="Otot Perut">
          <div class="card-text">OTOT PERUT</div>
        </div>
      </div>

      <!-- Card 2 -->
      <div class="col-md-4">
        <div class="card-custom">
          <img src="public/member/foto/ototlengan.jpg" alt="Otot Lengan">
          <div class="card-text">OTOT LENGAN</div>
        </div>
      </div>

      <!-- Card 3 -->
      <div class="col-md-4">
        <div class="card-custom">
          <img src="public/member/foto/ototdada.jpg" alt="Otot Dada">
          <div class="card-text">OTOT DADA</div>
        </div>
      </div>

      <!-- Card 4 -->
      <div class="col-md-4">
        <div class="card-custom">
          <img src="public/member/foto/ototpunggung.png" alt="Otot Punggung">
          <div class="card-text">OTOT PUNGGUNG</div>
        </div>
      </div>

      <!-- Card 5 -->
      <div class="col-md-4">
        <div class="card-custom">
          <img src="5.jpg" alt="Otot Bahu">
          <div class="card-text">OTOT BAHU</div>
        </div>
      </div>

      <!-- Card 6 -->
      <div class="col-md-4">
        <div class="card-custom">
          <img src="6.jpg" alt="Otot Kaki">
          <div class="card-text">OTOT KAKI</div>
        </div>
      </div>

    </div>
  </div>
</section>


  <!-- PROCESSO -->
  <section class="container">
    <div class="processo text-center">
      <h3>Coming Soon</h3>
      <p>StayTune Untuk fitur Selanjutnya cuyyyyyy</p>
    </div>
  </section>

</body>
</html>
