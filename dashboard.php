<?php
session_start();
include("koneksi.php");

if (!isset($_SESSION['email'])) {
    header("location:login.php");
    exit;
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Dashboard - SIPAGON</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="../css/tampilan.css">
</head>

<body>
    <?php include "header.php"; ?>

    <div class="container">
        <div class="welcome-card">
            <h1>Selamat Datang, <?php echo htmlspecialchars($_SESSION['nama']); ?>!</h1>
            <p>Sistem Pakar Diagnosa Kecanduan Game Online</p>
            <div class="action-buttons">
                <a href="diagnosa.php" class="btn-primary">
                    <i class="fa fa-stethoscope"></i>
                    Mulai Diagnosa
                </a>
                <a href="riwayat.php" class="btn-secondary">
                    <i class="fa fa-history"></i>
                    Lihat Riwayat
                </a>
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
            max-width: 1200px;
            margin: 5rem auto;
            padding: 0 1rem;
        }

        .welcome-card {
            background: rgb(255, 255, 255);
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.35);
            text-align: center;
        }

        .action-buttons {
            margin-top: 2rem;
            display: flex;
            gap: 1rem;
            justify-content: center;
        }

        .btn-primary,
        .btn-secondary {
            padding: 1rem 2rem;
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