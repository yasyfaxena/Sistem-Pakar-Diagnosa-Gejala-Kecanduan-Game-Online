<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIPAGON - Sistem Pakar Diagnosa Kecanduan Game Online</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background: #f0f8ff;
            color: #333;
        }

        .navbar {
            background: linear-gradient(to right, #0B2F9F, rgb(57, 99, 227));
            padding: 1rem 2rem;
            position: fixed;
            width: 100%;
            z-index: 1000;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
        }

        .nav-container {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 1rem;
            color: white;
            text-decoration: none;
            margin-left: -15px;
        }

        .brand img {
            height: 50px;
        }

        .brand-text {
            display: flex;
            flex-direction: column;
        }

        .brand-text .title {
            font-size: 24px;
            font-weight: bold;
            line-height: 1.2;
        }

        .brand-text .subtitle {
            font-size: 14px;
            opacity: 0.9;
            margin-top: -2px;
        }

        .nav-buttons {
            display: flex;
            gap: 1rem;
        }

        .btn {
            padding: 0.8rem 1.5rem;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-login {
            background: rgba(255, 255, 255, 0.1);
            color: white;
        }

        .btn-register {
            background: white;
            color: #0B2F9F;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .hero {
            padding-top: 120px;
            min-height: 100vh;
            background: linear-gradient(135deg, #f0f8ff 0%, #e6f3ff 100%);
        }

        .hero-content {
            max-width: 800px;
            margin: 0 auto;
            padding: 2rem;
            text-align: center;
        }

        .hero-text h1 {
            font-size: 3.5rem;
            margin-bottom: 1.5rem;
            color: #0B2F9F;
            line-height: 1.2;
        }

        .hero-text p {
            font-size: 1.1rem;
            color: #666;
            margin-bottom: 2rem;
            line-height: 1.6;
        }

        .cta-buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
            margin-bottom: 4rem;
        }

        .btn-primary {
            background: linear-gradient(45deg, #0B2F9F, rgb(57, 99, 227));
            color: white;
            padding: 1rem 2rem;
        }

        .features {
            display: flex;
            justify-content: center;
            gap: 2rem;
            margin: 0 auto;
            max-width: 1200px;
            padding: 0 1rem;
        }

        .feature-card {
            background: white;
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            text-align: center;
            transition: transform 0.3s ease;
            width: calc(33.333% - 1.33rem);
            min-width: 250px;
        }

        .feature-card:hover {
            transform: translateY(-5px);
        }

        .feature-icon {
            font-size: 2.5rem;
            color: #0B2F9F;
            margin-bottom: 1rem;
        }

        .feature-card h3 {
            margin-bottom: 1rem;
            color: #0B2F9F;
        }

        .feature-card p {
            color: #666;
            line-height: 1.5;
        }

        @media (max-width: 1024px) {
            .features {
                flex-wrap: wrap;
            }

            .feature-card {
                width: 300px;
            }
        }
    </style>
</head>

<body>
    <nav class="navbar">
        <div class="nav-container">
            <a href="#" class="brand">
                <img src="sipagon.png" style="width: 70px; height: 70px;" alt="Logo SIPAGON">
                <div class="brand-text">
                    <div class="title">SIPAGON</div>
                    <div class="subtitle">Sistem Pakar Game Online</div>
                </div>
            </a>
            <div class="nav-buttons">
                <a href="login.php" class="btn btn-login">Masuk</a>
                <a href="buat-akun.php" class="btn btn-register">Daftar</a>
            </div>
        </div>
    </nav>

    <section class="hero">
        <div class="hero-content">
            <div class="hero-text">
                <h1>Kenali Tingkat Kecanduan Game Online Anda</h1>
                <p>SIPAGON hadir sebagai solusi inovatif untuk membantu Anda mengenali dan mengatasi kecanduan game online. Dengan pendekatan sistematis dan berbasis pengetahuan ahli, kami membantu Anda memahami pola gaming Anda dengan lebih baik.</p>

                <div class="cta-buttons">
                    <a href="buat-akun.php" class="btn btn-primary">Mulai Sekarang</a>
                </div>

                <div class="features">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fa fa-stethoscope"></i>
                        </div>
                        <h3>Diagnosa Akurat</h3>
                        <p>Analisis berbasis sistem pakar dengan tingkat akurasi tinggi</p>
                    </div>
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fa fa-clock-o"></i>
                        </div>
                        <h3>Hasil Instan</h3>
                        <p>Dapatkan hasil diagnosa dan rekomendasi dalam hitungan detik</p>
                    </div>
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fa fa-shield"></i>
                        </div>
                        <h3>Privasi Terjamin</h3>
                        <p>Data Anda sepenuhnya aman dan terjaga kerahasiaannya</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</body>

</html>