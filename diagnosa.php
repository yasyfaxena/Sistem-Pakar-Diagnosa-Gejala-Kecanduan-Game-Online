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

$queryHipotesis = mysqli_query($con, "SELECT * FROM tingkat_kecanduan");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Diagnosa - SIPAGON</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        .info-group {
            margin-bottom: 1.5rem;
        }

        .info-label {
            color: #0052cc;
            font-weight: 500;
            margin-bottom: 0.5rem;
        }

        input[type="text"] {
            width: 100%;
            padding: 0.8rem;
            border: 2px solid #e1e1e1;
            border-radius: 8px;
            background: #f8f9fa;
            font-size: 0.9rem;
        }

        .btn-primary {
            background: linear-gradient(45deg, #1e90ff, #4169E1);
            color: white;
            padding: 1rem 2rem;
            border: none;
            border-radius: 8px;
            font-weight: bold;
            width: 100%;
            cursor: pointer;
            transition: all 0.3s ease;
        }

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
            padding: 2rem;
        }

        .question-item {
            background: #f8f9fa;
            padding: 1rem;
            margin-bottom: 1rem;
            border-radius: 8px;
        }

        .question-text {
            margin-bottom: 0.5rem;
            font-weight: 500;
        }

        .answer-options {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .radio-option {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .radio-option input[type="radio"] {
            margin: 0;
        }

        .btn-submit {
            background: linear-gradient(45deg, #1e90ff, #4169E1);
            color: white;
            padding: 1rem 2rem;
            border: none;
            border-radius: 8px;
            font-weight: bold;
            cursor: pointer;
            width: 100%;
            margin-top: 2rem;
            transition: transform 0.3s;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
        }

        .criteria-item {
            background: #f8f9fa;
            padding: 1rem;
            margin-bottom: 0.5rem;
            border-radius: 8px;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .criteria-item input[type="checkbox"] {
            width: 20px;
            height: 20px;
        }

        .criteria-item label {
            flex: 1;
            margin: 0;
            cursor: pointer;
        }

        #criteriaList {
            margin-top: 1rem;
        }

        #criteriaList h4 {
            margin-bottom: 1rem;
            color: #0052cc;
        }

        select {
            width: 100%;
            padding: 0.8rem;
            border: 2px solid #e1e1e1;
            border-radius: 8px;
            font-size: 0.9rem;
            background-color: white;
        }

        select:focus {
            border-color: #1e90ff;
            outline: none;
        }

        .criteria-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem;
            background: white;
            border-radius: 8px;
            margin-bottom: 0.8rem;
        }

        .criteria-item label {
            flex: 1;
        }

        .criteria-item select {
            width: 150px;
        }

        .keyakinan-select {
            padding: 0.5rem;
            border: 1px solid #e1e1e1;
            border-radius: 4px;
            background: #f8f9fa;
        }
    </style>
</head>

<body>
    <?php include "header.php"; ?>

    <div class="container">
        <div class="diagnosa-card">
            <div class="card-header">
                <h2><i class="fa fa-stethoscope"></i> Diagnosa Kecanduan Game Online</h2>
            </div>

            <div class="diagnosa-content">
                <form id="diagnosaForm" method="post" action="pertanyaan.php" onsubmit="return validateForm()">
                    <div class="info-group">
                        <div class="info-label">Email:</div>
                        <input type="text" name="email" value="<?php echo $data['email']; ?>" readonly />
                    </div>

                    <div class="info-group">
                        <div class="info-label">Nama:</div>
                        <input type="text" name="nama" value="<?php echo $data['nama']; ?>" readonly />
                    </div>

                    <div class="info-group">
                        <div class="info-label">Hipotesis:</div>
                        <select name="hipotesis" id="hipotesis" required onchange="showCriteria(this.value)" title="Pilih tingkat kecanduan" placeholder="Pilih hipotesis">
                            <option value="">Pilih Hipotesis</option>
                            <?php while ($hipotesis = mysqli_fetch_array($queryHipotesis)): ?>
                                <option value="<?php echo $hipotesis['kode_tingkat']; ?>">
                                    <?php echo $hipotesis['nama_tingkat']; ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div id="criteriaBox" class="criteria-box" style="display: block;">
                        <h3>Kriteria Terpilih:</h3>
                        <div id="criteriaList" class="criteria-list">
                        </div>
                    </div>

                    <button type="submit" name="button" class="btn-primary">
                        <i class="fa fa-arrow-right"></i> Lanjutkan
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function showCriteria(kode_tingkat) {
            const criteriaList = document.getElementById('criteriaList');

            if (!kode_tingkat) {
                criteriaList.innerHTML = '';
                return;
            }

            criteriaList.innerHTML = '<p>Memuat gejala...</p>';

            fetch('./get_criteria.php?kode_tingkat=' + kode_tingkat)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    criteriaList.innerHTML = '<h4>KRITERIA TERPILIH BERDASARKAN HIPOTESIS:</h4>';

                    if (Array.isArray(data) && data.length > 0) {
                        data.forEach(criteria => {
                            criteriaList.innerHTML += `
                        <div class="criteria-item">
                            <input type="checkbox" name="evidence[]" value="${criteria.kode_evidence}" id="${criteria.kode_evidence}">
                            <label for="${criteria.kode_evidence}">${criteria.kode_evidence} - ${criteria.nama_evidence}</label>
                        </div>
                    `;
                        });
                    } else if (data.error) {
                        criteriaList.innerHTML += `<p class="error-message" style="color: red;">${data.error}</p>`;
                    } else {
                        criteriaList.innerHTML += '<p>Tidak ada gejala yang ditemukan untuk hipotesis ini</p>';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    criteriaList.innerHTML = `
                <div class="error-message" style="color: red; padding: 10px;">
                    Terjadi kesalahan saat memuat data: ${error.message}. Silakan coba lagi.
                </div>
            `;
                });
        }

        function validateForm() {
            let checkboxes = document.querySelectorAll('input[name="evidence[]"]');
            let checked = false;

            for (let checkbox of checkboxes) {
                if (checkbox.checked) {
                    checked = true;
                    break;
                }
            }

            if (!checked) {
                alert('Pilih minimal satu gejala');
                return false;
            }

            return true;
        }
    </script>
</body>

</html>