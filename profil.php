<?php
session_start();
include("koneksi.php");

if (!isset($_SESSION['email'])) {
    header("location:login.php");
    exit;
}

$email = $_SESSION['email'];
$query = mysqli_query($con, "SELECT * FROM pasien WHERE email='$email'");
$data = mysqli_fetch_assoc($query);

?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Profil - SIPAGON</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="../css/tampilan.css">
</head>

<body>
    <?php include "header.php"; ?>

    <div class="container">
        <div class="profile-card">
            <div class="card-header">
                <h2><i class="fa fa-user"></i> Profil Saya</h2>
            </div>

            <div class="profile-content">
                <div class="profile-info">
                    <table class="info-table">
                        <tr>
                            <td>Email</td>
                            <td>:</td>
                            <td><?php echo htmlspecialchars($data['email']); ?></td>
                        </tr>
                        <tr>
                            <td>Nama</td>
                            <td>:</td>
                            <td><?php echo htmlspecialchars($data['nama']); ?></td>
                        </tr>
                        <tr>
                            <td>Jenis Kelamin</td>
                            <td>:</td>
                            <td><?php echo htmlspecialchars($data['jenis_kelamin']); ?></td>
                        </tr>
                        <tr>
                            <td>Tanggal Lahir</td>
                            <td>:</td>
                            <td><?php echo $data['tanggal_lahir'] ? date("d F Y", strtotime($data['tanggal_lahir'])) : '-'; ?></td>
                        </tr>
                        <tr>
                            <td>Alamat</td>
                            <td>:</td>
                            <td><?php echo htmlspecialchars($data['alamat'] ?? '-'); ?></td>
                        </tr>
                    </table>

                    <div class="action-buttons">
                        <a href="edit-profil.php" class="btn-primary">
                            <i class="fa fa-edit"></i> Edit Profil
                        </a>
                    </div>
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

        .profile-card {
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

        .profile-content {
            padding: 2rem;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 2rem;
        }

        .info-table td {
            padding: 12px;
            border-bottom: 1px solid #eee;
        }

        .info-table td:first-child {
            width: 150px;
            color: #666;
        }

        .info-table td:nth-child(2) {
            width: 20px;
        }

        .action-buttons {
            text-align: right;
        }

        .btn-primary {
            background: linear-gradient(45deg, #1e90ff, #4169E1);
            color: white;
            padding: 0.8rem 1.5rem;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
            transition: transform 0.3s ease;
            display: inline-block;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
        }
    </style>
</body>

</html>