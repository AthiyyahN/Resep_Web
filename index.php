<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php"); // Jika belum login, arahkan ke halaman login
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resep Makanan</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-attachment: fixed;
            background-size: cover;
            background: linear-gradient(35deg, #F5E1C8,rgb(203, 185, 150));
            padding-top: 10px;
        }

        /* Navbar */
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: linear-gradient(to right, #8B0000, #5A3E36);
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .navbar .nav-link {
            color: white;
            font-weight: 600;
            transition: 0.3s;
            list-style: none;
            display: flex;
            gap: 15px;
        }

        .nav-links li a {
            text-decoration: none;
            color: black;
            font-weight: bold;
        }

        .navbar-nav .nav-link:hover,
        .navbar .nav-link.active {
            color: #000000;
            background-color: rgba(255, 255, 255, 0.3);
            border-radius: 5px;
            padding: 5px 10px;
        }

        .navbar-toggler {
            border-color: white;
        }

        .navbar-toggler-icon {
            background-color: white;
            border-radius: 5px;
            padding: 2px;
        }

        .search-bar {
            display: flex;
            align-items: center;
        }

        .search-bar input {
            padding: 8px;
            border: none;
            border-radius: 20px;
            width: 200px;
        }

        /* Agar tidak tertutup navbar */
        #home, #cari-resep {
            padding-top: 90px;
        }

        .carousel {
            margin-top: 0 !important;
            margin: 0 auto;
            padding: 0;
        }

        .carousel-inner img {
            width: 100%;
            height: 500px;
            object-fit: cover;
        }

        .carousel-img {
            width: 100%;
            height: 100vh;
            object-fit: cover;
        }

        #homeCarousel .carousel-inner, #homeCarousel .carousel-item {
            height: 85vh;
        }

        /* Daftar Resep */
        #recipes-list {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(190px, 1fr)); /* 4 kolom pada layar besar */
            gap: 20px;
            justify-content: center;
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .recipe {
            border: 1px solid #ddd;
            padding: 10px;
            cursor: pointer;
            width: 200px;
            text-align: center;
            border-radius: 5px;
        }

        .recipe-card {
            width: 250px; /* Ukuran kartu seragam */
            min-height: 350px; /* Mencegah tinggi kartu berubah */
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .recipe-card img {
            height: 200px;
            object-fit: cover;
        }

        .card-body {
            padding: 15px;
            text-align: center;
        }

        .badge {
            font-size: 14px;
            margin-right: 5px;
        }

        .btn-primary {
            width: 100%;
        }

        .recipe:hover {
            transform: scale(1.05);
            transition: 0.3s;
        }

        .hidden {
            display: none;
        }

        #recipe-detail {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: white;
            padding: 20px;
            border: 1px solid #ddd;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.3);
            border-radius: 8px;
            width: 90%;
            max-width: 500px;
            max-height: 60vh;
            overflow-y: auto;
        }

        #recipe-detail.hidden {
            display: none;
        }

        #recipe-detail:not(.hidden) {
            display: block;
        }

        /* Halaman Tentang Kami & Komentar */
        #tentang, #komentar {
            padding: 60px;
            background-color: #f8f9fa;
            text-align: center;
        }

        .container {
            max-width: 800px;
            margin: auto;
            text-align: left;
        }

        .comment-box {
            margin-top: 20px;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: white;
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container">
            <a class="navbar-brand text-white" href="#">Cookvana</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="#home">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#tentang">Tentang</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#resep">Resep</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#komentar">Komentar/Saran</a>
                    </li>

                    <?php if (isset($_SESSION['username'])): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                            <?= $_SESSION['username']; ?>
                        </a>
                        <ul class="dropdown-menu">
                        <li>
                        <a class="dropdown-item" href="#profile">Profile</a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="logout.php">Logout</a>
                    </li>
                </ul>
                </li>
                <?php else: ?>
                    <li class="nav-item"><a class="nav-link" href="#login">Login/Daftar</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<!-- Home Section dengan Carousel -->
<section id="home">
    <div id="homeCarousel" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="home4.jpg" class="d-block w-100 carousel-img" alt="Makanan 1">
                <div class="carousel-caption">
                    <h1>Hari ini mau masak apa?</h1>
                    <p>Yuk cari resep makanan favoritmu di sini!</p>
                </div>
            </div>
            <div class="carousel-item">
                <img src="Nasi_Padang.jpg" class="d-block w-100 carousel-img" alt="Makanan 2">
                <div class="carousel-caption">
                    <h1>Temukan Inspirasi Memasak</h1>
                    <p>Resep mudah dan lezat untuk setiap hari.</p>
                </div>
            </div>
            <div class="carousel-item">
                <img src="download (1).jpg" class="d-block w-100 carousel-img" alt="Makanan 3">
                <div class="carousel-caption">
                    <h1>Masak dengan Cinta</h1>
                    <p>Buat hidangan spesial untuk keluarga tercinta.</p>
                </div>
            </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#homeCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#homeCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>
</section>

    <!-- Resep Section -->
    <section id="resep">
        <div class="container text-center">
            <h2>Resep Terkini</h2>
            <input type="text" id="searchInputResep" class="form-control mb-3" placeholder="Cari Resep..." onkeyup="searchRecipe()">
            <div class="row" id="recipes-list">
                <div class="col-md-4 mb-4">
                    <div class="card recipe-card">
                        <img src="nasi_goreng.jpg" class="card-img-top" alt="Nasi Goreng">
                        <div class="card-body text-start">
                            <h3 class="recipe-title">Nasi Goreng</h3>
                            <p class="badge bg-warning text-dark">⏳ 30mnt</p>
                            <p class="badge bg-success">🍽 Mudah</p>
                            <button class="btn btn-primary mt-2" onclick="showRecipe('Nasi Goreng')">Lihat Resep</button>
                        </div>
                    </div>
                </div>
    
                <div class="col-md-4 mb-4">
                    <div class="card recipe-card">
                        <img src="soto_ayam.jpg" class="card-img-top" alt="Soto Ayam">
                        <div class="card-body text-start">
                            <h3 class="recipe-title">Soto Ayam</h3>
                            <p class="badge bg-warning text-dark">⏳ 1j 30mnt</p>
                            <p class="badge bg-danger">🍽 Sedang</p>
                            <button class="btn btn-primary mt-2" onclick="showRecipe('Soto Ayam')">Lihat Resep</button>
                        </div>
                    </div>
                </div>
    
                <div class="col-md-4 mb-4">
                    <div class="card recipe-card">
                        <img src="ayam_pedas.jpg" class="card-img-top" alt="Ayam Bumbu Pedas">
                        <div class="card-body text-start">
                            <h3 class="recipe-title">Ayam Bumbu Pedas</h3>
                            <p class="badge bg-warning text-dark">⏳ 45jam</p>
                            <p class="badge bg-success">🍽 Mudah</p>
                            <button class="btn btn-primary mt-2" onclick="showRecipe('Ayam Bumbu Pedas')">Lihat Resep</button>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 mb-4">
                    <div class="card recipe-card">
                        <img src="brownies.jpg" class="card-img-top" alt="Fudgy Brownies">
                        <div class="card-body text-start">
                            <h3 class="recipe-title">Fudgy Brownies</h3>
                            <p class="badge bg-warning text-dark">⏳ 1j</p>
                            <p class="badge bg-success">🍽 Mudah</p>
                            <button class="btn btn-primary mt-2" onclick="showRecipe('Fudgy Brownies')">Lihat Resep</button>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 mb-4">
                    <div class="card recipe-card">
                        <img src="pancake.jpg" class="card-img-top" alt="Pancake">
                        <div class="card-body text-start">
                            <h3 class="recipe-title">Pancake</h3>
                            <p class="badge bg-warning text-dark">⏳ 25mnt</p>
                            <p class="badge bg-success">🍽 Mudah</p>
                            <button class="btn btn-primary mt-2" onclick="showRecipe('Pancake')">Lihat Resep</button>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 mb-4">
                    <div class="card recipe-card">
                        <img src="coffe_latte.jpg" class="card-img-top" alt="Coffee Latte">
                        <div class="card-body text-start">
                            <h3 class="recipe-title">Coffee Latte</h3>
                            <p class="badge bg-warning text-dark">⏳ 25mnt</p>
                            <p class="badge bg-success">🍽 Mudah</p>
                            <button class="btn btn-primary mt-2" onclick="showRecipe('Coffee Latte')">Lihat Resep</button>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 mb-4">
                    <div class="card recipe-card">
                        <img src="es_kuwut.jpg" class="card-img-top" alt="Es Kuwut">
                        <div class="card-body text-start">
                            <h3 class="recipe-title">Es Kuwut</h3>
                            <p class="badge bg-warning text-dark">⏳ 35mnt</p>
                            <p class="badge bg-success">🍽 Mudah</p>
                            <button class="btn btn-primary mt-2" onclick="showRecipe('Es Kuwut')">Lihat Resep</button>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 mb-4">
                    <div class="card recipe-card">
                        <img src="opor_ayam.jpg" class="card-img-top" alt="Opor Ayam">
                        <div class="card-body text-start">
                            <h3 class="recipe-title">Opor Ayam</h3>
                            <p class="badge bg-warning text-dark">⏳ 1j 35mnt</p>
                            <p class="badge bg-danger">🍽 Sedang</p>
                            <button class="btn btn-primary mt-2" onclick="showRecipe('Opor Ayam')">Lihat Resep</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
            <div id="recipe-detail" class="hidden">
        <h2 id="recipe-title"></h2>
        <p id="recipe-description"></p>
        <button onclick="closeRecipe()" class="btn btn-danger mt-2">Tutup</button>
        </div>
    </section>

    <!-- Tentang Kami Section -->
    <section id="tentang">
        <div class="container">
            <h2>Tentang Kami</h2>
            <p>Kami hadir untuk menginspirasi banyak orang dengan berbagi resep lezat yang mudah diikuti. Misi kami adalah menjadikan memasak lebih sederhana dan menyenangkan bagi siapa saja. Dengan berbagi resep dan tips memasak, kami ingin mendukung dan membangun  eksplorasi kuliner. Mari memasak dengan cinta dan ciptakan momen spesial disetiap hidangan!</p>        </div>
    </section>

    <!-- Komentar Section -->
    <section id="komentar">
    <div class="container">
        <h2>Berikan Komentar/Saran</h2>
        <div class="comment-box">
            <form id="commentForm" method="POST" action="tambah_komentar.php">
                <div class="mb-3">
                    <label for="comment" class="form-label">Komentar/Saran</label>
                    <textarea class="form-control" name="komentar" rows="3" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Kirim</button>
            </form>
        </div>

        <h3 class="mt-4">Komentar:</h3>
        <?php
        include 'koneksi.php';
        $result = $conn->query("SELECT * FROM komentar ORDER BY created_at DESC");
        while ($row = $result->fetch_assoc()) {
            echo "<div class='card p-2 my-2'>
                    <p>{$row['komentar']}</p>
                    <small class='text-muted'>Dikirim pada: {$row['created_at']}</small>
                    <a href='edit_komentar.php?id={$row['id']}' class='btn btn-warning btn-sm'>Edit</a>
                    <a href='hapus_komentar.php?id={$row['id']}' class='btn btn-danger btn-sm'>Hapus</a>
                  </div>";
        }
        ?>
    </div>
</section>


    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.querySelectorAll('.nav-link').forEach(link => {
            link.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                target.scrollIntoView({ behavior: 'smooth' });
            });
        });
    </script>
    
    <!-- JavaScript -->
    <script>
        const recipes = {
            "Nasi Goreng": `
                <strong>Bahan-bahan:</strong>
                <ul>
                    <li>1 piring nasi putih</li>
                    <li>1 butir telur</li>
                    <li>2 siung bawang putih, cincang halus</li>
                    <li>1 batang daun bawang, iris tipis</li>
                    <li>1 sdm kecap manis</li>
                    <li>1 sdt saus tiram (opsional)</li>
                    <li>½ sdt garam</li>
                    <li>¼ sdt merica bubuk</li>
                    <li>1 sdm minyak goreng</li>
                </ul>
                <strong>Cara Membuat:</strong>
                <ol>
                    <li>Panaskan minyak di wajan, tumis bawang putih hingga harum.</li>
                    <li>Masukkan telur, orak-arik hingga matang.</li>
                    <li>Masukkan nasi putih, aduk hingga rata dengan telur.</li>
                    <li>Tambahkan kecap manis, saus tiram, garam, dan merica. Aduk rata.</li>
                    <li>Masukkan daun bawang, aduk sebentar hingga layu.</li>
                    <li>Angkat dan sajikan nasi goreng selagi hangat.</li>
                </ol>
            `,
            "Soto Ayam": `
                <strong>Bahan-bahan:</strong>
                <ul>
                    <li>500 gram daging ayam</li>
                    <li>1,5 liter air</li>
                    <li>2 batang serai, memarkan</li>
                    <li>3 lembar daun jeruk</li>
                    <li>2 lembar daun salam</li>
                    <li>3 siung bawang putih, haluskan</li>
                    <li>5 butir bawang merah, haluskan</li>
                    <li>1 ruas kunyit, haluskan</li>
                    <li>1 sdt garam</li>
                    <li>½ sdt merica bubuk</li>
                    <li>Minyak goreng secukupnya</li>
                    <li>Soun, kol, tauge, dan telur rebus (pelengkap)</li>
                </ul>
                <strong>Cara Membuat:</strong>
                <ol>
                    <li>Rebus ayam dengan air hingga matang, angkat dan suwir-suwir dagingnya.</li>
                    <li>Panaskan sedikit minyak, tumis bawang putih, bawang merah, dan kunyit hingga harum.</li>
                    <li>Masukkan serai, daun jeruk, dan daun salam, aduk rata.</li>
                    <li>Tuangkan bumbu tumisan ke dalam air rebusan ayam, tambahkan garam dan merica.</li>
                    <li>Masak hingga kuah mendidih dan bumbu meresap.</li>
                    <li>Siapkan mangkuk, tata soun, kol, tauge, dan ayam suwir. Siram dengan kuah soto.</li>
                    <li>Sajikan dengan telur rebus, jeruk nipis, dan sambal jika suka.</li>
                </ol>
            `,
            "Ayam Bumbu Pedas": `
                <strong>Bahan-bahan:</strong>
                <ul>
                    <li>500 gram sayap ayam</li>
                    <li>1 sachet Desaku Marinasi</li>
                    <li>1 sachet Desaku Bawang Putih Bubuk</li>
                    <li>1/2 sendok makan Ladaku Merica Bubuk</li>
                    <li>2 sendok makan Maizena</li>
                    <li>1 sendok makan Desaku Cabe Bubuk</li>
                    <li>1 butir telur</li>
                </ul>
                <strong>Cara Membuat:</strong>
                <ol>
                    <li>Campur semua bahan di dalam kontainer, kocok hingga tercampur rata.</li>
                    <li>Diamkan minimal 30 menit.</li>
                    <li>Goreng sayap ayam pada minyak yang sudah panas dengan api kecil selama 7 menit.</li>
                </ol>
            `,
            "Fudgy Brownies": `
                <strong>Bahan-bahan:</strong>
                <ul>
                    <li>200 g dark chocolate (potong-potong)</li>
                    <li>150 g mentega tawar</li>
                    <li>250 g gula pasir</li>
                    <li>3 butir telur</li>
                    <li>1 sdt ekstrak vanili</li>
                    <li>100 g tepung terigu serbaguna</li>
                    <li>30 g cokelat bubuk</li>
                    <li>Sejumput garam</li>
                </ul>
                <strong>Cara Membuat:</strong>
                <ol>
                    <li>Panaskan oven pada suhu 175°C. Lapisi loyang (ukuran 20×20 cm) dengan kertas roti.</li>
                    <li>Lelehkan dark chocolate dan mentega dengan metode double boiler hingga tercampur rata.</li>
                    <li>Angkat, lalu tambahkan gula dan aduk rata. Masukkan telur satu per satu beserta ekstrak vanili.</li>
                    <li>Ayam tepung terigu, cokelat bubuk, dan garam ke dalam adonan. Aduk perlahan hingga tercampur (jangan overmix).</li>
                    <li>Tuangkan adonan ke dalam loyang dan ratakan permukaannya. Panggang selama 20–25 menit; bagian tepi agak mengeras sementara tengahnya masih lembab.</li>
                    <li>Dinginkan, potong-potong, dan sajikan.</li>
                </ol>
            `,
            "Pancake": `
                <strong>Bahan-bahan:</strong>
                <ul>
                    <li>200 g tepung terigu serbaguna</li>
                    <li>2 sdm gula pasir</li>
                    <li>2 sdt baking powder</li>
                    <li>Sejumput garam</li>
                    <li>250 ml susu cair</li>
                    <li>1 butir telur</li>
                    <li>2 sdm mentega cair (plus tambahan untuk menggoreng)</li>
                    <li>1 sdt ekstrak vanili (opsional)</li>
                </ul>
                <strong>Cara Membuat:</strong>
                <ol>
                    <li>Campurkan bahan kering: tepung, gula, baking powder, dan garam.</li>
                    <li>Di mangkuk terpisah, kocok telur, susu, mentega cair, dan ekstrak vanili.</li>
                    <li>Tuangkan bahan cair ke dalam bahan kering, aduk perlahan hingga tercampur (hindari overmix agar pancake tetap lembut).</li>
                    <li>Panaskan wajan anti lengket dengan sedikit mentega. Tuang sekitar 1/4 cangkir adonan per pancake.</li>
                    <li>Masak hingga muncul gelembung di permukaan dan pinggiran mulai kering, balik, dan masak sisi lainnya hingga berwarna keemasan.</li>
                    <li>Sajikan hangat dengan sirup maple, madu, buah segar, atau topping sesuai selera.</li>
                </ol>
            `,
            "Coffee Latte": `
                <strong>Bahan-bahan:</strong>
                <ul>
                    <li>1 shot espresso (sekitar 30 ml) atau 60 ml kopi yang diseduh sangat kuat</li>
                    <li>200 ml susu segar</li>
                    <li>Gula secukupnya (opsional)</li>
                    <li>Bubuk cokelat atau kayu manis untuk taburan (opsional)</li>
                </ul>
                <strong>Cara Membuat:</strong>
                <ol>
                    <li>Siapkan kopi dengan menyeduh espresso atau kopi kuat.</li>
                    <li>Panaskan susu hingga hampir mendidih, lalu buihkan menggunakan steam wand atau dengan dikocok hingga berbusa.</li>
                    <li>Tuangkan kopi ke dalam cangkir, kemudian tambahkan susu panas beserta busanya di atasnya.</li>
                    <li>Jika diinginkan, tambahkan gula dan taburi dengan bubuk cokelat atau kayu manis. Sajikan hangat.</li>
                </ol>
            `,
            "Es Kuwut": `
                <strong>Bahan Kuah Santan:</strong>
                <ul>
                    <li>200 ml santan (dari kelapa parut)</li>
                    <li>100 ml air</li>
                    <li>50 g gula pasir (sesuai selera)</li>
                    <li>1 lembar daun pandan (opsional)</li>
                    <li>Sejumput garam</li>
                </ul>
                <strong>Bahan Topping:</strong>
                <ul>
                    <li>Es serut secukupnya</li>
                    <li>Potongan buah (misalnya: alpukat, nangka, kelapa muda)</li>
                    <li>Cincau atau agar-agar (dipotong dadu)</li>
                    <li>Kacang merah rebus (opsional)</li>
                </ul>
                <strong>Cara Membuat:</strong>
                <ol>
                    <li>Campurkan santan, air, gula, dan garam dalam panci; masukkan daun pandan jika digunakan. Masak dengan api kecil sambil terus diaduk hingga gula larut dan kuah sedikit mengental. Dinginkan.</li>
                    <li>Siapkan mangkuk saji dan tata potongan buah, cincau/agar-agar, serta kacang merah sesuai selera.</li>
                    <li>Masukkan es serut ke dalam mangkuk, lalu siram dengan kuah santan manis yang sudah dingin.</li>
                    <li>Sajikan sebagai pencuci mulut yang menyegarkan.</li>
                </ol>
                `,
            "Opor Ayam": `
                <strong>Bahan-bahan:</strong>
                <ul>
                    <li>1 ekor ayam utuh, potong 8 bagian, buang kulitnya</li>
                    <li>1 liter santan encer</li>
                    <li>200 ml santan kental</li>
                    <li>300 ml susu rendah lemak</li>
                    <li>4 buah daun jeruk purut segar biarkan utuh</li>
                    <li>1 gram daun kunyit (kira-kira 1 lembar)</li>
                    <li>1 gram (kira-kira seruas ibu jari) lengkuas, memarkan</li>
                    <li>1 batang serai memarkan</li>
                    <li>1 sdm Royco kaldu ayam</li>
                    <li>2 sdm bawang goreng</li>
                    <li>Bumbu Halus: 3 buah bawang merah</li>
                    <li>Bumbu Halus: 6 siung bawang putih</li>
                    <li>Bumbu Halus: 1 gram kencur (kira-kira seruas jari), memarkan</li>
                    <li>Bumbu Halus: 1 sdt merica putih bubuk</li>
                    <li>Bumbu Halus: 1 sdt ketumbar butiran</li>
                    <li>Bumbu Halus: 5 buah kemiri, sangrai</li>
                    <li>Bumbu Halus: 2 sdt garam</li>
                    <li>Bumbu Halus: 1 sdt gula pasir</li>
                </ul>
                <strong>Cara Membuat:</strong>
                <ol>
                    <li>Aduk rata daging ayam dengan bumbu halus, lalu biarkan selama sekitar 15 menit hingga bumbu meresap.</li>
                    <li>Rebus santan encer dan susu rendah lemak, masukkan Royco Kaldu Ayam dan semua bahan kecuali santan kental. Masak di atas api kecil sambil sesekali diaduk agar santan tidak pecah.</li>
                    <li>Tuangkan bahan cair ke dalam bahan kering, aduk perlahan hingga tercampur (hindari overmix agar pancake tetap lembut).</li>
                    <li>Masukkan santan kental, aduk perlahan hingga ayam empuk. Angkat.</li>
                    <li>Sajikan dengan taburan bawang merah goreng.</li>
                </ol>
            `
        };
    
        function showRecipe(name) {
            document.getElementById('recipe-title').innerText = name;
            document.getElementById('recipe-description').innerHTML = recipes[name]; // Gunakan innerHTML agar daftar muncul dengan benar
            document.getElementById('recipe-detail').classList.remove('hidden');
        }
    
        function closeRecipe() {
            document.getElementById('recipe-detail').classList.add('hidden');
        }
    
        function searchRecipe() {
        let inputResep = document.getElementById('searchInputResep').value.toLowerCase();
        let recipeItems = document.querySelectorAll('#recipes-list .col-md-4'); // Ambil elemen container resep

        recipeItems.forEach(item => {
            let titleElement = item.querySelector('.recipe-title');
            if (titleElement) {
                let title = titleElement.innerText.toLowerCase();
                if (title.includes(inputResep)) {
                    item.style.display = "grid"; // Gunakan flex agar tetap sesuai grid Bootstrap
                } else {
                    item.style.display = "none";
                }
            }
        });
    }
    </script>
</body>
</html>