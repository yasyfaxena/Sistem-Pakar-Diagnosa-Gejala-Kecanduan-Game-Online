<?php
session_start();
include("koneksi.php");
if (!isset($_SESSION['userlogin'])) {
    header("location:index.php?pesan=Belum Login");
    exit;
}

$email = $_SESSION['email'];
$query = mysqli_query($con, "SELECT * FROM pasien WHERE email='$email'");
$data = mysqli_fetch_array($query);

$evidence_terpilih = isset($_POST['evidence']) ? $_POST['evidence'] : [];
$hipotesis = isset($_POST['hipotesis']) ? $_POST['hipotesis'] : '';

if (empty($evidence_terpilih) || empty($hipotesis)) {
    header("location:diagnosa.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pertanyaan Diagnosa - SIPAGON</title>
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
        }

        .container {
            max-width: 800px;
            margin: 2rem auto;
            padding: 0 1rem;
        }

        .diagnosa-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        }

        .card-header {
            background: linear-gradient(45deg, #0052cc, #0066ff);
            color: white;
            padding: 1.5rem;
            border-radius: 15px 15px 0 0;
        }

        .diagnosa-content {
            padding: 1.5rem;
        }

        .question-item {
            background: #f8f9fa;
            padding: 1rem;
            margin-bottom: 0.8rem;
            border-radius: 8px;
            display: flex;
            align-items: center;
            gap: 1rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .question-text {
            flex: 1;
            font-size: 0.95rem;
            color: #333;
            margin-bottom: 0;
        }

        .jawaban-select {
            width: 200px;
            padding: 0.5rem;
            border: 2px solid #e1e1e1;
            border-radius: 6px;
            font-size: 0.9rem;
            background-color: white;
            transition: border-color 0.3s ease;
        }

        .jawaban-select:focus {
            border-color: #1e90ff;
            outline: none;
        }

        .btn-primary {
            background: linear-gradient(45deg, #1e90ff, #4169E1);
            color: white;
            padding: 0.8rem 1.5rem;
            border: none;
            border-radius: 8px;
            font-weight: bold;
            width: 100%;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 1rem;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        @media (max-width: 768px) {
            .question-item {
                flex-direction: column;
                align-items: stretch;
            }

            .question-text {
                margin-bottom: 0.5rem;
            }

            .jawaban-select {
                width: 100%;
            }
        }
    </style>
</head>

<body>
    <?php include "header.php"; ?>

    <div class="container">
        <div class="diagnosa-card">
            <div class="card-header">
                <h2><i class="fa fa-question-circle"></i> Pertanyaan Diagnosa</h2>
            </div>

            <div class="diagnosa-content">
                <form method="post" action="hitung-cf.php">
                    <input type="hidden" name="hipotesis" value="<?php echo htmlspecialchars($hipotesis); ?>">

                    <?php foreach ($evidence_terpilih as $evidence): ?>
                        <input type="hidden" name="evidence[]" value="<?php echo htmlspecialchars($evidence); ?>">
                        <?php
                        $query_evidence = mysqli_query($con, "SELECT * FROM evidence WHERE kode_evidence='$evidence'");
                        $data_evidence = mysqli_fetch_array($query_evidence);
                        ?>
                        <div class="question-item">
                            <div class="question-text">
                                <?php echo $data_evidence['nama_evidence']; ?>?
                            </div>
                            <select name="jawaban[<?php echo $evidence; ?>]" class="jawaban-select" required>
                                <option value="">Pilih Jawaban</option>
                                <option value="Sangat yakin">Sangat yakin</option>
                                <option value="Yakin">Yakin</option>
                                <option value="Cukup Yakin">Cukup Yakin</option>
                                <option value="Hampir yakin">Hampir yakin</option>
                                <option value="Kurang yakin">Kurang yakin</option>
                                <option value="Tidak yakin">Tidak yakin</option>
                            </select>
                        </div>
                    <?php endforeach; ?>

                    <button type="submit" class="btn-primary">
                        <i class="fa fa-arrow-right"></i> Lanjutkan ke Hasil Diagnosa
                    </button>
                </form>
            </div>
        </div>
    </div>
</body>

</html>