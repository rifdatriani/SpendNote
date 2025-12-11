<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>SpendNote</title>
    <style>
        body { 
            margin: 0; 
            font-family: Arial, sans-serif; 
        }

        /* NAVBAR */
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 40px;
            background: #fff;
            border-bottom: 1px solid #eee;
            width: 90%;
        }

        .navbar img {
            height: 300px; /* Ukuran logo besar sesuai desain */
            height: auto;
            max-height: 60px; /* <= ini kunci utama */
        }

        .btn-login, .btn-register {
            padding: 12px 22px;
            border-radius: 8px;
            border: none;
            font-size: 16px;
            cursor: pointer;
        }

        .btn-login {
            background: #e3e3e3;
        }

        .btn-register {
            background: #5e2ea6;
            color: #fff;
        }

        /* HERO */
        .hero {
            display: flex;
            padding: 60px 80px;
            align-items: center;
            justify-content: space-between;
            background: #fafafa;
            width: 88%;
        }

        .hero h1 {
            font-size: 39px;
            font-weight: bold;
            line-height: 1.3;
            margin-bottom: 25px;
        }

        .btn-primary {
            background: #f4a261;
            padding: 15px 31px;
            border: none;
            color: #fff;
            border-radius: 10px; /* button  "mulai sekarang* */
            font-size: 18px;
            cursor: pointer;
        }

        .hero img { 
            max-width: 100%;
            height: auto;   
            margin-left: 1%; /* gambar akan geser ke kanan */
            display: block;
        }

        /* FEATURES */
        .features {
            background: #e9e9e9;
            padding: 60px;
            width: 91%;
        }

        .features h2 {
            text-align: center;
            font-size: 32px;
            margin-bottom: 50px;
        }

        .feature-container {
            display: flex;
            justify-content: center;
            gap: 40px;
        }

        .feature-card {
            background: #fff;
            padding: 30px;
            border-radius: 20px;
            width: 300px;
            text-align: center;
            box-shadow: 0px 4px 12px rgba(0,0,0,0.1);
        }

        .feature-card img {
            width: 200px;
            margin-bottom: 15px;
        }

        /* RESPONSIVE */
        @media (max-width: 900px) {
            .hero {
                flex-direction: column;
                text-align: center;
            }

            .hero img {
                width: 65%;
                margin-top: 30px;
            }

            .feature-container {
                flex-direction: column;
                align-items: center;
            }
        }
    </style>
</head>

<body>

    <!-- NAVBAR -->
    <!-- NAVBAR -->
    <div class="navbar">
        <img src="/assets/logo.png" alt="SpendNote Logo">

        <div>
            <button class="btn-login" onclick="window.location.href='/login'">Login</button>
            <button class="btn-register" onclick="window.location.href='/register'">Sign Up</button>
        </div>
    </div>


    <!-- HERO SECTION -->
    <div class="hero">
        <div class="left">
            <h1>CATAT Pengeluaranmu,<br>KONTROL Keuanganmu.</h1>
            <button class="btn-primary">Mulai Sekarang</button>
        </div>

        <div class="right">
            <img src="/assets/org.png" alt="Hero Image">
        </div>
    </div>

    <!-- FEATURES SECTION -->
    <div class="features">
        <h2>Mengapa Memilih SpendNote?</h2>

        <div class="feature-container">

            <div class="feature-card">
                <img src="/assets/Pencatatan.png">
                <h3>Pencatatan Mudah</h3>
                <p>SpendNote memudahkan kamu mencatat setiap pengeluaran dengan cepat dan rapi.</p>
            </div>

            <div class="feature-card">
                <img src="/assets/Analysis.png">
                <h3>Analisis Mendalam</h3>
                <p>Grafik otomatis dan ringkasan keuangan.Dapatkan gambaran jelas tentang pola keuanganmu melalui grafik dan ringkasan otomatis.</p>
            </div>

            <div class="feature-card">
                <img src="/assets/Tujuan.png">
                <h3>Tujuan Finansial</h3>
                <p>Buat dan pantau tujuan keuangan agar kamu lebih teratur dalam mengatur uang.</p>
            </div>

        </div>
    </div>

</body>
</html>
