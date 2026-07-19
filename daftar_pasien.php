<?php
session_start();
include("koneksi.php");

if (!isset($_SESSION['adminlogin'])) {
    header("location:login.php");
    exit;
}

$query = mysqli_query($con, "SELECT p.email, p.nama, tk.nama_tingkat, h.hasil, h.tanggal
                           FROM pasien p
                           LEFT JOIN hasil_cf h ON p.email = h.email
                           LEFT JOIN tingkat_kecanduan tk ON h.kode_penyakit = tk.kode_tingkat
                           ORDER BY h.tanggal DESC");
?>

<!DOCTYPE html>
<html>

<head>
    <title>Data Pasien - SIPAGON</title>
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

        .card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            overflow: hidden;
        }

        .card-header {
            background: linear-gradient(45deg, #0052cc, #0066ff);
            color: white;
            padding: 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .table-container {
            padding: 1.5rem;
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
        }

        th,
        td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid #eee;
        }

        th {
            background: #f8f9fa;
            font-weight: 600;
            color: #0052cc;
        }

        tr:hover {
            background: #f8f9fa;
        }

        .hasil-value {
            font-family: monospace;
            font-weight: bold;
            text-align: center;
        }

        .tanggal {
            color: #666;
            font-size: 0.9rem;
        }

        .no-results {
            text-align: center;
            padding: 2rem;
            color: #666;
        }

        @media (max-width: 768px) {
            .card-header {
                text-align: center;
                padding: 1rem;
            }

            th,
            td {
                padding: 0.75rem;
                font-size: 0.9rem;
            }
        }
    </style>
</head>

<body>
    <?php include "header_admin.php"; ?>

    <div class="container">
        <div class="card">
            <div class="card-header">
                <h2><i class="fa fa-users"></i> Data Pasien</h2>
            </div>

            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th style="width: 50px;">No</th>
                            <th>Email</th>
                            <th>Nama</th>
                            <th colspan="2">Hasil</th>
                            <th>Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (mysqli_num_rows($query) > 0) {
                            $no = 1;
                            while ($data = mysqli_fetch_array($query)) {
                                echo "<tr>";
                                echo "<td>" . $no++ . "</td>";
                                echo "<td>" . htmlspecialchars($data['email']) . "</td>";
                                echo "<td>" . htmlspecialchars($data['nama']) . "</td>";
                                echo "<td>" . htmlspecialchars($data['nama_tingkat'] ?? '-') . "</td>";
                                echo "<td class='hasil-value'>" .
                                    ($data['hasil'] ? number_format($data['hasil'], 2) . '%' : '-') .
                                    "</td>";
                                echo "<td class='tanggal'>" .
                                    ($data['tanggal'] ? date('d-m-Y H:i', strtotime($data['tanggal'])) : '-') .
                                    "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='6' class='no-results'>Belum ada data pasien</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>

</html>