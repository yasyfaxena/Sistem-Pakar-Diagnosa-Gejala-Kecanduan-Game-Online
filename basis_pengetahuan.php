<?php
session_start();
include("koneksi.php");

if (!isset($_SESSION['adminlogin'])) {
    header("location:login.php");
    exit;
}

if (isset($_POST['add'])) {
    $kode_penyakit = $_POST['kode_penyakit'];
    $kode_evidence = $_POST['kode_evidence'];
    $mb = $_POST['mb'];

    $insert = mysqli_query($con, "INSERT INTO basis_pengetahuan (kode_penyakit, kode_evidence, mb) 
                                 VALUES ('$kode_penyakit', '$kode_evidence', '$mb')");

    if ($insert) {
        echo "<script>alert('Data berhasil ditambahkan!');</script>";
        echo "<script>window.location.href='basis_pengetahuan.php';</script>";
    } else {
        echo "<script>alert('Gagal menambahkan data!');</script>";
    }
}

if (isset($_POST['update'])) {
    $kode_penyakit = $_POST['edit_kode_penyakit'];
    $kode_evidence = $_POST['edit_kode_evidence'];
    $mb = $_POST['edit_mb'];

    $update = mysqli_query($con, "UPDATE basis_pengetahuan SET 
                                 mb = '$mb'
                                 WHERE kode_penyakit = '$kode_penyakit' 
                                 AND kode_evidence = '$kode_evidence'");

    if ($update) {
        echo "<script>alert('Data berhasil diupdate!');</script>";
        echo "<script>window.location.href='basis_pengetahuan.php';</script>";
    } else {
        echo "<script>alert('Gagal mengupdate data!');</script>";
    }
}

$query_tingkat = mysqli_query($con, "SELECT * FROM tingkat_kecanduan ORDER BY kode_tingkat");

$query_gejala = mysqli_query($con, "SELECT * FROM evidence ORDER BY kode_evidence");

$query = mysqli_query($con, "SELECT bp.*, tk.nama_tingkat, e.nama_evidence
                           FROM basis_pengetahuan bp
                           JOIN tingkat_kecanduan tk ON bp.kode_penyakit = tk.kode_tingkat  
                           JOIN evidence e ON bp.kode_evidence = e.kode_evidence
                           ORDER BY bp.kode_penyakit, bp.kode_evidence");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Data Basis Pengetahuan - SIPAGON</title>
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

        .btn-add {
            background: white;
            color: #0052cc;
            padding: 0.6rem 1.2rem;
            border: none;
            border-radius: 6px;
            font-weight: bold;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .btn-add:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
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

        .action-buttons {
            display: flex;
            gap: 0.5rem;
        }

        .btn-edit,
        .btn-delete {
            padding: 0.4rem 1rem;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 0.3rem;
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .btn-edit {
            background: #4CAF50;
            color: white;
        }

        .btn-delete {
            background: #f44336;
            color: white;
        }

        .btn-edit:hover,
        .btn-delete:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .mb-value {
            font-family: monospace;
            text-align: center;
            background: #f8f9fa;
            padding: 0.2rem 0.5rem;
            border-radius: 4px;
        }

        @media (max-width: 768px) {
            .card-header {
                flex-direction: column;
                gap: 1rem;
                text-align: center;
            }

            .btn-add {
                width: 100%;
                justify-content: center;
            }

            .action-buttons {
                flex-direction: column;
            }

            .btn-edit,
            .btn-delete {
                width: 100%;
                justify-content: center;
            }
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            animation: fadeIn 0.3s;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        .modal-content {
            background: white;
            margin: 5% auto;
            padding: 2rem;
            border-radius: 15px;
            width: 90%;
            max-width: 600px;
            position: relative;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
            animation: slideIn 0.3s;
        }

        @keyframes slideIn {
            from {
                transform: translateY(-100px);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: #333;
        }

        .form-group select,
        .form-group input {
            width: 100%;
            padding: 0.8rem;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .form-group select:focus,
        .form-group input:focus {
            border-color: #0066ff;
            outline: none;
            box-shadow: 0 0 0 3px rgba(0, 102, 255, 0.1);
        }

        .modal-title {
            margin-bottom: 1.5rem;
            color: #0052cc;
            font-size: 1.5rem;
        }

        .close {
            position: absolute;
            right: 20px;
            top: 15px;
            font-size: 28px;
            font-weight: bold;
            color: #aaa;
            cursor: pointer;
            transition: color 0.3s;
        }

        .close:hover {
            color: #333;
        }

        .btn-submit {
            background: linear-gradient(45deg, #0052cc, #0066ff);
            color: white;
            padding: 0.8rem 1.5rem;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            width: 100%;
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0, 82, 204, 0.2);
        }
    </style>
</head>

<body>
    <?php include "header_admin.php"; ?>
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h2><i class="fa fa-database"></i> Data Basis Pengetahuan</h2>
                <button onclick="openAddModal()" class="btn-add">
                    <i class="fa fa-plus"></i> Tambah Data
                </button>
            </div>

            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th style="width: 50px;">No</th>
                            <th>Kode Hipotesis</th>
                            <th>Nama Hipotesis</th>
                            <th>Kode Gejala</th>
                            <th>Nama Gejala</th>
                            <th style="width: 100px;">MB</th>
                            <th style="width: 200px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        while ($data = mysqli_fetch_array($query)) {
                            echo "<tr>";
                            echo "<td>" . $no++ . "</td>";
                            echo "<td>" . htmlspecialchars($data['kode_penyakit']) . "</td>";
                            echo "<td>" . htmlspecialchars($data['nama_tingkat']) . "</td>";
                            echo "<td>" . htmlspecialchars($data['kode_evidence']) . "</td>";
                            echo "<td>" . htmlspecialchars($data['nama_evidence']) . "</td>";
                            echo "<td><div class='mb-value'>" . number_format($data['mb'], 1) . "</div></td>";
                            echo "<td class='action-buttons'>
                           <button onclick='openEditModal(\"" . $data['kode_penyakit'] . "\", 
                                                        \"" . $data['kode_evidence'] . "\", 
                                                        \"" . htmlspecialchars($data['nama_tingkat'], ENT_QUOTES) . "\", 
                                                        \"" . htmlspecialchars($data['nama_evidence'], ENT_QUOTES) . "\", 
                                                        \"" . $data['mb'] . "\")' class='btn-edit'>
                               <i class='fa fa-edit'></i> Ubah
                           </button>
                           <a href='hapus_basis_pengetahuan.php?kode_penyakit=" . $data['kode_penyakit'] .
                                "&kode_evidence=" . $data['kode_evidence'] . "' 
                              onclick='return confirm(\"Apakah Anda yakin ingin menghapus data ini?\")' 
                              class='btn-delete'>
                               <i class='fa fa-trash'></i> Hapus
                           </a>
                         </td>";
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div id="addModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeAddModal()">&times;</span>
            <h3 class="modal-title">Tambah Data Basis Pengetahuan</h3>
            <form method="POST">
                <div class="form-group">
                    <label>Hipotesis:</label>
                    <select name="kode_penyakit" required>
                        <option value="">Pilih Hipotesis</option>
                        <?php
                        mysqli_data_seek($query_tingkat, 0);
                        while ($tingkat = mysqli_fetch_array($query_tingkat)) {
                            echo "<option value='" . $tingkat['kode_tingkat'] . "'>" . $tingkat['kode_tingkat'] . " - " . $tingkat['nama_tingkat'] . "</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Gejala:</label>
                    <select name="kode_evidence" required>
                        <option value="">Pilih Gejala</option>
                        <?php
                        mysqli_data_seek($query_gejala, 0);
                        while ($gejala = mysqli_fetch_array($query_gejala)) {
                            echo "<option value='" . $gejala['kode_evidence'] . "'>" . $gejala['kode_evidence'] . " - " . $gejala['nama_evidence'] . "</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Nilai MB:</label>
                    <input type="number" name="mb" step="0.1" min="0" max="1" required>
                </div>
                <button type="submit" name="add" class="btn-submit">Tambah Data</button>
            </form>
        </div>
    </div>
    <div id="editModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeEditModal()">&times;</span>
            <h3 class="modal-title">Edit Data Basis Pengetahuan</h3>
            <form method="POST">
                <input type="hidden" id="edit_kode_penyakit" name="edit_kode_penyakit">
                <input type="hidden" id="edit_kode_evidence" name="edit_kode_evidence">
                <div class="form-group">
                    <label>Hipotesis:</label>
                    <input type="text" id="edit_nama_tingkat" readonly>
                </div>
                <div class="form-group">
                    <label>Gejala:</label>
                    <input type="text" id="edit_nama_evidence" readonly>
                </div>
                <div class="form-group">
                    <label>Nilai MB:</label>
                    <input type="number" id="edit_mb" name="edit_mb" step="0.1" min="0" max="1" required>
                </div>
                <button type="submit" name="update" class="btn-submit">Update Data</button>
            </form>
        </div>
    </div>

    <script>
        const addModal = document.getElementById("addModal");
        const editModal = document.getElementById("editModal");

        function openAddModal() {
            addModal.style.display = "block";
        }

        function closeAddModal() {
            addModal.style.display = "none";
        }

        function openEditModal(kodePenyakit, kodeEvidence, namaTingkat, namaEvidence, mb) {
            document.getElementById("edit_kode_penyakit").value = kodePenyakit;
            document.getElementById("edit_kode_evidence").value = kodeEvidence;
            document.getElementById("edit_nama_tingkat").value = kodePenyakit + " - " + namaTingkat;
            document.getElementById("edit_nama_evidence").value = kodeEvidence + " - " + namaEvidence;
            document.getElementById("edit_mb").value = mb;
            editModal.style.display = "block";
        }

        function closeEditModal() {
            editModal.style.display = "none";
        }

        window.onclick = function(event) {
            if (event.target == addModal) {
                closeAddModal();
            }
            if (event.target == editModal) {
                closeEditModal();
            }
        }
    </script>
</body>

</html>