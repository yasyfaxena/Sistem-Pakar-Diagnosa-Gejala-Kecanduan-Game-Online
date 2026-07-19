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
                            ORDER BY h.id DESC");
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Riwayat Diagnosa - SIPAGON</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="../css/tampilan.css">
</head>

<body>
    <?php include "header.php"; ?>

    <div class="container">
        <div class="riwayat-card">
            <div class="card-header">
                <h2><i class="fa fa-history"></i> Riwayat Diagnosa</h2>
            </div>

            <div class="riwayat-content">
                <?php if (mysqli_num_rows($query) > 0): ?>
                    <?php while ($data = mysqli_fetch_assoc($query)): ?>
                        <div class="riwayat-item">
                            <div class="diagnosa-info">
                                <h3><?php echo htmlspecialchars($data['nama_tingkat']); ?></h3>
                                <div class="confidence">
                                    Tingkat Keyakinan: <span class="percentage"><?php echo number_format($data['hasil'], 2); ?>%</span>
                                </div>
                                <div class="tanggal">
                                    Tanggal Diagnosa: <span><?php echo date('d F Y H:i', strtotime($data['tanggal'])); ?></span>
                                </div>
                            </div>
                            <div class="rekomendasi">
                                <h4>Rekomendasi:</h4>
                                <p><?php echo nl2br(htmlspecialchars($data['solusi'])); ?></p>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="no-data">
                        <i class="fa fa-info-circle"></i>
                        <p>Belum ada riwayat diagnosa.</p>
                        <a href="diagnosa.php" class="btn-primary">Mulai Diagnosa</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <style>
        body {
            background-image: url("bg.png");
        }
        * {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .container {
            max-width: 800px;
            margin: 2rem auto;
            padding: 0 1rem;
        }

        .riwayat-card {
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

        .riwayat-content {
            padding: 2rem;
        }

        .riwayat-item {
            border: 1px solid #e1e1e1;
            border-radius: 8px;
            padding: 1.5rem;
            margin-bottom: 1rem;
        }

        .diagnosa-info {
            margin-bottom: 1rem;
        }

        .confidence {
            margin-top: 0.5rem;
        }

        .tanggal {
            margin-top: 0.5rem;
            color: #666;
            font-size: 0.9rem;
        }

        .percentage {
            font-weight: bold;
            color: #1e90ff;
        }

        .rekomendasi {
            background: #f8f9fa;
            padding: 1rem;
            border-radius: 4px;
        }

        .rekomendasi h4 {
            color: #0052cc;
            margin-bottom: 0.5rem;
        }

        .no-data {
            text-align: center;
            padding: 3rem 0;
        }

        .no-data i {
            font-size: 3rem;
            color: #1e90ff;
            margin-bottom: 1rem;
        }

        .btn-primary {
            display: inline-block;
            margin-top: 1rem;
            padding: 0.8rem 1.5rem;
            background: linear-gradient(45deg, #1e90ff, #4169E1);
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: bold;
            transition: transform 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
        }
    </style>
</body>

</html>