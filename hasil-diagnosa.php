<?php
session_start();
include("koneksi.php");

if (!isset($_SESSION['email'])) {
    header("location:login.php");
    exit;
}

$email = $_SESSION['email'];

$query = mysqli_query($con, "SELECT h.*, tk.nama_tingkat, s.solusi 
                            FROM hasil_cf h
                            JOIN tingkat_kecanduan tk ON h.kode_penyakit = tk.kode_tingkat
                            JOIN solusi s ON tk.kode_solusi = s.kode_solusi
                            WHERE h.email = '$email'
                            ORDER BY h.id DESC LIMIT 1");

if (!$query) {
    die("Error in query: " . mysqli_error($con));
}

$diagnosa_tertinggi = mysqli_fetch_assoc($query);
if (!$diagnosa_tertinggi) {
    header("location:diagnosa.php");
    exit;
}

if (!isset($diagnosa_tertinggi['nama_tingkat']) || !isset($diagnosa_tertinggi['solusi']) || !isset($diagnosa_tertinggi['hasil'])) {
    die("Data tidak lengkap dalam database");
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Hasil Diagnosa - SIPAGON</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="../css/tampilan.css">
</head>

<body>
    <?php include "header.php"; ?>

    <div class="container">
        <div class="hasil-card">
            <div class="card-header">
                <h2><i class="fa fa-clipboard"></i> Hasil Diagnosa</h2>
            </div>

            <div class="hasil-content">
                <div class="diagnosa-utama">
                    <h3>Diagnosa: <?php echo htmlspecialchars($diagnosa_tertinggi['nama_tingkat']); ?></h3>
                    <div class="confidence">
                        Tingkat Keyakinan: <span class="percentage"><?php echo number_format($diagnosa_tertinggi['hasil'], 2); ?>%</span>
                    </div>
                </div>

                <div class="rekomendasi">
                    <h4>Rekomendasi Penanganan:</h4>
                    <p><?php echo nl2br(htmlspecialchars($diagnosa_tertinggi['solusi'])); ?></p>
                </div>

                <div class="action-buttons">
                    <a href="diagnosa.php" class="btn-secondary">
                        <i class="fa fa-refresh"></i> Diagnosa Ulang
                    </a>
                    <a href="detail-hasil.php?id=<?php echo $diagnosa_tertinggi['id']; ?>" class="btn-primary">
                        <i class="fa fa-save"></i> Simpan Hasil
                    </a>
                </div>
            </div>
        </div>
    </div>

    <style>
        * {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-image: url("bg.png");
        }

        .container {
            max-width: 800px;
            margin: 2rem auto;
            padding: 0 1rem;
        }

        .hasil-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            overflow: hidden;
        }

        .card-header {
            background: linear-gradient(45deg, #0052cc, #0066ff);
            color: white;
            padding: 1.5rem;
        }

        .hasil-content {
            padding: 2rem;
        }

        .diagnosa-utama {
            text-align: center;
            margin-bottom: 2rem;
        }

        .confidence {
            margin-top: 1rem;
            font-size: 1.2rem;
        }

        .percentage {
            font-size: 1.5rem;
            font-weight: bold;
            color: #1e90ff;
        }

        .rekomendasi {
            background: #f8f9fa;
            padding: 1.5rem;
            border-radius: 8px;
            margin-bottom: 2rem;
        }

        .rekomendasi h4 {
            color: #0052cc;
            margin-bottom: 1rem;
        }

        .action-buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
        }

        .btn-primary,
        .btn-secondary {
            padding: 0.8rem 1.5rem;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
            transition: transform 0.3s ease;
        }

        .btn-primary {
            background: linear-gradient(45deg, #1e90ff, #4169E1);
            color: white;
        }

        .btn-secondary {
            background: #f8f9fa;
            color: #1e90ff;
            border: 2px solid #1e90ff;
        }

        .btn-primary:hover,
        .btn-secondary:hover {
            transform: translateY(-2px);
        }
    </style>
</body>

</html>