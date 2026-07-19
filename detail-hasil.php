<?php
session_start();
include("koneksi.php");

if (!isset($_SESSION['email'])) {
    header("location:login.php");
    exit;
}

$email = $_SESSION['email'];
$id_hasil = $_GET['id'];

$user_query = mysqli_query($con, "SELECT * FROM pasien WHERE email = '$email'");
$user_data = mysqli_fetch_assoc($user_query);

$hasil_query = mysqli_query($con, "SELECT h.*, tk.nama_tingkat, s.solusi 
                                  FROM hasil_cf h 
                                  JOIN tingkat_kecanduan tk ON h.kode_penyakit = tk.kode_tingkat
                                  JOIN solusi s ON tk.kode_solusi = s.kode_solusi 
                                  WHERE h.id = '$id_hasil'");
$hasil_data = mysqli_fetch_assoc($hasil_query);

$gejala_query = mysqli_query($con, "SELECT e.kode_evidence, e.nama_evidence, cf.md as cf_user 
                                   FROM evidence e
                                   JOIN certainty_factor cf ON e.kode_evidence = cf.kode_evidence 
                                   WHERE cf.email = '$email' 
                                   ORDER BY e.kode_evidence");
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Detail Hasil Diagnosa - SIPAGON</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="../css/tampilan.css">
</head>

<body>
    <?php include "header.php"; ?>

    <div class="detail-container">
        <table class="user-info">
            <tr>
                <td>Email</td>
                <td>:</td>
                <td><?php echo htmlspecialchars($user_data['email']); ?></td>
            </tr>
            <tr>
                <td>Nama Lengkap</td>
                <td>:</td>
                <td><?php echo htmlspecialchars($user_data['nama']); ?></td>
            </tr>
            <tr>
                <td>Tempat, Tanggal Lahir</td>
                <td>:</td>
                <td><?php
                    if ($user_data['tanggal_lahir']) {
                        echo htmlspecialchars(date("d F Y", strtotime($user_data['tanggal_lahir'])));
                    } else {
                        echo "-";
                    }
                    ?></td>
            </tr>
            <tr>
                <td>Jenis Kelamin</td>
                <td>:</td>
                <td><?php echo htmlspecialchars($user_data['jenis_kelamin']); ?></td>
            </tr>
            <tr>
                <td>Alamat</td>
                <td>:</td>
                <td><?php echo htmlspecialchars($user_data['alamat'] ?? '-'); ?></td>
            </tr>
        </table>

        <div class="section-title">GEJALA</div>
        <div class="gejala-list">
            <?php
            if ($gejala_query && mysqli_num_rows($gejala_query) > 0) {
                while ($gejala = mysqli_fetch_assoc($gejala_query)) {
                    $cf_value = floatval($gejala['cf_user']);

                    if ($cf_value >= 1.0) $keyakinan = "Sangat yakin";
                    else if ($cf_value >= 0.8) $keyakinan = "Yakin";
                    else if ($cf_value >= 0.6) $keyakinan = "Cukup Yakin";
                    else if ($cf_value >= 0.4) $keyakinan = "Hampir yakin";
                    else if ($cf_value >= 0.2) $keyakinan = "Kurang yakin";
                    else $keyakinan = "Tidak yakin";

                    echo "<div class='gejala-item'>";
                    echo $gejala['kode_evidence'] . "  - " . htmlspecialchars($gejala['nama_evidence']) . " - (" . $keyakinan . ")";
                    echo "</div>";
                }
            } else {
                echo "Tidak ada gejala yang dipilih";
            }
            ?>
        </div>

        <div class="section-title">HASIL DIAGNOSA</div>
        <table class="diagnosa-info">
            <tr>
                <td>Hipotesis</td>
                <td>:</td>
                <td><?php echo htmlspecialchars($hasil_data['nama_tingkat']); ?></td>
            </tr>
            <tr>
                <td>Nilai Akurasi</td>
                <td>:</td>
                <td><?php echo number_format($hasil_data['hasil'], 2); ?>%</td>
            </tr>
        </table>

        <div class="section-title">SOLUSI</div>
        <div class="solusi-content">
            <?php echo nl2br(htmlspecialchars($hasil_data['solusi'])); ?>
        </div>
    </div>

    <style>
        * {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-image: url("bg.png");
        }

        .detail-container {
            max-width: 800px;
            margin: 2rem auto;
            padding: 20px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.35);
        }

        .user-info,
        .diagnosa-info {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .user-info td,
        .diagnosa-info td {
            padding: 8px;
            border-bottom: 1px solid #eee;
        }

        .user-info td:first-child,
        .diagnosa-info td:first-child {
            width: 150px;
        }

        .user-info td:nth-child(2),
        .diagnosa-info td:nth-child(2) {
            width: 20px;
        }

        .section-title {
            background: linear-gradient(45deg, #1e90ff, #4169E1);
            color: white;
            padding: 8px 15px;
            margin: 20px 0 10px 0;
        }

        .gejala-list,
        .solusi-content {
            padding: 15px;
            background: #f8f9fa;
            border-radius: 4px;
            line-height: 1.6;
        }
    </style>
</body>

</html>