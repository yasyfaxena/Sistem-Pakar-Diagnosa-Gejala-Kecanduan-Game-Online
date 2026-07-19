<?php
session_start();
include("koneksi.php");

if (!isset($_SESSION['adminlogin'])) {
    header("location:login.php");
    exit;
}

$username = $_SESSION['username'];
$query = mysqli_query($con, "SELECT * FROM Admin WHERE username='$username'");
$data = mysqli_fetch_array($query);
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin - SIPAGON</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-image: url("bg.png");
            padding-top: 80px;
        }

        .container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 1rem;
        }

        .welcome-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            margin-bottom: 2rem;
        }

        .card-header {
            background: linear-gradient(45deg, #0052cc, #0066ff);
            color: white;
            padding: 1.5rem;
        }

        .card-content {
            padding: 2rem;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-top: 2rem;
        }

        .stat-card {
            background: white;
            padding: 1.5rem;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .stat-card i {
            font-size: 2.5rem;
            color: #0066ff;
            margin-bottom: 1rem;
        }

        .stat-number {
            font-size: 1.8rem;
            font-weight: bold;
            color: #0052cc;
            margin-bottom: 0.5rem;
        }

        .stat-label {
            color: #666;
            font-size: 0.9rem;
        }
    </style>
</head>

<body>
    <?php include "header_admin.php"; ?>

    <div class="container">
        <div class="welcome-card">
            <div class="card-header">
                <h2><i class="fa fa-dashboard"></i> Dashboard Admin</h2>
            </div>
            <div class="card-content">
                <h3>Selamat datang, <?php echo htmlspecialchars($data['nama']); ?>!</h3>
                <p>Anda login sebagai Administrator Sistem Pakar Diagnosis Kecanduan Game Online.</p>
            </div>
        </div>

        <div class="stats-grid">
            <?php
            $query_pasien = mysqli_query($con, "SELECT COUNT(*) as total FROM pasien");
            $total_pasien = mysqli_fetch_assoc($query_pasien)['total'];

            $query_gejala = mysqli_query($con, "SELECT COUNT(*) as total FROM evidence");
            $total_gejala = mysqli_fetch_assoc($query_gejala)['total'];

            $query_hipotesis = mysqli_query($con, "SELECT COUNT(*) as total FROM tingkat_kecanduan");
            $total_hipotesis = mysqli_fetch_assoc($query_hipotesis)['total'];

            $query_basis = mysqli_query($con, "SELECT COUNT(*) as total FROM basis_pengetahuan");
            $total_basis = mysqli_fetch_assoc($query_basis)['total'];
            ?>

            <div class="stat-card">
                <i class="fa fa-users"></i>
                <div class="stat-number"><?php echo $total_pasien; ?></div>
                <div class="stat-label">Total Pasien</div>
            </div>

            <div class="stat-card">
                <i class="fa fa-list"></i>
                <div class="stat-number"><?php echo $total_gejala; ?></div>
                <div class="stat-label">Total Gejala</div>
            </div>

            <div class="stat-card">
                <i class="fa fa-sitemap"></i>
                <div class="stat-number"><?php echo $total_hipotesis; ?></div>
                <div class="stat-label">Total Hipotesis</div>
            </div>

            <div class="stat-card">
                <i class="fa fa-database"></i>
                <div class="stat-number"><?php echo $total_basis; ?></div>
                <div class="stat-label">Total Basis Pengetahuan</div>
            </div>
        </div>
    </div>
</body>

</html>